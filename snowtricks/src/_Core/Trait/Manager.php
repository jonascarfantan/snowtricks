<?php
namespace App\_Core\Trait ;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait Manager {
    
    public static function toArray($object): array {
        return json_decode(json_encode($object), true);
    }
    
    public function redirectUnauthenticated(Request $request): Response|bool
    {
        if($user = $this->getUser() === null) {
            $request->getSession()->getFlashBag()->add('error', 'Vous devez être authentifié pour réaliser cette action.');
    
            return $this->redirect('/login');
        }
        
        return true;
    }
    
    public static function slugable(string $title, int $version): string
    {
        return str_replace(' ','-',strtolower($title)).'-v-'.(string)$version;
    }
    
}
