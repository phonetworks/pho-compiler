<?php

namespace Pho\Compiler\Prototypes;

final class PrototypeList implements PrototypeInterface {

    private $list = [];

    public function add(PrototypeInterface $prototype): void
    {
        if(get_class($prototype)==get_class($this)) {
            return;
        }
        $this->list[] = $prototype;
    }

    public function toArray(): array
    {
        return $this->list;
    }

}