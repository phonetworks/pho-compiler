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
 * Thrown when node and edge definitions mismatch.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class NodeEdgeMismatchImparityException extends AbstractCorruptParityException
{
    /**
     * Constructor.
     * 
     * @param string $asset Path to the directory or file causing the mismatch.
     */
    public function __construct(string $asset)
    {
        parent::__construct();
        $this->message = sprintf("Node/Edge definitions mismatch at %s", $asset);
    }
}