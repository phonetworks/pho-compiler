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
 * Thrown when the PhoGQL file does not have a must-have key.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class MissingValueException extends \Exception
{
    /**
     * Constructor.
     */
    public function __construct(string $key)
    {
        parent::__construct();
        $this->message = sprintf("The PGQL file is missing a must-have key: %s", $key);
    }
}