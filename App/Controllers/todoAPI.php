<?php
namespace App\Controllers;
use App\Models\User;
use App\Models\TODOList;
use App\Models\Task;

use \Slim\Views\Twig as View;

class todoAPI extends Controller {
    public function createList($req, $res) {
        // create new list
        $list = new TODOList;
        $list->title = 'Example title';
        $list->status_id = 1;
        $list->save();
        // with first exampled task
        $task = new Task;
        $task->list_id = $list->id;
        $task->task = 'Task example';
        $task->status_id = 1;
        $task->save();

        return $res->withJson(array(
            'list' => $list->id,
            'task' => $task->id
        ));
    }

    public function editListTitle($req, $res, $args) {
        $list = TODOList::find($args['id']);
        $title = $req->getParam('title');

        $list->title = $title;
        $list->save();

        return $res->withJson(array('title' => $title));
    }

    public function removeList($req, $res, $args) {
        $id = $args['id'];
        $list = TODOList::find($id);
        $list->delete();

        $tasks = Task::where('list_id', $id)->get();
        foreach($tasks as $task) {
            $task->delete();
        }

        return $res->withJson(array('id' => $id));
    }

    public function editTaskStatus($req, $res, $args) {
        $id = $args['id'];
        $statusId = $req->getParam('status_id');

        $task = Task::find($id);
        $task->status_id = $statusId;
        $task->save();

        $response = array(
            'id' => $id,
            'task_value' => $task->task,
            'status_id' => $statusId,
            'status_name' => $task->status->name
        );

        return $res->withJson($response);
    }

    public function createTask($req, $res, $args) {
        if(strlen($req->getParam('task_value')) > 0) {
            $listId = $args['list_id'];
            $task = Task::create([
                'list_id' => $listId,
                'task' => $req->getParam('task_value'),
                'status_id' => 1
            ]);
            return $res->withJson(array('task_id' => $task->id));
        }
        return $res->withJson(null);
    }

    public function removeTask($req, $res, $args) {
        $id = $args['id'];
        $task = Task::find($id);
        $task->delete();

        return $res->withJson('taskRemoved');
    }


};
