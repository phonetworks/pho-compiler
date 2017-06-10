<?php

namespace Pho\Compiler\V1;

class NodePermissionsArgumentAnalyzer extends AbstractArgumentAnalyzer {

    protected static $argument_properties = ["mod", "mask"];
    protected static $prototype_functions = [
        "mod"=>"setMod", 
        "mask"=>"setMask"
    ];

}