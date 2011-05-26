<? 
    abstract class VRessource{
        protected $_source = "";
        
        public function __construct($source = ""){
            $this->setSource($source);
        }
        
        public function getInstance(){
            return $this;
        }
        
        abstract public function getSource();

        abstract public function setSource($source);
        
        abstract public function get();
    }
?>