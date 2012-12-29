<?
    /**
    *   @Version 0.3
    *   method route(): Routing of applications added. 
    *   @Version 0.2
    *   @Version 0.1
    *   VRouter-class added 
    */ 
	class VRouter{
		const modul = "module";
		private static $__routerParams = array();
		private static $__calledController = Null;
		private static $__calledMethod = Null;
		private static $__calledModul = Null;
		private static $__calledRequestTyp = Null;
		private static $__referer = Null;
		
		const developmentRouteControllerOnDefault = "applications";
		const developmentRouteMethodOnDefault = "applicationsInit";
		const development = "_development";
		
		public function __construct(){
		    //self::$__calledController = Vimerito::$configuration['routeControllerOnDefault'];
		    //self::$__calledMethod = Vimerito::$configuration['routeMethodOnDefault'];
		    if(array_key_exists("HTTP_REFERER", $_SERVER)){
		    	self::$__referer = $_SERVER['HTTP_REFERER'];	
		    }
        }
        
		/**	
		* Extractes controller, action, modul, special requestes and overgiven parameters out
		* of the URI.
		* 
		* @version 0.4 Bug removed
		* @version 0.6 Modulrequest integrated. 
		* @version 0.6 Also GET-Parameters saved to self::$__routerParams and all parameters copied to $_GET
		*/		
		public static function route(){	
			VEvent::triggerEventBefore("route", new VRouter());
			
		    $param = "";
		    $parama = array();
            if(isset($_GET['param'])){
    		    $param = urldecode($_GET['param']);
    		    $param = str_replace(".html", "", $param);
    		    $parama = explode("/", $param);                
            }	
		    $found = 0;
		    $appArray = Vimerito::getApplicationArray();		    
		    for($parameterCounter = 0; $parameterCounter < count($parama); $parameterCounter++){
		    	if($parama[$parameterCounter] != str_replace(Vimerito::$configuration['routingParamtersSeperator'], "", $parama[$parameterCounter])){
					$__params = explode(Vimerito::$configuration['routingParamtersSeperator'], $parama[$parameterCounter]);
					self::$__routerParams[$__params[0]] = $__params[1];
					$_GET[$__params[0]] = $__params[1];		    		
		    	}elseif(strtolower($parama[$parameterCounter]) == self::modul){
		    		VRequest::enableModulRequest();
		    		self::$__calledModul = $parama[$parameterCounter + 1];
		    		$parameterCounter++;
		    	}elseif(strtolower($parama[$parameterCounter]) == "javascript"){
                    VRequest::enableJavaScriptRequest();
                }elseif(strtolower($parama[$parameterCounter]) == "getview"){
                    VRequest::enableViewRequest();
                }elseif(strtolower($parama[$parameterCounter]) == self::development){
                    VRequest::enableDevelopmentRequest();
                    Vimerito::setDevelopmentApplicationPath();
			    }elseif(!empty($appArray) AND array_key_exists($parama[$parameterCounter], $appArray) AND Vimerito::getApplicationName() == ''){
                    Vimerito::setApplicationPath($parama[$parameterCounter]);
				}elseif(self::$__calledController == Null){
                    self::$__calledController = $parama[$parameterCounter];
				}elseif(self::$__calledMethod == Null and strtolower($parama[$parameterCounter]) != "ajax"){
                    self::$__calledMethod = $parama[$parameterCounter]; 
				}elseif($parama[$parameterCounter] == "api" and $found > 0){
                    self::$__calledRequestTyp = $parama[$parameterCounter];    
                }	
		    }
		    $__gets = explode("?", $_SERVER['REQUEST_URI']);
		    if(count($__gets) > 0 AND key_exists("1", $__gets)){
			    $get = explode("&", $__gets[1]);
			    foreach($get as $key=>$value){
			    	$__get = explode("=", $value);
			    	
			    	self::$__routerParams[$__get[0]] = $__get[1]; 
			    }		
		    }
            VEvent::triggerEventAfter("route", new VRouter());
		}
		/** Returns the called Controller-Class
		*     @version 0.4 Bug removed
		*/ 
		public static function calledController(){
            if(self::$__calledController == Null){
            	if(VRequest::calledRequestType() != DevelopmentRequest){
                	self::$__calledController = Vimerito::$configuration['routeControllerOnDefault'];
            	}else{ 
                	self::$__calledController = self::developmentRouteControllerOnDefault;
            	}
            }
            return self::$__calledController;
        }
		/** Returns the called Controller-Action
		*     @version 0.4 Bug removed
		*/        
        public static function calledMethod(){
            if(self::$__calledMethod == Null){
                self::$__calledMethod = Vimerito::$configuration['routeMethodOnDefault'];
            }
            return self::$__calledMethod;
        }
        
        /**
         * Returns the called modul
         * @version 0.6
         */
        public static function calledModul(){
        	return self::$__calledModul;
        } 
        
        /**
         * 
         * Returns the requesttype
         * @version 0.6
         * 
         */
        public static function calledRequestType(){
            return self::$__calledRequestTyp;
        }
        
        /**
         * 
         * Returns a specified parameter of the URL
         * @param string $name Name of the parameter
         */
        public static function getParam($name){
            if(isset(self::$__routerParams[$name]))
                return self::$__routerParams[$name];
            else
                return Null;
        }
        
        /**
         * 
         * Checks if a specified parameter exists in the URL
         * @param string $name Name of the parameter
         */
        public static function isParam($name){
            return isset(self::$__routerParams[$name]);
        } 
        
        /**
         * Returns all given parameters from the URL as an array
         * 
         */
        public static function getParamArray(){
            return self::$__routerParams;
        }
	}
?>