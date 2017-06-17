<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Compiler\Exceptions;

/**
 * Thrown when the destination diretory is not writeable.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class DestinationNotWriteableException extends \Exception
{
    public function __construct(string $dir)
    {
        parent::__construct();
        $this->message = sprintf("The destination %s is not writeable.", 
                                $dir);
    }
}