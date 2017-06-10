<?php

namespace Pho\Compiler\V1;

class EdgeNodesArgumentAnalyzer extends AbstractArgumentAnalyzer {

    protected static $argument_properties = ["head", "tail"];
    protected static $prototype_functions = [
        "head"=>"setHeadNodes", 
        "tail"=>"setTailNodes"
    ];

}