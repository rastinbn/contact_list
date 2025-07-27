<?php
class PasswordStrange
{
    public $password;
    function __construct($password)
    {
        $this ->password = $password;
    }
    public function isStrange(){
        $width =0;
       if ( $lenghtOK = strlen($this->password)>=8){
           $width +=25;
       }

       if ( $hasUpper = preg_match('/[A-Z]/' , $this->password)){
           $width +=25;
       }

       if ( $hasLower = preg_match('/[a-z]/' , $this->password)){
           $width +=25;
       }
      if (  $hasNumber = preg_match('/[0-9]/' , $this->password)){
          $width +=25;
      }
        return $width;

    }
    public  function getWeakPoint(){

        $issues = [];
        if (strlen($this->password) < 8){

        }
    }
}