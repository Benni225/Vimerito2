<? 
    class demo extends VController{
        
        public function __construct(){
            parent::__construct();
        }
        
        public function demoInit(){
            $blocks = array(
                'inhalt'   =>  '#content'
            ); 
            VLayout::load("blue/index.html", $blocks);  
            $view = new VView;
            $view->load("startpage.php");  
             
            $view->render(CacheToVar); 
                        
            VLayout::insertIntoBlock('inhalt', $view->cachedView);
            VLayout::renderLayout();
        }
    }
?>