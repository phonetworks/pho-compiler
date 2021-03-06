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
use Pho\Lib\GraphQL\Parser\Definitions\Directive;

abstract class AbstractDirectiveAnalyzer extends AbstractAnalyzer
{

    public static function process(/*array*/ $directives, PrototypeInterface $prototype): void
    {
        //print_r($directives);
        array_walk(
            $directives, function (Directive $directive) use ($prototype) {
                self::unitProcess($directive, $prototype);
            }
        ); 
    }

    protected static function _getEntityType(): string
    {
        $ref = new \ReflectionClass(get_called_class());
        return str_replace("DirectiveAnalyzer", "", $ref->getShortName());
    }

    protected static function _getDirectiveArguments(string $entity_type): array
    {
        $res = [];
        $file_pattern = sprintf("/^%s([A-Z][a-z]+)ArgumentAnalyzer\.php$/", $entity_type);
        $dir_contents = scandir(dirname(__FILE__));
        foreach($dir_contents as $file) {
            if(preg_match($file_pattern, $file, $matches)) {
                $res[] = strtolower($matches[1]);
            }
        }
        return $res;
    }

    protected static function unitProcess(Directive $directive, PrototypeInterface $prototype): void
    {
        $entity_type = self::_getEntityType();
        $directive_name = strtolower($directive->name());
        if(in_array($directive_name, self::_getDirectiveArguments($entity_type))) {
            $class = sprintf("%s\\%s%sArgumentAnalyzer", __NAMESPACE__, $entity_type, ucfirst($directive_name));
            $class::process($directive->arguments(), $prototype);
        }
    }

}