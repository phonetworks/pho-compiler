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

// webmozart/assert
class FieldDirectiveAnalyzer extends AbstractAnalyzer
{

    protected static $constraints;

    public static function process(/*array*/ $directives, PrototypeInterface $prototype /* unused */): array
    {
        self::reset();
        foreach($directives as $directive) {
            if($directive->name()=="constraints") {
                self::analyzeArguments($directive->arguments());
                break;
            }
        }
        return self::$constraints;
    }

    protected static function reset(): void
    {
        self::$constraints = [
            "minLength" => null, // string
            "maxLength" => null, // string
            "uuid" => null, // string
            "regex" => null, // string
            "greaterThan" => null, // int
            "lessThan" => null, // int
        ];
    }

    protected static function analyzeArguments(array $args): void
    {
        foreach($args as $arg) {
            if(array_key_exists($arg->name(), self::$constraints)) {
                self::$constraints[$arg->name()] = $arg->value();
            }
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
    }

}