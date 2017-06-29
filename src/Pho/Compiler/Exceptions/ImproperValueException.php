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
 * Thrown when the PhoGQL file holds an erroneous value.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class ImproperValueException extends \Exception
{
    /**
     * Constructor.
     */
    public function __construct(string $key)
    {
        parent::__construct();
        $this->message = sprintf("The value of the key %s was improperly set.", $key);
    }
}