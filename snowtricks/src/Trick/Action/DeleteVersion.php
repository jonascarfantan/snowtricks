<?php

namespace App\Trick\Action;

use App\_Core\Trait\Manager;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Repository\TrickRepository;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DeleteVersion extends AbstractController
{
    use Manager;
    
    #[Route(path: '/tricks/{slug}/delete', name: 'delete.trick', methods: ['GET'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        TrickRepository $trick_repository,
        Trick $version
    ): Response
    {
        
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        
        $tricks_manager = new TricksManager([Trick::class], $em);
        $user = $this->getUser();
        if(($trick = $tricks_manager->remove($version, $user)) === $version) {
            $request->getSession()->getFlashBag()
                ->add('error','Vous ne pouvez pas supprimer une version antérieur à celle courrante pour garder l\'historique et le cohérence.');
        } elseif ($trick === false) {
            $request->getSession()->getFlashBag()
                ->add('error','Seul les versions non publiées dont vous êtes l\'auteur peuvent être supprimées');
            $trick = $version;
        }
        $trick = $tricks_manager->trickWithTree($trick);
        
        return $this->render('trick/show.html.twig', [
            'title' => 'Zoom sur ' . $trick['title'],
            'trick' => $trick,
        ]);
    }
}
