<?php

namespace App\Trick\Domain;

use App\_Core\Domain\EntityManager;
use App\Trick\Domain\Entity\Trick;
use Doctrine\Common\Collections\Criteria;

class TricksManager extends EntityManager {
    
    public function getTricksPreview(string $offset): array
    {
        $repo = $this->em->getRepository(Trick::class);
        $segment = $repo->findBy(['state' => 'published'], null, 4, 4 * $offset);
        
        $tricks = [];
        foreach($segment as $trick) {
            
            $medias = $trick->getMedias();
            $criteria = Criteria::create()->where(Criteria::expr()->eq("type", "img_preview"));
            $preview_img = $medias->matching($criteria)->first();
            if(!is_bool($preview_img)) {
                $url = $preview_img->getUrl();
            }
            $tricks[] = [
                'id' => $trick->getId(),
                'title' => $trick->getTitle(),
                'slug' => $trick->getSlug(),
                'preview_path' => isset($url) ? $url : '../assets/images/contest.webp',
                ];
        }
    
        return $tricks;
    }
    
}
