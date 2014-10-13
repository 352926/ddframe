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

    public function get_list($where, $page, $page_size, $column = NULL) {
        $page = intval($page);
        $page = $page ? ($page - 1) * $page_size : 0;
        $where['LIMIT'] = array($page, $page_size);
        if (!isset($where['ORDER'])) {
            $where['ORDER'] = 'id DESC';
        }
        $list = $this->find($where, $column);
        return $list;
    }

    public function get_by_id($id, $column = NULL) {
        if (!is_numeric($id) || !$id) {
            return FALSE;
        }
        return $this->get(array('id' => $id), $column);
    }

    public function update_by_id($id, $data) {
        if (!$data || !is_array($data)) {
            return FALSE;
        }
        $data['gmt_modified'] = time();
        return $this->update($data, array('id' => $id));
    }

    public function del_by_id($id) {
        if (!$id) {
            return FALSE;
        }
        return $this->delete(array('id' => $id));
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

    public function error($idx = NULL) {
        $error = $this->db->error();
        if (is_numeric($idx)) {
            return isset($error[$idx]) ? $error[$idx] : print_r($error, TRUE);
        }
        return $error;
    }
}

spl_autoload_register('load_model');