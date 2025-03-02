<?php 

namespace App\classes\php;

interface Model {
    public function create($table_name, $data): bool|string;
    public function read($table_name, $data): bool|string;
    public function update();
    public function delete();
}

?>