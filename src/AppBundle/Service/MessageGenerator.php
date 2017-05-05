<?php

namespace AppBundle\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator
{
    private $logger;
    
    private $enabled_logger;
    
    public function __construct(LoggerInterface $logger, $enabled_logger) {
        $this->logger = $logger;
        $this->enabled_logger = $enabled_logger;
    }
    
    public function getHappyMessage()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);
        
        if ($this->enabled_logger) {
            $this->logger->info('Do jajaja');
        }
        
        return $messages[$index];
    }

}

