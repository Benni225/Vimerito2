<?
    /**
    *   @author     cameleon Internet Media
    *   @copyright  2011 cameleon Internet Media
    *   @file   VActivRecorder.class.php
    *   @date   22.04.2011
    *   @version    0.2 
    *   @version    0.1
    *   VActicRecorder-class added
    */

    /** VActicRecorder
    *   This class stores data from a databasesource and get access to results of
    *   queries.
    *
    */
    class VActivRecorder extends VDatabase{
        public $_classname;
        private $_cells = array();
        private $_cellsType = array();
        private $_cellsPrimaryKey = Null;      
        public $resultCount;  
    
        /** Is the constructor. It analyses the databasetable and stores the result.
        *   @param  $analyse    BOOL    On true the databasetable automaticly analysed
        */ 
        public function __construct($analyse = true){
            parent::__construct(); 
            $this->_classname = str_replace("Model", "", get_class($this)); 
            if($analyse == true){ //automatically analysing database 
                $this->analyseDatabase();
            }
        }  
        /** The destructor. Calls the parent-destructor.
        */ 
        public function __destruct(){
            parent::__destruct();
        }
        
        public function __set($name, $value){
            $this->_cells[$name] = $value;    
        }
        
        public function __get($name){
            if(array_key_exists($name, $this->_cells)){
                return $this->_cells[$name];
            }else{
                return Null;
            }    
        }
        /** Set the current result to the first result of the recordset.
        *   @return BOOL If the method fails it return false. Otherwise true.
        */ 
        public function first(){
            if(count($this->__recordset) > 0){
                reset($this->__recordset);
                foreach(current($this->__recordset) as $key=>$val){
                    $this->_cells[$key] = $val;
                }
                return true;
            }else{
                return false;
            }
        }
        /** Set the current result to the next result of the recordset.
        *   @return BOOL If the method fails it return false. Otherwise true.
        */        
        public function next(){
            if(count($this->__recordset) > 0){
                if(next($this->__recordset) === false){
                    return false;
                }else{
                    foreach(current($this->__recordset) as $key=>$val){
                        $this->_cells[$key] = $val;
                    }
                }
                return true;
            }else{
                return false;
            }              
        }
        /** Set the current result to the result before of the recordset.
        *   @return BOOL If the method fails it return false. Otherwise true.
        */        
        public function prev(){
            if(count($this->__recordset) > 0){
                if(prev($this->__recordset) === false){
                    return false;
                }else{
                    foreach(current($this->__recordset) as $key=>$val){
                        $this->_cells[$key] = $val;
                    }
                }
                return true;
            }else{
                return false;
            }                
        }  
        /**
        *   Set the current result to the last result of the recordset.
        *   @return BOOL If the method fails it return false. Otherwise true.
        */        
        public function last(){
            if(count($this->__recordset) > 0){
                if(end($this->__recordset) == false){
                    return false;
                }else{
                    foreach(current($this->__recordset) as $key=>$val){
                        $this->_cells[$key] = $val;
                    }
                }
                return true;
            }else{
                return false;
            }
        } 
        /**
        *   Checks if the current result is the last result of the recordset.
        *   @return bool If the current result is the last result it return true. Otherwise false.
        */        
        public function isLast(){
            if(count($this->__recordset) > 0){
                if(next($this->__recordset) == false){
                    return true;
                }else{
                    prev($this->__recordset);
                    return false;
                }
            }else{
                return true;
            }
        }    
        /** Finds a dataset in the databasetable by the primary key
        *   @param  $value  The value of the primary key 
        *   @param  $column (optional) The columns that will be stored in the results.
        *   @return Mysql-Result 
        */ 
        public function findByPK($value, $column = array()){
            $sql = VQuerybuilder::getQuery(
                array(
                    'SELECT'    => $column,
                    'FROM'      => $this->_classname,
                    'WHERE'     => array($this->_cellsPrimaryKey, $value, '=')  
                )
            );
            $_return = $this->sendQuery($sql);
            $this->first();
            return $_return;
        } 
        /** Finds a dataset in the databasetable with a specified WHERE-condition
        *   @param  $condition  @li String - a valid mysql where condition is required, without the where-statement. E.g. "`column1` = 'value1' and `coulmn2` > 'value2'".
        *                       @li Array  - an array that represents the statement. For example:
        *                           array(
        *                               array('column1', 'value1', '=', 'and'),
        *                               array('column2', 'value2', '>')
        *                           );
        *   @param  $column (optional) The columns that will be stored in the results.
        *   @return Mysql-Result
        */        
        public function findWhere($condition, $column = array()){
            $sql = VQuerybuilder::getQuery(
                array(
                    'SELECT'    => $column,
                    'FROM'      => $this->_classname,
                    'WHERE'     => $condition
                )
            );
            
            $_return = $this->sendQuery($sql);  
            $this->first();
            return $_return;          
        }
        /** Finds all datasets of a table.
        *   @version 0.3
        *   @param  $column (optional) The columns that will be stored in the results.
        *   @return Mysql-Result
        */        
        public function findAll($column = array()){
            $sql = VQuerybuilder::getQuery(
                array(
                    'SELECT'    => $column,
                    'FROM'      => $this->_classname,
                )
            );
            $_return = $this->sendQuery($sql);
            $this->first();
            return $_return;
        }
        /** Finds the last datasets of a table. Also the datasets can be ordered.
        *   @param  $column (optional) The columns that will be stored in the results.
        *   @param  $limit  (optional) Is an array. If $limit only includes one number, then the number represents the count of datasets that returned. If two numbers given the first number represents the datasetnumber and the second the datasetcount. On default only one dataset returned.
        *   @param  $order  (optional) Is an array. The array-key represents the column for sorting and the value the sortingdirection (asc, desc). If no value given the sortingdirection is 'desc'.
        *   @param  $where  @li String - a valid mysql where condition is required, without the where-statement. E.g. "`column1` = 'value1' and `coulmn2` > 'value2'".
        *                       @li Array  - an array that represents the statement. For example:
        *                           array(
        *                               array('column1', 'value1', '=', 'and'),
        *                               array('column2', 'value2', '>')
        *                           );
        *   
        *   @return Mysql-Result
        */        
        public function findLast($column=array(), $limit=array(1), $order=Null, $where=Null){
            $sql = VQuerybuilder::getQuery(
                array(
                    'SELECT'    => $column,
                    'FROM'      => $this->_classname,
                    'WHERE'     => $where,
                    'ORDER'     => $order,
                    'LIMIT'     => $limit
                )
            );
            $_return = $this->sendQuery($sql);
            $this->first();
            return $_return;            
        }
        /** Updates datasets filtered by a where-condition.
        *   @param  $values Is an array. The array-key represents the column and the value represents the value.
        *   @param  $condition  (optional) 
        *                       @li String - a valid mysql where condition is required, without the where-statement. E.g. "`column1` = 'value1' and `coulmn2` > 'value2'".
        *                       @li Array  - an array that represents the statement. For example:
        *                           array(
        *                               array('column1', 'value1', '=', 'and'),
        *                               array('column2', 'value2', '>')
        *                           );
        *   @return Mysql-Result
        */        
        public function updateTable($values, $condition=array()){
            $sql = VQuerybuilder::getQuery(
                array(
                    'UPDATE'    => $this->_classname,
                    'SET'       => $values,
                    'WHERE'     => $condition
                )
            );
            return $this->sendQuery($sql);
        }
        /** Updates datasets filtered by the primary key.
        *   @param  $PKvalue    The value of the primary key. 
        *   @param  $values Is an array. The array-key represents the column and the value represents the value.
        *   @return Mysql-Result
        */        
        public function updateTableByPK($PKvalue, $values){
            $sql = VQuerybuilder::getQuery(
                array(
                    'UPDATE'    => $this->_classname,
                    'SET'       => $values,
                    'WHERE'     => array(array($this->_cellsPrimaryKey, $value, '='))
                )
            );
            return $this->sendQuery($sql);
        } 
        /** Updates datasets filtered by a condition with data, that stored in the model.
        *   @param  $condition  (optional)
        *                       @li String - a valid mysql where condition is required, without the where-statement. E.g. "`column1` = 'value1' and `coulmn2` > 'value2'".
        *                       @li Array  - an array that represents the statement. For example:
        *                           array(
        *                               array('column1', 'value1', '=', 'and'),
        *                               array('column2', 'value2', '>')
        *                           );
        *   @return Mysql-Result
        */        
        public function updateThis($condition = Null){
            if($condition == PK or $condition == Null){
                $c = array($this->_cellsPrimaryKey, $this->_cells[$this->_cellsPrimaryKey], '=');
            }else{
                $c = $condition;
            }
            $sql = VQuerybuilder::getQuery(
                array(
                    'UPDATE'    => $this->_classname,
                    'SET'       => $this->_cells,
                    'WHERE'     => $c
                )
            );
            return $this->sendQuery($sql);            
        }
        /** Insert a dataset with data, that stored in the model.
        *   @return Mysql-Result
        */        
        public function insertThis(){
            $sql = VQuerybuilder::getQuery(
                array(
                    'INSERT'    => $this->_classname,
                    'SET'       => $this->_cells
                )
            );
            return $this->sendQuery($sql);            
        }
        /** Insert a dataset with data, that stored in the formmodel.
        *   @param  $Form   Is an instance of a formmodel.
        *   @param  $exception (optional)   Is an array with fieldnames of the formmodel that skiped in the insertstatement.
        *   @return Mysql-Result. If $Form is no object this method returns FALSE
        *   @version 0.3
        */        
        public function insertForm(&$Form, $exception = array()){
            $_setArray = "";
            $_valuesArray = "";
            if(is_object($Form)){
                foreach($Form->Fields as $key=>$value){
                    if(!in_array($key, $exception) and key_exists($key, $this->_cells)){  
                        $_setArray.="`".$key."`,";
                    }
                }
                $_setArray = substr($_setArray, 0, -1);
                foreach($Form->Fields as $field=>$value){
                   if(!in_array($field, $exception) and key_exists($field, $this->_cells)){
                        $_valuesArray .= "'".$Form->_vars[$field]."',";
                    }
                } 
                $_valuesArray = substr($_valuesArray, 0, -1);
                $sql = VQuerybuilder::getQuery(array(
                            'INSERT'    =>  $this->_classname,
                            'COLS'       =>  $_setArray,
                            'VALUES'    =>  $_valuesArray
                            )
                        );
                return $this->sendQuery($sql);          
            }else{
                return false;
            }
        }
        
        public function updateForm(&$Form, $where, $exception = array()){
            $_setArray = array();
            $_valuesArray = "";
            if(is_object($Form)){
                foreach($Form->Fields as $key=>$value){
                    if(!in_array($key, $exception) and key_exists($key, $this->_cells)){
                        //$_setArray.="`".$key."`,";
                        $_setArray = array_merge($_setArray, array($key=>$Form->_vars[$key]));
                        //echo $key."=".$Form->_vars[$key]."<br />";
                    }
                }
                /*$_setArray = substr($_setArray, 0, -1);
                foreach($Form->Fields as $field=>$value){
                   if(!in_array($field, $exception) and key_exists($field, $this->_cells)){
                        $_valuesArray .= "'".$Form->_vars[$field]."',";
                    }
                }
                $_valuesArray = substr($_valuesArray, 0, -1); */
                //var_dump($_setArray);
                $sql = VQuerybuilder::getQuery(array(
                            'UPDATE'    =>  $this->_classname,
                            'SET'       =>  $_setArray,
                            'WHERE'     =>  $where
                            )
                        );
                //echo $sql;
                return $this->sendQuery($sql);
            }else{
                return false;
            }
        }
        /** Analyses the tablestructur, copying the structur into the model and chaches the result into a file.
        *   @param  $cacheThisTable  On TRUE the structure will cached into a file, otherwise nothing cached.
        */        
        public function analyseDatabase($cacheThisTable = true){
            
            if($cacheThisTable == true){  //caching of the databasestructure true
                if(file_exists(Vimerito::getApplicationPath()."/models/".$this->_classname.".cache.php")){
                    require Vimerito::getApplicationPath()."/models/".$this->_classname.".cache.php";
                    $this->_cells = $__cached_cells;
                    $this->_cellsType = $__cached_cell_types;
                    $this->_cellsPrimaryKey = $__cached_cells_primary_key;
                }
            }
            if(empty($this->_cells) or empty($this->_cellsType)){
                $result = $this->sendQuery("DESCRIBE ".$this->_classname, false);
                while($cell = mysql_fetch_object($result)){
                    $this->_cells[$cell->Field] = $cell->Default;
                    $this->_cellsType[$cell->Field] = $cell->Type;
                    if($cell->Key == "PRI"){
                        $this->_cellPrimaryKey = $cell->Field;
                    }
                }
                if($cacheThisTable == true){  //caching of the databasestructure true
                    $handle = fopen(Vimerito::getApplicationPath()."/models/".$this->_classname.".cache.php", "w+");
                    $str_to_insert = "<?\n\r";
                    $str_to_insert .="\$__cached_created = ".time()."; \n\r";
                    $str_to_insert .="\$__cached_cells = array(";
                    foreach($this->_cells as $key=>$value){
                        $str_to_insert.= "'".$key."' => ";
                        if(is_string($value)){
                            $str_to_insert.= "'".$value."'";  
                        }elseif($value == ''){
                            $str_to_insert.= "Null";     
                        }else{
                            $str_to_insert.= $value;    
                        }  
                        $str_to_insert.= ",";  
                    }
                    $str_to_insert = substr($str_to_insert, 0, -1);
                    $str_to_insert.= ");\n\n\r";
                    $str_to_insert.= "\$__cached_cell_types = array(";
                    foreach($this->_cellsType as $key=>$value){
                        $str_to_insert.= "'".$key."' => '".$value."',";
                    }
                    $str_to_insert = substr($str_to_insert, 0, -1);
                    $str_to_insert.= ");\n\n\r";
                    if($this->_cellPrimaryKey != Null){
                        $str_to_insert.= "\$__cached_cells_primary_key='".$this->_cellPrimaryKey."';\n\r";    
                    }
                    $str_to_insert.= "?>";
                    fwrite($handle, $str_to_insert);
                    fclose($handle);
                }
            }
        }
    }
?>