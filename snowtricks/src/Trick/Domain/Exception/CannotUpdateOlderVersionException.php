<?php


namespace App\Trick\Domain\Exception;

use Symfony\Component\Security\Core\Exception\RuntimeException;

/**
 * This exception is thrown when the csrf token is invalid.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Alexander <iam.asm89@gmail.com>
 */
class CannotUpdateOlderVersionException extends RuntimeException
{
    
    public function getMessageKey(): string
    {
        return 'Vous ne pouvez pas modifier une version antèrieur à la version courrante.';
    }
}
