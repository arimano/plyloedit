<?php

abstract class DBData {

    public $id;
    public $comments;
    public $comments_count;

    /**
     * 
     * Връзка с базата
     * @var ADODB_mysql
     */
    protected $dbconn;
    protected $dbconn_archive;
    protected $dbdata_class;
    protected $dbdata_table;
    protected $dbdata_fields;
    protected $err;

    function __construct($dbconn) {
        $this->dbdata_class = 'DBData';
        $this->dbconn = $dbconn;
        $this->err = null;
    }

    protected function seterr($s) {
        $this->err = $s;

    }

    public function err() {
        return ($this->err);
    }

//======FUNCTION FETCH
	/**
	 * Зарежда обекта
	 * 
	 * Връща true ако обектът е зареден,
	 * в противен случай връща false.
	 * 
	 * @param int $id
	 * @return boolean
	 */
    public function fetch($id = null) {

        if ($id == null) $id = $this->id;
        
        if ($id == null) {
            $this->seterr("No " . get_class($this) . " ID specified");
            return (false);
        }

        $q = "SELECT " . implode(",", $this->dbdata_fields) . " FROM " . $this->dbdata_table . " WHERE id = ? ";

        $st = $this->dbconn->Prepare($q);
        if (!$st) {
            $_SESSION['hidden_error'] = "Could not prepare the $this->dbdata_class fetch statement! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        $r = $this->dbconn->GetRow($st, array($id));
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the $this->dbdata_class record! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        
       
        foreach ($this->dbdata_fields as $value) {
            
            if (!in_array($value, array('newick','description'))){
                $this->$value = htmlspecialchars($r[$value]);
            } elseif (in_array($value, array('creation_date','update_date'))) {
                $this->$value = date('d.m.Y, H:i', strtotime($r[$value]));
            } else {
                $this->$value = $r[$value];
            }                        
        }
                
        return true;
    }

    /**
     * Зарежда обектът с данните за първия намерен запис при подаден филтър
     * 
     * Филтърът е масив с ключове 'filter' съдържа текстовия филтър
     * и ключ 'values', което е масив със стойностите нужни за филтъра
     * 
     * Ако има намерени и заредени данни се връща true, в противен случай false
     * 
     * @param array $filter
     * @return boolean
     */
    function fetchByFilter($filter = false) {

        if (!$filter) {
            $filter = array('filter' => '', 'values' => array());
        }

        $q = "SELECT " . $this->dbdata_table . '.' . implode("," . $this->dbdata_table . '.', $this->dbdata_fields) . " FROM " . $this->dbdata_table . ' ' . $filter['filter'];

        $st = $this->dbconn->Prepare($q);
        if (!$st) {
            $_SESSION['hidden_error'] = "Could not prepare the $this->dbdata_class fetch statement! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        $r = $this->dbconn->GetRow($st, $filter['values']);
        
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the $this->dbdata_class record! " . $this->dbconn->ErrorMsg();
            return (false);
        }

        foreach ($this->dbdata_fields as $value) {
            $value_lang = $value."_".$_SESSION['lang'] ;
            
            if (!in_array($value, array('worktime_text','creation_date', 'description', 'created', 'published', 'news', 'html', 'video','address', 'youtube', 'message', 'client_address', 'conditions', 'value_html', 'value'))){
                if(isset($r[$value_lang]) and defined('PUBLIC_MODE')) $this->$value = htmlspecialchars($r[$value_lang]);
                else $this->$value = htmlspecialchars($r[$value]);
            } else {
                if(isset($r[$value_lang]) and defined('PUBLIC_MODE')) $this->$value = $r[$value_lang];
                else $this->$value = $r[$value];
            }
        }

        return true;
    }

    /**
     * Изтрива обектите при подаден филтър
     * 
     * Филтърът е масив с ключове 'filter' съдържа текстовия филтър
     * и ключ 'values', което е масив със стойностите нужни за филтъра
     * 
     * Ако заявката се изпълни коректно връща true, в противен случай false
     * 
     * @param array $filter
     * @return boolean 
     */
    function delByFilter($filter = false) {

        if (!$filter) {
            $filter = array('filter' => '', 'values' => array());
        }

        $q = "DELETE FROM " . $this->dbdata_table . ' ' . $filter['filter'];

        $st = $this->dbconn->Prepare($q);
        if (!$st) {
            $_SESSION['hidden_error'] = "Could not prepare the $this->dbdata_class fetch statement! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        $r = $this->dbconn->Execute($st, $filter['values']);
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not execute the delete query! " . $this->dbconn->ErrorMsg();
            return (false);
        }



        return true;
    }

    /**
     * Връща масив от елементи от текущия клас
     * Филтърът е масив с ключове 'filter' съдържа текстовия филтър
     * и ключ 'values', което е масив със стойностите нужни за филтъра
     * 
     * 
     * @param array $filter
     * @param string $fetch_data - (simple | all)оказва кое поле трябва да се върне и да се използва при последващо филтриране
     * @param boolean $distinct
     * @return boolean|array
     */
    function fetchAll($filter = false, $fetch_data = 'simple', $distinct = false ) {
        if (!$filter) {
            $filter = array('filter' => '', 'values' => array());
        }

        $obj_array = array();
        
        $query = 'select ' . ($distinct ? ' distinct ' : '') . $this->dbdata_table . '.* from ' . $this->dbdata_table . ' ' . $filter['filter'];
        $st = $this->dbconn->Prepare($query);
        if (!$st) {
            $_SESSION['hidden_error'] = "Could not prepare the $this->dbdata_class fetch statement! " . $this->dbconn->ErrorMsg();
            return array();
        }
        $r = $this->dbconn->GetAll($st, $filter['values']);
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the $this->dbdata_class record! " . $this->dbconn->ErrorMsg();
            return array();
        }


        // генерира масив с обекти от резултата
        $obj_array = array();
        if ($r) {
            $is_prepared = false;
            foreach ($r as $row) {
                $obj_row = new $this->dbdata_class($this->dbconn, $row['id']);
                $obj_row->fromArray($row);
                $obj_row->fetchParams();
                if($fetch_data=='all')  $is_prepared = $obj_row->fetchParamsOptimized($is_prepared);
                $obj_array[] = $obj_row;
            }
        }

        return $obj_array;
    }

    /**
     * 
     * @param type $filter
     * @param type $primary_key
     * @return integer
     * 
     */
    function fetchCount($filter = false, $primary_key = 'id') {
        if (!$filter) {
            $filter = array('filter' => '', 'values' => array());
        }

        $obj_array = array();

        $query = 'select count(' . $primary_key . ') from ' . $this->dbdata_table . ' ' . $filter['filter'];
        $st = $this->dbconn->Prepare($query);
        if (!$st) {
            $_SESSION['hidden_error'] = "Could not prepare the $this->dbdata_class fetch statement! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        $r = $this->dbconn->GetAll($st, $filter['values']);
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the $this->dbdata_class record! " . $this->dbconn->ErrorMsg();
            return (false);
        }


        return $r[0][0];
    }

    /**
     * Връща масив от елементи от текущия клас
     * Филтърът е масив с ключове 'filter' съдържа текстовия филтър
     * и ключ 'values', което е масив със стойностите нужни за филтъра
     * 
     * Връща една страница от резултатите
     * 
     * @param array $filter
     * @param string $primary_key - оказва кое поле трябва да се върне и да се използва при последващо филтриране
     * @return boolean|array
     */
    function fetchAllByPages($filter = false, $primary_key = 'id', $page_num = 1, $page_count = 10) {
        if ($page_num <= 0)
            $page_num = 1;
        if (!$filter) {
            $filter = array('filter' => '', 'values' => array());
        }

        $obj_array = array();

        $query = 'select '.$this->dbdata_table.'.* from ' . $this->dbdata_table . ' ' . $filter['filter'] . 'limit ' . (($page_num - 1) * $page_count) . ' , ' . $page_count;
        $st = $this->dbconn->Prepare($query);
        if (!$st) {
            $_SESSION['hidden_error'] = "Could not prepare the $this->dbdata_class fetch statement! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        $r = $this->dbconn->GetAll($st, $filter['values']);
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the $this->dbdata_class record! " . $this->dbconn->ErrorMsg();
            return (false);
        }

        $obj_array = array();
        if ($r) {
            $is_prepared = false;
            foreach ($r as $row) {
                $obj_row = new $this->dbdata_class($this->dbconn, $row['id']);
                $obj_row->fromArray($row);

                $obj_row->fetchParams();
                $is_prepared = $obj_row->fetchParamsOptimized($is_prepared);
                $obj_array[] = $obj_row;
            }
        }

        return $obj_array;
    }

    /**
     * Връща броя на  от резултатите според филтъра
     * Функцията се ползва в комбинация с fetchAllByPages
     * 
     * @param array $filter
     * @param string $primary_key - оказва кое поле трябва да се върне и да се използва при последващо филтриране
     * @return boolean|integer
     */
    function getCountAllResults($filter = false, $primary_key = 'id') {
        if (!$filter) {
            $filter = array('filter' => '', 'values' => array());
        }

        $obj_array = array();

        $query = 'select '.$this->dbdata_table. '.' . $primary_key . ' from ' . $this->dbdata_table . ' ' . $filter['filter'];
        $st = $this->dbconn->Prepare($query);
        if (!$st) {
            $_SESSION['hidden_error'] = "Could not prepare the $this->dbdata_class fetch statement! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        $r = $this->dbconn->GetAll($st, $filter['values']);
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the $this->dbdata_class record! " . $this->dbconn->ErrorMsg();
            return (false);
        }

        return count($r);
    }

    /**
     * Проверява дали всички задължители полета имат стойност в подадения масив.
     * Целта е директно да се подават $_POST, $_GET, $_REQUEST и автоматично да се връща проверка.
     * Ако е нужно преди това тези масиви могат да се манипулират
     * 
     * Ако има липсващи стойности се връщат имената на колоните, за който няма стойности в масив,
     * ако всичко е наред се връща false
     * 
     * @param array $data
     * @param array $required_fields - ако не е подадено автоматично се попълва със стойността на $this->dbdata_required
     * @return multitype:array|boolean
     */
    public function checkRequiredData($data, $required_fields = false) {
        if (!$required_fields) {
            if (isset($this->dbdata_required)) {
                $required_fields = $this->dbdata_required;
            } else {
                $required_fields = array();
            }
        }

        $return = array();


        foreach ($required_fields as $required) {
            if (empty($data[$required])) {
                $return[$required] = 'Полето е задължително!';
            }
        }

        if (!empty($return)) {
            return $return;
        }

        return false;
    }

    /**
     * Генерира масив с ключове 
     * 'columns' - имена на колони от таблицата
     * 'valuse' - стойности за колините от таблицата
     * 
     * Целта е като входни параметри да се подават $_POST, $_GET, $_REQUEST,
     * а върнатото директно да се подава към $this->insert и $this->update
     * 
     * @param array $data
     * @param array $columns - ако не е подадено се използва $this->dbdata_fields
     * @param boolean $add_empty - оказва дали колоните за които няма стойности да се връщата
     * @return array
     */
    public function generateColumnsValues($data, $columns = array(), $add_empty = false) {
        if (empty($columns)) {
            $columns = $this->dbdata_fields;
        }

        $return = array();

        foreach ($columns as $column) {

            //ако параметърът е файл
            if (!empty($_FILES[$column]['name'])) {

                if ($this->id > 0) {
                    $this->fetch();

                    if (!file_exists(ROOT_PATH . "images")) {
                        mkdir(ROOT_PATH . "images", 0755);
                        chmod(ROOT_PATH . "images", 0755);
                    }
                    if (!file_exists(ROOT_PATH . "images/$this->dbdata_class")) {
                        mkdir(ROOT_PATH . "images/$this->dbdata_class", 0755);
                        chmod(ROOT_PATH . "images/$this->dbdata_class", 0755);
                    }
                    
                    $pos = strpos($this->$column, 'no_picture');
                    if ($pos===false and file_exists(ROOT_PATH . "images/" . $this->dbdata_class . "/" . $this->$column))
                        unlink(ROOT_PATH . "images/" . $this->dbdata_class . "/" . $this->$column);
                }

                $file_arr = explode(".", $_FILES[$column]['name']);
                end($file_arr);
                $file_suff = current($file_arr);
                $database_file = $file_arr[0] . "_" . $this->dbdata_class . "_" . $this->id . "_" . rand(0, 100) . "." . $file_suff;
                $new_file = ROOT_PATH . "images/" . $this->dbdata_class . "/" . $database_file;



                if (!move_uploaded_file($_FILES[$column]['tmp_name'], $new_file)) {
                    $_SESSION['system_error'] = _("Неуспешно качване на файл! Обадете се на администратор!");
                } else {
                    chmod($new_file, 0755);
                }

                $data[$column] = $database_file;
            }
            
            //Качва снимки за аватар на профилите
           
            if($column=='photo' and isset($_REQUEST[$column]) and $_REQUEST[$column]) {
                if(strpos($_REQUEST[$column], '/temp/') !== false ){
                    $photo_arr = explode('/', $_REQUEST[$column]);
                    end($photo_arr);
                    $type_path = 'uploads/Profile/' . $_SESSION['profile']->profile_type->sysname .'/';

                    if (!file_exists($type_path)) {
                        mkdir($type_path);
                        chmod($type_path, 0755);
                    }
                    
                    if (!file_exists($type_path .'/thumb/')) {
                        mkdir($type_path .'/thumb/');
                        chmod($type_path .'/thumb/', 0755);
                    }

                    chmod ($_REQUEST[$column], 0644);

                    if (copy($_REQUEST[$column], $type_path . current($photo_arr))) {
                      chmod ($type_path . current($photo_arr), 0755);

                        $pos = strpos($_SESSION['profile']->photo, 'no_picture');
                        if($pos===false and file_exists($type_path . $_SESSION['profile']->photo) and is_file($type_path . $_SESSION['profile']->photo))unlink($type_path . $_SESSION['profile']->photo);
                        
                        $pos = strpos($_REQUEST[$column], 'no_picture');
                        if($pos===false) unlink($_REQUEST[$column]);
                    }
                    
                    $inner_path_thumb = $type_path. 'thumb/';
                    $destination_thumb = MAIN_PATH . $inner_path_thumb;
               
                    $upload = new Upload($type_path . current($photo_arr), $destination_thumb, current($photo_arr), array('maxSize' => 10 * 1024, 'fileType' => array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png'), 'possibleLocation' => $destination_thumb));
                    $upload->resizeToWidth(40);
                    $upload->saveThumb(0755);
          
                    $data['thumb'] = $inner_path_thumb . current($photo_arr);
                    $data[$column] = $type_path . current($photo_arr);
                }
            }
            
            //Качва снимки за аватар на профилите
           
            if($column=='photo_profile' and isset($_REQUEST[$column]) and $_REQUEST[$column]) {

                $photo_arr = explode('/', $_REQUEST[$column]);
                end($photo_arr);
                $type_path = 'uploads/Profile/' . $_SESSION['profile']->profile_type->sysname .'/';

                if (!file_exists($type_path)) {
                    mkdir($type_path);
                    chmod($type_path, 0755);
                  }
                  
                /*if (!file_exists($type_path .'/thumb/')) {
                   mkdir($type_path .'/thumb/');
                   chmod($type_path .'/thumb/', 0755);
                 }*/


                chmod ($_REQUEST[$column], 0644);
                 
                if (copy($_REQUEST[$column], $type_path . current($photo_arr))) {
                  chmod ($type_path . current($photo_arr), 0755);
                  
                  $pos = strpos($_SESSION['profile']->photo_profile, 'no_picture');    
                  if($pos===false and file_exists($type_path . $_SESSION['profile']->photo_profile) and is_file($type_path . $_SESSION['profile']->photo_profile))unlink($type_path . $_SESSION['profile']->photo_profile);
                  
                  $pos = strpos($_REQUEST[$column], 'no_picture');      
                  if($pos===false) unlink($_REQUEST[$column]);
                } 
                
                //Закоментирано от Калоян - бъркат се профилна снимка и с презентационна снимка и създава грешен тъмб
                //$inner_path_thumb = $type_path. 'thumb/';
                //$destination_thumb = MAIN_PATH . $inner_path_thumb;
                //$upload = new Upload($type_path . current($photo_arr), $destination_thumb, current($photo_arr), array('maxSize' => 10 * 1024, 'fileType' => array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png'), 'possibleLocation' => $destination_thumb));
                
                $upload = new Upload($type_path . current($photo_arr), $type_path, current($photo_arr), array('maxSize' => 10 * 1024, 'fileType' => array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png'), 'possibleLocation' => $type_path));
                
                //$upload->resizeToWidth(40);
                //$upload->saveThumb(0755);
                //$data['thumb'] =  $inner_path_thumb . current($photo_arr);
                
                $data[$column] = $type_path . current($photo_arr);

            }


            if (!isset($data[$column])) {
                if ($add_empty) {
                    $return['columns'][] = $column;
                    $return['values'][] = '';
                }
            } elseif ($column != 'id') {

                $return['columns'][] = $column;
                $search = array(
                    '@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
                 ); 
                $return['values'][] = preg_replace($search, '', $data[$column]); 
            }
        }

        return $return;
    }

    /**
     * Генерира масив с данните за обекта
     * 
     * @param array $columns - списък на нужните колони, които трябва да бъдат върнати. 
     * 	Ако не е подаден се връща целия списък от колони. Ако подаденията списък има ключове
     *  се използват неговите ключове.
     * @return array
     */
    public function toArray($columns = array()) {
        $return = array();

        if (empty($columns)) {
            $columns = $this->dbdata_fields;
        }

        foreach ($columns as $key => $column) {
            if (is_numeric($key)) {
                $key = $column;
            }
            if (isset($this->$column)) {
                $return[$key] = $this->$column;
            } else {
                $return[$key] = '';
            }
        }

        return $return;
    }

    /**
     * Попълва данните от масив в колоните на елемента
     * 
     * @param array $data - асоциативен масив с данните
     * @param array $mapping - ако ключа на данните в масива не съвпада с името на колоната тук се прави мапинг
     */
    public function fromArray($data, $mapping = array()) {
        foreach ($this->dbdata_fields as $column) {
            $column_lang = $column."_".$_SESSION['lang'];
            $key = isset($mapping[$column]) ? $mapping[$column] : $column;
            $key_lang = isset($mapping[$column_lang]) ? $mapping[$column_lang] : $column_lang;
            
            if (isset($data[$key_lang]) and defined('PUBLIC_MODE')) {
                $this->$column = $data[$key_lang];
            } elseif (isset($data[$key])) {
                $this->$column = $data[$key];
            }
        }
    }

    /**
     * Генерира филтър от подадени данни
     * 
     * Целта е като входни параметри да се подават $_POST, $_GET, $_REQUEST,
     * а върнатото директно да се подава към $this->fetchAll или $this->fetchByFilter
     * 
     * @param array $data
     * @param array $columns - ако не е подадено се използва $this->dbdata_fields
     * @param boolean $add_empty - оказва дали колоните за които няма стойности да се включат във филтъра
     * @return Ambigous <multitype:string multitype: , unknown>
     */
    public function generateFilter($data, $columns = array(), $add_empty = false) {
        if (empty($columns)) {
            $columns = $this->dbdata_fields;
        }

        $return = array('filter' => 'where 1 = 1', 'values' => array());

        foreach ($columns as $column) {
            if (empty($data[$column])) {
                if ($add_empty) {
                    $return['filter'] .= ' and ' . $column . " = ''";
                }
            } else {
                $return['filter'] .= ' and ' . $column . " = ?";
                $return['values'][] = $data[$column];
            }
        }

        return $return;
    }

    /**
     * Изпълнява подадения селект, като връща резултата от изпълнението
     * 
     * Филтърът е масив с ключове 'filter' съдържа текстовия филтър
     * и ключ 'values', което е масив със стойностите нужни за филтъра
     * 
     * Задължително трябва да се подават заявки само за select
     * 
     * При успешно изпълнение се връяа array, а при грешка се
     * връща false
     * 
     * @param string $query - задължително трябва да е select ...
     * @param array $filter
     * @return mixed: false|array
     */
    function select($query, $filter = false) {

        if (!$filter) {
            $filter = array('filter' => '', 'values' => array());
        }

        $query .= ' ' . $filter['filter'];


        $st = $this->dbconn->Prepare($query);

        if (!$st) {
            $_SESSION['hidden_error'] = "Could not prepare the $this->dbdata_class fetch statement! " . $this->dbconn->ErrorMsg();
            return (false);
        }

        $r = $this->dbconn->GetAll($st, $filter['values']);
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the $this->dbdata_class record! " . $this->dbconn->ErrorMsg();
            return (false);
        }

        return empty($r) ? false : $r;
    }

//======FUNCTION INSERT	
    public function insert($cols, $vars, $archive = 0) {
        $cols_update = $cols;
        $vars_update = $vars;
        
        $q = "INSERT INTO " . $this->dbdata_table . "(" . implode(",", $cols) . ") VALUES (" . str_repeat("?,", count($vars) - 1) . "?)";
        $st = $this->dbconn->Prepare($q);
        $r = $this->dbconn->Execute($st, $vars);
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the " . get_class($this) . " record! " . $this->dbconn->ErrorMsg();
            return (false);
        }

        //get last inserted id
        $q = "SELECT id from " . $this->dbdata_table . " order by id desc limit 1";
        $r = $this->dbconn->GetAll($q);
        $this->id = $r[0][0];
        $id = $r[0][0];
        
        //generate log
        if(in_array($this->dbdata_table, array('profiles','contents')) and $archive==1){
            $cols_update[] = 'event';
            $vars_update[] = 'insert';

            $cols_update[] = 'event_id';
            $vars_update[] = $this->id;

            $cols_update[] = 'event_profile_id';
            $vars_update[] = $_SESSION['profile_id'];

            $cols_update[] = 'event_date';
            $vars_update[] = date("Y-m-d H:i:s");


            $dbconn_archive = DBConn::connectArchive();
            $q_archive = "INSERT INTO " . $this->dbdata_table . "(" . implode(",", $cols_update) . ") VALUES (" . str_repeat("?,", count($vars_update) - 1) . "?)";
            $st_archive = $dbconn_archive->Prepare($q_archive);
            $r_archive = $dbconn_archive->Execute($st_archive, $vars_update);
        }
        $dbconn = DBConn::getInstance()->conn;
        $this->dbconn = $dbconn;
        
        return $id;
    }
    
    /**
     * Записва данните
     * 
     * Ако има id се прави update, 
     * в противен случай прави insert.
     * 
     * При грешка връща false. 
     * Ако няма грешка се връща новото id,
     * или true.
     * 
     * @return mixed: boolean, int
     */
    public function save() {
    	$columns = array();
    	$values = array();
    	foreach($this->dbdata_fields as $column) {
    		if ($column == 'id') {
    			continue;
    		}
    		
    		if (isset($this->$column)) {
    			$columns[] = $column;
    			$values[] = $this->$column;
    		}
    	}
    	
    	if (empty($this->id)) {
    		return $this->insert($columns, $values);
    	} else {
            
    		return $this->update($columns, $values);
    	}
    }

//======FUNCTION UPDATE	
    public function update($cols, $vars, $archive = 0) {

        $cols_update = $cols;
        $vars_update = $vars;
        
        $q = "UPDATE " . $this->dbdata_table . " SET " . implode("=?,", $cols) . "=? WHERE id=?";
        $st = $this->dbconn->Prepare($q);
        $vars[] = $this->id;
        
        $r = $this->dbconn->Execute($st, $vars);
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the " . get_class($this) . " record! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        
        //generate log
        if(in_array($this->dbdata_table, array('profiles','contents')) and $archive == 1){
            $cols_update[] = 'event';
            $vars_update[] = 'update';

            $cols_update[] = 'event_id';
            $vars_update[] = $this->id;

            $cols_update[] = 'event_profile_id';
            $vars_update[] = $_SESSION['profile_id'];

            $cols_update[] = 'event_date';
            $vars_update[] = date("Y-m-d H:i:s");


            $dbconn_archive = DBConn::connectArchive();
            $q_archive = "INSERT INTO " . $this->dbdata_table . "(" . implode(",", $cols_update) . ") VALUES (" . str_repeat("?,", count($vars_update) - 1) . "?)";
            $st_archive = $dbconn_archive->Prepare($q_archive);
            $r_archive = $dbconn_archive->Execute($st_archive, $vars_update);
        }
        $dbconn = DBConn::getInstance()->conn;
        $this->dbconn = $dbconn;
        
        return (true);
    }

//======FUNCTION DELETE
    public function del($archive=0) {
        $q = "DELETE FROM  " . $this->dbdata_table . " WHERE id=? ";
        $st = $this->dbconn->Prepare($q);
        $r = $this->dbconn->Execute($st, array($this->id));
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the " . get_class($this) . " record! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        //generate log
        if(($this->dbdata_table =='profiles' || $this->dbdata_table=='contents') and $archive==1){
            $cols_update=array();
            $vars_update=array();

            $cols_update[] = 'event';
            $vars_update[] = 'delete';

            $cols_update[] = 'event_id';
            $vars_update[] = $this->id;

            $cols_update[] = 'event_profile_id';
            $vars_update[] = $_SESSION['profile_id'];

            $cols_update[] = 'event_date';
            $vars_update[] = date("Y-m-d H:i:s");


            $dbconn_archive = DBConn::connectArchive();
            $q_archive = "INSERT INTO " . $this->dbdata_table . "(" . implode(",", $cols_update) . ") VALUES (" . str_repeat("?,", count($vars_update) - 1) . "?)";
            $st_archive = $dbconn_archive->Prepare($q_archive);
            $r_archive = $dbconn_archive->Execute($st_archive, $vars_update);
        }
        $dbconn = DBConn::getInstance()->conn;
        $this->dbconn = $dbconn;
        
        return (true);
    }

    function fetchParams() {

        return true;
    }
    function fetchParamsOptimized() {

        return true;
    }

    /**
     * Връща масив от елементи от текущия клас
     * Филтърът е масив с ключове 'filter' съдържа текстовия филтър
     * и ключ 'values', което е масив със стойностите нужни за филтъра
     * 
     * 
     * @param array $filter
     * @param string $primary_key - оказва кое поле трябва да се върне и да се използва при последващо филтриране
     * @return boolean|array
     */
    function fetchOptimized($joins = array(), $st = false) {
        
        $fields = array();
        foreach ($this->dbdata_fields as $field) {
            //добавя таблицата към имената на колоните, за да не се получи  конфликт с другите таблици
            $fields[] = $this->dbdata_table . "." . $field . " as " . $this->dbdata_table . "_" . $field;
            $mapping[$field] = $this->dbdata_table . "_" . $field;
        }
        $query = '';
        if ($joins) {
            foreach ($joins as $join) {
                $join_object = new $join['class']($this->dbconn);
                $parameters = $join['parameters'];
                
                if (is_array($join['parameters'])) {
                    //multi join - n:m
                    if (empty($join['parameters']['index']))
                        $join['parameters']['index'] = 'id';
                    $query .= " LEFT JOIN " . $parameters['tbl_name'] . " ON " . $parameters['tbl_name'] . "." . $parameters['join_column'] . "=" . $this->dbdata_table . "." . $join['parameters']['index'] . " ";
                    $query .= " LEFT JOIN " . $join_object->dbdata_table . " ". $join['object'] . " ON " . $parameters['tbl_name'] . "." . $parameters['class_column'] . "=" . $join['object'] . ".id  ";
                } else {
                    //single join - 1:n
                    $query .= " LEFT JOIN " . $join_object->dbdata_table. " ". $join['object'] . " ON " . $this->dbdata_table . "." . $parameters . "=" . $join['object'] . ".id  ";
                }
                foreach ($join_object->dbdata_fields as $field)
                    $fields[] = $join['object'] . "." . $field . " as " . $join['object'] . "_" . $field;
            }
        }

        $query = 'select ' . implode(',', $fields) . ' from ' . $this->dbdata_table . " " . $query . " WHERE " . $this->dbdata_table . ".id = ?" ;

        if(!$st) $st = $this->dbconn->Prepare($query);
        if (!$st) {
            $_SESSION['hidden_error'] = "Could not prepare the $this->dbdata_class fetch statement! " . $this->dbconn->ErrorMsg();
            return (false);
        }
        
        $r = $this->dbconn->GetAll($st, array($this->id));
        if (!$r) {
            $_SESSION['hidden_error'] = "Could not fetch the $this->dbdata_class record! " . $this->dbconn->ErrorMsg();
            return (false);
        }


        // генерира масив с обекти от резултата
        $obj_array = array();
        if ($r) {
            foreach ($joins as $join) {

                $this->$join['object'] = new $join['class']($this->dbconn);
                $mapping = array();
                foreach ($this->$join['object']->dbdata_fields as $field) {
                    $mapping[$field] = $join['object'] . "_" . $field;
                }
                if (!is_array($join['parameters'])) {
                    //multi join - n:m        
                    $this->$join['object']->fromArray($r[0], $mapping);
                } else {
                    
                    $this->$join['object'] = array();
                    foreach ($r as $row) {
                    	if (!empty($row[$join['class'] . '_id'])) {
	                        $obj_row = new $join['class']($this->dbconn, $row[$join['class'] . '_id']);
	                        if (!in_array($row[$join['class'] . '_id'], $this->$join['object'])) {
	                            $obj_row->fromArray($row, $mapping);
	                            $obj_row->fetchParams();
	                            $this->{$join['object']}[$row[$obj_row->dbdata_table . '_id']] = $obj_row;
	                        }
                    	} else {
                    		$obj_row = new $join['class']($this->dbconn);
                    	}
                    }
                }
            }
        }

        return $st;
    }

        
    
}

?>