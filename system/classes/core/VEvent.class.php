<?
    class VEvent{
        private static $__events = array();
        
        /** Add a new Event to an eventlist. 
        * @param    $type Constant Before or After The event is called before the event or after.
        *           $eventname Is an array includes the classname and the method, who trigger the event
        *           $callback Is an array with classname and the method that called. If classname has an static: - Prefix a static method called. (static:myClassname)
        * @version 0.5
        */ 
        public static function add($type, $eventname, $callback){
            if(isset(self::$__events[$type])){
                if(!isset(self::$__events[$type][strtolower($eventname[0])][strtolower($eventname[1])])){
                    $count = 0;
                }else{
                    $count = count(self::$__events[$type][strtolower($eventname[0])][strtolower($eventname[1])]);    
                }
            }else{
                $count = 0;
            }
            self::$__events[$type][strtolower($eventname[0])][strtolower($eventname[1])][$count] = $callback; 
        } 

        /**
         * Calls specified methods before a Controller-Method or the method VRouter::route is running
         * @version 0.6 Can't-call-bug removed
         * @param string $event The triggering method
         * @param object $obj The instance of the triggering controller
         * @param array $args Can be: useClassname, useMethodname or parameters given by your. These parameters will given to the triggered method.
         */
        
        public static function triggerEventBefore($event, &$obj = Null, $args = array()){
            if(file_exists(Vimerito::getApplicationPath()."configuration\eventConfiguration.php")){
                require Vimerito::getApplicationPath()."configuration\eventConfiguration.php";
                if(!empty($__eventAllBefore)){
                    foreach ($__eventAllBefore as $e){
                        if(count($e) >= 2){
                            $__function = "call_user_func_array(array('$e[0]', '$e[1]')";
                            
                            if(count($e) > 2){
                                $param = array();
                                for($i = 2; $i < count($e); $i++){  
                                    switch($e[$i]){
                                        case useClassname:
                                            $param[]= get_class($obj);
                                            break;
                                        case useMethodname:
                                            $param[]= $event;
                                            break;
                                        case useMethodArgs:
                                            $param[]= $args;
                                            break;
                                        default:
                                            $param[]= $e[$i];
                                            break;         
                                    }

                                }
                                $__function.= ", \$param";
                            }
                        }
                        $__function.= ");";
                        echo $__function;
                        eval($__function);
                    }
                }
                if(!empty($__eventMethodBefore)){
                    if(count($__eventMethodBefore[get_class($obj)][$event]) > 0){
                        foreach($__eventMethodBefore[get_class($obj)][$event] as $e){
                            // min 2 arguments (classname and methodname) AND NOT an action
                            if(count($e) >= 2 && strtolower($e[1]) == str_replace("action", "", strtolower($e[1]))){
                                $__function = "call_user_func(array(\$e[0], \$e[1])";
                                if(count($e) > 2){
                                    for($i = 2; $i < count($e); $i++){
                                        $param = $e[$i];
                                        if($param == useClassname){
                                            $param = get_class($obj);
                                        }
                                        if($param === useMethodname){
                                            $param = $event;
                                        }
                                        if($param === useMethodArgs){
                                            $param = $args;
                                        }
                                        $__function.= ", \$param";
                                    }
                                }
                                $__function = ");";
                                eval($__function);
                            }                            
                        }
                    }
                }
                if(!empty($__eventActionBefore)){
                    if(count($__eventActionBefore[get_class($obj)][$event]) > 0){
                    	$__e = $__eventActionBefore[get_class($obj)][$event];
                        foreach($__e as $e){
                            // min 2 arguments (classname and methodname) AND an action
                            if(count($e) >= 2 && strtolower($e[1]) != str_replace("action", "", strtolower($e[1]))){
                                $__function = "call_user_func(array(\$e[0], \$e[1])";
                                if(count($e) > 2){
                                    for($i = 2; $i < count($e); $i++){
                                        $param = $e[$i];
                                        if($param == useClassname){
                                            $param = get_class($obj);
                                        }
                                        if($param === useMethodname){
                                            $param = $event;
                                        }
                                        if($param === useMethodArgs){
                                            $param = $args;
                                        }
                                        $__function.= ", \$param";
                                    }
                                }
                                $__function = ");";
                                eval($__function);
                            }
                        }
                    }
                }
            }
            if(!empty(self::$__events[Before][strtolower($event[0])][strtolower($event[1])])){
                for($i = 0; $i < count(self::$__events[Before][strtolower($event[0])][strtolower($event[1])]); $i++){
                    $callback = self::$__events[Before][strtolower($event[0])][strtolower($event[1])][$i];    
                    $class = $callback[0];
                    $method = $callback[1];
                    if(str_replace(":", "", $class) != $class){
                        $c = explode(":", $class);
                        call_user_func(array($c[1], $method), $obj);
                    }else{
                        if($obj){
                            $instance->$method($obj);
                        }else{
                            $instance->$method();
                        }                            
                    }
                } 
            }
        }
        /**
         * Calls specified methods after a Controller-Method or the method VRouter::route is running
         * @version 0.6 Can't-call-bug removed 
         * @param string $event The triggering method
         * @param object $obj The instance of the triggering controller
         * @param array $args Can be: useClassname, useMethodname or parameters given by your. These parameters will given to the triggered method.
         */        
        public static function triggerEventAfter($event, &$obj = Null){
            if(file_exists(Vimerito::getApplicationPath()."configuration\eventConfiguration.php")){
                require Vimerito::getApplicationPath()."configuration\eventConfiguration.php";
                if(!empty($__eventAllAfter)){
                    foreach ($__eventAllAfter as $e){
                        if(count($e) >= 2){
                            $__function = "call_user_func(array(\$e[0], \$e[1])";
                            if(count($e) > 2){
                                for($i = 2; $i < count($e); $i++){
                                    $param = $e[$i];
                                    if($param == useClassname){
                                        $param = get_class($obj);
                                    }
                                    if($param === useMethodname){
                                        $param = $event;
                                    }
                                    if($param === useMethodArgs){
                                        $param = $args;
                                    }
                                    $__function.= ", \$param";
                                }
                            }
                            $__function = ");";
                            eval($__function);
                        }
                        $__function = ");";
                        eval($__function);
                    }
                }
                if(!empty($__eventMethodAfter)){
                    if(count($__eventMethodAfter[get_class($obj)][$event]) > 0){
                    	$__e = $__eventMethodAfter[get_class($obj)][$event];
                        foreach($__e as $e){
                            // min 2 arguments (classname and methodname) AND NOT an action
                            if(count($e) >= 2 && strtolower($e[1]) == str_replace("action", "", strtolower($e[1]))){
                                $__function = "call_user_func(array('".$e[0]."', '".$e[1]."')";
                                if(count($e) > 2){
                                    for($i = 2; $i < count($e); $i++){
                                        $param = $e[$i];
                                        if($param == useClassname){
                                            $param = get_class($obj);
                                        }
                                        if($param === useMethodname){
                                            $param = $event;
                                        }
                                        if($param === useMethodArgs){
                                            $param = $args;
                                        }
                                        $__function.= ", \$param";
                                    }
                                }
                                $__function.= ");";
                                eval($__function);
                            }
                        }
                    }
                }
                if(!empty($__eventActionAfter)){
                    if(count($__eventActionAfter[get_class($obj)][$event]) > 0){
                        foreach($__eventActionAfter[get_class($obj)][$event] as $e){
                            // min 2 arguments (classname and methodname) AND an action
                            if(count($e) >= 2 && strtolower($e[1]) != str_replace("action", "", strtolower($e[1]))){
                                $__function = "call_user_func(array(\$e[0], \$e[1])";
                                if(count($e) > 2){
                                    for($i = 2; $i < count($e); $i++){
                                        $param = $e[$i];
                                        if($param == useClassname){
                                            $param = get_class($obj);
                                        }
                                        if($param === useMethodname){
                                            $param = $event;
                                        }
                                        if($param === useMethodArgs){
                                            $param = $args;
                                        }
                                        $__function.= ", \$param";
                                    }
                                }
                                $__function = ");";
                                eval($__function);
                            }
                        }
                    }
                }
            }
            if(!empty(self::$__events[After][strtolower($event[0])][strtolower($event[1])])){
                for($i = 0; $i < count(self::$__events[After][strtolower($event[0])][strtolower($event[1])]); $i++){
                    $callback = self::$__events[After][strtolower($event[0])][strtolower($event[1])][$i];
                    $class = $callback[0];
                    $method = $callback[1];
                    if(str_replace(":", "", $class) != $class){
                        $c = explode(":", $class);
                        call_user_func(array($c[1], $method), $obj);
                    }else{
                        if($obj){
                            $instance->$method($obj);
                        }else{
                            $instance->$method();
                        }
                    }
                }
            }
        }
    }
?>