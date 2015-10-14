<?php


include('inc/config.inc.php');

$page = empty($_REQUEST['page']) ? 'main' : str_replace(array('http://', '.', '/'), "", $_REQUEST['page']);
$act = empty($_REQUEST['act']) ? '' : $_REQUEST['act'];
$id = empty($_REQUEST['id']) ? null : $_REQUEST['id'];

$header = new HeaderController();


// assign options arrays
$smarty->assign('page', $page);
$smarty->assign('act', $act);
$smarty->assign('id', $id);




if (method_exists($header, $act)) $header->$act($id);

$header->index();

if( isset($_SESSION['user_id'])) User::loadCurrentUser ();
if (isset($_SESSION['user']) )$smarty->Assign('user', $_SESSION['user']);


//create current page object
if (isset($page)) {
    $page_object_name = ucfirst($page) . "Controller";
    if (file_exists('php/' . $page_object_name . '.php')) {
        $controller = ucfirst($page_object_name);
        $page_object = new $controller();

        if (($act) && (method_exists($page_object, $act)))
            $page_object->$act($id);
        else
            $page_object->index($id);
    } else {
        header("HTTP/1.0 404 Not Found");
        $page_object_name = "NopageController";
        $page_object = new $page_object_name();
        if ($act and method_exists($page_object, $act))
            $page_object->$act($id);
        else
            $page_object->index($id);
    }
} else {

    $page_object_name = "MainController";
    $page_object = new $page_object_name();
    if ($act and method_exists($page_object, $act))
        $page_object->$act($id);
    else
        $page_object->index($id);
}


unset($_SESSION['user']);
?>
