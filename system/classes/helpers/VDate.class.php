<? 
    /** VDate.class.php
    * Offers date- and time-functions, who handles dates and times 
    * before the 1.1.1970.
    * 
    * This class uses the adodb-time-library:
    * ADOdb Date Library, part of the ADOdb abstraction library
    * Download: http://phplens.com/phpeverywhere/
    * 
    * @date 1.11.2011
    * @version 0.1
    * @copyright cameleon internet media 2011
    * 
    */ 
    require_once(__SystemPath."/helpers/adodb_time/adodb-time.inc.php");
    class VDate{
        public $timestamp;
        public $seconds;
        public $minutes;
        public $hours;
        public $mday;
        public $wday;
        public $mon;
        public $year;
        public $yday;
        public $weekday;
        public $month;
        private $__dateArray;
        
        public function __construct($timestamp = false){
            if($timestamp == false){
                $this->timestamp = time();
            }else{
                $this->timestamp = $timestamp;
            }            
        }
        
        public function getDate(){
            $this->__dateArray = adodb_getdate($this->timestamp);
            $this->seconds  = $this->__dateArray['seconds'];   
            $this->minutes  = $this->__dateArray['minutes'];  
            $this->hours    = $this->__dateArray['hours'];  
            $this->mday     = $this->__dateArray['mday'];  
            $this->wday     = $this->__dateArray['wday'];  
            $this->mon      = $this->__dateArray['mon'];  
            $this->year     = $this->__dateArray['year'];  
            $this->yday     = $this->__dateArray['yday'];  
            $this->weekday  = $this->__dateArray['weekday'];
            $this->month    = $this->__dateArray['month'];    
        }
        
        public function date($format){
            return adodb_date($format, $this->timestamp);
        }
        
        public function dateIso($format, $isoDateString){
            return adodb_date2($format, $isoDateString);
        }
    }
?>