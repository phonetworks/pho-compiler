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

    protected static function unitProcess(Field $field, PrototypeInterface $prototype): void
    {
        $field_name = strtolower($field->name());
        //$constraints = FieldDirectiveAnalyzer::process($field->directives(), $prototype);
        $constraints = [];
        $prototype->addField($field_name, $field->type(), (bool) $field->nullable(), (bool) $field->list(), (bool) $field->native(), $constraints);
    }

}