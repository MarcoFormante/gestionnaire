<?php
require_once 'ContactManager.php';


$manager = new ContactManager();

while (true) {
    $line = readline("Entrez votre commande : ");
    
    if($line === 'list'){
        $contacts = $manager->findAll();
        foreach ($contacts as $contact) {
            echo $contact;
        }
    }
}