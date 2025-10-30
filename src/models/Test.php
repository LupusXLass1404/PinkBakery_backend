<?php
require_once __DIR__ . '/DB.php';

class Test extends DB {
    function __construct(){
        parent::__construct('test'); // 對應資料表名稱
    }

    // 可以自定義方法，例如：
    // public function findByEmail($email){
    //     $sql = "SELECT * FROM `{$this->table}` WHERE email = ?";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute([$email]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }
}