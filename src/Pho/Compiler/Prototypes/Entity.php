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
    protected $subtype; // actor, object, graph or transmit, subscribe, write, read etc.
    protected $fields = [];
    protected $type;
    protected $ref;

    public function __construct() {
        $this->ref = new \ReflectionObject($this);
        $this->type = strtolower($this->ref->getShortName());
    }

    public function __call(string $method, array $args) //: mixed
    {
        if(strlen($method)>3 && substr($method, 0, 3)=="set") {
            $this->setter(
                substr($method, 3), 
                $args[0]
            );
        }
        // else log?
    }

    protected function setter(string $property, /* mixed */ $value): void
    {
        //echo "starting: ".$property."\n";
        $original_property = $property;
        $property = \Stringy\StaticStringy::underscored($property);
        if($property=="fields"||$property=="type"||$property=="ref") {
            throw new \Exception(sprintf("set%s is not a valid method in the class %s", $original_property, get_class($this)));
        }
        $ref = new \ReflectionObject($this);
        try {
            $ref->getProperty($property);
        }
        catch(\ReflectionException $r) {
            //echo "Unknown property: ".$property;
            return; // maybe log this incident (with warning) in the future.
        }
        //echo "here i am: ".$property."\n\n";
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

    public function toArray(): array 
    {
        $props = $this->ref->getProperties(\ReflectionProperty::IS_PROTECTED);
        $res = [];
        array_walk($props, function($prop) use (&$res) {
            $key = $prop->getName();
            if($key=="ref")
                return;
            $res[$key] = $this->$key;
        });
        return $res;
    }

}