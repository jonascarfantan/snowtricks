<?php

namespace App\_Core\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ActionInterface {
    
    public function __invoke(): Response;
    
}
