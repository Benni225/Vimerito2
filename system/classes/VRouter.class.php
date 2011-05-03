<?
    /**
    *   @Version 0.3
    *   method route(): Routing of applications added. 
    *   @Version 0.2
    *   @Version 0.1
    *   VRouter-class added 
    */ 
	class VRouter{
		private static $__routerParams = array();
		private static $__calledController = Null;
		private static $__calledMethod = Null;
		private static $__calledRequestTyp = Null;
		private static $__referer;
		
		public function __construct(){
		    //self::$__calledController = Vimerito::$configuration['routeControllerOnDefault'];
		    //self::$__calledMethod = Vimerito::$configuration['routeMethodOnDefault'];
		    self::$__referer = $_SERVER['HTTP_REFERER'];
        }
		
		public static function route(){		
		    $param = urldecode($_GET['param']);
		    $param = str_replace(".html", "", $param);
		    $parama = explode("/", $param);
		    $found = 0;
			foreach($parama as $key){
			    $appArray = Vimerito::getApplicationArray();
                if($key != str_replace(Vimerito::$configuration['routingParamtersSeperator'], "", $key)){
					$__params = explode(Vimerito::$configuration['routingParamtersSeperator'], $key);
					self::$__routerParams[$__params[0]] = $__params[1];
					$_GET[$__params[0]] = $__params[1];
			    }elseif(!empty($appArray) AND array_key_exists($key, $appArray) AND Vimerito::getApplicationName() == ''){
                    Vimerito::setApplicationPath($key);
				}elseif(self::$__calledController == Null){
                    self::$__calledController = $key;
				}elseif(self::$__calledMethod == Null and strtolower($key) != "ajax"){
                    self::$__calledMethod = $key; 
				}elseif($key == "api" and $found > 0){
                    self::$__calledRequestTyp = $key;    
                }
			}
            if(self::$__calledController == Null){
                self::$__calledController = Vimerito::$configuration['routeControllerOnDefault'];
            }
            if(self::$__calledMethod == Null){
                self::$__calledMethod = Vimerito::$configuration['routeMethodOnDefault'];    
            }
            $_GET = $__routerParams;		
		}
		
		public static function calledController(){
            return self::$__calledController;
        }
        
        public static function calledMethod(){
            return self::$__calledMethod;
        }
        
        public static function calledRequestType(){
            return self::$__calledRequestTyp;
        }
        
        public static function getParam($name){
            return self::$__routerParams[$name];
        }
        
        public static function getParamArray(){
            return self::$__routerParams;
        }
	}
?>