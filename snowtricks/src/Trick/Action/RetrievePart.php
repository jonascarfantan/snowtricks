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

final class RetrievePart extends AbstractController {
    use Manager;
    
    #[Route(path: '/tricks/{id}/{name}', name: 'retrieve.trick.part.json', methods: ['GET'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        TrickRepository $trick_repository,
        Trick $trick,
        string $name
    ): Response {
        
        if(!in_array($name, ['images','videos'])) {
            $data = $trick->get($name);

        } else {
            //TODO get media instead of add
//            $media = new Media();
//            if($name === 'images'){
//                $media->setSlug('trick-'.$trick->getId().'-img');
//                $media->setPath($field_value);
//                $media->setAlt('Image d\'une figure de snowboard.' );
//                $media->setType('img');
//            } else {
//                $media->setSlug('trick-'.$trick->getId().'-mov');
//                $media->setIframe($field_value);
//                $media->setType('mov');
//            }
//            $trick->addMedia($media);
        }
        
        $response = new JsonResponse(
            [
                'code' => 200,
                'message' => 'Ressource récupérée',
                'data' => $data,
                'datatype' => $name
            ]);
        
        return $response;
    }
}
