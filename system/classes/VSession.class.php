<? 
    class VSession{
    	public static $sessionVal = array();
    	private static $__sessionId;
    	public function __construct(){
			session_start();
    		self::$sessionVal = $_SESSION;
			self::saveSession();
    	}
		
		public function __destruct(){
			//$_SESSION = array_merge($_SESSION, self::$sessionVal);
		}
    	
    	public static function saveSession(){
    		//session_unset();
    		foreach(self::$sessionVal as $key=>$value){
    			$_SESSION[$key] = $value;
    		}
    	}
    	
    	public static function destroySession(){
    	   self::$sessionVal = Null;
    	   session_unset($_SESSION);
    	   session_destroy();
    	}
    	
    	public static function generateID($lng = 15){
    	    mt_srand((double)microtime()*1000000);
    	    $charset = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
    	    $length  = strlen($charset)-1;
    	    $code    = '';
    	    for($i=0;$i<$lng;$i++){
    	      $code .= $charset{mt_rand(0, $length)};
    	    }
    		return md5($code);
    	}
    	
    	public static function createSession(){
    		//self::$__sessionId = self::generateID(); 
    		session_start();
    		self::$sessionVal = $_SESSION;
    		//session_id(self::$__sessionId);
    	}
    	
    	public static function regenerate($delete = false){
    		self::$__sessionId = self::generateID(); 
    		session_regenerate_id($delete);
    		session_id(self::$__sessionId);
    	}
		
		public static function get($name){
			return self::$sessionVal[$name];
		}
		
		public static function set($name, $value = 0){
            if(is_array($name)){
                self::$sessionVal = array_merge(self::$sessionVal, $name);    
            }else{
                self::$sessionVal[$name] = $value;    
            }
		}
    }
?>