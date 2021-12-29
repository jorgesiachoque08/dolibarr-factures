<?php
use Phalcon\Validation;
use Phalcon\Di\Injectable;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Date as DateValidator;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\File as FileValidator;
use Phalcon\Validation\Validator\InclusionIn as Enum;
use Phalcon\Validation\Validator\Identical;

class ValidationComponent extends injectable{
    protected $required;
    protected $integers;
    protected $email;
    protected $dates;
    protected $regex;
    protected $length;
    protected $file;
    protected $enum;
    protected $identical;


    public function validate($params)
    {
        $Validation = new Validation();
        $messagesRetorno = [];
        
        
        if(isset($this->required)){
            $this->validateRequired($Validation);
        }

        if(isset($this->integers)){
            $this->validateIntegers($Validation);
        }

        if(isset($this->email)){
            $this->validateEmails($Validation);
        }

        if(isset($this->dates)){
            $this->validateDates($Validation);
        }

        if(isset($this->regex)){
            $this->validateRegex($Validation);
        }
        if(isset($this->length)){
            $this->validateLength($Validation);
        }
        if(isset($this->file)){
            $this->validateFile($Validation);
        }

        if(isset($this->enum)){
            $this->validateEnum($Validation);
        }

        if(isset($this->identical)){
            $this->validateIdentical($Validation);
        }

        try {
            $messages = $Validation->validate($params);

            if (count($messages)) {
                foreach ($messages as $message) {
                    
                    $messagesRetorno[$message->getField()][] = $message->getMessage();
                }
                
                $this->response->setStatusCode(400);
                $this->response->setJsonContent(array("status"=>"error","message"=>$messagesRetorno));
                $this->response->send();
                return false;
            }else{
                return true;
            }
        } catch (\Exception $e) {
            $this->response->setStatusCode(500);
            $this->response->setJsonContent(array("status"=>"error","message"=>'Error en el servidor'));
            $this->response->send();
            return false;
        }
    }

   

    public function validateRequired($Validation)
    {   
        $message = ["message"=>[]];
        foreach ($this->getRequired() as $value) {
            $message["message"][$value] = $value." es requerido";

        }
        $Validation->add(
            $this->getRequired(),
            new PresenceOf(
                $message
            )
        );
    }

    public function validateIntegers($Validation)
    {
        $message = ["message"=>[],"allowEmpty" => true];
        foreach ($this->getIntegers() as $value) {
            $message["message"][$value] = $value." no es de tipo entero";

        }
        $Validation->add(
            $this->getIntegers(),
            new DigitValidator(
                $message
            )
        );
    }

    public function validateEmails($Validation)
    {
        $message = ["message"=>[],"allowEmpty" => true];
        foreach ($this->getEmails() as $value) {
            $message["message"][$value] = $value." no es valido";

        }
        $Validation->add(
            $this->getEmails(),
            new Email(
                $message
            )
        );
    }

    public function validateDates($Validation)
    {
        $message = ["message"=>[],"format"=>[],"allowEmpty" => true];
        foreach ($this->getDates() as $value) {
            $message["message"][$value] = $value." no es tiene el formato (Y-m-d) valido";
            $message["format"][$value] = "Y-m-d";

        }
        $Validation->add(
            $this->getDates(),
            new DateValidator(
                $message
            )
        );
    }

    public function validateRegex($Validation)
    {
        $Validation->add(
            $this->getRegex(),
            new Regex(
                $this->getMsjCamposRegex()
            )
        );
    }
    public function validateLength($Validation)
    {
        $Validation->add(
            $this->getLength(),
            new StringLength(
                $this->getMsjCamposLength()
            )
        );
    }

    public function validateFile($Validation)
    {
        $Validation->add(
            $this->getFile(),
            new FileValidator(
                $this->getMsjCamposFile()
            )
        );
    }

    public function validateEnum($Validation)
    {
        $message = ["message"=>[],"domain"=>[],"allowEmpty" => true];
        foreach ($this->getEnum() as $value) {
            if($value == "genre"){
                $message["message"][$value] = $value." El valor debe ser (Masculino,Femenino,No definido)";
                $message["domain"][$value] = ['Masculino', 'Femenino', 'No definido'];
            }else{
                $message["message"][$value] = $value." El valor debe ser (Movil,Web,Smart)";
                $message["domain"][$value] = ["Movil","Web","Smart"];
            }
            

        }
        $Validation->add(
            $this->getEnum(),
            new Enum(
                $message 
            )
        );
    }

    public function validateIdentical($Validation){
        $message = ["message"=>[],"accepted"=>[],"allowEmpty" => true];
        foreach ($this->getIdentical() as $value) {
            $message["message"][$value] = $value." El valor debe ser true";
            $message["accepted"][$value] = "true";

        }
        $Validation->add(
            $this->getIdentical(),
            new Identical(
                $message 
            )
        );
    }

    public function setRequired($required)
    {
       $this->required = $required;
    }

    public function getRequired()
    {
        return $this->required;
    }


    public function setIntegers($integers)
    {
       $this->integers = $integers;
    }


    public function getIntegers()
    {
        return $this->integers;
    }

    public function setEmails($email)
    {
       $this->email = $email;
    }

    public function getEmails()
    {
        return $this->email;
    }

    public function setDates($dates)
    {
       $this->dates = $dates;
    }

    public function getDates()
    {
        return $this->dates;
    }

    public function setRegex($regex)
    {
       $this->regex = $regex;
    }

    public function getRegex()
    {
        return $this->regex;
    }


    public function setLength($length)
    {
       $this->length = $length;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function setFile($file)
    {
       $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setEnum($enum)
    {
       $this->enum = $enum;
    }

    public function getEnum()
    {
        return $this->enum;
    }
    public function setIdentical($identical)
    {
       $this->identical = $identical;
    }

    public function getIdentical()
    {
        return $this->identical;
    }
}
