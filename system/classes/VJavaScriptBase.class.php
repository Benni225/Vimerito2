<? 
    class VJavaScriptBase extends VLayout{
        
        public static function insertJavaScript(){
            foreach(Vimerito::$javaScriptLibraries as $name=>$source){
                $_return .= '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', __SystemPath).'js/'.$source.'" type="text/javascript"></script>';
            }

            if(self::$__userJavaScriptLibraries != Null){
                foreach(self::$__userJavaScriptLibraries as $name){
                    $_return .= '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', __ApplicationPath).'js/'.Vimerito::$userJavaScriptLibraries[$name].'" type="text/javascript"></script>';
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
                $_return = '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', __SystemPath).'js/'.Vimerito::$javaScriptLibraries[$name].'" type="text/javascript"></script>';
            }elseif(file_exists(__SystemPath."js/".$name)){
                $_return = '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', __SystemPath).'js/'.$name.'" type="text/javascript"></script>';                
            }elseif(file_exists(__ApplicationPath."js/".$name)){
                $_return = '<script src="'.Vimerito::$configuration['pageUrl'].'/'.str_replace(__Basedir, '', __ApplicationPath).'js/'.$name.'" type="text/javascript"></script>';    
            }    
        }
    }
?>