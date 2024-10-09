<?php
// src/Service/MessageGenerator.php
namespace App\Service;

class MessageGenerator
{
    public function getRandomMessage(): string
    {
        $messages = [
            'Bonjour le monde !',
            'Comment ça va ?',
            'Bienvenue sur notre site !',
            'Mathis',
            'léo',
            'Emeric',
        ];

        return $messages[array_rand($messages)];
    }
}
?>