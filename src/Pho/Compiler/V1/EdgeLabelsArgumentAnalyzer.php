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

class EdgeLabelsArgumentAnalyzer extends AbstractArgumentAnalyzer
{

    protected static $prototype_functions = [
        "headsingular"=>"setLabelHeadSingular", 
        "tailsingular"=>"setLabelTailSingular",
        "headplural"=>"setLabelHeadPlural", 
        "tailplural"=>"setLabelTailPlural",
        "headcallablesingular"=>"setLabelHeadCallableSingular",
        "headcallableplural"=>"setLabelHeadCallablePlural",
        "tailcallablesingular"=>"setLabelTailCallableSingular",
        "tailcallableplural"=>"setLabelTailCallablePlural",
        "feedsimple"=>"setLabelSimpleFeed",
        "feedaggregated"=>"setLabelAggregatedFeed"
    ];

}