<?php 

class ContactManager 
{
 
    public function findAll():array{

        return ["TEST"];
    }
}

$c = new ContactManager();

var_dump($c->findAll());