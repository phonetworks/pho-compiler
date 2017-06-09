<?php

namespace Pho\Compiler\V1;

//use Pho\Compiler\Definitions as CompilerDefinitions;
use Pho\Lib\GraphQL\Parser\Definitions;
use Pho\Compiler\Prototypes;

class EntityAnalyzer extends Version {

    private $entity;
    
    private $interface;
    private $type;
    private $name;

    private $prototype;

    public function __construct(Definitions\Entity $entity) {
        $this->entity = $entity;
        $this->interface = $entity->implementation(0);
        try {
            $this->type = $this->getEntityType($this->interface);
        }
        catch(Exceptions\InvalidImplementationException $e) {
            throw $e;
        }
        $this->formPrototype();
        $this->prototype->name($entity->name());
        $directive_analyzer = $this->type."DirectiveAnalyzer";
        if(class_exists($directive_analyzer)) {
            $directive_analyzer::process($this->prototype, $entity->directives());
        }
        else {
            throw new Exceptions\InvalidImplementationException(
                $this->version, 
                $this->type
            );
        }
        new FieldAnalyzer($entity->fields());
    }

    public function formPrototype(): void
    {
        $class = "Prototypes\\".$this->type;
        if(class_exists($class)) {
            $this->prototype = new $class();
        }
        else {
            throw new Exceptions\InvalidImplementationException(
                $this->version, 
                $this->type
            );
        }
    }

    protected function getEntityType(string $interface): string
    {
        $type = substr($interface, -4);
        if($type=="Edge"||$type=="Node")
            return $type;
        else
            throw new Exceptions\InvalidImplementationException($interface, $type);
    }

}