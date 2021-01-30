<?php

namespace App\Trick\Domain;

use App\_Core\Domain\EntityManager;
use App\Trick\Domain\Entity\Trick;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;

class TricksManager extends EntityManager {
    
    public function currentVersions(string $offset): array
    {
        $repo = $this->em->getRepository(Trick::class);
        // TODO rename state in current
        $segment = $repo->findBy(['state' => 'published'], null, 8, 8 * $offset);
        
        $tricks = [];
        foreach($segment as $trick) {
            
            $medias = $trick->getMedias();
            //Todo change type from img to img_preview
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
    
    public function trickWithTree(string $id): array
    {
        $repo = $this->em->getRepository(Trick::class);
        $trick = $repo->find($id);
        $family_tree = [];
        // get the entire family tree with version information
        $raw_family_tree = $trick->getParent()->getChildren();
        $raw_family_tree->add($trick->getParent());
        $raw_family_tree->map(function($version) use (&$family_tree) {
            $date = Carbon::parse(new Carbon($version->getCreatedAt()));
            $family_tree[$version->getVersion()-1] = [
                'id' => $version->getId(),
                'version' => $version->getVersion(),
                'state' => $version->getState(),
                'created_at' => $date->toDateString(),
                'contributor' => $version->getContributor(),
            ];
        });
        ksort($family_tree);
        $family_tree = ['family_tree' => $family_tree];
        
        // Merge trick wi his tree
        return array_merge($this->trick($id), $family_tree);
    }
    
    public function trick(string $id): array
    {
        $repo = $this->em->getRepository(Trick::class);
        $trick = $repo->find($id);
    
        // Retrieve media split by type img & mov
        $medias = $trick->getMedias();
        $criteria = Criteria::create()->where(Criteria::expr()->eq("type", "img"));
        $img = $medias->matching($criteria);
        $criteria = Criteria::create()->where(Criteria::expr()->eq("type", "mov"));
        $mov = $medias->matching($criteria);
    
        // Prepare trick to being displayed
        $prepared_trick = [
            'id' => $trick->getId(),
            'version' => $trick->getVersion(),
            'title' => $trick->getTitle(),
            'state' => $trick->getState(),
            'slug' => $trick->getSlug(),
            'img' => $img,
            'mov' => $mov,
            'description' => $trick->getDescription(),
            'contributor' => $trick->getContributor(),
            'preview_path' => isset($path) ? $path : '/build/images/home_page.webp',
            'created_at' => $trick->getCreatedAt(),
        ];
    
        return $prepared_trick;
    }
    
    public function extractCurrentVersion(Trick $trick) {
    
    }
    
}
