<?php

class MainController extends BaseController {

    function index($varnish_cashe = NULL) {
        //Последно регистрирани
        $user_model = new User($this->dbconn);
        $users = $user_model->fetchAll(array('filter'=>' ORDER BY id desc LIMIT 10 ','values'=>array()));
        $this->smarty->Assign('users', $users);
        
        //Последно създадени дървета
        
        $tree_model = new Tree($this->dbconn);
        $trees = $tree_model->fetchAll(array('filter'=>' ORDER BY id desc LIMIT 10 ','values'=>array()),'all');
        $this->smarty->Assign('trees', $trees);
        
        $this->display('Main/main.tpl');
    }
        
}

?>
