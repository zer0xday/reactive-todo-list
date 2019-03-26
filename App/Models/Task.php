<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {
    protected $table = 'tasks';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $guarded = [];

    public function status() {
        return $this->belongsTo('App\Models\Task_status', 'status_id', 'id');
    }
}