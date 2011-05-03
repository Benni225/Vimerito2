<? 
    class VRequest{
        private $__classname;
        protected $_methods = array();
        protected $_returnType = string();
        public function __construct(){
            $this->__classname = get_class($this);
            $this->_methods['default'] = $this->__classname."Init";            
        }
        
        //Todo: proof of method exists
        public function run($method){
            if(Vimerito::$configuration['automaticAuthentication'] == true){
                if(VAccessRights::authenticateUser()){
                    if(!isset($method) or $method == '')
                        $method = $this->_methods['default'];
                    else
                        $method.="Action";
                    $this->$method();
                }else{
                    Vimerito::redirect(401, false, VAccessRights::getRedirectController());
                }
            }else{
                if(!isset($method) or $method == '')
                    $method = $this->_methods['default'];
                else
                    $method.="Action";
                $this->$method();
            }
        }
    }
?>