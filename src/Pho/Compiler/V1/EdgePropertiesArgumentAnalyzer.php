<?php

namespace Pho\Compiler\V1;

class EdgePropertiesArgumentAnalyzer extends AbstractArgumentAnalyzer {

    protected static $argument_properties = ["binding"];
    protected static $prototype_functions = [
        "binding"=>"setBinding"
    ];

}
