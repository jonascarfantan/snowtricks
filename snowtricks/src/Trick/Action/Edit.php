<?php

namespace App\Trick\Action;

use App\_Core\Service\FileUploader;
use App\_Core\Trait\Manager;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Form\TrickEditionType;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class Edit extends AbstractController
{
    use Manager;
    
    #[Route('/tricks/create', name: 'trick.create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request,
                             EntityManagerInterface $em,
                             UserPasswordEncoderInterface $password_encoder,
                             FileUploader $file_uploader
    ): Response
    {
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        
        $user = $this->getUser();
        $form = $this->createForm(TrickEditionType::class, new Trick() );
        $form->handleRequest($request);
        $form->getErrors(true);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $carbon = new Carbon();
            $now = $carbon->now();
            $new_trick = $form->getData();
            $new_trick->setContributor($user)
                      ->setState('current')
                      ->setVersion(1)
                      ->setCreatedAt($now);
            
            $images = $request->files->get('trick_edition')['images'];
            $videos = $request->get('trick_edition')['videos'];
            foreach($images as $image) {
                $uploaded = $file_uploader->upload($image);
                $media = new Media();
                $media->setType('img')
                    ->setTrick($new_trick)
                    ->setPath('/images/tricks/'.$uploaded['file_path'])
                    ->setSlug($uploaded['file_name'])
                    ->setCreatedAt($now);
                $new_trick->addMedia($media);
            }
            foreach($videos as $video) {
                $media = new Media();
                $media->setType('mov')
                    ->setTrick($new_trick)
                    ->setIframe($video)
                    ->setSlug('blap')
                    ->setCreatedAt($now);
                $new_trick->addMedia($media);
            }
            $em->persist($new_trick);
            $em->flush();
        }
        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Ajouter une figure',
        ]);
    }
    
}
