<? 
    class VViewRessource extends VRessource{
        private $__filePath;
        public function __construct($source = ""){
            if($source != "")
            $this->setSource($source);
        }
        
        public function setSource($source){
            $this->__filePath = $source;
            $this->_source = file_get_contents($source);        
        }
        
        public function setHtmlSource($newSource){
            $this->_source = $newSource;
        }
        
        public function getSource(){
            return $this->__filePath;
        }
        
        public function get(){
            return $this->_source;
        }
    }
?>