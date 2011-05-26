<?
    /**
    *   @author     cameleon Internet Media
    *   @copyright  2011 cameleon Internet Media
    *   @file VDatabase.class.php
    *   @date   22.04.2011
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
        private $__DatabaseConfiguration = Array();
        private $__databaseID = Null;
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
            if($this->__databaseID){
                mysql_close($this->__databaseID);
            }   
        }
        /** Create a connetion to the database, after loading the configurationfile.
        */ 
        public function connect(){
            require Vimerito::getApplicationPath()."configuration/databaseConfiguration.php";
            $this->__DatabaseConfiguration = $DatabaseConfiguration;
            if($this->__DatabaseConfiguration['port']){
                $server = $this->__DatabaseConfiguration['server'].":".$this->__DatabaseConfiguration['port'];
            }else{
                $server = $this->__DatabaseConfiguration['server'];
            }
            $this->__databaseID = mysql_connect($server, $this->__DatabaseConfiguration['username'], $this->__DatabaseConfiguration['user_password']);
            mysql_select_db($this->__DatabaseConfiguration['database_name'], $this->__databaseID);            
        } 
        /** Executes a databasequery and store the result in a dataset.
        *   @param  $query   Is the SQL-query.
        *   @param  $addToRecordSet On TRUE the result stores to a set of data. FALSE for not.
        *   @return MySQL-Result 
        *   @version 0.3 Insert- and updatebug removed      
        */ 
        public function sendQuery($query, $addToRecordSet = 1){
            if(!$this->__databaseID){
                
                self::connect();
            }
            $query = utf8_decode($query);
            $__query = explode(" ", $query);
            $typeTest = strtolower($__query[0]);
            if($typeTest == "insert" OR $typeTest == "update" or $typeTest == "delete"){
                $addToRecordSet = 0;    
            }
            VDatabase::incrementDatabaseRequestsCount();
            if($addToRecordSet == 1){
                $this->__lastResult = mysql_query($query, $this->__databaseID);
                if(mysql_error($this->__databaseID) == ''){
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
                }else{
                    echo "There was a mysqlerror: <br />".mysql_error($this->__databaseID);
                }
                return $this->__lastResult;
            }else{
                $this->__lastResult = mysql_query($query, $this->__databaseID);
                return $this->__lastResult;                
            }
        }
        /** Returns the result as MySql-Ressource of the last query.
        *   @return MySQL-Result
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
        */
        public static function getDatabaseRequestsCount(){
            return self::$__databaseRequestsCount;
        }
    }
?>