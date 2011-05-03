<?
    class VView{
        protected $_vars = array();
        private static $__templates = array();
        private static $__templateFilenames = array();
        private static $__templateVars = array();
        protected static $__templateCacheVar = array();
        public $path = "";
        
        public function __get($name){
            return $__templateVars[$name];
        }
        
        public function loadView($name, $filename){
            if($this->isModul()){
                self::$__templates[$name] = Vimerito::getModulPath()."/views/".$filename;
                self::$__templateFilenames[$name] = $filename; 
            }else{
                self::$__templates[$name] = Vimerito::getApplicationPath()."views/".$filename; 
                self::$__templateFilenames[$name] = $filename;  
            }    
        }  
        
        /*
        * Alias for self::_renderAll();
        */ 
        public function renderAll(){
            self::_renderAll();
        } 
        
        public static function _renderAll(){
            foreach(self::$__templates as $path){
                $this->path = dirname($path);
                if(file_exists($path)){
                    require($path);
                }
            }            
        }
        
        public function assignVar($name, $value){
            self::$__templateVars[$name] = $value;    
        }
        //In context $caching=CacheToFile $cachingtime = time that the file will cached
        //In context $caching=CacheToVar $cachingtime = true or false. On true the cached viewfile renderd after caching to var.
        public function render($name, $caching = false, $cachingtime = 0){
            if(file_exists(self::$__templates[$name])){
                $this->path = dirname($path);
                if($caching == CacheToFile and $cachingtime > 0){
                    if(file_exists(dirname(self::$__templates[$name])."/cache/".self::$__templateFilenames[$name])){
                        if(filemtime(dirname(self::$__templates[$name])."/cache/".self::$__templateFilenames[$name]) + $cachingtime > time()){
                            require(dirname(self::$__templates[$name])."/cache/".self::$__templateFilenames[$name]);
                            $__loaded = true;
                        }    
                    }
                    if($__loaded != true){
            			ob_start();
            			ob_implicit_flush(false);
            			require(self::$__templates[$name]);
            			$__cache = ob_get_clean();
                        $path = dirname(self::$__templates[$name])."/cache/";
                        if(!file_exists($path)){
                            mkdir($path, 660);
                        }
                        if(file_exists($path)){
                            $handle = fopen($path.self::$__templateFilenames[$name], "w+");
                            fwrite($handle, $__cache);
                            fclose($handle);                            
                        }
                        echo $__cache;                        
                    }              
                }elseif($caching == CacheToVar){
        			ob_start();
        			ob_implicit_flush(false);
        			require(self::$__templates[$name]);
        			$__cache = ob_get_clean(); 
                    self::$__templateCacheVar[$name] = $__cache;
                    if($cachingtime == true){
                        echo $__cache;
                    }                        
                }else{
                    require(self::$__templates[$name]);    
                }

            }
        }  

	    public function viewInView($view1, $cssselector, $view2){
			$viewdata1 = self::$__templateCacheVar[$view1];
			$viewdata2 = self::$__templateCacheVar[$view2];
			
			if($viewdata1 != '' and $viewdata2 != ''){
				$view = new SimpleHtmlDom;
				$view->load($viewdata1);
				$view->find($cssselector);
				foreach($view as $e){
					$e->innertext($viewdata2);
				}
				
			}
		}
    }
?>