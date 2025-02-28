<?php 

namespace App\classes;

interface Model {
    public function create($table_name, $data): bool|string;
    public function update();
    public function delete();
}

?>