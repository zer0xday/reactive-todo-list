<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TODOList extends Model {
    protected $table = 'list';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $guarded = [];

    public function tasks() {
        return $this->hasMany('App\Models\Task', 'list_id', 'id');
    }
}