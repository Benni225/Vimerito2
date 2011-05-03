<? 
    /** Is the Vimerito-Exception-class
    *   @author cameleon Internet Media
    *   @version 0.2 VException-class added 
    *   @copyright cameleon Internet Media
    */ 
    class VException extends Exception{
        public function __construct($message){
            parent::__construct($message);    
        }    
        
        public function showError(){
            if($this->message == ""){
                $this->message = "Unknown error!";
            }
            echo $this->file." on Line ".$this->line.":<br />".$this->message."<br /> Stack trace:\n".$this->getTraceAsString();
        }
    }
?>