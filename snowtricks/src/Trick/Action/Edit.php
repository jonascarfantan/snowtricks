<?php

namespace App\Trick\Action;

use App\_Core\Service\FileUploader;
use App\_Core\Trait\Manager;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Form\TrickEditionType;
use App\Trick\Domain\TricksManager;
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
        $same_page_response = $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Ajouter une figure',
        ]);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $tricks_manager = new TricksManager([Trick::class], $em);
            $new_trick = $form->getData();
            if($tricks_manager->isTitleAlreadyExists($new_trick->getTitle())) {
                $request->getSession()->getFlashBag()->add('error','Une figure portant le même titre existe déjà, veuillez le consulter');
                
                return $this->redirect('/tricks/create');
            }
            $carbon = new Carbon();
            $now = $carbon->now();
            $new_trick->setContributor($user)
                      ->setState('current')
                      ->setVersion(1)
                      ->setCreatedAt($now);
            //Prevent title duplication with our constraints
            
            
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
                if($image->getClientOriginalName() === $request->get('img_banner')) {
                    $media->setIsBanner(true);
                }
                $new_trick->addMedia($media);
            }
            foreach($videos as $video) {
                $media = new Media();
                $media->setType('mov')
                    ->setTrick($new_trick)
                    ->setIframe($video)
                    ->setSlug('trick-mov'.random_bytes(2))
                    ->setCreatedAt($now);
                $new_trick->addMedia($media);
            }
            try {
                $em->persist($new_trick);
                $em->flush();
                $new_trick->setSlug();
                $em->flush();
            } catch (\PDOException $e) {
                $request->getSession()->getFlashBag()->add('error', $e->getMessage());
            }
            
            return $this->redirectToRoute('show.trick',['id' => $new_trick->getId()]);
        }
        
        return $same_page_response;
    }
    
}
