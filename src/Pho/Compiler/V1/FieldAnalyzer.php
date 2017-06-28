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

use Pho\Compiler\Prototypes\PrototypeInterface;
use Pho\Lib\GraphQL\Parser\Definitions\Field;

class FieldAnalyzer extends AbstractAnalyzer
{

    public static function process(/*array*/ $fields, PrototypeInterface $prototype): void
    {
        array_walk(
            $fields, function (Field $field) use ($prototype) {
                self::unitProcess($field, $prototype);
            }
        ); 
    }

    protected static function _checkFieldDirectives(array $directives): array
    {
        $constraints = [
            "minLength" => null, // string
            "maxLength" => null, // string
            "uuid" => null, // string
            "regex" => null, // string
            "greaterThan" => null, // int
            "lessThan" => null, // int
        ];

        foreach($directives as $directive) {
            if($directive->name()=="constraints") {
                foreach($directive->arguments() as $arg) {
                    if(array_key_exists($arg->name(), $constraints)) {
                        $constraints[$arg->name()] = $arg->value();
                    }
                }     
                break;
            }
        }

        return $constraints;

    }

    protected static function unitProcess(Field $field, PrototypeInterface $prototype): void
    {
        $field_name = strtolower($field->name());
        $constraints = self::_checkFieldDirectives($field->directives());
        $prototype->addField($field_name, $field->type(), (bool) $field->nullable(), (bool) $field->list(), (bool) $field->native(), $constraints);
    }

}