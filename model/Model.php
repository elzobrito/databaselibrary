<?php

namespace elzobrito\Model;

use elzobrito\QueryBuilder;
use Lib\Database;
use elzobrito\Model as iModel;

class Model implements iModel
{
    protected $table;
    protected $drive;
    protected $fillable = array();

    /**
     * @var array
     */
    private $clausules = [];

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    function __call($name, $arguments)
    {
        $clausule = $arguments[0];
        if (count($arguments) > 1) {
            $clausule = $arguments;
        }
        $this->clausules[strtolower($name)] = $clausule;

        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function update($fields, $wheres, $values)
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        return  $qb
            ->table($this->table)
            ->fields($fields)
            ->where($wheres)
            ->update($values);
    }

    public function save($fields = null, $valores = null)
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        return $qb
            ->table($this->table)
            ->fields(($fields ?? $this->fill()))
            ->insert(($valores ?? $this->values()));
    }

    public function delete()
    {
        if (isset($this->id)) {
            $qb = new QueryBuilder(Database::getDB($this->drive));
            return $qb
                ->table($this->table)
                ->where(['id = ?'])
                ->delete([$this->id]);
        }
    }

    public function find($fields, $wheres, $values, $join = null, $group = null, $order = null, $having = null, $limit = null)
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        return $qb->table($this->table)
            ->fields($fields)
            ->where($wheres)
            ->join($join)
            ->group($group)
            ->order($order)
            ->having($having)
            ->limit($limit)
            ->select($values);
    }


    public function all($fields = null, $order = null, $limit = null)
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        return $qb->table($this->table)
            ->fields($fields ?? ['*'])
            ->order($order)
            ->limit($limit)
            ->select();
    }

    public function count($wheres = null, $values = null, $join = null, $group = null, $order = null, $having = null, $limit = null)
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        return $qb->table($this->table)
            ->fields(['count(*) as total'])
            ->where($wheres)
            ->join($join)
            ->group($group)
            ->order($order)
            ->having($having)
            ->limit($limit)
            ->select($values);
    }

    public function findForId($id, $primaryKey = null)
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        return $qb->table($this->table)
            ->fields(['*'])
            ->where([($primaryKey ?? 'id') . ' = ?'])
            ->select([$id]);
    }

    public function values()
    {
        $filds = [];
        $array = [];
        $filds = $this->fill();
        foreach ($this as $key => $value) {
            if (in_array($key, $filds))
                $array[] = $value;
        }
        return $array;
    }

    /**
     * Este método é utilizado para pegar parametros criptografados via POST
     * Então você pode criar o método decryptIt para fazer essa descriptografia. 
     */
    public function request_cripto()
    {
        $val_temp = null;
        foreach ($this->fillable as $key => $value)
            if (substr($value, 0, 2) == 'id') {
                if (isset($_REQUEST[$value]))
                    if (!is_numeric($_REQUEST[$value])) {
                        $val_temp = $this->decryptIt($_REQUEST[$value]);
                        if (is_numeric($val_temp)) {
                            $this->$value = $val_temp;
                        } else {
                            $this->$value = $_REQUEST[$value];
                        }
                    } else {
                        $this->$value = $_REQUEST[$value];
                    }
            } else {
                $this->$value = $_REQUEST[$value];
            }
    }

    public function request($fields = null)
    {
        $campos = $fields ?? $this->fillable;
        foreach ($campos as $key => $value)
            if (isset($_REQUEST[$value]))
                $this->$value = $_REQUEST[$value];
    }

    public function fill()
    {
        $array = [];
        foreach ($this->fillable as $key => $value) {
            $array[] = $value;
        }
        return $array;
    }
}
