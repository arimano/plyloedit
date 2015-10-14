<?php

class Tree extends DBData {

    public $id;
    public $user_id;
    public $title;
    public $description;
    public $newick;
    public $creation_date;
    public $update_date;
    
    //constructor
    function __construct($dbconn, $id = null) {
        parent::__construct($dbconn);
        $this->dbdata_class = get_class($this);
        $this->dbdata_fields = array('id', 'user_id', 'title',  'description','newick','creation_date','update_date');
        $this->dbdata_required = array('newick');
        $this->dbdata_table = "trees";
        $this->id = $id;
    }

    
    public function parseTree($newick){
        $newick = str_replace(array(";"," "),"",$newick);
        
        //ако последния символ на newick стринга е различен от ")", това е името на текущия нод
        if (substr($newick, -1) !=")" and strpos($newick, ")")) {
            $temp = explode(")", $newick);
            end($temp);
            $node_name = current($temp);
            $newick = trim($newick, ")$node_name");
            $node_name_array = explode(":", $node_name);
            $node_name = $node_name_array[0];
            $node_distance = $node_name_array[1];
        }
                
        $result = array();
        
        if($node_name)$result['title'] = $node_name;
        if($node_distance)$result['distance'] = $node_distance;
        
        
        $newick_array  = explode(",", trim($newick,"()"),2);
        
        //всеки възел може да има точно разклонения (или 3 ако 
        if(strpos($newick_array[0], "(")  !== false ) {
            $newick_array[0];
            $result['sub1'] = $this->parseTree($newick_array[0]);
            if (!$result['sub1']) return false;
        } elseif($newick_array[0]) {
            $leaf_array = explode(":", $newick_array[0]);
            $result['sub1'] = array('title'=>$leaf_array[0],'distance'=>$leaf_array[1]);
        } else return false;
        
        if(strpos($newick_array[1], "(") !== false) {
            $result['sub2'] = $this->parseTree($newick_array[1]);
            if (!$result['sub2']) return false;
        } elseif($newick_array[1]) {
            $leaf_array = explode(":", $newick_array[1]);
            $result['sub2'] = array('title'=>$leaf_array[0],'distance'=>$leaf_array[1]);
        } else return false;
        
        return $result;
        
    }
    
    public function validateNewick($newick){
        if(!$newick ) {
            $_SESSION['system_error'] .= _('Моля, въведете newick стринг!');
            return false;
        }
        
        //парсване на newick стринга до масив
        if ($tree_array = $this->parseTree($newick)) return true;
        
        
        return false;
    }
   
    public function validateDescription($description){

        return true;
    }
    public function validateTitle($title){
        
        return true;
    }
    
   
    public function parse($newick){
        $newick_array = explode("DATA", $newick);
        
        if(count($newick_array) == 2){
            $this->parameters = $newick_array[0];
            $this->newick = $newick_array[1];
        } else {
            $this->newick = $newick_array[0];
            
        }
    
    }
    
    function getEnseblDataByNode($node){
        $dbconn_ensembl = &ADONewConnection('mysql');
        $dbconn_ensembl->Connect(DB_HOST_ENSEMBL, DB_USER_ENSEMBL, DB_PASS_ENSEMBL, DB_NAME_ENSEMBL);
        
        $dbconn_ensembl->debug=true;
        $q = "select dbprimary_acc as reference
                from member_xref, member
                where external_db_id = 1000
                and member_xref.member_id = member.member_id
                and stable_id = ?";
        $st = $dbconn_ensembl->Prepare($q);
        $r =  $dbconn_ensembl->getAll($st, array($node));
        
        if($r){
            $annotations  = array();
            foreach ($r as $row) $annotations[] = $row;
        }
        
    }
    
    
    /*
     * Записва параметрите при импортиране на данни от текстов формат
     */
    public function saveParameters(){
        
        if($this->parameters){
            
            //explode по SEQ
            $parameters_array = explode("SEQ", trim($this->parameters));
            //explode по space
            $q = "insert into tree_parameters (user_id,tree_id,parameter,value,node) VALUES ";
            foreach($parameters_array as $param){
                if(strlen($param)>0){
                    
                    $param_array = explode(" ", trim($param));

                    $node_id = $param_array[1];

                    $parameter_1 = $param_array[0];
                    $parameter_2 = $param_array[2];
                    $parameter_3 = $param_array[3];
                    $parameter_4 = $param_array[4];
                    $parameter_5 = $param_array[5];
                    $parameter_6 = $param_array[6];
                    $parameter_7 = $param_array[7];

                    
                    
                    $q .= "(".$_SESSION['user_id'].", $this->id, 'parameter_1','$parameter_1', '$node_id') ,";
                    $q .= "(".$_SESSION['user_id'].", $this->id, 'parameter_2','$parameter_2', '$node_id') ,";
                    $q .= "(".$_SESSION['user_id'].", $this->id, 'parameter_3','$parameter_3', '$node_id') ,";
                    $q .= "(".$_SESSION['user_id'].", $this->id, 'parameter_4','$parameter_4', '$node_id') ,";
                    $q .= "(".$_SESSION['user_id'].", $this->id, 'parameter_5','$parameter_5', '$node_id') ,";
                    $q .= "(".$_SESSION['user_id'].", $this->id, 'parameter_6','$parameter_6', '$node_id') ,";
                    $q .= "(".$_SESSION['user_id'].", $this->id, 'parameter_7','$parameter_7', '$node_id') ,";
                    
                }
            }
            
            
        }
        $this->dbconn->Execute(trim($q,","));
        
        return true;
    }
    
    public function fetchParamsOptimized($is_prepared = false){
        
        $r =  $this->fetchOptimized(array(
            array('parameters' => 'user_id', 'class' => 'User', 'object' => 'user')

        ), $is_prepared);

     
        
        
        return $r;
    }
    
    
    
}