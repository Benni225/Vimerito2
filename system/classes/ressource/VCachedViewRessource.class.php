<? 
    class VCachedViewRessource extends VRessource{
        public $name;
        public function __construct($source = array()){
            $this->setSource($source['source']);
            $this->name = $source['name'];   
        }
        
        public function setSource($source){
            $this->_source = $source;
        }
        
        public function getSource(){
            return $this->name;    
        }
        
        public function get(){
            return $this->_source;
        }
    }
?>