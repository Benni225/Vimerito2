<?
    class registrationForm extends VFormModel{
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
                'pw2'  =>  array(
                    'type'      =>  'password',
                    'size'      =>  '35',
                    'label'   =>  'Passwort wiederholen'
                ),
                'submited'      =>  array(
                    'type'      =>  'submit',
                    'size'      =>  '35',
                    'value'   =>  'Verschicken'
                )
            );


            $this->Criteria = array(
                'email'     =>  'required',
                'email'     =>  array('is', 'email'),
                'pw1'       =>  array('=', 'pw2')
            );
            
            $this->Form = array(
                'type'      => 'data',
                'action'    =>  array('controller'=>'register', 'method'=>'registerProof'),
                'method'    =>  'POST'
            );
            parent::__construct();
        }
    }
?>