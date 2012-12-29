<?php 

class VModel{
	private $__recorder;
	private $__cells = array();
	
	public function __construct(){
		$__recorder = new VActivRecorder();
		$properties = new ReflectionObject($this);
		foreach($properties AS $prop){
			$propName = $prop->getName();
			$__cells[] = array(
				$propName=>$this->$propName
			);
		}
	}
	

}