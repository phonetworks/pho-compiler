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
    protected $multiplicable;
    protected $persistent;
    protected $consumer;
    protected $notifier;
    protected $subscriber;
    protected $formative;
    
    protected $head_nodes;
    protected $head_nodes_only;
    protected $tail_node;

    protected $label_head_singular;
    protected $label_tail_singular;
    
    protected $label_head_plural;
    protected $label_tail_plural;

    protected $label_head_callable_singular;
    protected $label_tail_callable_singular;
    protected $label_head_callable_plural;
    protected $label_tail_callable_plural;

    protected $label_simple_feed;
    protected $label_aggregated_feed;


}