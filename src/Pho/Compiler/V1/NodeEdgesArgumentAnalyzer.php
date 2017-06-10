<?php

namespace Pho\Compiler\V1;

class NodeEdgesArgumentAnalyzer extends AbstractArgumentAnalyzer {

    protected static $argument_properties = ["in", "out"];
    protected static $prototype_functions = [
        "in"=>"setIncomingEdges", 
        "out"=>"setOutgoingEdges"
    ];

}