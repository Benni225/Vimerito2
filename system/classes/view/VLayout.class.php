<?
    class VLayout{
    	const standard = 2001;
        private static $__layoutFile = Null;
        private static $__layoutPath = Null;
        private static $__layoutSource = Null;
        private static $__layoutBlocks = array();
        private static $__layoutBlockInsert = array();
		private static $__caching = false;
		private static $__cachingTime = 0;
		private static $__useJavaScriptLinks = Null;
		protected static $__userJavaScriptLibraries = Array();
		public static $__layoutLink;
        
        public function __construct($cache = false, $cacheTime = 0){
		    self::$__caching = $cache;
			self::$__cachingTime = $cacheTime;
        } 

        /**
         * Loads a layoutfile. 
         * 
         * @param string $filename
         * @param array $__blocks
         * @param bool $__modulOption
         */
        public static function load($filename, $__blocks=Null, $__modulOption = FALSE){
            $blocks = Null;
			if($filename != ""){
				self::$__layoutFile = basename($filename);
				if($__modulOption == TRUE && VRequest::isModulRequest() == TRUE){
					self::$__layoutPath = dirname(Vimerito::getModulPath()."layout/".$filename)."/";	
					self::$__layoutLink = Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', Vimerito::getModulPath())."layout/".dirname($filename)."/";
				}else{
			    	self::$__layoutPath = dirname(Vimerito::getApplicationPath()."layout/".$filename)."/";
					self::$__layoutLink = Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', Vimerito::getApplicationPath())."layout/".dirname($filename)."/";
				}
				if(file_exists(self::$__layoutPath."blocksConfiguration.php")){
	                if($__blocks == Null){
	                    require self::$__layoutPath."blocksConfiguration.php";
	                }
	            }
	            if($blocks == Null){
	                $blocks = $__blocks;
	            }
	            if(file_exists(self::$__layoutPath."__cached__".self::$__layoutFile) and self::$__caching == true and self::$__cachingTime > 0 and filemtime(self::$__layoutPath."__cached__".self::$__layoutFile) + self::$__cachingTime > time()){
					self::$__layoutFile = "__cached__".self::$__layoutFile;
				}else{
			        if($blocks != Null and is_array($blocks)){
			            foreach($blocks as $name => $selector){
			                $methodname = strtolower($name)."Insert";
			                self::$__layoutBlocks[$name] = $selector;    
			            }
			        }   
				}
			}else{
				throw new Exception('No layoutfile is given!');
			}
        }
        /** Adds a new layoutblock for insering views
        * @version 0.4
        * 
        * 
        */ 
        public static function addBlock($block){
            self::$__layoutBlocks = array_merge(self::$__layoutBlocks, $block);
        }
		
		public static function compile(){
		    if(!VRequest::isJavaScriptRequest()){ 
                ob_start();
                ob_implicit_flush(false);
                require(self::$__layoutPath.self::$__layoutFile);
                self::$__layoutSource = ob_get_clean();	
                if(Vimerito::$configuration['javaScriptMode'] == true){
        			$Dom = new SimpleHtmlDom;
        			$Dom->load(self::$__layoutSource); 
        			
                    $elements = $Dom->find("head");     
                    $elements[0]->innertext = VJavaScriptBase::insertJavaScript().$elements[0]->innertext;
                    
                    self::$__layoutSource = $Dom->save();   
           	    }
    			if(self::$__caching == true){
    				$handle = fopen(self::$__layoutPath."__cached__".self::$__layoutFile, "w+");
    				fwrite($handle, self::$__layoutSource);
    				fclose($handle);
    			}
			}
		}
        
		/*
		*	$blocks = array(
		*		'menu'	=>	'div#menu',
		*		'content'	=>	'div.content'
		*	);
		*	After blockregistration the methods
		*	VLayout::menuInsert($viewname, 'append');
		*
		*	and
		*
		*	VLayout::contentInsert($viewname, 'prepend');
		*	VLayout::contentInsert($viewname);
		*/
        public static function registerBlocks($blocks){
            if($blocks != Null and is_array($blocks)){
                foreach($blocks as $name => $selector){
                    $methodname = strtolower($name)."Insert";
                    self::$__layoutBlocks[$name] = $selector;
                }
            }            
        } 
        
		/**
		*	Will be called if a insert-method executed
		*/
	/*	public static function insertIntoBlock($methodname, $blockname, $viewname){
            self::$__layoutBlockInsert[$blockname] = $viewname;
        }    */
        
        public static function insertIntoBlock($blockname, $ressource){
            self::$__layoutBlockInsert[$blockname] = $ressource;
        }
        
        public static function insertIntoBlockC($blockname, $action = LayoutAppend){
            if(!VRequest::isJavaScriptRequest()){
                if(self::$__layoutBlocks[$blockname] != ""){
                    $Dom = new SimpleHtmlDom;
    				$Dom->load(self::$__layoutSource);
    				$elements = $Dom->find(self::$__layoutBlocks[$blockname]);
                    foreach($elements as $e){
    					if($action == LayoutPrepend)
    						$e->innertext = self::$__layoutBlockInsert[$blockname]->get()."\n".$e->innertext;
    					elseif($action == LayoutAppend)
    						$e->innertext .= "\n".self::$__layoutBlockInsert[$blockname]->get();
    					else
    						$e->innertext = self::$__layoutBlockInsert[$blockname]->get();
    				}
    				self::$__layoutSource = $Dom->save();      
    			}
			}
        }  
		
		public static function renderLayout(){
		    if(!VRequest::isJavaScriptRequest()){
    		    self::compile();
    		    foreach(self::$__layoutBlockInsert as $key=>$value){
                    self::insertIntoBlockC($key);
                }
                if(self::$__useJavaScriptLinks != Null){
                    self::$__layoutSource = VJavaScript::useJavaScriptLinks(self::$__layoutSource, self::$__useJavaScriptLinks);
                }
                if(!VRouter::calledRequestType() == 'Ajax'){
                    echo self::$__layoutSource;
                }else{
                    VView::_renderAll();    
                }     
    		}
		}
		
		public static function registerUserJavaScriptLibraries($libraries){
            self::$__userJavaScriptLibraries = array_merge(self::$__userJavaScriptLibraries, array($libraries));
        }

		public function useJavaScriptLinks($selector='a'){
		    self::$__useJavaScriptLinks = $selector;
        }
    }
?>