<? 
    class VHtmlElement{
        public $tag = "";
        public $innerText = "";
        public $class = "";
        public $style = "";
        public $name = "";
        public $id = "";
        public $html = "";
        public $parent = "";
        
        protected $_attributes = array();
        protected $_widgets = array();
        protected $_htmlRessource = array();
        
        protected $__insertMode = Append;
        protected $__source = "";
        
        protected $__insertCounter;
        protected $__hash;
        
        protected $__nonBlockElements = array();
        
        protected $__view = "";
        
        public function __construct($tag = ""){
            $this->tag = $tag;    
            $this->__nonBlockElements = array(
                'image',
                'hr',
                'br',
                'link'
            );
        }
        
        public function setTag($tag){
            $this->tag = $tag;
        }
        
        public function setInnerText($text){
            $this->innerText = (string)$text;    
        }
        
        public function setClass($class){
            $this->class = (string)$class;
        }
        
        public function setStyle($style){
            $this->style = (string)$style;
        }
        
        public function setName($name){
            $this->name = (string)$name;
        }
        
        public function setId($id){
            $this->id = (string)$id;
        }
        
        public function addAttribute($name, $value){
            $this->_attributes[$name] = (string)$value;
        }
        
        public function removeAttribute($name){
            $this->_attributes[$name] = Null;
        }
        
        public function setParent($selector){
            $this->parent = (string)$selector;
        }
        
        public function setInsertMethod($mode){
            $this->__insertMode = $mode;
        }
        
        public function setSource($source){
            $this->__source = $source;
        }
        
        public function setView(&$view){
            try{
                $view->render(CacheToVar);
                $this->innerText = $view->cachedView->get();
                return true;                
            }catch(Exception $e){
                $e->getMessage();
                return false;
            }
        }
        
        public function createHtml(){
            if(strtolower($this->tag) != "script"){
                $this->html = '<'.$this->tag;
                if($this->id)       $this->html .= ' id=&quot;'.$this->id.'&quot;';
                if($this->name)     $this->html .= ' name=&quot;'.$this->name.'&quot;';
                if($this->style)    $this->html .= ' style=&quot;'.$this->style.'&quot;';
                if($this->class)    $this->html .= ' class=&quot;'.$this->class.'&quot;';
                foreach($this->_attributes as $name=>$value){
                    $this->html .= ' '.$name.'="'.$value.'"';
                }
                if(!in_array(strtolower($this->tag), $this->__nonBlockElements)){
                    $this->html .= '>';
                    $this->html .= $this->innerText;
                    $this->html .= '</'.$this->tag.'>';
                }else{
                    $this->html .= ' />';
                }
            }else{
                $this->html = '<'.$this->tag;  
                $this->html .= '>'.chr(10);
                $this->html .= $this->innerText;
                $this->html .= chr(10).'</'.$this->tag.'>'.chr(10);  
            }
            $this->_htmlRessource = new VHtmlRessource($this->html);
        }
        
        public function insert($source = Null, $mode = Append){
            if($this->__insertCounter === Null){
                $this->__insertCounter = 0;
            }else{
                $this->__insertCounter++;   
            }
            $this->createHtml();
            $this->__hash = hash('md5', $this->_htmlRessource->get());
             
            if($source == Null){
                $source = $this->__source;    
            }
            if(!is_object($source) and $source == Layout){
                if($this->parent != ""){
                    VLayout::addBlock(array(
                        $this->__hash.$this->__insertCounter    =>  $this->parent
                    ));
                    VLayout::insertIntoBlock($this->__hash.$this->__insertCounter, $this->_htmlRessource);
                }
            }elseif(is_object($source) and get_class($source) == 'VView'){
                /*if($this->parent == "head"){
                    $this->parent = Null;
                }*/
                if($source->cachedView == Null){
                    VViewManipulation::insert($source->_ressource, $this->_htmlRessource, $mode, $this->parent);
                }else
                    VViewManipulation::insert($source->cachedView, $this->_htmlRessource, $mode, $this->parent);        
            }else{
                throw new Exception('Invalid source given. Layout or a VView-object required!');
            }
        }
        
        public function returnRessource(){
        	return $this->_htmlRessource;
        }
    }
?>