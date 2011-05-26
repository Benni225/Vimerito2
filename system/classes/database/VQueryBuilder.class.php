<?
    class VQuerybuilder{
        private static $__commands = array(
            'select',
            'delete',
            'update',
            'insert',
            'limit',
            'order',
            //'drop',
            //'create',
            //groupBy,
            //having,
            'from',
            'where',
            'values',
            'set',
            'cols');
            
        private static $sqlQuery = "";
        /*****************************************************************
        * ****************************************************************
        * $condition = array('SELECT'   => array(
        *                                       'row1',
        *                                       'row2',
        *                                       'row3'),
        *                    'FROM'     => 'Table',
        *                    'WHERE'    => array(
        *                                       array('row1', 'value1', '='),
        *                                       array('row2', 'value2', '>', 'OR')
        *                                  )
        *                   );
        * 
        * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        * array('DELETE'    =>  'Table')
        * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        * ****************************************************************
        ******************************************************************/
        public static function getQuery(array $condition){
            self::$sqlQuery = "";
            foreach($condition as $command=>$value){
                if(in_array(strtolower($command), self::$__commands)){
                    $function = "self::build".ucfirst(strtolower($command));
                    call_user_func($function, $value);
                }   
            }
            return self::$sqlQuery.';';
        }
        
        private static function buildSelect($condition){
            if(is_array($condition) AND !empty($condition)){
                $sqlReturn = "SELECT ";
                foreach($condition as $c){
                    $sqlReturn.= "`".$c."`, ";
                }   
                $sqlReturn = substr($sqlReturn, 0, -2)." ";     
            }else{
                $sqlReturn = "SELECT * ";
            }
            self::$sqlQuery .= $sqlReturn;
        }
        //@ PARAM String condition
        private static function buildDelete($condition){
            self::$sqlQuery = "DELETE ";
            self::buildFrom($condition);        
        }
        
        //@ PARAM String condition
        private static function buildUpdate($condition){
            self::$sqlQuery = "UPDATE `".$condition."` ";    
        }
        //@ PARAM String condition
        private static function buildInsert($condition){
            self::$sqlQuery = "INSERT INTO `".$condition."` ";
        }
        //@ PARAM String condition
        private static function buildFrom($condition){
            self::$sqlQuery .= "FROM ".$condition." "; 
        }
        
        private static function buildOrder($condition){
            if(is_array($condition) and !empty($condition)){
                self::$sqlQuery .= " ORDER BY ";
                foreach($condition as $row=>$mode){
                    if($mode == "" or $mode == Null){
                        $mode = "DESC";
                    }
                    self::$sqlQuery.= "`".$row."` ".$mode.",";    
                }
                self::$sqlQuery = substr(self::$sqlQuery, 0, -1)." ";
            }else{
                self::$sqlQuery.= $condition." ";    
            }
        }
        
        private static function buildLimit($condition){
            if(is_array($condition) and !empty($condition)){
                self::$sqlQuery .= " Limit ";  
                foreach($condition as $number){                
                    self::$sqlQuery.= $number.",";
                } 
                self::$sqlQuery = substr(self::$sqlQuery, 0, -1)." ";                     
            }       
        }
        
        private static function buildWhere($condition){
            if(is_array($condition) and !empty($condition)){
                self::$sqlQuery.=" WHERE";
                if(is_array($condition[0])){
                    foreach($condition as $value){
                        $counter++;
                        if($value[2] == "" OR $value[2] == Null)
                                $value[2] = "=";
                        if($value[3] == "" OR $value[3] == Null)
                                $value[3] = " AND";
                        $value[3] = strtoupper($value[3]);
                        if($counter > 1)
                            self::$sqlQuery.= $value[3];
                        self::$sqlQuery.= " `".$value[0]."`".$value[2]."'".$value[1]."' ";
                    }
                }else{
                    if($condition[2] == "" OR $condition[2] == Null)
                            $condition[2] = "=";
                    if($condition[3] == "" OR $condition[3] == Null)
                            $condition[3] = " AND";
                    $condition[3] = strtoupper($condition[3]);
                    if($counter > 1)
                        self::$sqlQuery.= $condition[3];
                    self::$sqlQuery.= " ".$condition[0].$condition[2]."'".$condition[1]."' ";                    
                }
                self::$sqlQuery = substr(self::$sqlQuery, 0, -1)." ";
            }elseif(is_string($condition)){
                self::$sqlQuery.=" WHERE ".$condition;    
            }
             
        }
        
        private static function buildValues($condition){
            $noarray = 0;
            self::$sqlQuery.=" VALUES  ";
            if(is_array($condition)){
                self::$sqlQuery.= "(";
                foreach($condition as $value){
                    self::$sqlQuery .= "'".$value."',";
                }
                self::$sqlQuery = substr(self::$sqlQuery, 0, -1).")";
            }else{
                self::$sqlQuery.=" (".$condition.") ";
            }
        }
        
        private static function buildSet($condition){
            $noarray = 0;
            if(is_array($condition)){
                self::$sqlQuery.=" SET  ";
                //if(is_array($condition[0])){
                    foreach($condition as $key=>$value){
                        $counter++;
                        if($value == '' and $counter == 1 or $value == NULL and $counter == 1){
                            $noarray = 1;
                            break;
                        }
                        self::$sqlQuery.= " `".$key."` = '".$value."',";    
                    }
                    //self::$sqlQuery = substr(self::$sqlQuery, 0, -1);
                //}elseif($noarray == 1){
                    if($noarray == 1){
                        self::$sqlQuery .= " (";
                        foreach($condition as $value){
                            self::$sqlQuery.= " `".$value."`,";
                        }                 
                    }
                    self::$sqlQuery = substr(self::$sqlQuery, 0, -1);
                    if($noarray == 1)
                        self::$sqlQuery .= ") ";
            }else{
                self::$sqlQuery.=" SET(".$condition.") ";
            }    
        }
        
        private static function buildCols($condition){
            $noarray = 0;
            if(is_array($condition)){
                self::$sqlQuery.=" ";
                foreach($condition as $value){
                    if(is_array($value)){
                        foreach($value as $key=>$val){
                            self::$sqlQuery.= " `".$key."` = '".$val."',";
                        }
                    }else{
                        $noarray = 1;
                        break;
                    }
                }
                if($noarray == 0){
                    self::$sqlQuery = substr(self::$sqlQuery, 0, -1);
                }elseif($noarray == 1){
                    self::$sqlQuery.= "(";
                    foreach($condition as $value){
                        self::$sqlQuery.= "`".$value."`,";
                    }
                    self::$sqlQuery = substr(self::$sqlQuery, 0, -1).")";
                }
            }else{
                self::$sqlQuery.=" (".$condition.") ";
            }            
        }
        
    }
?>