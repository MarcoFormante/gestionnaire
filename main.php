<?php
require_once 'ContactManager.php';
require_once 'Command.php';


$manager = new ContactManager();
$command = new Command($manager);

while (true) {
    $command->start();
    $command->handleCommand(($command->line));
}