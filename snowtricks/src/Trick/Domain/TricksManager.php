<?php

namespace App\Trick\Domain;

use App\_Core\EntityManager;
use App\Media\Domain\Entity\Media;
use App\Trick\Domain\Entity\Trick;
use App\Trick\Domain\Exception\CannotUpdateOlderVersionException;
use App\Trick\Domain\Exception\DraftVersionAlreadyExistsException;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;
use phpDocumentor\Reflection\Types\Iterable_;
use Psr\Http\Message\ServerRequestInterface;

class TricksManager extends EntityManager {
    
    public function getHomeSample(string $offset): array
    {
        $repo = $this->em->getRepository(Trick::class);
        $segment = $repo->findBy(['state' => 'current'], ['id' => 'DESC'], 8, 8 * $offset);
        
        $tricks = [];
        foreach($segment as $trick) {
            
            $medias = $trick->getMedias();
            //Todo change type from img to img_preview
            $criteria = Criteria::create()->where(Criteria::expr()->eq("type", "img"));
            $preview_img = $medias->matching($criteria)->first();
            if($preview_img instanceof Media) {
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
    
    public function trickWithTree(Trick $trick): array
    {
        $repo = $this->em->getRepository(Trick::class);
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
        
        // Merge trick with his tree TODO improve number of request
        return array_merge($this->showTrick($trick), $family_tree);
    }
    //Check if the trick version is the current one
    public function isCurrentVersion(Trick $trick): bool
    {
        if('current' === $trick->getState()){
            return true;
        }
        throw new CannotUpdateOlderVersionException();
    }
    
    //Check if the trick to update already has a draft
    public function alreadyHasDraft(Trick $trick): bool
    {
        $all_version_expect_first = $trick->getParent()->getChildren();
        foreach($all_version_expect_first as $version) {
            if($version->getState() === 'draft')
                return true;
        }
        
        return false;
    }
    
    //Retrieve the draft version of this trick
    public function getDraftIfExists(Trick $trick): Trick | bool
    {
        $all_version_expect_first = $trick->getParent()->getChildren();
        
        foreach($all_version_expect_first as $version){
            if($version->getState() === 'draft') {
                return $version;
            }
        }
        
        return false;
        
    }
    
    public function showTrick(Trick $trick): array
    {
        $repo = $this->em->getRepository(Trick::class);
        
        // Retrieve media split by type img & mov
        $medias = $trick->getMedias();
        $criteria = Criteria::create()->where(Criteria::expr()->eq("type", "img"));
        $img = $medias->matching($criteria);
        $criteria = Criteria::create()->where(Criteria::expr()->eq("type", "mov"));
        $mov = $medias->matching($criteria);
        
        $path = $img->first() !== false ? $img->first()->getPath() : '/images/home_page.webp' ;
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
            'preview_path' => $path,
            'created_at' => $trick->getCreatedAt(),
        ];
        return $prepared_trick;
    }
    
    public function getCurrentVersion(Trick $trick): Trick {
        $legacy_trick = $trick->getParent();
        if($legacy_trick->getState() === 'current')
            return $legacy_trick;
        else {
            $others_versions = $legacy_trick->getChildren();
            foreach($others_versions as $version) {
                if($version->getState() === 'current') {
                    return $version;
                }
            }
        }
    }
    
    public function cloneMedias(PersistentCollection $medias, Trick $trick): array
    {
        $cloned_medias = [];
        foreach($medias as $media) {
            $clone = clone $media;
            $clone->setTrick($trick);
            $cloned_medias[] = $clone;
        }
        
        return $cloned_medias;
    }
    
    public function remove(Trick $trick, User $user): Trick|bool
    {
        
        if(!($trick->getState() === 'draft')) {
            $return = false;
        } else {
            $parent = $trick->getParent();
            // Retrieve the the real parent of draft
            if ( ((int)$trick->getVersion() - 1) === (int)$parent->getVersion() ) {
                $return = $parent;
            } else {
                $tricks = $trick->getParent()->getChildren();
                if(count($tricks) > 1) {
                    $return = $tricks[count($tricks) - 2];
                }
            }
            if ($trick->getContributor() !== $user) {
                $return = false;
            } else {
                $this->em->remove($trick);
                $this->em->flush();
            }
        }
        
        return $return;
    }
    
}
