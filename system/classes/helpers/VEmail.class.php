<? 
    class VEmail{
        public static function sendMail($receiverEmail, $senderEmail, $headline, $text, $sender = ""){
            if($sender == ""){
                $sender = $senderEmail;
            }
            
            if(mail($receiverEmail, $headline, $text, "From: ".$sender."<".$senderEmail.">")){
                return true;
            }else{
                return false;
            }    
        }
        
        public static function sendHtmlEmail($receiverEmail, $senderEmail, $headline, $view, $sender = ""){
            if($sender == ""){
                $sender = $senderEmail;
            }
            $from = "From: ".$sender." <".$senderEmail.">\r\n";
            $from .= "Replay-To: ".$senderEmail."\r\n"; 
            $from .= "Content-Type: text/html\r\n";
            $from .= 'X-Mailer: PHP/' . phpversion();
            
            $view->render(CacheToVar);
            if(mail($receiverEmail, $headline, $view->cachedView, $from)){
                return true;
            }else{
                return false;
            }              
        }
    }
?>