<?php
	class VModul{
		public $__modulPath;
		public $__instance;
		
		/**
		 * The magic method __construct. It creats the modulpath.
		 * Enter description here ...
		 */
		public function __construct(){
			$this->__modulPath = Vimerito::getApplicationPath()."moduls/".get_class($this)."/";
		}
		
		
		public static function isModul(){
			return true;
		}
		
		/**
		 * Return the modulpath.
		 * @return string The modulpath.
		 */
		public function getModulPath(){
			return $this->__modulPath;
		}
		
		/** Runs the requested controller and action of a modul.
		 * @param string $__controller The name of the requested controller
		 * @param string $__method The name of the requested action.
		 */
		public function run($__controller, $__method){
			$this->__instance = new $__controller();
			$this->__instance->run($__method);
		}
	}
?>