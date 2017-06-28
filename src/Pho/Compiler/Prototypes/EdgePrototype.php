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

class EdgePrototype extends EntityPrototype
{

    protected $binding;
    protected $persistent;
    protected $consumer;
    protected $notifier;
    protected $subscriber;
    
    protected $head_nodes;
    protected $tail_nodes;

    protected $label_head_singular;
    protected $label_tail_singular;
    
    protected $label_head_plural;
    protected $label_tail_plural;


}