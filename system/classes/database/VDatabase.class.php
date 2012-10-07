<?
    /**
    *   @author     cameleon Internet Media
    *   @copyright  2011 cameleon Internet Media
    *   @file VDatabase.class.php
    *   @date   22.04.2011
    *   @version    0.6 
    *   @version    0.2
    *   @version    0.1 The VDatabase-class added
    */

    /**
    *   VDatabase
    *   This create a connection to a database, configured in databaseConfiguration.php
    *   and executes databasequerys.
    *
    */
    class VDatabase{
        protected $__DatabaseConfiguration = Array();
        private $__databaseID = Null;
        public $error = Null;
        public $isError = Null;
        protected $__lastResult = Null;
        protected $__recordset = array();
        private $__resultCount = 0;
        private static $__databaseRequestsCount = 0;
        
        public function __construct(){
        }
        /** Destructor. Closes the databaseconnection
        * 
        */ 
        public function __destruct(){
            if(is_resource($this->__databaseID)){
                mysql_close($this->__databaseID);
            }   
        }
        /** Create a connetion to the database by the configuration of _databaseConfiguration or after loading the configurationfile.
        *   @version 0.6 The variable $this->_server configured by the model overwrites the values of the configurationfile "configuration/databaseConfiguration.php" temporary. Connections to more than one database is possible now.
        */ 
        public function connect(){
            try{
                if(empty($this->_databaseConfiguration)){
                    require Vimerito::getApplicationPath()."configuration/databaseConfiguration.php";
                    $this->__DatabaseConfiguration = $DatabaseConfiguration;                
                }else{
                    $this->__DatabaseConfiguration = $this->_databaseConfiguration;
                }

                if($this->__DatabaseConfiguration['server'] != '' AND $this->__DatabaseConfiguration['username'] != '' AND $this->__DatabaseConfiguration['database_name'] != ''){
                    if(isset($this->__DatabaseConfiguration['port'])){
                        $server = $this->__DatabaseConfiguration['server'].":".$this->__DatabaseConfiguration['port'];
                    }else{
                        $server = $this->__DatabaseConfiguration['server'];
                    }
                    $this->__databaseID = mysql_connect($server, $this->__DatabaseConfiguration['username'], $this->__DatabaseConfiguration['user_password']);
                    mysql_select_db(mysql_real_escape_string($this->__DatabaseConfiguration['database_name']), $this->__databaseID);                        
                }else{
                    throw new VException("A connection to the database over the model ".get_class($this)." is not possible. An important value is missing. Check the configuration.");   
                }
            }catch(VException $e){
                $e->showError();
            }
        } 
        /** Executes a databasequery and store the result in a dataset.
        *   @param  $query   Is the SQL-query.
        *   @param  $addToRecordSet On TRUE the result stores to a set of data. FALSE for not.
        *   @return MySQL-Result 
        *   @version 0.1 New added.
        *   @version 0.3 Insert- and updatebug removed      
        */ 
        public function sendQuery($query, $addToRecordSet = 1){
            $counter = 0;
            if(!$this->__databaseID){
                
                self::connect();
            }
            $__query = explode(" ", $query);
            $typeTest = strtolower($__query[0]);
            if($typeTest == "insert" OR $typeTest == "update" or $typeTest == "delete"){
                $addToRecordSet = 0;    
            }
            VDatabase::incrementDatabaseRequestsCount();
            if($addToRecordSet == 1){
                $this->__lastResult = mysql_query($query, $this->__databaseID);
                $this->error = mysql_error($this->__databaseID);
                if($this->error == ''){
                    if($this->__lastResult != 0){
                        if(mysql_affected_rows($this->__databaseID) >=0){
                            $this->__resultCount = mysql_affected_rows($this->__databaseID);
                        }elseif(mysql_num_rows($this->__lastResult) >=0){
                            $this->__resultCount = mysql_num_rows($this->__databaseID);
                        }else{
                            $this->__resultCount = -1;
                        }
                        $counter = -1;
                        $this->__recordset = Null;
                        while($r = mysql_fetch_array($this->__lastResult)){
                            $counter++;
                            $this->__recordset[$counter] = $r;
                        }
                        $this->resultCount = $counter+1;
                    }
                    $this->isError = false;
                }else{
                    if(Vimerito::debugMode() == 1){
                        echo "There was an mysqlerror: <br />".mysql_error($this->__databaseID)."<br />";
                        echo "In Query: ".$query;
                    }
                    $this->isError = true;
                }
                return $this->__lastResult;
            }else{
                $this->__lastResult = mysql_query($query, $this->__databaseID);
                return $this->__lastResult;                                   
            }
        }
        /** Returns the result as MySql-Ressource of the last query.
        *   @return MySQL-Result
        *   @version 0.1 New added.
        */        
        public function getLastDatabaseResult(){
            return $this->__lastResult;
        }
        /** Increments the count of all requests during the working routine.
        */  
        public static function incrementDatabaseRequestsCount(){
            self::$__databaseRequestsCount++;
        }
        /** Returns the count of all requests during the working routine.
        *   @return Integer
        *   @version 0.1 New added.
        */
        public static function getDatabaseRequestsCount(){
            return self::$__databaseRequestsCount;
        }
        /** Returns the whole recordset.
        *   @return Array
        *   @version 0.6 New added. 
        */ 
        public function getRecordset(){
            return $this->__recordset;
        }
    }
?>