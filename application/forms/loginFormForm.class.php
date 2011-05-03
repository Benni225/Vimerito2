<?
    class loginForm extends VFormModel{
        public function __construct(){
            $this->Fields = array(
                'email'  =>  array(
                    'type'      =>  'input',
                    'size'      =>  '35',
                    'label'   =>  'Emailadresse'
                ),
                'pw1'  =>  array(
                    'type'      =>  'password',
                    'size'      =>  '35',
                    'label'   =>  'Passwort'
                ),
                'submited'      =>  array(
                    'type'      =>  'submit',
                    'size'      =>  '35',
                    'value'   =>  'Login'
                )
            );
            $this->Form = array(
                'type'      => 'data',
                'action'    =>  array('controller'=>'Login', 'method'=>'entry'),
                'method'    =>  'POST'
            );         
            parent::__construct();
            $this->Criteria = array(
                'email'     =>  'required',
                'pw1'       =>  'required'
            );
        }
        
        public function __destruct(){
            parent::__destruct();
        }
    }
?>