<?php

namespace Pho\Compiler\V1;

use Pho\Compiler\Prototypes\PrototypeInterface;
use Pho\Lib\GraphQL\Parser\Definitions\Directive;

class NodeDirectiveAnalyzer {

    public static function process(PrototypeInterface $prototype, array $directives): void
    {
        array_walk($directives, function(Directive $directive) use ($prototype) {
            self::_unitProcess($prototype, $directive);
        }); 
    }

    protected static function _unitProcess(PrototypeInterface $prototype, Directive $directive): void
    {
        switch($directive->name()) {
            case "edges":
            case "permissions":
        }
    }

}