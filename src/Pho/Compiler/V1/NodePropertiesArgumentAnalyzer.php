<?php

namespace Pho\Compiler\V1;

class NodePropertiesArgumentAnalyzer extends AbstractArgumentAnalyzer {

    protected static $argument_properties = ["expires", "volatile", "revisionable"];
    protected static $prototype_functions = [
        "expires"=>"setExpires", 
        "volatile"=>"setVolatile",
        "revisionable"=>"setRevisionable"
    ];

}