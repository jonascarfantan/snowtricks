<?php

namespace App\Trick\Domain;

use App\_Core\Domain\EntityManager;
use App\Trick\Domain\Entity\Trick;
use Doctrine\Common\Collections\Criteria;

class TricksManager extends EntityManager {
    
    public function getTricksPreview(string $offset): array
    {
        $repo = $this->em->getRepository(Trick::class);
        $segment = $repo->findBy(['state' => 'published'], null, 8, 8 * $offset);
        
        $tricks = [];
        foreach($segment as $trick) {
            
            $medias = $trick->getMedias();
            $criteria = Criteria::create()->where(Criteria::expr()->eq("type", "img"));
            $preview_img = $medias->matching($criteria)->first();
            if(!is_bool($preview_img)) {
                $path = $preview_img->getPath();
            }
            $tricks[] = [
                'id' => $trick->getId(),
                'title' => $trick->getTitle(),
                'slug' => $trick->getSlug(),
                'preview_path' => isset($path) ? $path : '/build/images/home_page.webp',
                ];
        }
    
        return $tricks;
    }
    
}
