<?php

namespace App\Trick\Action;

use App\_Core\Trait\Manager;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Exception\CannotUpdateOlderVersionException;
use App\Trick\Domain\Repository\TrickRepository;
use App\Trick\Domain\TricksManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CloneBeforeUpdate extends AbstractController {
    use Manager;
    
    #[Route(path: '/tricks/{slug}/update', name: 'show.update.trick', methods: ['GET'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        TrickRepository $trick_repository,
        Trick $trick
    ): Response {
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        
        $user = $this->getUser();
        $tricks_manager = new TricksManager([Trick::class], $em);
        try {
            // If there is no draft for this trick yet, we can create one
            if(!$tricks_manager->alreadyHasDraft($trick)) {
    
                // If this trick is the current one we clone it has a draft ready to be updated
                if($tricks_manager->isCurrentVersion($trick)) {
                    
                    $ancestor = $trick->getParent();
                    $draft_trick = clone $trick;
                    $draft_trick->setState('draft')
                                ->setSlug(Manager::slugable($trick->getTitle(), ((int)$trick->getVersion()+1)) )
                                ->setParent($ancestor)
                                ->setContributor($user)
                                ->setVersion((int)$trick->getVersion() + 1);
                    $tricks_manager->cloneMedias($trick->getMedias(), $draft_trick);
                    $em->persist($draft_trick);
                    $em->flush();
                    $request->getSession()->getFlashBag()
                        ->add('success','Une nouvelle version à été créer en brouillon.
                    Effectuer vos modifiaction et publiez la, pour que celle çi soit consultable par les autres membres.
                    Si vous n\'avez pas terminé vos modifications, elles sont sauvgardés, ainsi vous pourrez reprendre plus tard.');
                    
                    return $this->redirect('/tricks/'.$draft_trick->getSlug().'/update');
                }
            } else {
                // If there is a draft but you'r not the author
                if( !($tricks_manager->isContributor(user: $user,trick: $tricks_manager->getDraftIfExists($trick))) ) {
                    $request->getSession()->getFlashBag()->add('warning','Une modification est déjà en cours pour ce trick, vous pouvez la consulter en lecture seul.');
            
                    return $this->redirect('/tricks/'.$trick->getSlug());
                } else {
                    // There is a draft and you are the author so let's update it !
                    $draft_trick = $tricks_manager->getDraftIfExists($trick);
                    $trick = $tricks_manager->trickWithTree($draft_trick);
                }
            }
        } catch(CannotUpdateOlderVersionException $e) {
            $request->getSession()->getFlashBag()->add('error', $e->getMessage());
        }
        
        return $this->render('trick/show_update.html.twig', [
            'title' => 'Zoom sur ' . $trick['title'],
            'trick' => $trick,
        ]);
    }
}
