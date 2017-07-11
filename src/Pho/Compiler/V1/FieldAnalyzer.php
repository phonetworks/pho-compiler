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

use Pho\Compiler\Compiler;
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
        $dirs = [
            "md5" => false,
            "now" => false,
            "default" => Compiler::NO_VALUE_SET
        ];

        $constraints = [
            "minLength" => null, // string
            "maxLength" => null, // string
            "uuid" => null, // string
            "regex" => null, // string
            "greaterThan" => null, // int
            "lessThan" => null, // int
        ];

        foreach($directives as $directive) {
            switch($directive->name()) {
                case "constraints":
                    foreach($directive->arguments() as $arg) {
                        if(array_key_exists($arg->name(), $constraints)) {
                            $constraints[$arg->name()] = $arg->value();
                        }
                    }     
                    break;
                case "md5":
                case "now":
                    $dirs[$directive->name()] = true;
                    break;
                case "default":
                    switch(strtolower($directive->argument(0)->name())) {
                        case "null":
                            $dirs["default"] = null;
                            break;
                        case "int":
                            $dirs["default"] = (int) $directive->argument(0)->value();
                            break;
                        case "boolean":
                            $dirs["default"] = (bool) $directive->argument(0)->value();
                            break;
                        case "float":
                            $dirs["default"] = (float) $directive->argument(0)->value();
                            break;
                        case "string":
                        default:
                            $dirs["default"] = (string) $directive->argument(0)->value();
                            break;
                    }
                    break;
            }
        }

        return [
            $constraints,
            $dirs
        ];

    }

    protected static function unitProcess(Field $field, PrototypeInterface $prototype): void
    {
        $field_name = strtolower($field->name());
        [$constraints, $directives] = self::_checkFieldDirectives($field->directives());
        $prototype->addField($field_name, $field->type(), (bool) $field->nullable(), (bool) $field->list(), (bool) $field->native(), $constraints, $directives);
    }

}