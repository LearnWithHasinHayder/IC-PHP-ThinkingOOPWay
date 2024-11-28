<?php
class QueryBuilder {
    private $select;

    private $from;

    private $where = [];

    // id > 1 AND name LIKE %doe% AND age > 18

    private $limit;

    private $orderBy;

    function select($fields) {
        $this->select = $fields;
        return $this;
    }

    function from($table) {
        $this->from = $table;
        return $this;
    }

    function where($field, $operator, $value) {
        $this->where[] = "{$field} {$operator} '{$value}'"; // id > 1
        return $this;
    }

    function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    function orderBy($field, $order = 'ASC') {
        $this->orderBy = "{$field} {$order}";
        return $this;
    }

    function get() {
        $query = "";
        $query .= "SELECT {$this->select} ";
        $query .= "FROM {$this->from} ";
        if ($this->where) {
            $conditions = implode(' AND ', $this->where);
            $query .= "WHERE {$conditions} ";
        }

        if ($this->orderBy) {
            $query .= "ORDER BY {$this->orderBy} ";
        }

        if ($this->limit) {
            $query .= "LIMIT {$this->limit} ";
        }

        return $query;
    }

}

// $qb = new QueryBuilder;
// // $query = $qb->select('name, roll, email')->from('users')->where('id','>','1')->where('name','LIKE','%doe%')->limit(10)->get();
// $query = $qb->select('name, roll, email')
//     ->from('users')
//     ->where('id', '>', '1')
//     ->where('name', 'LIKE', '%doe%')
//     ->orderBy('name', 'DESC')
//     ->limit(10)->get();
// echo $query;

$connection = new PDO('mysql:host=127.0.0.1;dbname=Chinook', 'root', '');
$qb = new QueryBuilder;
$query = $qb->select('*')
    ->limit(10)
    ->orderBy('Title', 'DESC')
    ->where('id', '>', '1')
    ->from('Albums')
    // ->where('Title', 'LIKE', 'U%')
    ->get();

echo $query;
$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_OBJ);
print_r($result);

//SELECT name FROM users WHERE id > 1 AND name LIKE '%doe%' LIMIT 10