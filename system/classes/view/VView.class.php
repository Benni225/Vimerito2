<? 
    class VView{
        protected $_templateVars;
        protected $_templateFilename;
        public $_ressource;
        public $cachedView = Null;
        
        public function __construct($source = ""){
            if($source != ""){
                $this->load($source);
            }
        }
        
        public function __get($name){
            return $this->_templateVars[$name];
        }
        
        public function load($filename){
                $this->_ressource = new VViewRessource;
                $this->_ressource->setSource(Vimerito::getApplicationPath()."views/".$filename);
                $this->__templateFilename = $filename;
        }
        
        public function assignVar($name, $value = ""){
            if(is_array($name)){
                $this->_templateVars = array_merge(self::$__templateVars, $name);
            }else{
                $this->_templateVars[$name] = $value;    
            }
        }
        
        public function render($caching = false, $cachingtime = 0){
            if($caching == CacheToFile and $cachingtime > 0){
                if(file_exists(Vimerito::getApplicationPath()."/views/".dirname($this->__templateFilename)."/cache/".basename($this->__templateFilename))){
                    if(filemtime(Vimerito::getApplicationPath()."/views/".dirname($this->__templateFilename)."/cache/".basename($this->__templateFilename)) + $cachingtime > time()){

                        require Vimerito::getApplicationPath()."/views/".dirname($this->__templateFilename)."/cache/".basename($this->__templateFilename);
                        $__loaded = true;
                    }
                }
                if($__loaded != true){
        			ob_start();
        			ob_implicit_flush(false);

        			eval('?>'.$this->_ressource->get().'<? ');

        			$__cache = ob_get_clean();
                    $path = Vimerito::getApplicationPath()."/views/".dirname($this->__templateFilename)."/cache/";
                    if(!file_exists($path)){
                        mkdir($path, 660);
                    }
                    if(file_exists($path)){
                        $handle = fopen($path.basename($this->__templateFilename), "w+");
                        fwrite($handle, $__cache);
                        fclose($handle);
                    }
                    echo $__cache;
                }
            }elseif($caching == CacheToVar){
    			ob_start();
    			ob_implicit_flush(false);
    			
        		eval('?>'.$this->_ressource->get().'<? ');

    			$__cache = ob_get_clean();
    			$this->cachedView = new VCachedViewRessource(array(
    		        'name'   => $this->_templateFilename,
                    'source' => $__cache
                )); 
                if($cachingtime == true){
                    echo $__cache;
                }
                return $this->cachedView;
            }else{

        		eval('?>'.$this->_ressource->get().'<? ');

            }            
        }

    }
?>