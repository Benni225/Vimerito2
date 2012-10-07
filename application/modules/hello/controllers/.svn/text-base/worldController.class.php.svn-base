<? 
    class world extends VController{
        public function __construct(){
            parent::__construct();
        }
        
        public function worldInit(){
            $blocks = array(
                'inhalt'   =>  '#content'
            );
            VLayout::load("blue/index.html", $blocks);
            $view = new VView('world/init.php');
            $view->sendToLayout('inhalt');
            VLayout::renderLayout();
        }
    }
?>