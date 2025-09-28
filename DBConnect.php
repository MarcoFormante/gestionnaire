<?php
require_once 'config.php';

class DBConnect 
{
    
    private $pdo;

    public function getPDO(){
        $this->pdo = new PDO('mysql:host='.DB_HOST.';dbname=' .DB_NAME.';charset:utf8',DB_USER, DB_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        
        return $this->pdo;
    }
}
