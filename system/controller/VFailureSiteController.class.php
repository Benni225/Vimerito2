<? 
    class VFailureSite extends VController{
        public function __construct(){
            parent::__construct();
        }
        
        public function VFailureControllerInit(){
            echo VFailure::getFailureText(VRouter::getParam('failure'));
        }
    }
?>