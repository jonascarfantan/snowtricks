<?php

namespace App\Trick\Action;

use App\_Core\Trait\Manager;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Repository\TrickRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdatePart extends AbstractController {
    use Manager;
    
    #[Route(path: '/tricks/{slug}', name: 'update.trick.json', methods: ['PATCH'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        TrickRepository $trick_repository,
        ValidatorInterface $validator,
        Trick $trick
    ): Response {
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        $carbon = new Carbon();
        $now = $carbon->now();
        $json = $request->getContent();
        $arguments = json_decode($json);
        
        $field_name = $arguments->name;
        $field_value = $arguments->value;
        $token = $arguments->token;
        if ($this->isCsrfTokenValid('update_part', $token)) {
            if($field_name !== 'iframe') {
                
                if($field_name === 'is_banner') {
                    $medias = $trick->getMedias();
                    foreach($medias as $media) {
                        if($media->getId() == $field_value) {
                            $media->setIsBanner(true);
                            $returnable = [
                                'id' => $media->getId(),
                                'src' => $media->getPath()
                            ];
                        } else {
                            $media->setIsBanner(false);
                        }
                        $em->flush();
                    }
                } else {
                    //TODO add validation
                    $trick->set($field_name, $field_value);
                    //                $violation = $validator->validate($trick);
                    //                dd($violation);
                    $em->persist($trick);
                    $em->flush();
                    $returnable = $trick->get($field_name);
                }
            } else {
                $media = new Media();
                $media->setSlug('trick-mov'.random_bytes(2));
                $media->setIframe($field_value);
                $media->setType('mov');
                $media->setCreatedAt($now);
                $trick->addMedia($media);
                $em->persist($trick);
                $em->flush();
                $returnable = [
                    'id' => $media->getId(),
                    'iframe' => $media->getIframe()
                ];
            }
            
            $response = new JsonResponse(
                [
                    'code' => 200,
                    'message' => 'Ressource modifiée',
                    'data' => $returnable,
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
