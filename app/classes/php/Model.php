<?php 

namespace App\classes\php;

interface Model {
    public function create($table_name, $data);
    public function read($table_name, $data): bool|string;
    public function update($table_name, $data): bool|string;
    public function delete($table_name, $data): bool|string;
}

?>