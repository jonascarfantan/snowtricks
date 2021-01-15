<?php

namespace App\Auth\Action;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPassword extends AbstractController
{
    use ResetPasswordControllerTrait;
    
    public function __construct(private ResetPasswordHelperInterface $resetPasswordHelper)
    { }
    
    #[Route('/password/reset/{token}', name: 'reset_password', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);
            
            return $this->redirectToRoute('reset_password');
        }
        
        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }
        
        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));
            
            return $this->redirectToRoute('forgot_password');
        }
        
        // The token is valid; allow the user to change their password.
        $form = $this->createForm(UpdatePassword::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);
            
            // Encode the plain password, and set it.
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            
            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();
            
            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();
            
            return $this->redirectToRoute('home');
        }
        
        return $this->render('security/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
