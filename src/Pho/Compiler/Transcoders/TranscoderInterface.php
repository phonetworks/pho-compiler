<?php

namespace Pho\Compiler\Transcoders;

interface TranscoderInterface {

    // __construct(PrototypeInterface $prototype) {}
    public function transcode(): string;
}