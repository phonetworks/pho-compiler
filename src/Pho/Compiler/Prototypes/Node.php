<?php

namespace Pho\Compiler\Prototypes;

class Node {

    private $name;

    public function name(string $name): void
    {
        $this->name = $name;
    }

}