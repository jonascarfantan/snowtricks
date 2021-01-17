<?php

namespace App\Auth\Action;

use App\Auth\Domain\Form\ForgotPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ForgotPassword extends AbstractController
{
    
    #[Route('/password/forgot', name: 'password.forgot', methods: ['GET', 'POST'])]
    public function __invoke(Request $request,
                             MailerInterface $mailer,
                             UrlGeneratorInterface $url_generator,
                             ): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        $form->getErrors(true);
        if ($form->isSubmitted() && $form->isValid()) {
//            $transport = Transport::fromDsn('smtp://127.0.0.1');
//            $mailer = new Mailer($transport);
            $email_target = $form->getData()['email'];
            $email = (new Email())
                ->from('hello@example.com')
                ->to($email_target)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');
    
            $mailer->send($email);
            
            $request->getSession()->getFlashBag()->add('success','Email envoyé, vérifiez votre boite mail.');
            
            return new RedirectResponse($url_generator->generate('home'));
        }
        
        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier le mot de passe',
        ]);
    }
}
