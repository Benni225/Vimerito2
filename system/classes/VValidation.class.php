<?
    class VValidation{
        public $Criteria = Null;
        private static $__CriteriaArguments = Array();
        
        public function __construct($c = Null){
            if($c != Null)
                $this->Criteria = $c; 
            self::$__CriteriaArguments = Array(
                'text',
                'textonly',
                'number',
                'email'
            );   
        }
        
        public function __destruct(){
            $this->resetValidation();
        }
        
        public function resetValidation(){
            foreach($this->_vars as $field=>$value){
                $_SESSION["__".get_class($this)."_".$field."valide"] = Null;
                unset($_SESSION["__".get_class($this)."_".$field."valide"]);
            }            
        }
        
        public function validate(){
            foreach($this->Criteria as $field=>$c){

                if(array_key_exists($field, $this->_vars)){
                    $value1 = $this->_vars[$field];
                }
                /*if(array_key_exists($c[1], $this->_vars)){
                    $value2 = $this->_vars[$c[1]];
                }*/
                $value2 = $c[1];
                /*if(in_array($c[1], self::$__CriteriaArguments)){
                    $value2 = $c[1];
                }*/
                if(!is_array($c)){
                    switch(strtolower($c)){
                        case 'required':
                            if($value1 == ''){
                                $this->Fields[$field]['valide'] = false;    
                            }else{
                                $this->Fields[$field]['valide'] = true;    
                            }
                            VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                    }
                }
    
                if($this->Fields[$field]['valide'] == Null or $this->Fields[$field]['valide'] == true) {
                    switch ($c[0]){
                        case '=':
                            $this->Fields[$field]['valide'] = self::equal($value1, $value2);
                            VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                        case 'equal':
                            $this->Fields[$field]['valide'] = self::equal($value1, $value2);
                            VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                        case '!=':
                            $this->Fields[$field]['valide'] = self::nequal($value1, $value2);
                            VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                        case 'nequal':
                            $this->Fields[$field]['valide'] = self::nequal($value1, $value2);
                            VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                        case '>':
                            $this->Fields[$field]['valide'] = self::biggerThan($value1, $value2);
                            VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                        case 'bigger':
                            $this->Fields[$field]['valide'] = self::biggerThan($value1, $value2);
                            VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                        case '<':
                            $this->Fields[$field]['valide'] = self::smallerThan($value1, $value2);
                            VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                        case 'smaller':
                            $this->Fields[$field]['valide'] = self::smallerThan($value1, $value2);
                            VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                        case 'is':
                            if(in_array($value2, self::$__CriteriaArguments))
                                $this->Fields[$field]['valide'] = self::$value2($c[0], $value1);
                                VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;
                        case 'isnot':
                            if(in_array($value2, self::$__CriteriaArguments))
                                $this->Fields[$field]['valide'] = self::$value2($c[0], $value1);
                                VSession::set("__".get_class($this)."_".$field."valide", $this->Fields[$field]['valide']);
                            break;

                            
                    }
                }
            }
            foreach($this->Criteria as $field=>$c){
                if($this->Fields[$field]['valide']==false){
                    return false;
                }
            }
            return true;
        }
        
        public static function text($mode, $value){
            if(preg_match("/[^a-zA-Z0-9_-\?\!\"\'\.]{1, }/", $value) == 1){
                if($mode == 'is'){
                    return false;
                }else{
                    return true;
                }
            }else{
                if($mode == 'is'){
                    return true;
                }else{
                    return false;
                }
            }
        }
        
        public static function textonly($mode, $value){
            if(preg_match("/[0-9]{1,}/", $value) == 1){
                if($mode == 'is'){
                    return false;
                }else{
                    return true;
                }
            }else{
                if($mode == 'is'){
                    return true;
                }else{
                    return false;
                }
            }
        }
        
        public static function number($mode, $value){
            if(preg_match("/[A-Za-z_]{1,}/", $value) == 1){
                if($mode == 'is'){
                    return false;
                }else{
                    return true;
                }
            }else{
                if($mode == 'is'){
                    return true;
                }else{
                    return false;
                }
            }
        }
        
        public static function email($mode, $value){
            if(preg_match("/^[a-zA-Z\d][\w\.-]*[a-zA-Z\d]@[a-zA-Z\d][\w\.-]*\.[a-zA-Z]{2,4}$/", $value)){
                if($mode == 'is'){
                    return true;
                }else{
                    return false;
                }
            }else{
                if($mode == 'is'){
                    return false;
                }else{
                    return true;
                }
            }
        }
        
        public static function equal($value1, $value2){
            if($value1 == $value2){
                return true;
            }else{
                return false;
            }
        }
        
        public static function nequal($value1, $value2){
            if($value1 != $value2){
                return true;
            }else{
                return false;
            }
        }
        
        public static function biggerThan($value1, $value2){
            if($value1 > $value2){
                return true;
            }else{
                return false;
            }
        }
        
        public static function smallerThan($value1, $value2){
            if($value1 < $value2){
                return true;
            }else{
                return false;
            }
        }
        
        public function isValide($field){
            if(array_key_exists($field, $this->_vars)){
                $__tValide = $_SESSION["__".get_class($this)."_".$field."valide"];
                if($__tValide != false){
                    return true;
                }else{
                    return false;
                }                
            }else{
                return Null;
            }
        }
    }
?>