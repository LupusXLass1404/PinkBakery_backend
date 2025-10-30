<?php
date_default_timezone_set('Asia/Taipei');
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class DB{
    protected $pdo;
    protected $table;

    function __construct($table){
        $host = $_ENV['DB_HOST'];
        $db   = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

        $dsn = "mysql:host=$host;charset=utf8;dbname=$db";
        $this->pdo = new PDO($dsn, $user, $pass);
        $this -> table = $table;
    }

    function lastId(){
        return $this -> pdo -> lastInsertId();
    }

    function all(...$arg){
        $sql = "Select * From `{$this -> table}` ";
        if(!empty($arg[0])){
            if(is_array($arg[0])){
                // 是陣列
                $where = $this -> a2s($arg[0]);
                $sql .= " Where " . join(" && ", $where);
            } else {
                // 只有id
                $sql .= " Where id = {$arg[0]}";
            }
        }

        if(!empty($arg[1])){
            $sql .= $arg[1];
        }
        return $this -> fetchAll($sql);
    }

    function find($id){
        $sql = "Select * From `{$this -> table}` ";
        if(is_array($id)){
            // 是陣列
            $where = $this -> a2s($id);
            $sql .= " Where " . join(" && ", $where);
        } else {
            // 只有id
            $sql .= " Where id = {$id}";
        }

        return $this -> fetchOne($sql);
    }

    function del($id){
        $sql = "Delete From `{$this -> table}` ";
        if(is_array($id)){
            // 是陣列
            $where = $this -> a2s($id);
            $sql .= " Where " . join(" && ", $where);
        } else {
            // 只有id
            $sql .= " Where id = {$id}";
        }

        return $this -> pdo -> exec($sql);
    }

    function save($array){
        if(isset($array['id'])){
            // 修改
            $id = $array['id'];
            unset($array['id']);

            $tmp = $this -> a2s($array);

            $sql = "Update `{$this -> table}` Set " . join(", ", $tmp) . " Where id = {$id}";
        } else {
            // 新增
            $keys = array_keys($array);

            $sql = "Insert Into `{$this -> table}`(`" . join("`, `", $keys) . "`) Values ('" . join("', '", $array) . "') ";
        }
        // echo $sql;
        // return $sql;
        return $this -> pdo -> exec($sql);
    }

    protected function math($math, $col = 'id', $where = [],$arg = ''){
        $sql = "Select $math($col) From `{$this -> table}`";
        if(!empty($where)){
            $tmp = $this -> a2s($where);
            $sql .= " WHERE " . join(" && ", $tmp);
        }
        if(!empty($arg)){
            $sql .= $arg;
        }
        // echo $sql;
        return $this -> pdo -> query($sql) -> fetchColumn();
    }

    function count($where = [], $arg = ''){
        return $this -> math("count", "*", $where, $arg);
    }
    function sum($col, $where = [], $arg = ''){
        return $this -> math("sum", $col, $where, $arg);
    }
    function avg($col, $where = [], $arg = ''){
        return $this -> math("avg", $col, $where, $arg);
    }
    function max($col, $where = [], $arg = ''){
        return $this -> math("max", $col, $where, $arg);
    }
    function min($col, $where = [], $arg = ''){
        return $this -> math("min", $col, $where, $arg);
    }


    protected function fetchOne($sql){
        return $this -> pdo -> query($sql) -> fetch(PDO::FETCH_ASSOC);
    }
    protected function fetchAll($sql){
        return $this -> pdo -> query($sql) -> fetchAll(PDO::FETCH_ASSOC);
    }

    function a2s($array){
        $tmp = [];
        foreach($array as $key => $value){
            $tmp[] = "`{$key}` = '{$value}'";
        }
        // dd($array);
        return $tmp;
    }
}

function q($sql){
    $dbn = "mysql:host=localhost;charset=utf8;dbname=sugar_blossom";
    $pdo = new PDO($dbn, 'root', '');
    return $pdo -> query($sql) -> fetchAll();
}

function dd($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function to($url){
    header("location:" . $url);
}


$Test = new DB("test");

