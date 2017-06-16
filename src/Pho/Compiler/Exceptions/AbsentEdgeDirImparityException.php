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
 * Thrown when there is no Edge directory associated with the given node.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class AbsentEdgeDirImparityException extends AbstractCorrupParityException
{
    /**
     * Constructor.
     */
    public function __construct(string $edge_dir, string $node)
    {
        parent::__construct();
        $this->message = sprintf("The node %s was supposed to have an edge directory at %s.", $node, $edge_dir);
    }
}