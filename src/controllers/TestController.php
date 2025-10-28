<?php
require_once __DIR__ . '/../models/Test.php';

class TestController {
    public function getAllTest() {
        $test = new Test();
        $data = $test->all(); // 使用繼承的 all() 方法
        echo json_encode($data);
    }
}
