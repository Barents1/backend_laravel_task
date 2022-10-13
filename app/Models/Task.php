<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'finish',
        'user_id'
    ];


    // relacion de uno a muchos desde la entidad de users
    // segun los requerimientos una tarea solo puede pertenecer a un usario
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
