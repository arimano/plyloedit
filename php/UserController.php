<?php

class UserController extends BaseController {

    function index($id) {
        
        if($id){
            $user = new User($this->dbconn, $id);
            if($user->fetch()){
                $user->getTrees();
                $this->smarty->Assign('user', $user);
                $this->display('User/profile.tpl');
            } else {
                $_SESSION['system_error'] = _("Потребител с такова ID не съществува");
                $this->display('Main/main.tpl');
            }
            
        } else {
            $this->showList();
        }
    }
    
    
    function register() {
        $this->display('User/register.tpl');
    }
    
    
    
    function doRegister() {        
        $name = $_REQUEST['name'];
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $password_again = $_REQUEST['password_again'];

        $user = new User($this->dbconn);
        
        if(!$user->validateName($name) or !$user->validateEmail($email) or !$user->validatePassword($password,  $password_again)) $error_data = 1;


        
        if(!isset($error_data)){
            $this->dbconn->debug=true;
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;

            if($user->save()){
                $_SESSION['user_id'] = $user->id;  
                
                header('Location: index.php');
                exit();
            } else {
                $this->showErrors();
                $this->register();   
            }
        } else {
            
            $this->showErrors();
            $this->register();   
        }

    }
        
    
    function showLoginForm(){
        $this->display('User/login-form.tpl');
    }
    
    function showList(){
        //Списък потребители
        $user_model = new User($this->dbconn);
        $users = $user_model->fetchAll(array('filter'=>' ORDER BY id desc  ','values'=>array()));
        $this->smarty->Assign('users', $users);
        
        $this->display('User/list.tpl');
    }
}

?>
