<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Compiler\Prototypes;

class Entity implements PrototypeInterface {

    protected $name;
    protected $fields = [];

    public function __call(string $method, array $args) //: mixed
    {
        if(strlen($method)>3 && subst($method, 0, 3)=="set") {
            $this->setter(
                substr($method, 3), 
                $args[0]
            );
        }
        // else log?
    }

    protected function setter(string $property, /* mixed */ $value): void
    {
        $property = s($property)->underscored();
        if($property=="fields") {
            throw new \Exception(sprintf("setFields is not a valid method in the class %s", get_class($this)));
        }
        $ref = new \ReflectionObject($this);
        try {
            $ref->getProperty($property);
        }
        catch(\ReflectionException $r) {
            return; // maybe log this incident (with warning) in the future.
        }
        $this->$property = $value;
    }

    public function addField(string $name, string $type, bool $is_nullable): void
    {
        $this->fields[] = [
            "name"=>$name,
            "type"=>$type,
            "nullable"=>$is_nullable
        ];
    }

}