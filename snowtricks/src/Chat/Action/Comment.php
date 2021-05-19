<?php

namespace App\Chat\Action;

use App\_Core\EntityManager;
use App\_Core\Trait\Manager;
use App\Chat\Domain\Entity\Message;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Repository\TrickRepository;
use App\Trick\Domain\TricksManager;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class Comment extends AbstractController {
    use Manager;
    
    #[Route(path: '/tricks/{slug}/comment', name: 'comment.trick', methods: ['POST'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        Trick $trick
    ): Response {
        if( ($redirect = $this->redirectUnauthenticated($request)) instanceof Response ) {
            return $redirect;
        }
        
        $user = $this->getUser();
        $carbon = new Carbon();
        $now = $carbon->now();
        $token = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('comment', $token)) {
            $comment = new Message();
            $comment->setSpeaker($user)
                ->setContent($request->request->get('content'))
                ->setTrick($trick)
                ->setCreatedAt($now);
            $em->persist($comment);
            $em->flush();
            
        }
        $tricks_manager = new TricksManager([Trick::class], $em);
        $trick = $tricks_manager->trickWithTree($trick);
        return $this->render('trick/show.html.twig', [
            'title' => 'Zoom sur ' . $trick['title'],
            'trick' => $trick,
        ]);
    }
}
