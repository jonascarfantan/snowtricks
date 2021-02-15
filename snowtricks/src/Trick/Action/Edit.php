<?php

namespace App\Trick\Action;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Form\RegistrationType;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Form\TrickEditionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class Edit extends AbstractController
{
    #[Route('/trick/create', name: 'trick.create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $password_encoder): Response
    {
        $form = $this->createForm(TrickEditionType::class, new Trick(), );
        $form->handleRequest($request);
        $form->getErrors(true);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $new_trick = $form->getData();
            dd($form->get('image')->getData());
            dd($new_trick);
//            $encoded = $password_encoder->encodePassword($new_user, $new_user->getPassword());
//            $new_user->setPassword($encoded);
//            $role = $em->getRepository(Role::class)->findOneBy(['slug' => 'ROLE_USER']);
//            $new_user->promote($role);
//            $em->persist($new_user);
//            $em->flush();
        }
        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Ajouter une figure',
        ]);
    }
    
}
