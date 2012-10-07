<? 
    class VViewManipulation{
        public function __construct(){
            
        }
        
        public static function addElement($element, $mode = Append, $cssSelector){
            
        }
        
        public static function insert(&$targetRessource, &$sourceRessource, $mode = Append, $cssSelector = Null){
            if($cssSelector == Null){
                if($mode == Append){
                    $targetRessource->setSource($targetRessource->get()."\n".$sourceRessource->get());
                }elseif($mode == Prepend){
                    $targetRessource->setSource($sourceRessource->get()."\n".$targetRessource->get());
                }elseif($mode == Replace){
                    $targetRessource->setSource($sourceRessource->get());    
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
                $targetRessource->setSource($Dom->save());                
            }            
        }
    }
?>