<?php 

class Contact
{
    private int $id;
    private string $name;
    private string $email;
    private string $phone_number;


    public function setId(int $id):void{
        $this->id = $id;
    }

    public function getId():int{
        return $this->id;
    }



    public function setName(string $name):void{
        $this->name = $name;
    }

    public function getName():string{
        return $this->name;
    }



    public function setEmail(string $email):void{
        $this->email = $email;
    }

    public function getEmail():string{
        return $this->email;
    }



    public function setPhoneNumber(string $phone_number):void{
        $this->phone_number = $phone_number;
    }

    public function getPhoneNumber():string{
        return $this->phone_number;
    }


    public function __toString()
    {
        return " ID: {$this->id}\n NAME: {$this->name}\n EMAIL: {$this->email}\n PHONE_NUMBER: {$this->phone_number}\n---------------------------\n";
    }
}
