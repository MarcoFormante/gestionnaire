<?php 

class Command {
    public string $line; 
    private ContactManager $contactManager;

    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;
    }


    
    public function start(){
        $this->line = readline("Entrez votre commande (create, delete \$id, detail \$id, list, help) : ");
    }



    public function handleCommand(){
        // Handle List Command - Show all Contacts
        if ($this->line === 'list') {
            $this->list();

            
        // Handle detail Command - Display details of one Contact by its ID
        }elseif(preg_match("/^detail [0-9]+$/i", $this->line, $matches)){
            $this->detail($matches);
           


        // Handle detail Command - Display details of one Contact by its ID
        }elseif($this->line === "create"){
            $this->create();


        // Handle delete Command 
        }elseif(preg_match("/^delete [0-9]+$/i", $this->line, $matches)){
            $this->delete($matches);


        // Handle help Command
        }elseif($this->line === "help"){
                $this->help();


        // If the Command not exists, display help text
        }else{
             echo "This Command not exist!\n";
             $this->help();
        }
    }

    


    // Fetch all Contacts and display them
    private function list(){
        $contacts = $this->contactManager->findAll();
        foreach ($contacts as $contact) {
            echo $contact;
        }
    }


    // Fetch one Contact and display it
    public function detail(array $matches){
        $id = str_replace("detail ","",$matches)[0];
        $this->contactManager->findById($id);
    }


    //Check all Inputs and create a new Contact
    private function create(){
        $createLine = readline("Enter name, email, phone number: " );
        $fields = explode(",",$createLine);
        $hasAllFields = count($fields) === 3; 

        if (!$hasAllFields) {
          echo "All fields are required";
        }

        foreach ($fields as $field) {
            if (empty($field) || trim($field) === "") {
                echo "All fields are required." . PHP_EOL;
                return ;
            }elseif (strlen($fields[0]) > 30) {
                  echo "Name cannot exceed 30 characters." . PHP_EOL;
                return;
                
            }elseif (!filter_var($fields[1],FILTER_VALIDATE_EMAIL)) {
                  echo "Add a valid Email, Please." . PHP_EOL;
                return;
                
            }elseif (!preg_match("/^[0-9]{10}$/",$fields[2])) {
                  echo "Phone number must contain only numbers and must contain 10 numbers." . PHP_EOL;
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

        if($this->contactManager->create($contact)){
            echo "New Contact Created :" . PHP_EOL .   $contact;
        }else{
            echo "Error during creation action";
        }
        
    }


     //Check all Inputs and create a new Contact
    private function delete(array $matches){
        $id = str_replace("delete ","",$matches)[0];
        if($this->contactManager->delete($id)){
            echo "Contact Deleted" . PHP_EOL;
        }else{
               echo "The ID is not Valid" . PHP_EOL;
        }
    } 


    private function help(){
        $helpText = "
            help : affiche cette aide

            list : liste les contacts

            create [name], [email], [phone number] : crée un contact

            delete [id] : supprime un contact

            quit : quitte le programme
        ";
        echo $helpText;
    }



   
}