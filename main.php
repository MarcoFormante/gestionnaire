<?php
require_once 'ContactManager.php';
require_once 'Command.php';


$manager = new ContactManager();
$command = new Command($manager);

$loop = true;

while ($loop) {
    $command->start();
    $command->handleCommand($loop);
}