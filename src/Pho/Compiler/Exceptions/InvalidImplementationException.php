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
 * Thrown when the implemented interface is not recognizable.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class InvalidImplementationException extends \Exception
{
    public function __construct(int $version, string $interface)
    {
        parent::__construct();
        $this->message = sprintf("The interface \"%s\" is not recognized in Pho Compiler v%s", 
                                $interface, (string) $version);
    }
}