<? 
    class VGetView extends VController{
        public function __construct(){
            parent::__construct();
        }
        
        public function VGetViewInit(){
            $view = VRouter::getParam('view');
            $view = str_replace('_', '/', $view);
            $__view = new VView($view.".php");
            $__view->render();
        }
    }
?>