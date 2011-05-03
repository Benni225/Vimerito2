<?
    class VLayout extends VView{
        private static $__layoutFile = Null;
        private static $__layoutPath = Null;
        private static $__layoutSource = Null;
        private static $__layoutBlocks = array();
        private static $__layoutBlockInsert = array();
		private static $__caching = false;
		private static $__cachingTime = 0;
		private static $__useJavaScriptLinks = Null;
		protected static $__userJavaScriptLibraries = Null;
		public static $__layoutLink;
        
        public function __construct($cache = false, $cacheTime = 0){
		    self::$__caching = $cache;
			self::$__cachingTime = $cacheTime;
        } 
        
        /*public static function __callstatic($method, $parameters){
            if(method_exists('VLayout', $method)){
                $func = "\$_return = call_user_func(array('VLayout', \$method)";
                foreach($parameters as $p){
                    $func.= ", \$p";
                }
                $func.=");";
                eval($func);
                return $_return;
            }elseif(array_key_exists(str_replace('Insert', '', $method), self::$__layoutBlocks)){
                $func = "call_user_func(array(VLayout, insertIntoBlock), \$method, str_replace('Insert', '', \$method)";
                foreach($parameters as $p){
                    $func.= ", $p";
                }
                $func.=");";
                eval($func);
            }            
        }*/ 
        
        public static function load($filename, $blocks=Null){
			if($filename != ""){
				self::$__layoutFile = basename($filename);
		        self::$__layoutPath = dirname(Vimerito::getApplicationPath()."layout/".$filename)."/";
				self::$__layoutLink = Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', Vimerito::getApplicationPath())."layout/".dirname($filename)."/";
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
				//Fehler ausgeben!
			}
        }
		
		public static function compile(){
            ob_start();
            ob_implicit_flush(false);
            require(self::$__layoutPath.self::$__layoutFile);
            self::$__layoutSource = ob_get_clean();	
			$Dom = new SimpleHtmlDom;
			$Dom->load(self::$__layoutSource); 
			
            $elements = $Dom->find("head");     
            if(Vimerito::$configuration['javaScriptMode'] == true){
                $elements[0]->innertext = VJavaScriptBase::insertJavaScript().$elements[0]->innertext;
            }
            self::$__layoutSource = $Dom->save();   
       	
			if(self::$__caching == true){
				$handle = fopen(self::$__layoutPath."__cached__".self::$__layoutFile, "w+");
				fwrite($handle, self::$__layoutSource);
				fclose($handle);
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
        
		/*
		*	Will be called if a insert-method executed
		*/
	/*	public static function insertIntoBlock($methodname, $blockname, $viewname){
            self::$__layoutBlockInsert[$blockname] = $viewname;
        }    */
        
        public static function insertIntoBlock($blockname, $viewname){
            self::$__layoutBlockInsert[$blockname] = $viewname;
        }
        
        public static function insertIntoBlockC($blockname, $viewname, $action = Null){
            if(self::$__layoutBlocks[$blockname] != "" and $viewname != "" and self::$__templateCacheVar[$viewname] != ""){
                $Dom = new SimpleHtmlDom;
				$Dom->load(self::$__layoutSource);
				$elements = $Dom->find(self::$__layoutBlocks[$blockname]);
                foreach($elements as $e){
					if($action == LayoutPrepend)
						$e->innertext = self::$__templateCacheVar[$viewname]."\n".$e->innertext;
					elseif($action == LayoutAppend)
						$e->innertext .= "\n".self::$__templateCacheVar[$viewname];
					else
						$e->innertext = self::$__templateCacheVar[$viewname];
				}
				self::$__layoutSource = $Dom->save();      
			}
        }  
		
		public static function renderLayout(){
		    self::compile();
		    foreach(self::$__layoutBlockInsert as $key=>$name){
                self::insertIntoBlockC($key, $name);
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
		
		public static function registerUserJavaScriptLibraries($libraries){
            self::$__userJavaScriptLibraries = $libraries;
        }

		public function useJavaScriptLinks($selector='a'){
		    self::$__useJavaScriptLinks = $selector;
        }
    }
?>