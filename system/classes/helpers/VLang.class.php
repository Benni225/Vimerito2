<?php
	class VLang{
		static protected $language = "";
		static protected $__source;
		static protected $__sourceType;
		static protected $__sourceObject;
		static protected $__sourceFile;
		
		const sourceTypeFile = 1000;
		const sourceTypeObject = 1002;
		
		/**
		 * The magic-method __construct. Loads the default language and the default source.
		 */
		public function __construct(){
			if(VSession::get("__lang") != ""){
				self::$language = VSession::get("__lang");		
			}else{
				VSession::set("__lang", Vimerito::$configuration['defaultLanguage']);
			}		
			$__defaultSource = Vimerito::$configuration['defaultLanguageSource'];
			var_dump($__defaultSource);
			$__defaultSourceArray = explode(":", $__defaultSource);
			if(strtolower($__defaultSourceArray[0]) == "object"){
				$__object = $__defaultSourceArray[1];
				$__instance = new $__object();

				self::setSource($__instance);
			}elseif(strtolower($__defaultSourceArray[0]) == "file"){
					self::setSource($__defaultSourceArray[1]);
			}		
		}
		
		/**
		 * 
		 * Sets the the source. If $source is an object all queries will be send to the 
		 * database. If $source is a string an ini-file will be assumed.
		 * @param object, string $source
		 * @return True for an accepted source. False for not.
		 */
		public static function setSource($source){
			/* If the source a model every 
			 * languagequery is send to the database 
			 */
			if($source == NUll){
				if(VSession::get("__lang") != ""){
					self::$language = VSession::get("__lang");		
				}else{
					VSession::set("__lang", Vimerito::$configuration['defaultLanguage']);
				}		
				$__defaultSource = Vimerito::$configuration['defaultLanguageSource'];
				$__defaultSourceArray = explode(":", $__defaultSource);
				if(strtolower($__defaultSourceArray[0]) == "object"){
					$__object = $__defaultSourceArray[1];
					$__instance = new $__object();
	
					self::setSource($__instance);
				}elseif(strtolower($__defaultSourceArray[0]) == "file"){
						self::setSource($__defaultSourceArray[1]);
				}					
			}else{
				if(is_object($source)){
					self::$__sourceObject = $source;
					self::$__sourceType = self::sourceTypeObject;
					return true;
				}elseif(is_string($source)){
					self::$__sourceFile = $source;
					self::$__sourceType = self::sourceTypeFile;
					return true;
				}else{
					return false;
				}				
			}
		}
		
		/**
		 * 
		 * Loads a string by name from the source. In the database the table has to have 
		 * following columns: id, lang, name, string. The ini-file
		 * must be placed in (Application-Dir)/language/[$lang].ini.
		 * The format of the ini-file is: [$name] = "string". 
		 * For example: sayhello = "Welcome to my page".
		 * 
		 * @param string $lang
		 * @param string $name
		 * 
		 * @return string
		 */
		public static function getString($name){
			if(self::$__source == Null){
				self::setSource(Null);	
			}
				
			if(self::$__sourceType == self::sourceTypeObject){
				self::$__sourceObject->send("
					SELECT `string` FROM ".self::$__sourceObject->_classname."
					WHERE `lang` = '".VSession::get("__lang")."' AND `name` = '".$name."'
				", true);	
				if(!mysql_error()){
					return self::$__sourceObject->string;
				}else{
					return Null;
				}
			}elseif(self::$__sourceType == self::sourceTypeFile){
				$handle = parse_ini_file(Vimerito::getApplicationPath()."language/".VSession::get("__lang").self::$__sourceFile.".ini");
				if(array_key_exists($name, $handle)){
					return $handle[$name];
				}else{
					return "Text can't be found.";
				}
			}
		}
		
		public static function setLanguage($lang){
			VSession::set("__lang", $lang);
		}
		
		public static function output($name){
			echo self::getString($name);
		}
		
		
		
	}

?>