<?php

namespace App\Auth\Action;

use App\Auth\Domain\Entity\UpdatePasswordDto;
use App\Auth\Domain\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

final class UpdatePassword extends AbstractController
{
    #[Route('/password/update', name: 'update_password', methods: ['GET', 'POST'])]
    public function __invoke(Request $request,
                             EntityManagerInterface $em,
                             UserPasswordEncoderInterface $encoder,
                             Security $security,
                             UrlGeneratorInterface $url_generator,
                             ): Response
    {
//        $this->denyAccessUnlessGranted('ROLE_USER');
        $reset_password = new UpdatePasswordDto();
        $form = $this->createForm(UpdatePasswordType::class, $reset_password);
        $form->handleRequest($request);
        $form->getErrors(true);
        if ($form->isSubmitted() && $form->isValid()) {
            $reset_data = $form->getData();
            $user = $this->getUser();
            $input_password = $encoder->encodePassword($user, $reset_data->getPassword());
            $user->setPassword($input_password);
            $em->persist($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success','Mots de passe modifié.');
            
            return new RedirectResponse($url_generator->generate('home'));
        }
        
        return $this->render('security/update.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier le mot de passe',
        ]);
    }
}
