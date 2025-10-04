<?php 

class Command {
    public string $line; 
    private ContactManager $contactManager;

    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;
    }


    
    public function start(){
        $this->line = trim(strtolower(readline("Entrez votre commande (create, delete \$id, detail \$id, modify \$id list, help) : ")));
    }



    public function handleCommand(bool &$loop){
        // Handle List Command - ## Show all Contacts ## 
        if ($this->line === 'list') {
            $this->list();

            
        // Handle detail Command - ## Display details of one Contact by its ID  ##
        }elseif(preg_match("/^detail\s+[0-9]+$/i", $this->line, $matches)){
            $this->detail($matches);
           


        // Handle modify Command  # Modify contact properties
        }elseif(preg_match("/modify\s+(\d+)/i", $this->line, $matches)){
            $this->modify($matches);


        // Handle detail Command - ## Display details of one Contact by its ID ##
        }elseif($this->line === "create"){
            $this->createOrModify();


        // Handle delete Command 
        }elseif(preg_match("/^delete [0-9]+$/i", $this->line, $matches)){
            $this->delete($matches);


        // Handle help Command
        }elseif($this->line === "help"){
                $this->help();


         // Handle quit Command - ## Stop Loop ## 
        }elseif($this->line === "quit"){
            $this->quit($loop);


         // If the Command not exists, display help text
        }else{
          echo "Cette commande n'existe pas !" . PHP_EOL;
        }
    }

    


    // Fetch all Contacts and display them
    private function list(){
        $contacts = $this->contactManager->findAll();
        if ($contacts) {
            foreach ($contacts as $contact) {
                echo $contact;
            }
        }else{
            echo "Il n'y a aucun contact dans la base de données." . PHP_EOL;
        }
    }


    // Fetch one Contact and display it else display error
    public function detail(array $matches){
        $id = preg_replace('/detail\s+/i' ,"",$matches[0]);
        $contact = $this->contactManager->findById($id);
        if($contact){
            echo $contact;
        }else{
            echo "Ce contact n'existe pas." . PHP_EOL;
        }
    }


    //Check all Inputs and create a new Contact
    private function createOrModify(int $id = null){
        $createLine = trim(readline(($id ? "Modifier le contact -> " : "Nouveau contact -> ") . "Entrez le nom, l'email et le numéro de téléphone : "));
        $fields = explode(",",$createLine);
        $hasAllFields = count($fields) === 3; 

        if (!$hasAllFields) {
            echo "Tous les champs sont obligatoires." . PHP_EOL;
            return;
        }

        foreach ($fields as $field) {
            if (empty($field) || trim($field) === "") {
                echo "Tous les champs sont obligatoires." . PHP_EOL;
                return;
            } elseif (strlen($fields[0]) > 30) {
                echo "Le nom ne peut pas dépasser 30 caractères." . PHP_EOL;
                return;
            } elseif (!filter_var($fields[1], FILTER_VALIDATE_EMAIL)) {
                echo "Veuillez saisir un email valide." . PHP_EOL;
                return;
            } elseif (!preg_match("/^[0-9]{10}$/", $fields[2])) {
                echo "Le numéro de téléphone doit contenir exactement 10 chiffres." . PHP_EOL;
                return;
            }
        }

        $name = $fields[0];
        $email = $fields[1];
        $phone_number = $fields[2];
        $contact = new Contact();
        $contact->setName($name);
        $contact->setEmail($email);
        $contact->setPhoneNumber($phone_number);

        if ($id) {
            $contact->setId($id);
        }

        if ($this->contactManager->createOrModify($contact)) {
            echo ($contact->getId() ? "Contact mis à jour" : "Nouveau contact créé :") . PHP_EOL . $contact;
        } else {
            echo "Erreur lors de " . ($contact->getId() ? "la modification" : "la création"). PHP_EOL;
        }
    }


     //Delete Contact
    private function delete(array $matches){
        $id = preg_replace('/delete\s+/i',"",$matches[0]);
        if($this->contactManager->delete($id)){
            echo "Contact supprimé" . PHP_EOL;
        }else{
            echo "L'ID n'est pas valide." . PHP_EOL;
        }
    } 


    // Show Help Text
    private function help(){
        $helpText = "
            help : affiche cette aide

            list : liste les contacts

            create [name], [email], [phone number] : crée un contact

            delete [id] : supprime un contact

            quit : quitte le programme

            modify [id] : modifier un contact

            Attention à la syntaxe des commandes, les espaces et virgules sont importants.
        ";
        echo $helpText;
    }



    private function quit(bool &$loop){
        $loop = false;
    }



    // Handle Modify Command - ## Single param  or all params ## 
    private function modify(array $matches){
        $id = preg_replace('/modify\s+/i',"",$matches[0]);
        
        $contact = $this->contactManager->findById($id);

        if(!$contact){
            echo "L'ID n'est pas valide." . PHP_EOL;
            return;
        }

        $line = trim(strtolower(readline("Quelle propriété voulez-vous modifier ? (name, email, telephone, all): ")));
        
        
        switch($line){
            case "name":
                $name = trim(readline("Entrez un nouveau Nom: "));
                if (!preg_match('/([a-zA-Z]|[à-ü]|[À-Ü])/',$name) || strlen($name) > 30 ){
                    echo "Nom invalide. Veuillez saisir un nom valide et vous assurer qu'il ne dépasse pas 30 caractères." . PHP_EOL;
                    return;
                }else{
                    $contact->setName($name);
                }
            break;


            case "email":
                $email = trim(readline("Entrez un nouveau mail: "));
                if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
                    echo "Veuillez saisir un email valide." . PHP_EOL;
                    return; 
                }
                $contact->setEmail($email);
                
            break;


            case "telephone":
                $phone_number = trim(readline("Entrez un nouveau numero de téléphone: "));
                if (!preg_match("/^[0-9]{10}$/",$phone_number)) {
                    echo "Le numéro de téléphone doit contenir exactement 10 chiffres." . PHP_EOL;
                    return; 
                }
                $contact->setPhoneNumber($phone_number);
                
            break;

            // Handle all params
            case "all":
                $this->createOrModify($contact->getId());
                return;
            break;

            default:
                echo "Option invalide. Veuillez choisir name, email, telephone, ou all." . PHP_EOL;
                return;
        }
        
        // Handle single param
        if($this->contactManager->update($contact)){
            echo "Contact mis à jour : " . PHP_EOL . $contact;
        }else{
            echo "Erreur lors de la modification, veuillez réessayer." . PHP_EOL;
        }
    } 
   
}