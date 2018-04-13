<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Compiler\V1;

class NodeFeedArgumentAnalyzer extends AbstractArgumentAnalyzer
{

    protected static $prototype_functions = [
        "simpleUpdate"=>"setSimpleFeedUpdate", 
        "aggregatedUpdate"=>"setAggregatedFeedUpdate"
    ];

}