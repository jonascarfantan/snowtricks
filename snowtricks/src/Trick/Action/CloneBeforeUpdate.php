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

final class CloneBeforeUpdate extends AbstractController {
    use Manager;
    
    #[Route(path: '/trick/update/{id}', name: 'show.update.trick', methods: ['GET'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        TrickRepository $trick_repository,
        int $id
    ): Response {
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        
        $tricks_manager = new TricksManager([Trick::class], $em);
        $trick          = $trick_repository->find($id);
        // If there is no draft for this trick yet, we can create one
        if(!$tricks_manager->alreadyHasDraft($trick)) {
            
            // If this trick is the current one we clone it has a draft ready to be updated
            if($tricks_manager->isCurrentVersion($trick)) {
                $draft_trick = clone $trick;
                $draft_trick->setState('draft');
                $draft_trick->setParent($trick);
                $draft_trick->setVersion((int)$trick->getVersion() + 1);
                $tricks_manager->cloneMedias($trick->getMedias(), $draft_trick);
                $em->persist($draft_trick);
                $em->flush();
                
                $trick = $tricks_manager->trickWithTree($draft_trick);
                $request->getSession()->getFlashBag()
                    ->add('success','Une nouvelle version à été créer en brouillon.
                    Effectuer vos modifiaction et publiez la, pour que celle çi soit consultable par les autres membres.
                    Si vous n\'avez pas terminé vos modifications, elles sont sauvgardés, ainsi vous pourrez reprendre plus tard.');
            }
            
        } else {
            // If there is a draft but you'r not the author
            $user = $this->getUser();
            if(!($user === $trick->getContributor())) {
                $request->getSession()->getFlashBag()->add('warning','Une modification est déjà en cours pour ce trick, vous pouvez la consulter en lecture seul.');
                $request->getSession()->getFlashBag()->add('error','Une modification est déjà en cours pour ce trick, vous pouvez la consulter en lecture seul.');
                
                return $this->redirect('/trick/'.$trick->getId(),301);
            } else {
                // There is a draft and you are the author so let's update it !
                $draft_trick = $tricks_manager->getDraftIfExists($trick);
                $trick = $tricks_manager->trickWithTree($draft_trick);
            }
        }
        
        return $this->render('trick/show_update.html.twig', [
            'title' => 'Zoom sur ' . $trick['title'],
            'trick' => $trick,
        ]);
    }
}
