<?php

namespace App\Auth\Action;

use App\Auth\Domain\Form\ForgotPasswordType;
use App\Auth\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

final class ForgotPassword extends AbstractController
{
    
    #[Route('/password/forgot', name: 'password.forgot', methods: ['GET', 'POST'])]
    public function __invoke(
        Request $request,
        MailerInterface $mailer,
        UserRepository $user_repository,
        UrlGeneratorInterface $url_generator,
        TokenGeneratorInterface $token_generator,
        EntityManagerInterface $em,
        UrlHelper $url_helper
        ): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        $form->getErrors(true);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $email_target = $form->getData()['email'];
            $user = $user_repository->findOneBy(['email' => $email_target]);
            if($user) {
                $user->setForgotPasswordToken($token_generator->generateToken())
                    ->setForgotPasswordTokenRequestedAt(new \DateTimeImmutable('now'))
                    ->setForgotPasswordTokenMustBeVerifiedBefore(new \DateTimeImmutable('+15 minutes'));
                
                $em->flush();
                $url = $url_helper->getAbsoluteUrl('/forgot-password/' . $user->getId() . '/' . $user->getForgotPasswordToken());
                $this->sendEmail($mailer,$this->createMailContent($url),$email_target);
            }
            
            $request->getSession()->getFlashBag()->add('success','Email envoyé, vérifiez votre boite mail.');
            
            return new RedirectResponse($url_generator->generate('login'));
        }
        
        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier le mot de passe',
        ]);
    }
    
    private function createMailContent($url) {
        
        return <<<HTML
                    <h1>Hi rider,</h1>
                    <p>I eared you forgot your password ! please follow the link below to be redirected to create password form.</p>
                    <a href="$url">Lien pour créer un nouveau mot de passe</a>
                    HTML;

    }
    
    private function sendEmail(MailerInterface $mailer, $content, $email_target)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($email_target)
            ->subject('You asked for a reset password link')
            ->html($content);
        
        $mailer->send($email);
    }
}
