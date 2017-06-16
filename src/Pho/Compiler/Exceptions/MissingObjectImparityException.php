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
 * Thrown when there is no Object node defined.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class MissingActorImparityExceptions extends AbstractCorrupParityException
{
    /**
     * Constructor.
     */
    public function __construct(string $dir)
    {
        parent::__construct();
        $this->message = sprintf("The directory %s does not contain an object node.", $dir);
    }
}