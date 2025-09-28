<?php 

class Command {
    public string $line; 
    private ContactManager $contactManager;

    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    private function list(){
        $contacts = $this->contactManager->findAll();
        foreach ($contacts as $contact) {
            echo $contact;
        }
    }


    public function start(){
        $this->line = readline("Entrez votre commande : ");
    }

    public function handleCommand(string $line){
        if ($line === 'list') {
            $this->list();
            return;
        }
    }
}