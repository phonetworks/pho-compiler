<?php

namespace Pho\Compiler\V1;

use Pho\Compiler\Prototypes\PrototypeInterface;
use Pho\Lib\GraphQL\Parser\Definitions\Argument;

class EdgeNodesArgumentAnalyzer {

    public static function process(PrototypeInterface $prototype, array $arguments): void
    {
        array_walk($arguments, function(Argument $arg) use ($prototype) {
            self::_unitProcess($prototype, $arg);
        }); 
    }

    protected static function _unitProcess(PrototypeInterface $prototype, Argument $arg): void
    {
        $arg_name = strtolower($arg->name());
        if(in_array($arg_name, ["head", "tail"])) {
            $func = $arg_name."Nodes";
            $prototype->$func($arg->value());
        }
    }

}