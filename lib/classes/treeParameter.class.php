<?php

class TreeParameter extends DBData {

    public $id;
    public $user_id;
    public $tree_id;
    public $node;
    public $parameter;
    public $value;
    public $creation_date;
    public $update_date;
    
    //constructor
    function __construct($dbconn, $id = null) {
        parent::__construct($dbconn);
        $this->dbdata_class = get_class($this);
        $this->dbdata_fields = array('id','user_id', 'tree_id', 'node',  'parameter','value','creation_date','update_date');
        $this->dbdata_required = array('newick');
        $this->dbdata_table = "tree_parameters";
        $this->id = $id;
    }

    
    public function fetchParamsOptimized($is_prepared = false){
        $r =  $this->fetchOptimized(array(
            array('parameters' => 'user_id', 'class' => 'User', 'object' => 'user'),
            array('parameters' => 'tree_id', 'class' => 'Tree', 'object' => 'tree')

        ), $is_prepared);

     
        
        
        return $r;
    }
    
}