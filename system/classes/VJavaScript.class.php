<? 
    class VJavaScript{
        public static function useJavaScriptLinks($HTML, $selector){
            $Dom = new SimpleHtmlDom;
            $url = Vimerito::$configuration['pageUrl'];
            $Dom->load($HTML);
            $elements = $Dom->find('body');
            $elements[0]->innertext.="
                <script>
                    $(document).ready(function(){
                        var thref = new String;
                        var ar = new Array;
                        $('$selector').each(function(){
                            ar = new Array;
                            thref = $(this).attr('href');
                            ar = thref.split('$url/'); 
                            if(ar.count == 0){
                                ar = thref.split('$url');    
                            }
                            if(ar[1] == undefined){
                                ar[1] = '';
                            }
                            $(this).attr('href', '#'+ar[1]);
                            thref = new String;
                        });     
                    });                
                </script>
            ";
            $_return = $Dom->save();
            return  $_return;
        }

        public static function animateOut($HTML){
            $Dom = new SimpleHtmlDom;
            $Dom->load($HTML);
            $elements = $Dom->find('body');

        }
    }
?>