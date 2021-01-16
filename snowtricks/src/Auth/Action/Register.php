<?php

namespace App\Auth\Action;

use App\Auth\Domain\Entity\Role;
use App\Auth\Domain\Entity\User;
use App\Auth\Domain\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class Register extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $password_encoder): Response
    {
        $form = $this->createForm(RegistrationType::class, new User($password_encoder));
        $form->handleRequest($request);
        $form->getErrors(true);
        if ($form->isSubmitted() && $form->isValid()) {
            $new_user = $form->getData();
            $new_user->encodePassword($new_user, $new_user->getPassword());
            $role = $em->getRepository(Role::class)->findOneBy(['slug' => 'ROLE_USER']);
            $new_user->addRole($role);
            $em->persist($new_user);
            $em->flush();
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'title' => 'Inscription',
        ]);
    }
    
}
