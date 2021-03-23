<?php

namespace App\Media\Action;

use App\_Core\Service\FileUploader;
use App\_Core\Trait\Manager;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Form\TrickEditionType;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class Upload extends AbstractController
{
    use Manager;
    
    #[Route('/tricks/{id}/medias/upload', name: 'media.upload.json', methods: ['POST','PATCH'])]
    public function __invoke(Request $request,
                             EntityManagerInterface $em,
                             FileUploader $file_uploader,
                             Trick $trick
    ): Response
    {
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        $token = $request->request->get('token');
        if ($this->isCsrfTokenValid('update_part', $token)) {
        // TODO event doctrine to handle date with @prepush
            $carbon = new Carbon();
            $now = $carbon->now();
            $file = $request->files->get('file');
            $uploaded = $file_uploader->upload($file);
            $media = new Media();
            $media->setType('img')
                ->setTrick($trick)
                ->setPath('/images/tricks/'.$uploaded['file_path'])
                ->setSlug('trick-img'.$uploaded['file_name'])
                ->setAlt('image du trick '.$media->getSlug())
                ->setCreatedAt($now);
            $trick->addMedia($media);
            $em->persist($trick);
            $em->flush();
            $return_media = [
                'id' => $media->getId(),
                'path' => $media->getPath()
            ];
            $response = new JsonResponse(
                [
                    'code' => 200,
                    'message' => 'Media ajouté',
                    'data' => $return_media,
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
