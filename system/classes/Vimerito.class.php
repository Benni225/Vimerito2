<?
    /** @file  Vimerito.class.php
    *   @author    cameleon Internet Media   
    *   @date  21.4.2011
    *   @version 0.6
    *   	Moduls integrated
    *   @version 0.3
    *       The configurationarray: application added
    *       Vimerito::getApplicationArray() added
    *       Vimerito::setApplicationPath() added
    *       Vimerito::getApplicationPath() renamed to Vimerito::getControllerPath()
    *       Vimerito::getApplicationPath() added
    *   @version 0.2    
    *   - reimplemantation of the autoload-function 
    *   - implemantation of a failurepage    
    *   @version 0.1
    * 
    * 
    * 
    */ 
    mb_internal_encoding('UTF-8');
    defined("__VimeritoStartTime") or define("__VimeritoStartTime", microtime(true));
    defined("__Version") or define("__Version", "0.05");
    defined("__Basedir") or define("__Basedir", dirname($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])."/");
    defined("__SystemPath") or define("__SystemPath", __Basedir."system/");
    defined("__ApplicationPath") or define("__ApplicationPath", __Basedir."application/");

    defined("CacheToFile") or define("CacheToFile", 1);
    defined("CacheToVar") or define("CacheToVar", 2);

    defined("Layout") or define("Layout", 8);
    defined("LayoutAppend") or define("LayoutAppend", 1);
    defined("LayoutPrepend") or define("LayoutPrepend", 2);

    defined("ActualPage") or define("ActualPage", 3);

    defined("PK") or define("PK", 4);

	defined("CurrentApplication") or define("CurrentApplication", 5);
	
	defined("Prepend") or define("Prepend", 6);
	defined("Append") or define("Append", 7);
	defined("Replace") or define("Replace", 8);
	
	defined("useClassname") or define("useClassname", "VuseClassname");
	defined("useMethodname") or define("useMethodname", "VuseMethodname");
	defined("useMethodArgs") or define("useMethodArgs", "VuseMethodArgs");
	
	defined("Before") or define("Before", 0);
	defined("After") or define("After", 1);
	
	defined("JavaScriptRequest") or define("JavaScriptRequest", 10);
	defined("ViewRequest") or define("ViewRequest", 11);
	defined("ModulRequest") or define("ModulRequest", 12);
	defined("DevelopmentRequest") or define("DevelopmentRequest", 13);


    class Vimerito{
        private static $__classArray = array();
        private static $__userClassArray = array();
        private static $__userFileClassExtension = "class";
		private static $__aliasClass = array();
		private static $__application = array();
		private static $__controllerPath = array();
		private static $__calledController = "";
		private static $__calledMethod = "";
		private static $__systemController = array();
		private static $__requestType = '';
		private static $__applicationPath = '';
		private static $__currentApplication = "";
		private static $__javaScriptRequest;
		private static $__debugMode;

		private static $__moduls = array();
		private static $__calledModul = "";
		private static $__modulPath = "";

		public static  $configuration = array();
	    public static  $javaScriptLibraries = array();
	    public static  $userJavaScriptLibraries = array();
		public static  $router;

        public function __construct(){
            self::setDebugMode(false);    
        }

        /** Saves the actual session
        * 
        */ 
        public function __destruct(){
            VSession::saveSession();
        }

        /** Loads the configurationfiles and create a new session or continue the current session
        * 
        * 
        */ 
        public static function initApplication(){
            if(file_exists(__SystemPath."configuration/classArray.php")){
                require __SystemPath."configuration/classArray.php";
                self::$__classArray = $__cachedClassArray;
                self::$__systemController = $__cachedSystemController;
            }else{
                throw new VException('Configurationfile "classArray.php"', 0, E_WARNING);
            }
            if(file_exists(__ApplicationPath."configuration/applicationConfiguration.php")){
                require __ApplicationPath."configuration/applicationConfiguration.php";
                self::$configuration = $__cachedApplicationConfiguration;
            }else{
                throw new VException('Configurationfile "classArray.php"', 0, E_WARNING);
            }
            if(file_exists(__SystemPath."js/jslibraries.config.php")){
                require __SystemPath."js/jslibraries.config.php";
                self::$javaScriptLibraries = $__cachedJsLibraries;
            }else{
                throw new VException('Configurationfile "classArray.php"', 0, E_WARNING);
            }

            if(file_exists(__ApplicationPath."/configuration/javaScriptLibraries.conf.php")){
                require __ApplicationPath."/configuration/javaScriptLibraries.conf.php";
                self::$userJavaScriptLibraries = $__cachedJsLibraries;
            }else{
                throw new VException('Configurationfile "classArray.php"', 0, E_WARNING);
            }               
 
            VSession::createSession(); 
            //session_destroy();
            VRouter::route(); 
            Vimerito::loadApplicationConfiguration();
        }

        /** Returns a instance of a class
        *   @param[in]  $type    At the moment only the constant variable CurrentApplication supported
        *   @return     Object 
        */ 
		public static function getInstance($type){
			if($type == CurrentApplication){
				return self::$__application[self::$__calledController]['obj'];
			}
		}
		/** Loads the configurationfiles from the applicationpath
		*     @version 0.3 
		*/ 
		public static function loadApplicationConfiguration(){
            if(file_exists(__SystemPath."configuration/classArray.php")){
                require __SystemPath."configuration/classArray.php";
                self::$__classArray = $__cachedClassArray;
                self::$__systemController = $__cachedSystemController;
            }else{
                //Vimerito::redirect(501, array('failure' => '10'), array('VFailureController'));
                throw new Exception('Configurationfile classArray.php', 0);
            }
            if(file_exists(self::getApplicationPath(true)."configuration/applicationConfiguration.php")){                
            	require self::getApplicationPath(true)."configuration/applicationConfiguration.php";
                self::$configuration = $__cachedApplicationConfiguration;
            }else{
            	throw new Exception('Configurationfile classArray.php: '.self::getApplicationPath(true), 0);
                //Vimerito::redirect(501, array('failure' => '10'), array('VFailureController'));
            }
            if(file_exists(__SystemPath."js/jslibraries.config.php")){
                require __SystemPath."js/jslibraries.config.php";
                self::$javaScriptLibraries = $__cachedJsLibraries;
            }else{
            	throw new Exception('Configurationfile "classArray.php"', 0);
                //Vimerito::redirect(501, array('failure' => '10'), array('VFailureController'));
            }

            if(file_exists(self::getApplicationPath(true)."/configuration/javaScriptLibraries.conf.php")){
                require self::getApplicationPath(true)."/configuration/javaScriptLibraries.conf.php";
                self::$userJavaScriptLibraries = $__cachedJsLibraries;
            }else{
            	throw new Exception('Configurationfile "classArray.php"', 0);
                //Vimerito::redirect(501, array('failure' => '10'), array('VFailureController'));
            }      
        }
        /** Runs the requested controller and method or the requested modul.
        * 
        */ 
        public static function runApplication(){
            header('content-type: text/html; charset=iso-9951-8');
            self::$__requestType = VRequest::calledRequestType();
            if(self::$__requestType == ViewRequest){
                self::$__calledController = "VGetView";
                self::$__calledMethod = "";
                
                self::$__application[self::$__calledController]['obj'] = new self::$__calledController;
                self::$__application[self::$__calledController]['obj']->run(self::$__calledMethod);
            }elseif(self::$__requestType == ModulRequest){
            	self::$__calledModul = VRouter::calledModul(); 
            	self::$__calledController = VRouter::calledController();
            	self::$__calledMethod = VRouter::calledMethod();

            	self::$__application[self::$__calledController]['obj'] = new self::$__calledModul;
            	self::$__modulPath = self::$__application[self::$__calledController]['obj']->getModulPath();
            	self::$__application[self::$__calledController]['obj']->run(self::$__calledController, self::$__calledMethod);           		
            }else{
                self::$__calledController = VRouter::calledController();
                self::$__calledMethod = VRouter::calledMethod();
    
                self::$__application[self::$__calledController]['obj'] = new self::$__calledController;
                self::$__application[self::$__calledController]['obj']->run(self::$__calledMethod);
    
                VSession::set("__lastController", self::$__calledController);
                VSession::set("__lastMethod", self::$__calledMethod);
                VSession::set("__lastParams", VRouter::getParamArray());
                VSession::saveSession();                
            }
     
        }
        
        /**
         * Returns the scriptpath of the called modul.
         * @return string The modulpath
         */
        public static function getModulPath(){
        	return self::$__modulPath;
        }
        /** Sets wether the JavaScript-Library jQuery should integrated or not
        *   @param[in]  $mode   TRUE or FALSE 
        */ 
        public static function setJavaScriptMode($mode){
            self::$configuration['javaScriptMode'] = $mode;
        }
        /** Returns the version of Vimerito 2
        *   @return String: Version of Vimerito 2
        */ 
        public static function getVersion(){
            return __Version;
        }
        /** Add a class with specified path to an array. This array will utilized by autoloading classes.
        *   @param  $classname  name of the class 
        *   @param  $classpath  the realpath to the class starting at the documentroot 
        *   @return True if added or false if the class already added. 
        */ 
        public static function addUserClass($classname, $classpath){
            if(!isset(self::$__userClassArray[$classname])){
                self::$__userClassArray[$classname] = $classpath;
                return true;
            }else{
                return false;
            }
        }


		/** Adds a class under an aliasname to an array. This array will utilized by autoloading classes. 
		*   @param  $alias  Is an array. This array needs 3 keys with values. The keys are:
        *       @li 'alias' - the new name of the class
        *       @li 'classname' - the original name of the class
        *       @li 'path' - the realpath to the classfile starting at the documentroot
        *   @return True if the class added or false if the class already added.    
		*/ 
		public static function addAliasClass($alias){
			if(isset($alias['classname']) and isset($alias['alias']) and isset($alias['path'])){
				self::$__aliasClass[$alias['alias']]['classname'] = $alias['classname'];
				self::$__aliasClass[$alias['alias']]['path'] = $alias['path'];
			}else{
				return false;
			}
		}
        /** Returns the time that from calling the application till output (on server) of the results.
        *   @return String  Time in secondes
        * 
        */ 
        public static function getApplicationBuildingTime(){
            $timeend = microtime(true);
            return number_format(((substr($timeend,0,9)) + (substr($timeend,-10)) - (substr(__VimeritoStartTime,0,9)) - (substr(__VimeritoStartTime,-10))),4);
        }
        /** Autoloads unknown classes with specified conventions
        *   @param  $classname  The name of the class that called and is unknown 
        */ 
        public static function autoload($classname){
            if(self::$__requestType == 'api'){
            }elseif(self::$__requestType == ModulRequest && file_exists(self::getApplicationPath().'modules/'.str_replace("Modul", "", $classname)."/".$classname."Module.class.php")){
            	require_once(self::getApplicationPath().'modules/'.str_replace("Module", "", $classname)."/".$classname."Module.class.php");	
            }elseif(str_replace('api', '', strtolower($classname)) != strtolower($classname)){
                require_once(self::getApplicationPath().'api/'.$classname.'.'.self::$__userFileClassExtension.'.php');
            }elseif(Vimerito::__isSystemController($classname)){
                require_once(self::$__systemController[$classname]);
            }elseif(isset(self::$__classArray[$classname])){
                require_once(self::$__classArray[$classname]);
            }elseif(str_replace('helper', '', strtolower($classname)) != strtolower($classname)){
            	if(file_exists(self::getApplicationPath().'helpers/'.str_replace('_', '/', str_replace('helper', '', strtolower($classname))).'Helper'.'.'.self::$__userFileClassExtension.'.php')){
                	require_once(self::getApplicationPath().'helpers/'.str_replace('_', '/', str_replace('helper', '', strtolower($classname))).'Helper'.'.'.self::$__userFileClassExtension.'.php');    
            	}
            	if(self::$__requestType == ModulRequest){
            	    if(file_exists(self::getModulPath().'helpers/'.str_replace('_', '/', str_replace('helper', '', strtolower($classname))).'Helper'.'.'.self::$__userFileClassExtension.'.php')){
                		require_once(self::getModulPath().'helpers/'.str_replace('_', '/', str_replace('helper', '', strtolower($classname))).'Helper'.'.'.self::$__userFileClassExtension.'.php');    
            		}
            	}
            }elseif(str_replace('model', '', strtolower($classname)) != strtolower($classname)){
            	if(file_exists(self::getApplicationPath().'models/'.str_replace('_', '/', str_replace('model', '', strtolower($classname))).'Model'.'.'.self::$__userFileClassExtension.'.php')){
                	require_once(self::getApplicationPath().'models/'.str_replace('_', '/', str_replace('model', '', strtolower($classname))).'Model'.'.'.self::$__userFileClassExtension.'.php');
            	}
                if(self::$__requestType == ModulRequest){
            	    if(file_exists(self::getModulPath().'models/'.str_replace('_', '/', str_replace('model', '', strtolower($classname))).'Model'.'.'.self::$__userFileClassExtension.'.php')){
                		require_once(self::getModulPath().'models/'.str_replace('_', '/', str_replace('model', '', strtolower($classname))).'Model'.'.'.self::$__userFileClassExtension.'.php');    
            		}
            	}
            }elseif(isset(self::$__userClassArray[$classname])){
                self::$__controllerPath[$classname] = dirname(self::$__userClassArray[$classname]);
                require_once(self::$__userClassArray[$classname]);
			}elseif(file_exists(self::getApplicationPath().'controllers/'.str_replace('_', '/', $classname).'Controller'.'.'.self::$__userFileClassExtension.'.php')){
                self::$__controllerPath[$classname] = dirname(self::getApplicationPath().'controllers/'.str_replace('_', '/', $classname).'Controller'.'.'.self::$__userFileClassExtension.'.php');
                require_once(self::getApplicationPath().'controllers/'.str_replace('_', '/', $classname).'Controller'.'.'.self::$__userFileClassExtension.'.php');
			}elseif(VRequest::isModulRequest() && file_exists(self::getModulPath().'controllers/'.str_replace('_', '/', $classname).'Controller'.'.'.self::$__userFileClassExtension.'.php')){
				require_once(self::getModulPath().'controllers/'.str_replace('_', '/', $classname).'Controller'.'.'.self::$__userFileClassExtension.'.php');
            }elseif(file_exists(self::getApplicationPath().'forms/'.str_replace('_', '/', $classname).'Form'.'.'.self::$__userFileClassExtension.'.php')){
                require_once(self::getApplicationPath().'forms/'.str_replace('_', '/', $classname).'Form'.'.'.self::$__userFileClassExtension.'.php');
            }elseif(VRequest::isModulRequest() && file_exists(self::getModulPath().'forms/'.str_replace('_', '/', $classname).'Form'.'.'.self::$__userFileClassExtension.'.php')){
            	require_once(self::getModulPath().'forms/'.str_replace('_', '/', $classname).'Form'.'.'.self::$__userFileClassExtension.'.php');
            //}elseif(file_exists(self::getApplicationPath().'models/'.str_replace('_', '/', $classname).'Model'.'.'.self::$__userFileClassExtension.'.php')){
            //    require_once(self::getApplicationPath().'models/'.str_replace('_', '/', $classname).'Model'.'.'.self::$__userFileClassExtension.'.php');
            }elseif(file_exists(self::getApplicationPath().'events/'.str_replace('_', '/', $classname).'Event'.'.'.self::$__userFileClassExtension.'.php')){
                require_once(self::getApplicationPath().'events/'.str_replace('_', '/', $classname).'Event'.'.'.self::$__userFileClassExtension.'.php');
            }elseif(VRequest::isModulRequest() && file_exists(self::getModulPath().'events/'.str_replace('_', '/', $classname).'Event'.'.'.self::$__userFileClassExtension.'.php')){
            	require_once(self::getModulPath().'events/'.str_replace('_', '/', $classname).'Event'.'.'.self::$__userFileClassExtension.'.php');
            }elseif($classname != str_replace('_', '', $classname)){
            	if(self::$__requestType == ModulRequest){
            	    $__path = self::getModulPath().str_replace('_', '/', $classname);
                	if(file_exists($path)){
                    	require_once($path);
                	}            		
            	}else{
	                $__path = self::getApplicationPath().str_replace('_', '/', $classname);
	                if(file_exists($path)){
	                    require_once($path);
	                }
            	}
            }else{
                echo $classname;
                //Vimerito::redirect(501, array('failure' => '20'), array('VFailure'));
            }
        }   
        /** Checks if a called controller is a special systemused controller, like the failurepage.
        *   @param  $classname  The name of the controllerclass. 
        *   @return True if the controller is a systemused controller or false if not. 
        */ 
        private static function __isSystemController($classname){
            if(array_key_exists($classname, self::$__systemController)){
                return true;
            }else{
                return false;
            }
        }
        /** Registers an additional autoloader. The old autoloader, use by Vimerito 2 will not be affected.
        *   @param  $autoloader (mixed) Can be an array with classname and method or a functionname. 
        */ 
        public static function registerAutoloader($autoloader){
            spl_autoload_unregister(array('Vimerito','autoload'));
            spl_autoload_register($autoloader);
            spl_autoload_register(array('Vimerito','autoload'));
        }
        
        /**
         * Unregister an additional autoloader.
         * @param array, string $autoloader
         */
        public static function unregisterAutoloader($autoloader){
        	spl_autoload_unregister($autoloader);
        }

        /** Returns the realpath to the current controllerfile
        *   @param  $class  $classname of the controller
        *   @return String  Filepath of the controller
        *   @todo   Building in the constant variable CurrentApplication for default
        */ 
        public static function getControllerPath($class){
            return self::$__controllerPath[get_class($class)];
        }

        /**
         * Retruns if a specified object is a modul or not.
         * @param object $object
         */
        public static function isModul($object){
        	if(method_exists($object, 'isModul')){
        		if($object::isModel()){
        			return true;
        		}else{ 
        			return false;
        		}
        	}else{
        		return false;	
        	}
        }
        /** Create a valid Vimerito 2 weblink
        *   @param  $param  Is an array that includes the controllername an the methodname without the suffix 'Action' or 'Init'. 
        *                   Also the constant CurrentApplication can be used, if a link to the actual controller should be builded. 
        *   @param  $gets   (Optional)  Includes parameter that sended via the url. The array needs in every case a key for the parametername and a value.    
        *   @return String  The link to a page.
        *   @version 0.4.1  Applicationbug removed. The application-parameter was twice in an url.
        */ 
        public static function createUrl($param=Null, $gets=Null){
            if(self::$configuration['pageUrl'] != ""){
                if(is_array($param)){
                    $url = self::$configuration['pageUrl'];
                    if(count($param) < 3){
                        if(self::$__currentApplication != "" OR self::$__currentApplication != Null){
                            $url .= "/".self::$__currentApplication;
                        }
                    }
                    $__pCounter = 0;
                    foreach($param as $p){
                        if($p != '' and $__pCounter < count($param)){
                            $url = $url."/".$p;
                        }
                        $__pCounter++;
                    }
                }elseif(is_string($param)){
                    $url = self::$configuration['pageUrl'].'/'.(string)$param;
                }elseif($param == CurrentApplication){
                    $url = self::$configuration['pageUrl'];
                    if(self::$__currentApplication != "" OR self::$__currentApplication != Null){
                        $url .= "/".self::$__currentApplication;
                    }
                    $url .= "/".self::$__calledController;
                    if(self::$__calledMethod != "")
                        $url.="/".self::$__calledMethod;
                }else{
                    $url = self::$configuration['pageUrl'];
                }
                if(isset($gets)){
                    if(is_array($gets)){
                        foreach($gets as $key=>$value){
                            $url.='/';
                            $url.=$key.self::$configuration['routingParamtersSeperator'].$value;
                        }
                    }else{
                        $url.=$gets;
                    }
                }
                if($url != self::$configuration['pageUrl'])
                    $url.=".html";
                return $url;
            }
            return Null;
        }
        /** Redirects to the page before or a given page. Optional parameters in the url can be used.
        *   @param  $num    A specified redirectionscode.
        *   @param  $addParam   (mixed) If true the parameter of the last request are used, if is an array the parameter of the array used. On false no parameter used.
        *   @param  $toSite If NULL Vimerito 2 redirects to the last page. If an array given Vimerito 2 the controller and the action specified in the array. 
        */ 
        public static function redirect($num = 307, $addParam = true, $toSite = Null){
            static $http = array (
                100 => "HTTP/1.1 100 Continue",
                101 => "HTTP/1.1 101 Switching Protocols",
                200 => "HTTP/1.1 200 OK",
                201 => "HTTP/1.1 201 Created",
                202 => "HTTP/1.1 202 Accepted",
                203 => "HTTP/1.1 203 Non-Authoritative Information",
                204 => "HTTP/1.1 204 No Content",
                205 => "HTTP/1.1 205 Reset Content",
                206 => "HTTP/1.1 206 Partial Content",
                300 => "HTTP/1.1 300 Multiple Choices",
                301 => "HTTP/1.1 301 Moved Permanently",
                302 => "HTTP/1.1 302 Found",
                303 => "HTTP/1.1 303 See Other",
                304 => "HTTP/1.1 304 Not Modified",
                305 => "HTTP/1.1 305 Use Proxy",
                307 => "HTTP/1.1 307 Temporary Redirect",
                400 => "HTTP/1.1 400 Bad Request",
                401 => "HTTP/1.1 401 Unauthorized",
                402 => "HTTP/1.1 402 Payment Required",
                403 => "HTTP/1.1 403 Forbidden",
                404 => "HTTP/1.1 404 Not Found",
                405 => "HTTP/1.1 405 Method Not Allowed",
                406 => "HTTP/1.1 406 Not Acceptable",
                407 => "HTTP/1.1 407 Proxy Authentication Required",
                408 => "HTTP/1.1 408 Request Time-out",
                409 => "HTTP/1.1 409 Conflict",
                410 => "HTTP/1.1 410 Gone",
                411 => "HTTP/1.1 411 Length Required",
                412 => "HTTP/1.1 412 Precondition Failed",
                413 => "HTTP/1.1 413 Request Entity Too Large",
                414 => "HTTP/1.1 414 Request-URI Too Large",
                415 => "HTTP/1.1 415 Unsupported Media Type",
                416 => "HTTP/1.1 416 Requested range not satisfiable",
                417 => "HTTP/1.1 417 Expectation Failed",
                500 => "HTTP/1.1 500 Internal Server Error",
                501 => "HTTP/1.1 501 Not Implemented",
                502 => "HTTP/1.1 502 Bad Gateway",
                503 => "HTTP/1.1 503 Service Unavailable",
                504 => "HTTP/1.1 504 Gateway Time-out"
            );
            VSession::saveSession();
            header($http[$num]);
            if($toSite == Null){
                if($addParam == true){
                    header ("Location: ".self::createUrl(array(VSession::get("__lastController"), VSession::get("__lastMethod")), VSession::get("__lastParams")));
                }else{
                    header ("Location: ".self::createUrl(array(VSession::get("__lastController"), VSession::get("__lastMethod"))));
                }
            }elseif(is_array($toSite)){
                if(is_array($addParam)){
                    header ("Location: ".self::createUrl($toSite, $addParam));
                }elseif($addParam == true){
                    header ("Location: ".self::createUrl($toSite, VSession::get("__lastParams")));
                }else{
                    header ("Location: ".self::createUrl($toSite));
                }
            }else{
                header ("Location: ".self::createUrl(array('VFailureController'), array('failure'=>'20')));
            }
            exit();
        }
        /** Convert an array to an object
        *   @param  $array  The array.
        *   @return object
        */ 
        public static function arrayToObject($array) {
            $object = new stdClass();
            foreach( $array as $key => $value ) {
                if( is_array( $value ) ) {
                    $object->$key = Vimerito::arrayToObject( $value );
                }
                else {
                    $object->$key = $value;
                }
            }
            return $object;
        }
        /** Returns the array application from the configuration
        * 
        */ 
        public static function getApplicationArray(){
            return self::$configuration['applications'];   
        }
        /** Set the path for loading controller, views, layouts and models to the applicationpath
        *   @param  $applicationName    Name of the application. 
        */        
        public static function setApplicationPath($applicationName){
            self::$__currentApplication = $applicationName;
            self::$__applicationPath = self::$configuration['applications'][$applicationName];
            if(substr(self::$__applicationPath, 0, -1) != '/'){
                self::$__applicationPath.= '/';    
            }        
        }
        
        public static function setDevelopmentApplicationPath(){
        	self::$__currentApplication = '_development';
        	self::$__applicationPath = '_development/';	
        }
        
        public static function getApplicationPath($ignoreDevelopmentRequest = false){
        	if(VRequest::calledRequestType() != DevelopmentRequest OR $ignoreDevelopmentRequest == true){
		    	if(VRequest::calledRequestType() == DevelopmentRequest){
		    		return str_replace(__Basedir, "", __ApplicationPath);
		    	}else{
	        		if(self::$__applicationPath != '' or self::$__applicationPath != Null){
			            return str_replace(str_replace(__Basedir, "", __ApplicationPath), "", __ApplicationPath).self::$__applicationPath;
			       	}else{
			            return str_replace(__Basedir, "", __ApplicationPath);
			        }   
		    	}
        	}else{
        		return str_replace(__Basedir, "", __SystemPath).self::$__applicationPath;
        	}   
        }
        
        public static function getApplicationName(){
            return self::$__currentApplication;
        }
        
        public static function createCode($codeLength){
            srand((double)microtime()*1000000);
            $createdCode = '';
            $zeichen="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            for ($i=0;$i<$codeLength;$i++) {
                $n=rand() % strlen($zeichen);
                $createdCode .=substr($zeichen, $n, 1);
            }
            return $createdCode;
        }
        
        public static function setDebugMode($mode){
            self::$__debugMode = $mode;
            if($mode == true){
                error_reporting(E_WARNING | E_ERROR | E_PARSE | E_ALL);                
            }else{
                error_reporting(E_ERROR| E_PARSE);    
            }
        }
        
        public static function debugMode(){
            return self::$__debugMode;
        }

    }

    spl_autoload_register(array('Vimerito','autoload'));   
?>