<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use SebastianBergmann\Timer\Exception;


class TaskController extends Controller
{
    //Método para listar todas las tareas 
    public function index()
    {
        try {
            // consulta de todas las tareas
            $result = Task::all();
            
        } catch (Exception $e) {
            return response()->json(['Error' => $e]);
        }

        return $result;
    }


    public function store(Request $request)
    {
        try {
            // validación de campos requeridos para el registro de una nueva actividad
            $validator = Validator::make($request->all(),[
                'description' => 'required|string',
                'finish' => 'required|string|max:15',
                'user_id' => 'required|integer'
            ]);

            // validación si algo falla
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(),400);

            }
            // función que facilita la creación de la tarea
            Task::create([
                'description' => $request->get('description'),
                'finish' => $request->get('finish'),
                'user_id' => $request->get('user_id')
            ]);

        } catch (Exception $e) {
            return response()->json(['Error' => $e]);
        }
        return 201;
    }


    // método para actualizar una tarea 
    public function update(Request $request, $id)
    {
        try {
            $task = Task::where('id',$id)->first();
            if ($task != null ) {
                // actualiza el registro de la tarea
                $task->update($request->all());
            }else{
                return response()->json(['Error' => 'No se logro actualizar con exito'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['Error inesperado al intentar actualizar'], $e->getStatusCode());
        }
        return 205;
    }


    // método para eliminar
    public function delete($id)
    {

        try {
            // consulta una tarea especifica de la entidad de tarea
            $result =  Task::where('id',$id)->first();;
            if ($result == null) {
                return response()->json(['No se encontro ningun registro con ese Id para la eliminación'],404);
            } else {
                //eliminación de la tarea
                $result->delete();
            }
        } catch (Exception $e) {
            return response()->json(['Error inesperado al intentar eliminar'], $e->getStatusCode());
        }
        return 200;
    }


    // método para buscar una tarea en especifica segun el id del usuario
    public function show($id)
    {
        return Task::where('user_id', $id)->get();
    }
}
