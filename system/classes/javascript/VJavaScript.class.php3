<? 
    class VJavaScript extends VHtmlElement{
        public $code;
    
        public function __construct($script = ""){
            parent::__construct();
            $this->tag = "script";
            $this->parent = "head";
            
            $__scriptA = explode(":", $script);
            if(strtolower($__scriptA[0]) == "file"){
            	$this->loadFromFile($__scriptA[1]);
            }elseif(strtolower($__scriptA[0]) == "script"){
            	$this->setCode($__scriptA[1]);
            }
        }

        public function setCode($__code){
            $this->innerText = $__code;
        } 
        
        public function loadFromFile($__file){
        	if(VRequest::isModulRequest() != TRUE){
            	$this->innerText = file_get_contents(Vimerito::getApplicationPath()."js/".$__file);
        	}else{
        		$this->innerText = file_get_contents(Vimerito::getModulPath()."js/".$__file);
        	}
        }
        
        public static function returnJson($__a){ 
            echo htmlspecialchars(json_encode($__a), ENT_NOQUOTES);
        }
        
        public function insertHere(){
            echo "
                <script>
                ".$this->innerText."
                </script>
            ";
        }
        
        public function sendToLayout(){
        	$this->insert(Layout);
        }
    }
?>