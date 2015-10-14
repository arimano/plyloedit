<?php

class SearchController extends BaseController {

    function index() {
        
        $this->display('Search/main.tpl');
    }
    
    
    function doSearch() {
        $keyword = $_REQUEST['keyword'];
        
        $filter_keyword = '%'.$keyword.'%';
        $page_num = ($_REQUEST['page_num'])?$_REQUEST['page_num']:1;
        //get ids
        $q = "select id from trees where title like ? or description like ? ";
        $st = $this->dbconn->Prepare($q);
        $r1 = $this->dbconn->getAll($st, array($filter_keyword,$filter_keyword));
        
        $q = "select tree_id from tree_parameters where value like ? or node like ? ";
        $st = $this->dbconn->Prepare($q);
        $r2 = $this->dbconn->getAll($st, array($filter_keyword,$filter_keyword));
        
        $q = "select t.id from trees t LEFT JOIN users u ON u.id= t.user_id where u.name like ? or u.description like ? or u.email like ?  ";
        $st = $this->dbconn->Prepare($q);
        $r3 = $this->dbconn->getAll($st, array($filter_keyword,$filter_keyword,$filter_keyword));
        
        $r = array_merge($r1, $r2, $r3);
        $r_ids = array();
        foreach($r as $row) $r_ids[] = $row[0];
        
        rsort($r_ids);
        
        $pages_count = round((count($r_ids)/10)+0.4);
        $tree_model = new Tree($this->dbconn);
        $trees = $tree_model->fetchAll(array('filter'=>" WHERE id in (".implode(",", $r_ids).") LIMIT ". ($page_num-1)*10 .",10",'values'=>array()),'all');
        
        $this->smarty->Assign('keyword', $keyword);
        $this->smarty->Assign('trees', $trees);
        $this->smarty->Assign('page_num', $page_num);
        $this->smarty->Assign('pages_count', $pages_count);
        
        $this->display('Search/result.tpl');
    }
        
}

?>
