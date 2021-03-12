<?php

namespace App\Trick\Action;

use App\_Core\Trait\Manager;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Repository\TrickRepository;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UpdatePart extends AbstractController {
    use Manager;
    
    #[Route(path: '/tricks/{id}', name: 'update.trick.json', methods: ['PUT'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        TrickRepository $trick_repository,
        Trick $trick
    ): Response {
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        $json = $request->getContent();
        $arguments = json_decode($json);
        
        $field_name = $arguments->name;
        $field_value = $arguments->value;
        $token = $arguments->token;
    
    
        if ($this->isCsrfTokenValid('update_part', $token)) {
            
            if(!in_array($field_name, ['images','videos'])) {
                //TODO add validation
                $trick->set($field_name, $field_value);
                $em->persist($trick);
                $em->flush();
            } else {
                $media = new Media();
                if($field_name === 'images'){
                    $media->setSlug('trick-'.$trick->getId().'-img');
                    $media->setPath($field_value);
                    $media->setAlt('Image d\'une figure de snowboard.' );
                    $media->setType('img');
                } else {
                    $media->setSlug('trick-'.$trick->getId().'-mov');
                    $media->setIframe($field_value);
                    $media->setType('mov');
                }
                $trick->addMedia($media);
            }
            
            $response = new JsonResponse(
                [
                    'code' => 200,
                    'message' => 'Ressource modifiée',
                    'data' => $field_value,
                    'datatype' => $field_name
                ]);
            
        } else {
            $response = new JsonResponse(
                [
                    'code' => 403,
                    'message' => 'Le token csrf est invalide ! vous n\'êtes pas authorisé à effectuer cette action depuis cet endroit.'
                ]);
        }
        
        return $response;
    }
}
