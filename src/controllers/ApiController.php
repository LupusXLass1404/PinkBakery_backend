<?php
require_once __DIR__ . '/../models/DB.php';

class ApiController {
    protected $model;

    public function __construct($table) {
        if (empty($table)) {
            // 如果$table是空的給出錯誤訊息
            throw new Exception("Table name cannot be empty!");
        }

        $this->model = new DB($table);
    }

    public function getAll($request, $response) {
        $data = $this->model->all();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getOne($request, $response, $args) {
        $data = $this->model->find($args['id']);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // create
    public function create($request, $response) {
        $data = $request->getParsedBody();
        $id = $this->model->save($data);
        $response->getBody()->write(json_encode(['id' => $id]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // update
    public function update($request, $response, $args) {
        $data = $request->getParsedBody();
        $data['id'] = $args['id'];
        $id = $this->model->save($data);
        $response->getBody()->write(json_encode(['status' => 'updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // delete
    public function delete($request, $response, $args) {
        $this->model->del($args['id']);
        $response->getBody()->write(json_encode(['status' => 'deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}