<?php

class DBConn {

    private static $instance;
    public $conn;

    private function __construct($pconn = false) {
        //database connect
        $this->conn = &ADONewConnection('mysql');

        if (false != $pconn)
            $this->conn->PConnect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        else
            $this->conn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // $this->conn->debug=true;
        $this->conn->Execute("SET NAMES 'UTF8'");
    }

    public static function getInstance($newconn = false, $pconn = false) {
        if (!isset(self::$instance) || true == $newconn) {
            $className = __CLASS__;

            if (false != $pconn)
                self::$instance = new $className;
            else
                self::$instance = new $className(true);
        }

        return self::$instance;
    }

    public static function getNew() {
        $conn = &ADONewConnection('mysql');
        $conn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        //	$this->conn->debug=true;
        $conn->Execute("SET NAMES 'UTF8'");

        return $conn;
    }

    public static function connectArchive() {
        //DB-Archive config
        define('DB_HOST_ARCHIVE', "10.10.10.120");
        define('DB_NAME_ARCHIVE', "credoweb2_archive");
        define('DB_USER_ARCHIVE', "root");
        define('DB_PASS_ARCHIVE', "`12345';';");

        $dbconn_archive = &ADONewConnection('mysql');
        $dbconn_archive->Connect(DB_HOST_ARCHIVE, DB_USER_ARCHIVE, DB_PASS_ARCHIVE, DB_NAME_ARCHIVE);
        //$dbconn_archive->debug=true;
        $dbconn_archive->Execute("SET NAMES 'UTF8'");

        return $dbconn_archive;
    }

}

?>
