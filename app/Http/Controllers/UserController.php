<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// librerìa para validar campos necesarios
use Illuminate\Support\Facades\Validator;
// para la autenticación con el JWT(token)
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserController extends Controller
{

    //Método para retornar todos lo registros
    public function index()
    {
        return User::all();
    }


        
    // método para crear un nuevo usuario
    public function store(Request $request)
    {
        
        // validamos para que los campos sean requeridos
        $validator = Validator::make($request->all(), [
        'identification' => 'required|string|max:15',
            'firstname' => 'required|string|max:20',
            'lastname' => 'required|string|max:100',
            'birthday' => 'required|string|max:40',
            'email' => 'required|string|max:100|unique:users',
            'username' => 'required|string|max:20',
            'password' => 'required|string|min:8'
        ]);

        // en el caso que de falte un dato
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // obtención de los datos enviados del frontEnd
        $user = User::create([
            'identification' => $request->get('identification'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'birthday' => $request->get('birthday'),
            'email' => $request->get('email'),
            'username' => $request->get('username'),
            // encriptamos la contraseña
            'password' => Hash::make($request->get('password')),

        ]);
        //generación de token en la base para el usuario creado
        $token = JWTAuth::fromUser($user);
        //retorna si el usuario fue creado
        //201 significa que se ha creado
        return response()->json(['user' => $user, 'token' => $token], 201);
    }


     //Método para actualizar dato de un usuario
     public function update(Request $request, $id)
     {
         $user = User::findOrFail($id);
         $user->identification = $request->identification;
         $user->firstname = $request->firstname;
         $user->lastname = $request->lastname;
         $user->birthday = $request->birthday;
         $user->email = $request->email;
         $user->username = $request->username;
         $user->password = Hash::make($request->password);

         $user->save();
         return 205;
     }


    //Método para eliminar un usuario
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return 204;
    }


    // método para vertificar las credencialesde un usuario
    public function authenticate(Request $request)
    {
        // optención de los datos enviados
        $credentials = $request->only('email', 'password');
        $credentials2 = $this->userSearch($request);
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credeciales Invalidas', 'status' => '400']);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token', 'status' => '500']);
        }

        // return response()->json(compact('token'));
        return response()->json(['user' => $credentials2, 'token' => $token ]);
    }


    // método para loguearse
    public function getAuthenticateUser(Request $request)
    {
        try {
            // verificamos si el usuario existe con em método authenticate
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['Usuario no encontrado'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\Token\ExpiredException $e) {
            return response()->json(['Token expirado'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['Token inválido'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\JWTException $e) {
            return response()->json(['No se ha enviado ningun Token'], $e->getStatusCode());
        }

        return response()->json(['user' => $user], 'Bienvenido');
    }

    //Método para ver o buscar un usuario especifico por el email
    public function userSearch($request)
    {
        $email = $request->only('email');
        return User::where('email', '=', $email)->get();
    }
}
