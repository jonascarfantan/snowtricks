<?php

namespace App\Trick\Action;

use App\Service\FileUploader;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Form\TrickEditionType;
use App\Trick\Domain\Repository\TrickRepository;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class Update extends AbstractController {
    
    #[Route(path: '/trick/update_broken/{id}', name: 'update.trick', methods: ['GET', 'POST'])]
    public function __invoke(Request $request,
                             string $id,
                             EntityManagerInterface $em,
                             UserPasswordEncoderInterface $password_encoder,
                             FileUploader $file_uploader,
                             TrickRepository $trick_repository,
    ): Response
    {
        $user = $this->getUser();
        $trick = $trick_repository->find($id);
        $form = $this->createForm(TrickEditionType::class, $trick );
        $form->handleRequest($request);
        $form->getErrors(true);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $request->files->get('trick_edition')['images'];
            $videos = $request->get('trick_edition')['videos'];
            dd($images);
        }
        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'title' => 'Ajouter une figure',
        ]);
    }
}
