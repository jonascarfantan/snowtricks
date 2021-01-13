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
    public function __invoke(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(RegistrationType::class, new User());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = new User();
            $user = $user->create($data);
            $encoded = $encoder->encodePassword($user, $data->get('password'));
            $user->set('password', $encoded);
            $role = $em->getRepository(Role::class)->findOneBy(['slug' => 'user']);
            $user->addRole($role);
            $em->persist($user);
            $em->flush();
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'title' => 'Inscription',
        ]);
    }
    
}