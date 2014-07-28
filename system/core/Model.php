<?php
/**
 * User: 352926 <352926@qq.com>
 * Date: 14-4-24
 * Time: 14:42
 */

/**
 * Class DD_Model
 * 相关用法:http://medoo.in/doc
 */
class DD_Model {
    private $table = NULL;
    public $db;

    public function __construct($table = '') {
        $class = get_class($this);
        if ($class == 'DD_Model') {
            $this->table = $table;
        } else {
            $this->table = strtolower($class);
        }
        $this->db = DB();
    }

    public function find($where = NULL, $column = NULL) {
        $column = is_null($column) ? '*' : $column;
        return $this->table ? $this->db->select($this->table, $column, $where) : FALSE;
    }

    public function get_count($where) {
        return $this->db->count($this->table, $where);
    }

    public function get($where = NULL, $column = NULL) {
        $column = is_null($column) ? '*' : $column;
        return $this->table ? $this->db->get($this->table, $column, $where) : FALSE;
    }

    public function delete($where) {
        return $this->table ? $this->db->delete($this->table, $where) : FALSE;
    }

    public function insert($datas) {
        return $this->table ? $this->db->insert($this->table, $datas) : FALSE;
    }

    public function last_query() {
        return $this->db->last_query();
    }

    public function update($data, $where = NULL) {
        return $this->table ? $this->db->update($this->table, $data, $where) : FALSE;
    }

    public function quoto($str) {
        return $this->db->quote($str);
    }

    public function error() {
        return $this->db->error();
    }
}

spl_autoload_register('load_model');