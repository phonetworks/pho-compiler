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

class FieldAnalyzer extends AbstractAnalyzer {

    public static function process(/*array*/ $fields, ?PrototypeInterface $prototype): void
    {
        if(is_null($prototype)) throw new PrototypeRequiredException(__CLASS__);
        array_walk($fields, function(Field $field) use ($prototype) {
            self::unitProcess($field, $prototype);
        }); 
    }

    protected static function unitProcess(Field $field, PrototypeInterface $prototype): void
    {
        $field_name = strtolower($field->name());
        $prototype->addField($field->name(), $field->type(), $field->nullable());
        
    }

}