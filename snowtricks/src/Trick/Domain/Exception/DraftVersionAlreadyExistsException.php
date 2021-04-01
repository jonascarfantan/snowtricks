<?php


namespace App\Trick\Domain\Exception;

use Symfony\Component\Security\Core\Exception\RuntimeException;

/**
 * This exception is thrown when the csrf token is invalid.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Alexander <iam.asm89@gmail.com>
 */
class DraftVersionAlreadyExistsException extends RuntimeException
{
    
    public function getMessageKey(): string
    {
        return 'Cette version est déjà en cours de modification par un autre membre, veuillez patienter qu\'il termine avant de réessayer.';
    }
}
