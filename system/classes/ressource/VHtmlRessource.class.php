<? 
    class VHtmlRessource extends VRessource{
        public function __construct($source = ""){
            $this->setSource($source); 
        }
        
        public function setSource($source){
            $this->_source = $source;
        }
        
        public function getSource(){
            return "HtmlElement";    
        }
        
        public function get(){
            return $this->_source;
        }
    }
?>