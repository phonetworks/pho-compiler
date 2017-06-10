<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Compiler;

class SimpleTest extends \PHPUnit\Framework\TestCase {

    public function test00JustANode() {
        $compiler = new Compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."00JustANode.graphql");
        $compiler->dump();
    }

}