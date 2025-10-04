<?php 
require_once 'DBConnect.php';
require_once 'Contact.php';

class ContactManager 
{
    private $pdo;

    // Get PDO Connection from DBConnect Object when is created
    public function __construct()
    {
        $DBConnect = new DBConnect();
        $this->pdo = $DBConnect->getPDO();
    }


 
    //Find all Contacts and return Contact Objects 
    public function findAll(): array {
        $query = "SELECT id, name, email, phone_number FROM contact";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $contacts = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $contact = new Contact();
            
            $contact->setId($row['id']);
            $contact->setName($row['name']);
            $contact->setEmail($row['email']);
            $contact->setPhoneNumber($row['phone_number']);

            $contacts[] = $contact;
        }

        return $contacts;
    }



    //Find a Contact by ID and return Contact Object
    public function findById(int $id){
        $query = "SELECT id, name, email, phone_number FROM contact WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Display Contact by ID if in database
        if($row){
            $contact = new Contact();

            $contact->setId($row['id']);
            $contact->setName($row['name']);
            $contact->setEmail($row['email']);
            $contact->setPhoneNumber($row['phone_number']);

            return $contact;
        }
    } 


    // Insert a new Contact into the database and update its ID with the last inserted ID or update all params of a Contact if ID exists
    public function createOrModify(Contact $contact){
        if (!$contact->getId()) {
            $query = "INSERT INTO contact(name,email,phone_number) VALUES(:name,:email,:phone_number)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                "name" => $contact->getName(),
                "email" => $contact->getEmail(),
                "phone_number" =>$contact->getPhoneNumber(),
            ]);

            $id = $this->pdo->lastInsertId();
            $contact->setId($id);
        }else{
            $query = "UPDATE contact SET name = :name, email = :email, phone_number = :phone_number WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'id' => $contact->getId(),
                "name" => $contact->getName(),
                "email" => $contact->getEmail(),
                "phone_number" =>$contact->getPhoneNumber(),
            ]);
        }
       

        return $stmt->rowCount() > 0;
       
    }


    public function delete(string $id){
        $query = "DELETE FROM contact WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        if (!$stmt->rowCount()) {
            return false;
        }
        return true;
    }


    public function update(Contact $contact){
        $query = "UPDATE contact SET name = :name, email = :email, phone_number = :phone_number WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            "name" => $contact->getName(),
            "email" => $contact->getEmail(),
            "phone_number" => $contact->getPhoneNumber(),
            "id" => $contact->getId()
        ]);

        if (!$stmt->rowCount()) {
            return false;
        }

        return true;
    }
}
