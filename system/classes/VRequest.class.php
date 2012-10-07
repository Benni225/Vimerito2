<? 
    class VRequest{
        private static $__javaScriptRequest;
        private static $__viewRequest;
        private static $__modulRequest;
        private static $__requestedModul;
        
        protected $_returnType = "";
        
        public function __construct(){
            self::$__javaScriptRequest = false;
            self::$__viewRequest = false;
            self::$__modulRequest = false;
        }
        
        public static function enableJavaScriptRequest(){
            self::$__javaScriptRequest = true;
        }
        
        public static function enableViewRequest(){
            self::$__viewRequest = true;
        }
        
        public static function isJavaScriptRequest(){
            return self::$__javaScriptRequest;
        }
        
        public static function calledRequestType(){
            if(self::$__javaScriptRequest == true){
                return JavaScriptRequest;
            }elseif(self::$__viewRequest == true){
                return ViewRequest;
            }elseif(self::$__modulRequest == true){
            	return ModulRequest;
            }
        }

        public static function enableModulRequest(){
        	self::$__modulRequest = true;
        } 
        
        public static function isModulRequest(){
        	return self::$__modulRequest;
        }
        
        public static function requestedModul(){
        	return self::$__requestedModul;
        }
        
    }
?>