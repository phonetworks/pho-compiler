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
 * Thrown when the prototype is not of known type (node or edge).
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class UnknownPrototypeException extends \Exception
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->message = sprintf("Unknown prototype.");
    }
}