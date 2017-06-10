<?php

namespace Pho\Compiler\V1;

use Pho\Compiler\Prototypes\PrototypeInterface;
use Pho\Lib\GraphQL\Parser\Definitions\Directive;
use Pho\Compiler\Exceptions\PrototypeRequiredException;

class NodeDirectiveAnalyzer extends AbstractDirectiveAnalyzer  {

/*
    public static function process(array $directives, ?PrototypeInterface $prototype): void
    {
        if(is_null($prototype)) throw new PrototypeRequiredException($prototype, __CLASS__);
        array_walk($directives, function(Directive $directive) use ($prototype) {
            self::_unitProcess($prototype, $directive);
        }); 
    }

    protected static function _unitProcess(Directive $directive, ?PrototypeInterface $prototype): void
    {
        $directive_name = strtolower($directive->name());
        if(in_array($directive_name, ["edges", "permissions", "properties"])) {
            $class = sprintf("Node%sArgumentAnalyzer", ucfirst($directive_name));
            $class::process($prototype, $directive->arguments());
        }
    }
*/

}