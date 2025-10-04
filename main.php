<?php
require_once 'ContactManager.php';
require_once 'Command.php';


$manager = new ContactManager();
$command = new Command($manager);

while (true) {
    try {
        $command->start();
        $command->handleCommand();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}