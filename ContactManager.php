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
 
    //Find all contacts and return Contact Objects 
    public function findAll(): array {

        $query = "SELECT * FROM contact";
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
}
