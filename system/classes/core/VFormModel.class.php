<?
    /**
    *   @author     cameleon Internet Media
    *   @copyright  2011 cameleon Internet Media
    *   @file VFormModel.class.php
    *   @date   22.04.2011
    *   @version    0.2
    *   @version    0.1 The VDatabase-class added
    */
    
    /** The FormModel-Class helps by the creation and the handling of forms. 
    */ 
    class VFormModel extends VValidation{
        public $_vars = array();
        private $__classname = Null;
        /**
        *   @example
        *   $Fields = array(
        *       'Field1' =>  array(
        *           'label'     =>  'Name',
        *           'value'     =>  'Peter',
        *           'type'      =>  'text',
        *           'rows'      =>  5,
        *           'cols'      =>  35,
        *           'size'      =>  '35'
        *       ),
        *       'Field2' =>  array(
        *           'label'     =>  'Password',
        *           'value'     =>  '123456',
        *           'type'      =>  'password',
        *           'size'      =>  '35'
        *       ),
        *       'Field3' =>  array(
        *           'label'     =>  'List',
        *           'value'     =>  array(
        *               'value1'    =>  'This is value 1',
        *               'value2'    =>  'This is value2'
        *           ),
        *           'selected'  =>  'value1',
        *           'multiple'  =>  'false',
        *           'size'      =>  '1'
        *       )
        *   );
        * 
        * 
        */ 
        public  $Fields = array();
        /**
        *   @example
        *   $Form   =   array(
        *       'type'      =>  'data' / 'files',
        *       'header'    =>  'My Form',
        *       'action'    =>  array(
        *           'controller'    =>  'myController',
        *           'method'        =>  'myMethod'
        *       ),
        *       'action'    =>  http://mysite.com/formreceiver.php,
        *       'method'    =>  'POST' / 'GET'
        *   ) 
        * 
        * 
        * 
        */ 
        public  $Form = array();  
        /**
        *   @example
        *   $Validation = array(
        *       'Field1'    =>  array(
        *           'requierd'      =>  true,
        *           'type'          =>  'textOnly'
        *       ),
        *       'Field2'    =>  array(
        *           'required'      =>  true,
        *           'type'          =>  'text'
        *       )
        *   )
        * 
        * 
        */      
        /** Destructor. Calls the parent-destructor
        * 
        */ 
        public function __destruct(){
            parent::__destruct();
        }
        /** The magicmethod __set
        *   This method saves all attributes to the array $__vars.
        *   @param  $name   Is the name of the attribute.
        *   @param  $value  Is the value of the attribute.  
        */ 
        public function __set($name, $value){
            $this->_vars[$name] = $value;
        }
        /** The magicmethod __get. Returns the value of a attribute stored 
        *   in the array $__vars.
        *   @param  $name   Name of the attribute.
        *   @version 0.2
        *   @return mixed
        */
        public function __get($name){
            if(array_key_exists($name, $this->_vars)){
                return $this->_vars[$name];
            }else{
                return Null;
            }
        }
        
        public function configFormField($__fields){
            $this->Fields = $__fields;
        }
        
        public function configForm($__form){
            $this->Form = $__form;    
        }
        
        public function renderLabel($name){
            if(!empty($this->Fields[$name]) && isset($this->Fields[$name]['label'])){
                echo "<label for='".$name."'>".$this->Fields[$name]['label']."</label>";    
            }elseif(!empty($this->Fields[$name])){
                echo "<label for='".$name."'>".$name."</label>";    
            }            
        }
        
        public function renderField($name, $value = Null){
            if(!empty($this->Fields[$name])){

                //echo "Name: ".$name."<br/>";
                if($this->Fields[$name]['type'] == 'text'){
                    echo "<textarea  
                            name='".$name."'  
                            id='".$name."'  
                            cols='".$this->Fields[$name]['cols']."' 
                            rows='".$this->Fields[$name]['rows']."' >";
                                if($value != Null)
                                    echo $value;
                                elseif($this->Fields[$name]['value'] != ""){
                                    echo $this->Fields[$name]['value'];    
                                }
                            echo "</textarea>";  
                }elseif($this->Fields[$name]['type'] == 'image'){
                    echo "<input 
                            name='".$name."' 
                            id='".$name."' 
                            width='".$this->Fields[$name]['width']."'   
                            height='".$this->Fields[$name]['height']."' 
                            src='".$this->Fields[$name]['src']."' />";
                }elseif($this->Fields[$name]['type'] == 'listbox' OR $this->Fields[$name]['type'] == 'select'){
                    echo "<select 
                            name='".$name."' 
                            id='".$name."' 
                            multiple='".$this->Fields[$name]['multiple']."' 
                            size='".$this->Fields[$name]['size']."' >";
                            foreach($this->Fields[$name]['value'] as $value=>$text){
                                echo "<option value='".$value."' ";
                                if($this->Fields[$name]['selected'] == $value){
                                    echo "selected='selected'";
                                }
                                echo ">$text</option>";
                            }
                    echo "</select>";
                }else{
                    echo "<input 
                            name='".$name."' 
                            id='$name' 
                            type='".$this->Fields[$name]['type']."' 
                            size='".$this->Fields[$name]['size']."' 
                            value='";
                                if($value != Null)
                                    echo $value;
                                elseif(isset($this->Fields[$name]['value'])){
                                    echo $this->Fields[$name]['value'];
                                }
                            echo "' ";
                            if($this->Fields[$name]['type'] == 'checkbox'){
                                echo "checked='".$this->Fields[$name]['checked']."' ";
                            }
                    echo "/>";    
                }
            }
        }
        
        public function renderFormOpen($actionUrl = Null, $param = NULL){
            if(!isset($this->Form['type']) OR $this->Form['type'] == 'data' OR $this->Form['type'] == ''){
                if($actionUrl != Null){
                    $action = Vimerito::createUrl($actionUrl, $param);    
                }elseif(is_array($this->Form['action'])){
                    $action = Vimerito::createUrl($this->Form['action']);
                }else{
                    $action = $this->Form['action'];
                }
                echo "<FORM action='".$action."' method='".strtoupper($this->Form['method'])."'>";        
            }    
        }
        
        public function configValidationCriteria($__validation){
            $this->criteria = $__validation;
        }
        
        public function loadPostValues(){
            if(!empty($this->Fields)){
                foreach($this->Fields as $key=>$value){
                    if(strtoupper($this->Form['method']) == 'POST'){
                        if(isset($_POST[$key])){ 
                            $this->_vars[$key] = $_POST[$key];
                        }
                    }
                }//elseif(strtoupper($this->Form['method']) == 'GET')
            }
            
        }
        
        public function __construct(){
            parent::__construct();
            $this->__classname = get_class($this);  
            $this->loadPostValues();     
        }
    }
?>