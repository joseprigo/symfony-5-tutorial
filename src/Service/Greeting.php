<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Psr\Log\LoggerInterface;

/**
 * Description of Greeting
 *
 * @author josep
 */
class Greeting {

    /**
     * @var string
     */
    private $message;
    private $logger;
    
    function __construct(LoggerInterface $logger, string $message) {
        $this->logger = $logger;
        $this->message = $message;
    }

    public function greet(string $name): string
    {
        $this->logger->info("Gteeted $name");
        return "{$this->message} $name";
    }
}
