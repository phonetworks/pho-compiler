<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Compiler\V1;

use Pho\Lib\GraphQL\Parser\Definitions;
use Pho\Compiler\Prototypes;
use Pho\Compiler\Prototypes\PrototypeInterface;

class EntityAnalyzer extends AbstractAnalyzer {


    public static function process(/*Definitions\Entity*/ $entity, ?PrototypeInterface $prototype = null): void 
    {
        $interface = $entity->implementation(0);
        try {
            $type = self::getEntityType($interface);
        }
        catch(Exceptions\InvalidImplementationException $e) {
            throw $e;
        }
        $prototype = self::formPrototype($type);
        $prototype->setName($entity->name());
        $directive_analyzer = $type."DirectiveAnalyzer";
        if(class_exists($directive_analyzer)) {
            $directive_analyzer::process($entity->directives(), $prototype);
        }
        else {
            throw new Exceptions\InvalidImplementationException(
                self::VERSION, 
                $type
            );
        }
        FieldAnalyzer::process($entity->fields(), $prototype);
    }

    protected static function formPrototype(string $type): PrototypeInterface
    {
        $class = "Prototypes\\".$type;
        if(class_exists($class)) {
            return new $class();
        }
        else {
            throw new Exceptions\InvalidImplementationException(
                self::VERSION, 
                $type
            );
        }
    }

    protected static function getEntityType(string $interface): string
    {
        $type = substr($interface, -4);
        if($type=="Edge"||$type=="Node")
            return $type;
        else
            throw new Exceptions\InvalidImplementationException($interface, $type);
    }

}