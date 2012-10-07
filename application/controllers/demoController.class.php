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
            $view = new VView("startpage.php");  
            
            $text = "Hallo ich bin eingef&uuml;gt!";
            $text2 = "Ich auch!";
            
            $view->assignVar("var", $text);
            $view->assignVar("var2", $text2);
             
            $element = new VHtmlElement;
            $element->tag = "div";
            $element->innerText = "Ich bin ein Dynamischer Text!";
            $element->parent = "#dyn";
            $element->insert($view, Append);
            
            $js = new VJavaScript();
            $js->setCode(
                "//JavaScriptBlock"
            );
            
            $view->sendToLayout('inhalt');
            $js->sendToLayout();
            
            VLayout::renderLayout();
        }
    }
?>