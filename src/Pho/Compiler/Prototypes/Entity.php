<?php

namespace Pho\Compiler\Prototypes;

class Entity {

    protected $name;

    public function name(string $name): void
    {
        $this->name = $name;
    }

}