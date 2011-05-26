<?
    /**
    *   @author     cameleon Internet Media
    *   @copyright  2011 cameleon Internet Media
    *   @file VController.class.php
    *   @date   22.04.2011
    *   @version    0.2
    *   @version    0.1 The VController-class added
    */

    /**
    *   VController
    *
    *   This class authenticate user by their session and the
    *   access key given by the controller or controller method.
    *   If the user get no access to the site he will redirect to another page.
    *
    */
    class VController{
        protected $_methods = array();
        private $__classname;
        public $accessOption;
        
        /**  The constructor. Stores the own classname, sets the default method and registeres method aliases.
        * 
        */ 
        public function __construct(){
            $this->__classname = get_class($this); 
            $this->_methods['default'] = $this->__classname."Init";
            $this->registerMethodAlias("createUrl", array("Vimerito", "createUrl"));
            $this->registerMethodAlias("loadJsLibrary", array("VLayout", "registerUserJavaScriptLibraries"));
        }
        
        /** Magic method __call
        * 
        */ 
        public function __call($method, $parameters){
            try{
                if(method_exists($this, $method)){
                    Vimerito::checkMethodForEventsBefore($method, &$this);
                    $func = "\$_return = call_user_func(array(\$this, \$method)";
                    foreach($parameters as $p){
                        $func.= ", \$p";
                    }
                    $func.=");";
                    eval($func);
                    Vimerito::checkMethodForEventsAfter($method, &$this);
                    return $_return;
                }elseif(array_key_exists($method, $this->_methods)){
                    if(is_array($this->_methods[$method])){
                        //is a static method
                        $func = "\$_return = call_user_func(\$this->_methods[$method]";
                        for($i=0; $i<count($parameters); $i++){
                            $func.= ", \$parameters[$i]";
                        }
                        $func.=");";
                        eval($func);
                        return $_return;
                    }else{
                        $func = "\$_return = call_user_func(array(\$this, \$this->_methods[$method])";
                        foreach($parameters as $p){
                            $func.= ", \$p";
                        }
                        $func.=");";
                        eval($func);
                        return $_return;                        
                    }
                }else{
                    throw new VException('Method "'.$method.'" do not exist!');
                }                
            }catch(VException $e){
                $e->showError(); 
            }catch(Exception $e){
                $e->getMessage();
            }
        }
        
        
        /** Registeres any method(owned by class or not) under an other name and gives an access how to a classown method. 
        *   @param  $alias  The new name for the method.
        *   @param  $callback   (mixed) Can be functionname or an array with a classname and the methodname.
        *   @version 0.4 Bug removed
        * 
        */ 
        public function registerMethodAlias($alias, $callback){
            if(is_string($callback) or is_array($callback)){
                if(!method_exists($this, $alias)){
                    $this->_methods[(string)$alias] = $callback;
                }   
            }else{
                //Fehler ausgeben!
            }  
        }
        
        /** Runs the requested action
        *   @param  $method  Name of the action without the suffix "Action".
        *   @version 0.3 Authentification bug removed. Now, first the methodname will create and then the authentification starts
        */        
        public function run($method){
            if(Vimerito::$configuration['automaticAuthentication'] == true){ 
                if(!isset($method) or $method == ''){
                    $method = $this->_methods['default'];
                }else{
                    $method.="Action";
                }    
                if(VAccessRights::authenticateUser($method)){
                    $this->$method();
                }else{
                    Vimerito::redirect(401, false, VAccessRights::getRedirectController());
                }
            }else{
                if(!isset($method) or $method == '')
                    $method = $this->_methods['default'];
                else
                    $method.="Action";
                $this->$method();             
            }    
        }
        /** Returns the filepath to the controllerfile.
        *   @return String  The filepath to the controllerfile. 
        */ 
        public function getApplicationPath(){
            return Vimerito::getApplicationPath($this);
        }
        
        public function isModul(){
            return Vimerito::isModul($this);
        }
        
    }
?>