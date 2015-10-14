<?php

class NopageController extends BaseController {

    function index($id = null) {
        $url =  end(explode('/',$_SERVER['REQUEST_URI']));

        if($id && preg_match('/article.php/', $url)){
            $model_content =  new Content($this->dbconn, $id);
            $model_content->fetch();
            $model_content->fetchParamsOptimized();
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: $model_content->seo_url ");
            exit();
        } else {
            header("HTTP/1.0 404 Not Found");
        }
        
        $this->display('NopageController/404.tpl');
    }
}
        
?>
