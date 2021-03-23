<?php

namespace App\Media\Action;

use App\_Core\Trait\Manager;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RemoveMedia extends AbstractController {
    use Manager;
    
    #[Route(path: '/medias/{id}', name: 'remove.media.json',requirements: ['id' => '\d+'] , methods: ['DELETE'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        TrickRepository $trick_repository,
        ValidatorInterface $validator,
        Media $media
    ): Response {
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        $json = $request->getContent();
        $arguments = json_decode($json);
        $token = $arguments->token;
    
        if ($this->isCsrfTokenValid('update_part', $token)) {
            $media_id = $media->getId();
            $em->remove($media);
            $em->flush();
            
            $response = new JsonResponse(
                [
                    'code' => 200,
                    'media_id' => $media_id,
                    'message' => 'Ressource supprimée',
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
