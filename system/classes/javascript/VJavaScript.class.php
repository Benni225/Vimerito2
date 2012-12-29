<?
    class VJavaScript extends VHtmlElement{
        public $code;

        private $_templateVars = array();
    	private $cachedScript;
    	private $__name;

    	protected $_ressource;

        public function __construct($script = ""){
            parent::__construct();


            $this->_ressource = new VJavaScriptRessource();
            $this->tag = "script";
            $this->parent = "head";

            $this->__name = Vimerito::createCode(10);

            $__scriptA = explode(":", $script);
            if(strtolower($__scriptA[0]) == "file"){
            	$this->loadFromFile($__scriptA[1]);
            }elseif(strtolower($__scriptA[0]) == "script"){
            	$this->setCode($__scriptA[1]);
            }
        }

        public function __get($name){
        	return $this->_templateVars[$name];
        }

        public function assignVar($name, &$value = ""){
            if(is_array($name)){
                $this->_templateVars = array_merge((array)$this->__templateVars, $name);
            }else{
                $this->_templateVars[$name] = $value;
            }
        }

        public function setCode($__code){
            $this->_ressource->setHtmlSource($__code);
        }

        public function loadFromFile($__file){
        	if(VRequest::isModulRequest() != TRUE){
            	$this->_ressource->loadSource(Vimerito::getApplicationPath()."js/".$__file);
        	}else{
        		$this->_ressource->loadSource(Vimerito::getModulPath()."js/".$__file);
        	}
        }

        public static function returnJson($__a){
            echo htmlspecialchars(json_encode($__a), ENT_NOQUOTES);
        }

        public function insertHere(){
        	$this->render();
            echo "
                <script>
                ".$this->cachedScript."
                </script>
            ";
        }

        public function sendToLayout(){
        	$this->render();

            if($this->parent != ""){
            	VLayout::addBlock(array(
                    $this->__name    =>  $this->parent
                ));
            	VLayout::insertIntoBlock($this->__name, $this->cachedScript);
            }
        }

        private function render($caching = false, $cachingtime = 0){
    		ob_start();
    		ob_implicit_flush(false);

        	eval('?>'.$this->_ressource->get().'<? ');

    		$__cache = ob_get_clean();

    		$this->cachedScript = new VCachedJavaScriptRessource(array(
    			'name'   => $this->__name,
                'source' => "<script>".$__cache."</script>"
            ));
            return $this->cachedScript;
        }
    }
?>