<?php

namespace Pho\Compiler\V1;

//use Pho\Compiler\Definitions as CompilerDefinitions;
use Pho\Lib\GraphQL\Parser\Definitions;
use Pho\Compiler\Prototypes;

class EntityAnalyzer {

    private $name;
    private $type;

    public function __construct(Definitions\Entity $entity) {
        //$prototype = new Prototypes\Node();
        //$define->name = $node->name();
        $implements = $entity->implementation(0);
        
        switch($implements->name()) {
            case "ObjectNode":
                $prototype = new Prototypes\ObjectNode();
        }
        
    }

}