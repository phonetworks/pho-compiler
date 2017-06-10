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

use Pho\Lib\GraphQL\Parser;
use Pho\Compiler\Prototypes\PrototypeInterface;

class FileAnalyzer extends AbstractAnalyzer   {

    public static function process(/*string*/ $file, PrototypeInterface $prototypes): void
    {
        try {
            $ast = new Parser\Parse($file);
        } catch(\Exception $e) {
            throw $e;
        }
        $entities = $ast->entities();
        foreach($entities as $entity) {
            EntityAnalyzer::process($entity, $prototypes);
        }
    }

}