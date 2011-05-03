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
            $this->loadView("content", "startpage.php");    
            $this->render("content", CacheToVar); 
            
            VLayout::insertIntoBlock('inhalt', "content");
            VLayout::renderLayout();
        }
    }
?>