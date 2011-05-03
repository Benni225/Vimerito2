<? 
    class VFailure{
        public static function getFailureText($code){
            if(file_exists(__SystemPath."configuration/failureCodesConfiguration.php")){
                require(__SystemPath."configuration/failureCodesConfiguration.php");
                if(array_key_exists($code, $__failureCodeTable)){
                    return "Failure ".$code.": ".$__failureCodeTable[$code];
                }else{
                    return "The failurecode ".$code." is an undefined one.";
                }
            }else{
                return "The sytem is unable to load the failurecodetable in &quot;".__SystemPath."configuration/failureCodesConfiguration.php&quot;";
            }
        }
    }
?>