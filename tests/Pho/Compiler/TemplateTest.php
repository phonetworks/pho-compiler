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

class TemplateTest extends TestCase {

    public function test03NodeWithDirectives() {
        //$file = $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."03NodeWithDirectives.pgql")->dump();
        eval(\Psy\sh());
        //print_r($ast);
    }

    public function test04EdgeWithDirectives() {
        $file = $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."04EdgeWithDirectives.pgql")->dump();
        //eval(\Psy\sh());
        //print_r($ast);
    }


}