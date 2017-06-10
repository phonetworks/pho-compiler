<?php

namespace Pho\Compiler\Transcoders;

use Pho\Compiler\Prototypes\NodePrototype;

class NodeTranscoder implements TranscoderInterface {

    public function __construct(NodePrototype $prototype) {

    }
    
    public function transcode(): string;
}