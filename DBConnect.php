<?php
require_once 'config.php';

class DBConnect 
{
    private $pdo;

    public function getPDO(){
        try{
            $this->pdo = new PDO('mysql:host='.DB_HOST.';dbname=' .DB_NAME.';charset:utf8',DB_USER, DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        }catch(PDOException $e){
            die("Une erreur est survenue lors de l'accès à la base de données. Veuillez réessayer plus tard.");
        }
    }
}
