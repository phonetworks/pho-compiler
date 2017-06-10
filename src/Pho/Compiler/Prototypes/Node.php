<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Compiler\Prototypes;

class Node extends Entity {

    protected $incoming_edges;
    protected $outgoing_edges;
    protected $mod;
    protected $mask;
    protected $expires;
    protected $volatile;
    protected $revisionable;

}