<?php

class TreeController extends BaseController {

    function index($id) {
        if ($id) {
            $tree = new Tree($this->dbconn, $id);
            $tree->fetch();
            $tree->fetchParamsOptimized();
            $this->smarty->Assign('tree', $tree);
            $this->display('Tree/view.tpl');
        } else {
            $page_num = (isset($_REQUEST['page_num']))?$_REQUEST['page_num']:1;
            
            $tree = new Tree($this->dbconn);
            $trees = $tree->fetchAll(array('filter' => ' ORDER BY id DESC LIMIT '.($page_num-1)*10 .', 10 ', 'values' => array()), 'all');
            
            $trees_count = $tree->fetchCount(array());
            
            $pages_count = round(($trees_count/10)+0.4);
            
            $this->smarty->Assign('trees', $trees);
            $this->smarty->Assign('pages_count', $pages_count);
            $this->smarty->Assign('page_num', $page_num);

            $this->display('Tree/list.tpl');
        }
    }
    /*
     * Извиква се през AJAX  и връща newick формата на визуализираното дърво
     */
    function showTree($id) {
        $tree = new Tree($this->dbconn, $id);
        $tree->fetch();
        echo trim($tree->newick);
    }

    function newTree() {
        if (!$_SESSION['user_id']) {
            $_SESSION['system_error'] = _('За да добавите дърво, трябва да се регистрирате!');
            $this->showErrors();
            $this->smarty->assign('disabled', 1);
            $this->display('Tree/new.tpl');
        } else {
            $this->display('Tree/new.tpl');
        }
    }

    function doNewTree($newick=null, $import=0) {
        $title = $_REQUEST['title'];
        $description = $_REQUEST['description'];
        $newick = (isset($_REQUEST['newick']))?$_REQUEST['newick']:$newick;

        $tree = new Tree($this->dbconn);

        if (!$tree->validateTitle($title) or ! $tree->validateDescription($description) or ! $tree->validateNewick($newick))
            $error_data = 1;



        if (!isset($error_data)) {

            $tree->title = $title;
            $tree->description = $description;
            $tree->user_id = $_SESSION['user_id'];

            //парсваме newick текста и ако има SEQ редове си вкарваме и параметрите за нодовете
            $tree->parse($newick);

            if ($tree->save()) {
                //ако след парсването има параметри ги записва и тях

                $tree->saveParameters();
                
                //ако импортираме от файл
                if($import==1) return TRUE;
                
                header('Location: tree.php?id=' . $tree->id);
                exit();
            } else {
                //ако импортираме от файл
                if($import==1) return FALSE;
                
                $this->showErrors();
                $this->newTree();
            }
        } else {
            //ако импортираме от файл
            if($import==1) return FALSE;
            
            $this->showErrors();
            $this->newTree();
        }
    }

    /*
     * Записва допълнителната информация за листо
     */
    function doSaveNode() {
        $node_id = $_REQUEST['node_id'];
        $tree_id = $_REQUEST['tree_id'];


        //parameter 1
        $parameter = new TreeParameter($this->dbconn);
        $parameter->fetchByFilter(array('filter' => 'WHERE node=? and parameter =? ', 'values' => array($node_id, 'parameter_1')));
        if ($parameter->id > 0)
            $parameter->update(array('value', 'user_id', 'update_date'), array($_REQUEST['parameter_1'], $_SESSION['user_id'], date("Y-m-d H:i:s")));
        else
            $parameter->insert(array('user_id', 'tree_id', 'node', 'parameter', 'value'), array($_SESSION['user_id'], $tree_id, $node_id, 'parameter_1', $_REQUEST['parameter_1']));

        //parameter 2
        $parameter = new TreeParameter($this->dbconn);
        $parameter->fetchByFilter(array('filter' => ' WHERE node=? and parameter =? ', 'values' => array($node_id, 'parameter_2')));
        if ($parameter->id > 0)
            $parameter->update(array('value', 'user_id', 'update_date'), array($_REQUEST['parameter_2'], $_SESSION['user_id'], date("Y-m-d H:i:s")));
        else
            $parameter->insert(array('user_id', 'tree_id', 'node', 'parameter', 'value'), array($_SESSION['user_id'], $tree_id, $node_id, 'parameter_2', $_REQUEST['parameter_2']));

        //parameter 3
        $parameter = new TreeParameter($this->dbconn);
        $parameter->fetchByFilter(array('filter' => ' WHERE node=? and parameter =? ', 'values' => array($node_id, 'parameter_3')));
        if ($parameter->id > 0)
            $parameter->update(array('value', 'user_id', 'update_date'), array($_REQUEST['parameter_3'], $_SESSION['user_id'], date("Y-m-d H:i:s")));
        else
            $parameter->insert(array('user_id', 'tree_id', 'node', 'parameter', 'value'), array($_SESSION['user_id'], $tree_id, $node_id, 'parameter_3', $_REQUEST['parameter_3']));

        //parameter 4
        $parameter = new TreeParameter($this->dbconn);
        $parameter->fetchByFilter(array('filter' => ' WHERE node=? and parameter =? ', 'values' => array($node_id, 'parameter_4')));
        if ($parameter->id > 0)
            $parameter->update(array('value', 'user_id', 'update_date'), array($_REQUEST['parameter_4'], $_SESSION['user_id'], date("Y-m-d H:i:s")));
        else
            $parameter->insert(array('user_id', 'tree_id', 'node', 'parameter', 'value'), array($_SESSION['user_id'], $tree_id, $node_id, 'parameter_4', $_REQUEST['parameter_4']));

        //parameter 5
        $parameter = new TreeParameter($this->dbconn);
        $parameter->fetchByFilter(array('filter' => ' WHERE node=? and parameter =? ', 'values' => array($node_id, 'parameter_5')));
        if ($parameter->id > 0)
            $parameter->update(array('value', 'user_id', 'update_date'), array($_REQUEST['parameter_5'], $_SESSION['user_id'], date("Y-m-d H:i:s")));
        else
            $parameter->insert(array('user_id', 'tree_id', 'node', 'parameter', 'value'), array($_SESSION['user_id'], $tree_id, $node_id, 'parameter_5', $_REQUEST['parameter_5']));

        //parameter 6
        $parameter = new TreeParameter($this->dbconn);
        $parameter->fetchByFilter(array('filter' => ' WHERE node=? and parameter =? ', 'values' => array($node_id, 'parameter_6')));
        if ($parameter->id > 0)
            $parameter->update(array('value', 'user_id', 'update_date'), array($_REQUEST['parameter_6'], $_SESSION['user_id'], date("Y-m-d H:i:s")));
        else
            $parameter->insert(array('user_id', 'tree_id', 'node', 'parameter', 'value'), array($_SESSION['user_id'], $tree_id, $node_id, 'parameter_6', $_REQUEST['parameter_6']));

        //parameter 7
        $parameter = new TreeParameter($this->dbconn);
        $parameter->fetchByFilter(array('filter' => ' WHERE node=? and parameter =? ', 'values' => array($node_id, 'parameter_7')));
        if ($parameter->id > 0)
            $parameter->update(array('value', 'user_id', 'update_date'), array($_REQUEST['parameter_7'], $_SESSION['user_id'], date("Y-m-d H:i:s")));
        else
            $parameter->insert(array('user_id', 'tree_id', 'node', 'parameter', 'value'), array($_SESSION['user_id'], $tree_id, $node_id, 'parameter_7', $_REQUEST['parameter_7']));
    }

    function editNode($node_id) {
        $tree_id = $_REQUEST['tree_id'];
        $tree = new Tree($this->dbconn, $tree_id);
        if (!$tree->fetch())
            return false;

        //взима свързнаите с нода допълнителни данни, създадени от потребителите на системата
        $treeParameter = new TreeParameter($this->dbconn);
        $parameters = $treeParameter->fetchAll(array('filter' => ' WHERE node=? ', 'values' => array($node_id)));

        $node_parameters = array();
        foreach ($parameters as $param) {
            $node_parameters[$param->parameter] = $param->value;
        }

        //взима свързнаите с нода допълнителни данни - от Ensembl
        $tree->getEnseblDataByNode($node_id);
        
        $this->smarty->assign('node_parameters', $node_parameters);
        $this->smarty->assign('node_id', $node_id);
        $this->smarty->assign('tree_id', $tree_id);
        if ($tree->user_id == $_SESSION['user_id'])
            $this->display('Tree/edit-node.tpl');
        else
            $this->display('Tree/view-node.tpl');
    }

    function showImport() {
        if (!$_SESSION['user_id']) {
            $_SESSION['system_error'] = _('За да добавите дърво, трябва да се регистрирате!');
            $this->showErrors();
            $this->smarty->assign('disabled', 1);
            $this->display('Tree/import.tpl');
        } else {
            $this->display('Tree/import.tpl');
        }
    }

    function doImport() {
       $target_file = "uploads/" . basename($_FILES["import_file"]["name"]);

        if (move_uploaded_file($_FILES["import_file"]["tmp_name"], $target_file)) {
            chmod($target_file, 0755);
            $file_content = file_get_contents($target_file);
            $trees_array = explode("//", $file_content);
            
            foreach($trees_array as $tree_row){
                if(!$this->doNewTree($tree_row,1)) echo $tree_row;
            }
           // unlink($target_file);
        } else {
            
            $_SESSION['system_error'] = _("Проблем при качването на файл. Моля, свържете се със администратор!");
            $this->showErrors();
            $this->display('Tree/import.tpl');
        }
    }

    
    function validate(){
        $tree = new Tree($this->dbconn);
        if($result = $tree->validateNewick("(A:0.1,B:0.2,(C:0.3,D:0.4)E:0.5)F;")) echo "valid";
        else echo "no_valid";
    }
    
}
