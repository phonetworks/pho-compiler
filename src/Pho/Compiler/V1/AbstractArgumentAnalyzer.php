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
use Pho\Lib\GraphQL\Parser\Definitions\Argument;

abstract class AbstractArgumentAnalyzer extends AbstractAnalyzer
{

    protected static $argument_properties;
    protected static $prototype_functions;

    public static function process(/*array*/ $arguments, PrototypeInterface $prototype): void
    {
        array_walk(
            $arguments, function (Argument $arg) use ($prototype) {
                self::unitProcess($arg, $prototype);
            }
        ); 
    }

    protected static function unitProcess(Argument $arg, PrototypeInterface $prototype): void
    {
        $arg_name = strtolower($arg->name());
        if(array_key_exists($arg_name, static::$prototype_functions)) {
            $func = static::$prototype_functions[$arg_name];
            $prototype->$func($arg->value());
        }
    }
}