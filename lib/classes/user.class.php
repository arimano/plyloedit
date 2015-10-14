<?php

class User extends DBData {

    public $id;
    public $name;
    public $password;
    public $email;
    public $creation_date;
    //constructor
    function __construct($dbconn, $id = null) {
        parent::__construct($dbconn);
        $this->dbdata_class = get_class($this);
        $this->dbdata_fields = array('id', 'name', 'password',  'creation_date','email');
        $this->dbdata_required = array('email','password');
        $this->dbdata_table = "users";
        $this->id = $id;
    }

    /**
     * Логин може да се осъществи по потребителско име или email
     * @param string $username
     * @param string $password
     */
    function login($username, $password, $slw=null, $fb_id=null) {

        $this->fetchByFilter(array('filter' => ' where (email=? and password=?) or (email=? and password=?) ', 'values' => array( $username, md5($password), $username, $password)));

        if ($this->id ) {

            $_SESSION['user_id'] = $this->id;
            $_SESSION['user'] = serialize($this);
            
            return true;

        }
       
        $_SESSION['system_error'] = _('Неуспешен логин!');
        return false;
    }


    
    /**
     * Логоут 
     * 
     */
    function logout() {

        unset($_SESSION['user_id']);
        unset($_SESSION['user']);
        unset($_SESSION['serialized_user']);
        
        session_destroy();

        return true;
    }

    /**
     * Връща модел за текущия потребител
     * 
     * Ако има $_SESSION['user_id'] се връща модел,
     * с това което е успял да зареди, в противен случай
     * се връща false
     * 
     * @return mixed: User|boolean
     */
    static function loadCurrentUser() {
        if (empty($_SESSION['user_id'])) {
            return false;
        }
        
        if($_SESSION['user_serialized']){
            $_SESSION['user'] = unserialize($_SESSION['user_serialized']);
            $_SESSION['user']->dbconn = DBConn::getInstance()->conn;
        } else {
            $model_user = new User(DBConn::getInstance()->conn, $_SESSION['user_id']);
            $model_user->fetch();
           
            $_SESSION['user'] = $model_user;
            $_SESSION['user_serialized'] = serialize($model_user);
            
        }

        return $_SESSION['user'];
    }
    
 
    public function fetchParamsOptimized($is_prepared = false){
        
    }
    public function validateName($name){
        if(!$name ) {
            $_SESSION['system_error'] .= _('Моля, въведете име!');
            return false;
        }
        
        return true;
    }
    
    public function validateEmail($email){
        $user = new User($this->dbconn);
        if($user->fetchByFilter(array('filter'=>' WHERE email=? ','values'=>array($email)))){
            $_SESSION['system_error'] .= _('Потребител с такъв e-mail  съществува!');
            return false;
        }
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['system_error'] .= _('Невалиден e-mail!');
            return false;
        }
        
        return true;
    }
    
    public function validatePassword($password, $password_again){
        if(!isset($_SESSION['system_error'])) $_SESSION['system_error'] = '';
        
        if(!$password or strlen($password)<4) {
            $_SESSION['system_error'] .= _('Моля, въведете парола дълга не по-малко от 4 символа!');
            return false;
        }
        
        if($password != $password_again) {
            $_SESSION['system_error'] .= _('Въведените пароли не съвпадат!');
            return false;
        }
        return true;
    }
    
    
    public function getTrees(){
        $tree_model = new Tree($this->dbconn);
        $this->trees = $tree_model->fetchAll(array('filter'=>' WHERE user_id=? order by id desc ','values'=>array($this->id)));
        
    }
    
    
}