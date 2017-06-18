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
use Pho\Lib\GraphQL\Parser;
use Pho\Compiler\Prototypes\PrototypeInterface;

class FileAnalyzer extends AbstractAnalyzer
{

    public static function process(/*string*/ $file, PrototypeInterface $prototypes): void
    {
        try {
            $ast = new Parser\Parse($file);
        } catch(\Exception $e) {
            throw $e;
        }
        $entities = iterator_to_array($ast->entities());
        Compiler::logger()->info(sprintf("%d entities found.", sizeof($entities)));
        foreach($entities as $entity) {
            Compiler::logger()->info(sprintf("Entity name: %s.", $entity->name()));
            EntityAnalyzer::process($entity, $prototypes);
        }
    }

}