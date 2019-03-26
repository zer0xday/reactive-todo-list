<?php
namespace App\Controllers;
use App\Models\User;
use App\Models\TODOList;

use \Slim\Views\Twig as View;

class todoAPI extends Controller {
    public function createList($req, $res) {
        $user = new TODOList;

        $user->title = 'Example title';
        $user->status_id = 1;

        $user->save();

        return $res->withJson(array(
            'id' => $user->id
        ));
    }

    public function createTask($req, $res) {
        return $res->withJson('createTask');
    }

    public function deleteList($req, $res, $args) {
        return $res->withJson(array($args));
    }
};
