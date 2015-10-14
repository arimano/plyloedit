<?php

class BaseController {

    /**
     * 
     * @var DBConn
     */
    protected $dbconn;

    /**
     * 
     * @var Smarty
     */
    protected $smarty;
    protected $cur_account;

    function __construct() {
        $this->dbconn = DBConn::getInstance()->conn;
        $this->smarty = SmartyInstance::getInstance()->smarty;
        $this->system = system::getInstance($this->dbconn);
    }

    /**
     * Оказва кой темплейт да се зареди и коя е мастър страницата
     * 
     * За нормални страници се използва index.tpl като мастър страница,
     * в него си има логика, за да зареди посочения темплейт
     * 
     * Когато трябва да се връща резултат от ajax заявки като мастър
     * страница трябва да се изпозлва master_ajax.tpl. Тук има два
     * случая:
     *  - връщане на данни като json и подобни - за темплейт се задава
     *  	ajax_data.tpl, който връща това което е подадено на променливата 
     *  	ajax_data
     *  - връщане на html - задава се темплейтът, който ще генерира съответния html
     * 
     * @param string $template
     * @param string $master
     */
    function display($template = false, $master = 'index.tpl') {
        $xhr = ((!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
        if ($xhr)
            $master = 'ajax.tpl';
        if ($template) {
            $this->smarty->assign('template', $template);
        } else {
            $this->smarty->assign('template', get_class($this) . '/index.tpl');
        }



        //проверка на браузъра - за Imperavi Redactor
        $browser = preg_match('/(?i)msie [1-8]/', $_SERVER['HTTP_USER_AGENT']);
        $this->smarty->assign('browser', ($browser == 1) ? '8' : '');
        $this->smarty->display($master);
    }

    function fetch($template = false, $master = 'index.tpl') {
        $xhr = ((!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
        if ($xhr)
            $master = 'ajax.tpl';
        if ($template) {
            $this->smarty->assign('template', $template);
            $css_js = str_replace('PageController', '', get_class($this));
            $css_js{0} = strtolower($css_js{0});
            $this->smarty->assign('css_js_name', $css_js);
        } else {
            $this->smarty->assign('template', get_class($this) . '/index.tpl');
        }

        return $this->smarty->fetch($master);
    }

    function showErrors() {
        if (isset($_SESSION['system_message'])) {
            $this->smarty->assign('system_message', $_SESSION['system_message']);
            unset($_SESSION['system_message']);
        }
        if (isset($_SESSION['system_error'])) {
            $this->smarty->assign('system_error', $_SESSION['system_error']);
            unset($_SESSION['system_error']);
        }
    }

}
