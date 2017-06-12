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

class EntityPrototype implements PrototypeInterface {

    protected $name;
    protected $subtype; // actor, object, graph or transmit, subscribe, write, read etc.
    protected $fields = [];
    protected $type;
    protected $_ref;

    const INACCESSIBLE_VARS = ["_ref"];

    public function __construct() {
        $this->_ref = new \ReflectionObject($this);
        $this->type = substr(strtolower($this->_ref->getShortName()),0 , -1 * strlen("prototype")); // trimming prototype from class name
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

    public function __get(string $property) //: mixed
    {
        if(isset($this->$property) && !in_array($property, self::INACCESSIBLE_VARS))
            return $this->$property;
    }

    protected function setter(string $property, /* mixed */ $value): void
    {
        //echo "starting: ".$property."\n";
        $original_property = $property;
        $property = \Stringy\StaticStringy::underscored($property);
        if($property=="fields"||$property=="type"||$property=="_ref") {
            throw new \Exception(sprintf("set%s is not a valid method in the class %s", $original_property, get_class($this)));
        }
        try {
            $this->_ref->getProperty($property);
        }
        catch(\ReflectionException $r) {
            //echo "Unknown property: ".$property;
            return; // maybe log this incident (with warning) in the future.
        }
        //echo "here i am: ".$property."\n\n";
        $this->$property = $value;
    }

    public function addField(string $name, string $type, bool $is_nullable, bool $is_list, bool $is_native): void
    {
        $this->fields[] = [
            "name"=>$name,
            "type"=>$type,
            "nullable"=>$is_nullable,
            "list"=>$is_list,
            "native"=>$is_native
        ];
    }

    public function toArray(): array 
    {
        $props = $this->_ref->getProperties(\ReflectionProperty::IS_PROTECTED);
        $res = [];
        array_walk($props, function($prop) use (&$res) {
            $key = $prop->getName();
            if(in_array($key, self::INACCESSIBLE_VARS))
                return;
            $res[$key] = $this->$key;
        });
        return $res;
    }

}