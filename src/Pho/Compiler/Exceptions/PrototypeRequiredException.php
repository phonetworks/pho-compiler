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
 * Thrown when an analyzer is given no prototype object to push its changes.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class PrototypeRequiredException extends \Exception
{
    public function __construct(string $class)
    {
        parent::__construct();
        $this->message = sprintf("A prototype object is required by %s.", $class);
    }
}