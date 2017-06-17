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

use kyeates\PSRLoggers\EchoLogger;

class TestCase extends \PHPUnit\Framework\TestCase {

    protected $compiler;

    public function setUp() {
        $this->compiler = new Compiler(new EchoLogger());
    }

    public function tearDown() {
        unset($this->compiler);
    }

}