<?php defined('BASEPATH') or exit('No direct script access allowed');

class AppModel extends CI_Model
{
    protected $table;

    protected $field_primary_key = 'id';
    protected $field_created     = 'created';
    protected $field_modified    = 'modified';
    protected $field_published   = 'published';
    protected $fields            = '*';

    protected $default_args = array(
        'limit'      => null,
        'offset'     => null,
        'published'  => true,
        'conditions' => array(),
        'order'      => array(),
    );


    public function __construct()
    {
        parent::__construct();
        log_message('debug', 'AppModel Class Initialized');
        //@TODO: Programar datatables
        //isset($this->datatables) or $this->load->library('Datatables');
    }

    /**
     * Retorna os campos
     *
     * @param boolean $database Caso queira que seja retornado todos os campos da tabela
     * @return array
     */
    public function getFields($database = false)
    {
        if ($database === true) {
            $fields = $this->db->list_fields($this->table);
        } else {
            if ($this->fields === '*') {
                $this->fields = $this->getFields(true);
            }
            $fields = is_array($this->fields) ? $this->fields : explode(',', $this->fields);
        }

        return $fields;
    }

    protected function offset($limit, $page)
    {
        $page = ceil($page) - 1;
        $page = $page <= 0 ? 0 : $page;
        $offset = $page * $limit;
        return $offset < 0 ? 0 : ceil($offset);
    }

    protected function orderBy(array $order = array())
    {
        if (!empty($order)) {
            if (is_array($order)) {
                foreach ($order as $key => $value) {
                    if (is_numeric($key)) {
                        $this->db->order_by($value, 'ASC');
                    } else {
                        $this->db->order_by($key, $value);
                    }
                }
            } elseif (is_string($order)) {
                $this->db->order_by($order);
            }
        }
    }

    private function checkIsSetTable()
    {
        if (!isset($this->table) || empty($this->table)) {
            log_message('error', 'Table name is not defined '.$this->table);
            return false;
        }
        return true;
    }

    public function getAll($limit = null, $offset = null, $published = true, $from = null)
    {
        if ($this->checkIsSetTable()) {
            if ($limit != null && $offset != null) {
                $this->db->limit($limit, $offset);
            } elseif ($limit != null) {
                $this->db->limit($limit);
            }
            if ($published) {
                if ($this->db->field_exists($this->field_published, $this->table)) {
                    $this->db->where($this->table.'.'.$this->field_published, true);
                }
            }
            if (!empty($from)) {
                $this->db->from($from);
            } else {
                $this->db->from($this->table);
            }
            return $this->db->get()->result_array();
        }
        return false;
    }

    public function getById($id = null)
    {
        return (!empty($id)) ? $this->getBy($this->table.'.'.$this->field_primary_key, $id) : false;
    }

    public function getBy($key, $value = null)
    {
        $this->db->where($key, $value);
        $this->db->limit(1);
        $result = $this->getAll();
        return isset($result[0]) ? $result[0] : false;
    }

    public function getAllBy($key, $value = null)
    {
        $this->db->where($key, $value);
        $result = $this->getAll();
        return $result;
    }

    public function getCount($published = true)
    {
        if ($this->checkIsSetTable()) {
            if ($published) {
                if ($this->db->field_exists($this->field_published, $this->table)) {
                    $this->db->where($this->table.'.'.$this->field_published, true);
                }
            }
            return $this->db->count_all_results($this->table);
        }
        return false;
    }

    public function add(array $args = array())
    {
        if ($this->checkIsSetTable()) {
            if ($this->db->field_exists($this->field_created, $this->table)) {
                $args[$this->field_created] = date('Y-m-d H:i:s');
            }
            if ($this->db->field_exists($this->field_modified, $this->table)) {
                $args[$this->field_modified] = date('Y-m-d H:i:s');
            }
            $this->db->insert($this->table, $args);
            return $this->db->insert_id();
        }
        return false;
    }

    public function edit(array $args = array(), $where = array())
    {
        if ($this->checkIsSetTable()) {
            if (is_array($where)) {
                $this->db->where($where);
            } else {
                $this->db->where($this->table.'.'.$this->field_primary_key, $where);
            }
            if ($this->db->field_exists($this->field_modified, $this->table)) {
                $args[$this->field_modified] = date('Y-m-d H:i:s');
            }
            return $this->db->update($this->table, $args);
        }
        return false;
    }

    public function delete($id = false)
    {
        if ($this->checkIsSetTable() && $id) {
            $this->db->where($this->table.'.'.$this->field_primary_key, $id);
            if ($this->db->delete($this->table)) {
                return true;
            }
        }
        return false;
    }
}

/* End of file AppModel.php */
/* Location: ./application/core/AppModel.php */
