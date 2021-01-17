<?php

namespace App\_Core\Action;

use Symfony\Component\HttpFoundation\Request;

interface ActionInterface {
    
    public function __invoke(Request $request);
    
}
