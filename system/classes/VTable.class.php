<? 
    class VTable extends VActivRecorder{
        public $tableCondition = array();
        public $tablename;
        public $colFilter = array();
        public $labels = array();
        public $tableParts = array();
        public $numRows;
        public $numSite;
        /*
        *  $labels = array(
        *       'label'     =>  'databasefield'
        *  ); 
        */ 
        public function __construct(){
            parent::__construct(false);
            
            if(VRouter::getParam('step')){
                $this->numRows = VRouter::getParam('step');    
            }elseif(!isset($this->numRows)){
                $this->numRows = 10;    
            }
            
            if(VRouter::getParam('site')){
                $this->numSite = VRouter::getParam('site');
            }elseif($this->numSite == 0){
                $this->numSite = 1;
            }            
            
            $this->tableParts = array('thead', 'tbody', 'pager');
        }
        public function renderTable(){
            $this->_classname = $this->tablename;
            $this->analyseDatabase(false);
            $this->findWhere($this->tableCondition, $this->colFilter); 
            echo '<table>';
            if(empty($this->tableParts) or in_array('thead', $this->tableParts)){
                echo '<thead>';
                foreach($this->labels as $label=>$field){
                    echo '<th>'.$label.'</th>';
                }
                echo '</thead>';
            }
            
            if(empty($this->tableParts) or in_array('tbody', $this->tableParts)){
                echo '<tbody>';
                if($this->resultCount > (($this->numRows*$this->numSite)-$this->numRows) or !in_array('pager', $this->tableParts)){
                    if(in_array('pager', $this->tableParts)){
                        for($i = 1; $i < (($this->numRows*$this->numSite)-$this->numRows); $i++){
                            $this->next();
                        }
                        $rowCounter = 0;
                        do{
                            $rowCounter++;
                            echo '<tr>';
                            foreach($this->labels as $label=>$field){
                                echo '<td>'.$this->$field.'</td>';
                            }
                            echo '</tr>';
                            if($this->isLast() == true){
                                break;
                            }
                            $this->next();
                        }while($rowCounter != $this->numRows);
                    }else{
                        do{
                            $rowCounter++;
                            echo '<tr>';
                            foreach($this->labels as $label=>$field){
                                echo '<td>'.$this->$field.'</td>';
                            }
                            echo '</tr>';
                            if($this->isLast() == true){
                                break;
                            }
                            $this->next();                            
                        }while($rowCounter == $this->numRows);
                    }
                    echo '</tbody>';
                }    
            }    
            
            if(empty($this->tableParts) or in_array('tfoot', $this->tableParts)){
                echo '<tfoot>';
                foreach($this->labels as $label=>$field){
                    echo '<th>'.$label.'</th>';
                }
                echo '</tfoot>';
            }
            if(in_array('pager', $this->tableParts)){
                $lastpage = floor($this->resultCount/$this->numRows);
                if($this->resultCount%$this->numRows > 0){
                    $lastpage++;    
                }
                
                $startpage = $this->numSite - 5;
                $endpage = $this->numSite + 5;
                
                echo '<tr>';
                echo '<td colspan="'.count($this->labels).'"><center>';
                for($i = $startpage; $i < $this->numSite; $i++){
                    if($i > 0){
                        echo '<a href="'.Vimerito::createUrl(ActualPage, array('site'=>$i, 'step'=>$this->numRows)).'" target="_self">'.$i.'</a> ';
                    }
                }
                
                echo "<b>".$this->numSite."</b> ";
                
                for($i = $this->numSite+1; $i <= $endpage; $i++){
                    if($i <= $lastpage){
                        echo '<a href="'.Vimerito::createUrl(ActualPage, array('site'=>$i, 'step'=>$this->numRows)).'" target="_self">'.$i.'</a> ';
                    }
                }                
                echo '</center></td>';    
                echo '</tr>';
            }
            echo '</table>';
        }
        
    }
?>