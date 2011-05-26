<? 
    class VJavaScriptBase extends VLayout{
        
        public static function insertJavaScript(){
            foreach(Vimerito::$javaScriptLibraries as $name=>$source){
                $_return .= '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', __SystemPath).'js/'.$source.'" type="text/javascript"></script>';
            }

            if(!empty(self::$__userJavaScriptLibraries)){
                foreach(self::$__userJavaScriptLibraries as $name){
                    $_return .= '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', __ApplicationPath).'js/'.$name.'" type="text/javascript"></script>';
                }
            }           
            $_return .= '
            <script type="text/javascript">
                var __pageUrl = "'.Vimerito::$configuration['pageUrl'].'"; 
            </script>';
            
            return $_return;
        }
        
        public static function insertJSLib($name){
            if(array_key_exists($name, Vimerito::$javaScriptLibraries)){
                $_return = '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', __SystemPath).'js/'.Vimerito::$javaScriptLibraries[$name].'" type="text/javascript"></script>'.chr(10);
            }elseif(file_exists(__SystemPath."js/".$name)){
                $_return = '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', __SystemPath).'js/'.$name.'" type="text/javascript"></script>'.chr(10);                
            }elseif(file_exists(Vimerito::getApplicationPath()."js/".$name)){
                $_return = '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', Vimerito::getApplicationPath()).'js/'.$name.'" type="text/javascript"></script>'.chr(10);    
            }    
        }
        
        public static function insertPreDefinedScript($name){
            
        }
    }
?>