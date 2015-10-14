<?php
	class SmartyInstance {
		private static $instance;
		public $smarty;

		private function __construct() {
			$this->smarty = new SmartyBC;
// 			$this->smarty->allow_php_tag = true; 
		}

		public static function getInstance() {
			if (!isset(self::$instance)) {
				$className = __CLASS__;
				self::$instance = new $className;
			}
			return self::$instance;
		}
	}
?>