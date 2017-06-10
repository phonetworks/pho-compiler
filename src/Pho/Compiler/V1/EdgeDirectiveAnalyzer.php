<?php

namespace Pho\Compiler\V1;

use Pho\Compiler\Prototypes\PrototypeInterface;
use Pho\Lib\GraphQL\Parser\Definitions\Directive;

class EdgeDirectiveAnalyzer {

    public static function process(PrototypeInterface $prototype, array $directives): void
    {
        array_walk($directives, function(Directive $directive) use ($prototype) {
            self::_unitProcess($prototype, $directive);
        }); 
    }

    protected static function _unitProcess(PrototypeInterface $prototype, Directive $directive): void
    {
        $directive_name = strtolower($directive->name());
        if(in_array($directive_name, ["nodes", "properties"])) {
            $class = sprintf("Edge%sArgumentAnalyzer", ucfirst($directive_name));
            $class::process($prototype, $directive->arguments());
        }
    }

}