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
    private $db;

    public function __construct($table) {
        $this->table = $table;
        $this->db = DB();
    }

    public function find($column = NULL, $where = NULL) {
        $column = is_null($column) ? '*' : $column;
        return $this->db->select($this->table, $column, $where);
    }

    public function get($column = NULL, $where = NULL) {
        $column = is_null($column) ? '*' : $column;
        return $this->db->get($this->table, $column, $where);
    }

    public function delete($where) {
        return $this->db->delete($this->table, $where);
    }

    public function insert($datas) {
        return $this->db->insert($this->table, $datas);
    }

    public function update($data, $where = NULL) {
        return $this->db->update($this->table, $data, $where);
    }

    public function quoto($str) {
        return $this->db->quote($str);
    }

    public function error() {
        return $this->db->error();
    }
}