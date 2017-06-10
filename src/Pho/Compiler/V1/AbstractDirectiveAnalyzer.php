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
use Pho\Compiler\Exceptions\PrototypeRequiredException;

abstract class AbstractDirectiveAnalyzer extends AbstractAnalyzer {

    public static function process(array $directives, ?PrototypeInterface $prototype): void
    {
        if(is_null($prototype)) throw new PrototypeRequiredException(__CLASS__);
        array_walk($directives, function(Directive $directive) use ($prototype) {
            self::unitProcess($prototype, $directive);
        }); 
    }

    protected static function _getEntityType(): string
    {
        $ref = new \ReflectionObject($this);
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
    }

    protected static function unitProcess(Directive $directive, PrototypeInterface $prototype): void
    {
        $entity_type = self::_getEntityType();
        $directive_name = strtolower($directive->name());
        if(in_array($directive_name, self::_getDirectiveArguments($entity_type))) {
            $class = sprintf("%s%sArgumentAnalyzer", $entity_type, ucfirst($directive_name));
            $class::process($prototype, $directive->arguments());
        }
    }

}