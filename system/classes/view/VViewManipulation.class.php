<? 
    class VViewManipulation{
        public function __construct(){
            
        }
        
        public static function addElement($element, $mode = Append, $cssSelector){
            
        }
        
        public static function insert($targetRessource, $sourceRessource, $mode = Append, $cssSelector = ""){
            if($cssSelector == ""){
                if($mode == Append){
                    $targetRessource->setHtmlSource($targetRessource->get().$sourceRessource->get());
                }elseif($mode == Prepend){
                    $targetRessource->setHtmlSource($sourceRessource->get().$targetRessource->get());
                }elseif($mode == Replace){
                    $targetRessource->setHtmlSource($sourceRessource->get());    
                }else{
                    throw new Exception('Invalid mode given!');
                }        
            }else{
                $Dom = new SimpleHtmlDom;
    			$Dom->load($targetRessource->get());
    			$elements = $Dom->find($cssSelector);
                foreach($elements as $e){
    				if($mode == Prepend){
    					$e->innertext = $sourceRessource->get()."\n".$e->innertext;
    				}elseif($mode == Append){
    					$e->innertext .= "\n".$sourceRessource->get();
    				}elseif($mode == Replace){
    					$e->innertext = $sourceRessource->get();
    				}else{
                        throw new Exception('Invalid mode given!');
                    }
    			}
                $targetRessource->setHtmlSource($Dom->save());                
            }            
        }
    }
?>