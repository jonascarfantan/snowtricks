<?php

namespace App\Auth\Action;

use App\Auth\Domain\Dto\UpdatePasswordDto;
use App\Auth\Domain\Form\ResetPasswordType;
use App\Auth\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

final class UpdatePassword extends AbstractController
{
    #[Route('/password/update', name: 'update_password', methods: ['GET', 'POST'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        Security $security,
        UrlGeneratorInterface $url_generator,
        Session $session,
        UserRepository $user_repository
        ): Response
    {
//        GET USER AND TOKEN FROM LINK
        ['token' => $token, 'user_email' => $user_email ] = $this->getCredentialsFromSession($session);
        
        $user = $user_repository->findOneBy([
            'email' => $user_email
        ]);
        
        
        if(!$user) {
            $request->getSession()->getFlashBag()->add('error','l\'utilisateur n\'existe pas.');
            return $this->redirectToRoute('password.forgot');
        }
        
        $forgotPasswordTokenMustBeVerifiedBefore = $user->getForgotPasswordTokenMustBeVerifiedBefore();
    
        if( ($user->getForgotPasswordToken() === null) ||
            ($user->getForgotPasswordToken() !== $token) ||
            ($this->isNotRequestedInTime($forgotPasswordTokenMustBeVerifiedBefore))
        ) {
            $request->getSession()->getFlashBag()->add('error','Un problème dû au token empèche de poursuivre l\'action');
            
            return $this->redirectToRoute('password.forgot');
        }
        
//    HANDLE FORM
        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);
        $form->getErrors(true);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $reset_data = $form->getData();
    
            $encoded = $encoder->encodePassword($user, $reset_data->getPassword());
            $user->setPassword($encoded);
            $user->setForgotPasswordToken('')
                 ->setForgotPasswordTokenRequestedAt(new \DateTimeImmutable('now'));
            $this->removeCredentialsFromSession($session);
            $em->flush();
    
            $request->getSession()->getFlashBag()->add('success','Mots de passe créé.');
    
            return $this->redirectToRoute('login');
        }
    
        return $this->render('security/reset.html.twig', [
            'form' => $form->createView(),
            'title' => 'Créer un nouveau mot de passe',
        ]);
        
    }
    
    private function getCredentialsFromSession($session): array
    {
        return [
            'token' => $session->get('Reset-Password-Token-URL'),
            'user_email' => $session->get('Reset-Password-User-Email')
        ];
    }
    
    private function removeCredentialsFromSession(Session $session): void
    {
        $session->remove('Reset-Password-Token-URL');
        $session->remove('Reset-Password-User-Email');
    }
    
    private function isNotRequestedInTime(\DateTimeImmutable $time_limit): bool
    {
        return (new \DateTimeImmutable('now') > $time_limit);
    }
}
