<?php

namespace elzobrito;

interface Model
{
    public function getTable();
    public function update($fields, $wheres, $values);
    public function save($fields = null, $valores = null);
    public function delete();
    public function find($fields, $wheres, $values, $join = null, $group = null, $order = null, $having = null, $limit = null);
    public function all($fields = null);
    public function count($wheres = null, $values = null, $join = null, $group = null, $order = null, $having = null, $limit = null);
    public function findForId($id, $primaryKey = null);
    public function values();
    public function request_cripto();
    public function request($fields = null);
    public function fill();
}
