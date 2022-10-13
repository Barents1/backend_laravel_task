<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // esquema para generar la entidad usser con sus respectivos atributos
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identification',15);
            $table->string('firstname',30);
            $table->string('lastname',30);
            $table->string('birthday');
            $table->string('email',100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username',50);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // función para eliminar si existe la table de users
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
