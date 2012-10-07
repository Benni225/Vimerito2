<? 
    /** Is the Vimerito-Exception-class
    *   @author cameleon Internet Media
    *   @version 0.2 VException-class added 
    *   @copyright cameleon Internet Media
    */ 
    class VException extends Exception{
        public static $error = Array();
        
        public function __construct($message){
            parent::__construct($message);    
            self::$error["file"] = $this->file;
            self::$error["line"] = $this->line;
            self::$error["message"] = $this->message;
            self::$error["stackTrace"] = $this->getTraceAsString();
        }    
        
        public function showError(){
            if($this->message == ""){
                $this->message = "Unknown error!";
            }
            echo $this->file." on Line ".$this->line.":<br />".$this->message."<br /> Stack trace:\n".$this->getTraceAsString();
        }
        
        
    }
?>