<?php
class HeaderController extends BaseController {

    function index() {
	
        if(!isset($_SESSION['profile_id']) && !isset($_SESSION['profile']) && !isset($_SESSION['user']) && isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
            $_REQUEST['username']=$_COOKIE['cookname'];
            $_REQUEST['password']=$_COOKIE['cookpass'];
            $this->login();
        }
        
    
    }
    
        
    
    function login(){
        
        $username = empty($_REQUEST['username'])?'':$_REQUEST['username'];
        $password = empty($_REQUEST['password'])?'':$_REQUEST['password'];
        
        $user_model = new User($this->dbconn);
        if ($user_model->login($username, $password)){
            
            User::loadCurrentUser();    
            $_SESSION['system_message'] = _('Успешно се логнахте!');
            
            
            //REMEMBER ME
            if (isset($_REQUEST['remember'])){
               setcookie("cookname", $_REQUEST['username'], time()+2592000, "/");
               setcookie("cookpass", md5($_REQUEST['password']), time()+2592000, "/");
            }
            
            setcookie('userId', $user_model->id, time() + 86400 * 365 * 2);
            
        
        } else{
      
	    setcookie("cookname", "", time()-(60*60*24), "/");
	    setcookie("cookpass", "", time()-(60*60*24), "/");

            header('Location: index.php');
            exit();
         }
         


    }
    
    function logout(){
        
        $model_user = new User($this->dbconn, $_SESSION['user']->id);
        $model_user->fetch();
        $model_user->logout();
        
        if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
            setcookie("cookname", "", time()-2592000, "/");
            setcookie("cookpass", "", time()-2592000, "/");
            unset ($_COOKIE['cookname']);
            unset ($_COOKIE['cookpass']);
        }

        
        header('Location: index.php');
        exit();
    }
    
}
?>
