<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task_status extends Model {
    protected $table = 'tasks_status';
    public $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];
}