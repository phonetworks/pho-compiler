<?php

namespace Pho\Compiler\Prototypes;

class Edge extends Entity {

    protected $binding;

    public function headNodes(string $value): void
    {
        //$this->name = $name;
    }

    public function tailNodes(string $value): void
    {
        //$this->name = $name;
    }

    public function binding(bool $on): void
    {
        $this->binding = $on;
    }

}