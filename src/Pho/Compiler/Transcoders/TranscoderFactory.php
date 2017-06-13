<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Compiler\Transcoders;

use Pho\Compiler\Prototypes\PrototypeInterface;
use Pho\Compiler\Prototypes\NodePrototype;
use Pho\Compiler\Prototypes\EdgePrototype;
use Pho\Compiler\Exceptions\UnknownPrototypeException;

class TranscoderFactory {

    public static function transcode(PrototypeInterface $prototype): TranscoderInterface
    {
        if($prototype instanceof NodePrototype) {
            return new NodeTranscoder($prototype);
        }
        else if($prototype instanceof EdgePrototype) {  
            return new NodeTranscoder($prototype);
        }
        throw new UnknownPrototypeException();
    }

}