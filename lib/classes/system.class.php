<?php

require_once "DBData.class.php";

class system extends DBData {

    private static $instance;

    //constructor
    function __construct($dbconn, $system_id = null) {
        parent::__construct($dbconn);
    }

    public static function getInstance($dbconn, $system_id = null) {

        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className($dbconn, $system_id = null);
        }
        return self::$instance;
    }

    public function getAllElements($class_name, $sort_type = '', $limit = '', $where = '') {
        $sys_obj = new $class_name($this->dbconn);
        //get all IDs

        switch ($sort_type) {
            case 'desc':
                $q = "select id from " . $sys_obj->dbdata_table . " where 1=1 " . $where . " order by id desc";
                break;
            case 'state':
                $q = "select id from " . $sys_obj->dbdata_table . " where state=1 " . $where . "  order by id desc";
                break;

            default:
                if ($sort_type != '')
                    $q = "select id from " . $sys_obj->dbdata_table . " where 1=1 " . $where . "  order by  " . $sort_type;
                else
                    $q = "select id from " . $sys_obj->dbdata_table . " where 1=1 " . $where . "  order by id ";
                break;
        }

        if ($limit)
            $q .=" limit " . $limit;
//echo $q;
        $st = $this->dbconn->Prepare($q);
        $r = $this->dbconn->GetAll($st, array());
        $obj_array = array();
        if ($r)
            foreach ($r as $id) {
                $obj_row = new $class_name($this->dbconn, $id[0]);
                $obj_row->fetch();
                $obj_row->fetch_params();
                $obj_array[] = $obj_row;
            }
        $array_name = $class_name . "_array";
        $this->$array_name = $obj_array;
        return $obj_array;
    }

    public function getCount($table_name, $filter = '') {
        $q = "select count(id) from " . $table_name . " where 1=1 " . $filter;
        $st = $this->dbconn->Prepare($q);
        $r = $this->dbconn->getAll($st, array());

        return($r[0][0]);
    }

}

?>
