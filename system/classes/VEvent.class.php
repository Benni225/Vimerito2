<?
    class VEvent{
        private static $__events = array();
        
        public static function add($eventname, $callback){
            self::$__events[strtolower($eventname)][count(self::$__events[strtolower($eventname)])][$callback[0]]   =   $callback[1]; 
        } 
        
        public static function trigger($eventname, &$obj = Null){
            if(!isset(self::$__events[$eventname])){
                return false;
            }
            
            for($i = 0; $i < count(self::$__events[strtolower($eventname)]); $i++){
                foreach(self::$__events[strtolower($eventname)][$i] as $class=>$method){
                    $instance = new $class;
                    if($obj){
                        $instance->$method($obj);
                    }else{
                        $instance->$method();
                    }
                }
            }    
        }
        
        //Todo: create a observer that automaticliy run the events for a registered method
    }
 
?>