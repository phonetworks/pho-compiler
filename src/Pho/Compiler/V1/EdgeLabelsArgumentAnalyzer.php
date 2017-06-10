<?php

namespace Pho\Compiler\V1;

class EdgeLabelsArgumentAnalyzer extends AbstractArgumentAnalyzer {

    protected static $argument_properties = ["headSingular", "tailSingular", "headPlural", "tailPlural"];
    protected static $prototype_functions = [
        "headSingular"=>"setLabelHeadSingular", 
        "tailSingular"=>"setLabelTailSingular",
        "headPlural"=>"setLabelHeadPlural", 
        "tailPlural"=>"setLabelTailPlural",
    ];

}