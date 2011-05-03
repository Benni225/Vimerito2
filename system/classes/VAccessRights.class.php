<?
    /**
    *   @author     cameleon Internet Media 
    *   @copyright  2011 cameleon Internet Media
    *   @file       VAccessRights.class.php
    *   @date       22.04.2011
    *   @version    0.2
    *   @version    0.1  VAccessRights-class added
    */ 
    
    /**  VAccessRights
    *   This class authenticate user by their session and the
    *   access key given by the controller or controller method.
    *   If the user get no access to the site he will redirect to another page. 
    * 
    */ 
	class VAccessRights extends VSession{
	    /** $__accessKeys includes all different kinds of accounts given by accessConfiguration.php in the applicationdir.
	    * 
	    */ 
		private static $__accessKeys = array();
	    /** Includes all assoziations to between the kinds of accounts given by accessConfiguration.php
	    *  
	    */
		private static $__accessRights = array();
	    /** Is the signification in the session variable. It is configured in accessConfiguration.php in the applicationdir.
	    */
		private static $__nameForAccessKey = "";
	    /** In the case of unauthorizied user, the user redirect to that controller an method
	    *  @see VRouter.class.php
	    */
		private static $__redirectController = array();
	    /** Loads the configurationfile {applicationpath}/accessConfiguration.php 
	    *   
	    */		
		public function loadConfiguration(){
    	    if(file_exists(Vimerito::getApplicationPath()."configuration/accessConfiguration.php")){
        		require(Vimerito::getApplicationPath()."configuration/accessConfiguration.php");
        		self::$__accessKeys = $__cachedAccessKeys;
        		self::$__accessRights = $__cachedAccessRights;
        		self::$__nameForAccessKey = $__cachedNameforAccessKey;
        		self::$__redirectController = $__cachedRedirectController;
        		if(self::get(self::$__nameForAccessKey) == '' or self::get(self::$__nameForAccessKey) == Null){
                    self::set(self::$__nameForAccessKey, 0);
                }
            }  
        }
	    /** Checks if the user have the right for access.
	    *  
	    *  @access public
	    *  @return bool On access afforded true. If not this method returns false.
	    *  @version 0.3 Bug removed
	    */		
		public static function authenticateUser($method){
            if(empty(self::$__accessKeys)){
                self::loadConfiguration();
            }
			$controller = Vimerito::getInstance(CurrentApplication);
			if(isset($controller->accessOption)){
			    if(is_array($controller->accessOption[$method])){
                    switch($controller->accessOption[$method][1]){
                        case 'only':
                            if($controller->accessOption[$method][0] == self::$__accessKeys[self::get(self::$__nameForAccessKey)]){
                                return true;
                            }else{
                                return false;
                            }
                            break;
                    }
                }else{
                    $accessKeyIndex = false;
                    $accessKeyIndex = array_search($controller->accessOption[$method], self::$__accessKeys); 
                    if(self::$__accessKeys[$accessKeyIndex] == self::$__accessKeys[self::get(self::$__nameForAccessKey)] or in_array($controller->accessOption[$method], self::$__accessRights[self::$__accessKeys[self::get(self::$__nameForAccessKey)]])){
                        return true;
                    }else{
                        return false;
                    }
                } 
            }else{
                return true;
            }
		}
	    /**    Return the controller an method for redirection in case of denied access.
	    *      @return array Is an array in form of array('Mycontroller'[, 'MyMethod'])
	    */		
		public static function getRedirectController(){
            return self::$__redirectController;      
        }
        /** Return the name that use in the session for storing the userstate
        *   @return String  Name of the key in the session-variable 
        */ 
        public static function getNameForAccessKey(){
            if(empty(self::$__accessKeys)){
                self::loadConfiguration();
            }
            return self::$__nameForAccessKey;
        } 
        /** Return the state of the user represented by a number
        *   @return Integer The number that represents the userstate 
        */  
        public static function getUserState(){
            if(empty(self::$__accessKeys)){
                self::loadConfiguration();
            }
            return self::get(self::$__nameForAccessKey);    
        }
        /** Return the userstate as word. These words configured in the file accessConfiguration.php
        *   @return String The userstate as word.  
        */ 
        public static function getUserAccountName(){
            if(empty(self::$__accessKeys)){
                self::loadConfiguration();
            }
            return self::$__accessKeys[self::get(self::$__nameForAccessKey)];    
        }
	}
?>