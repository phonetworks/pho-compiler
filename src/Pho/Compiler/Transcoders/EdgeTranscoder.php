<?php

namespace Pho\Compiler\Transcoders;

use Pho\Compiler\Prototypes\EdgePrototype;

class EdgeTranscoder implements TranscoderInterface {

    public function __construct(EdgePrototype $prototype) {

    }
    
    public function transcode(): string;
}