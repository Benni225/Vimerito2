<? 
    class VJavaScript extends VHtmlElement{
        public $code = String;
    
        public function __construct(){
            parent::__construct();
            $this->tag = "script";
            $this->parent = "body";
        }

        public function setCode($__code){
            $this->innerText = $__code;
        } 
    }
?>