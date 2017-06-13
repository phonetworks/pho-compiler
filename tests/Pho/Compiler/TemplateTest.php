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

    public function test00JustANode() {
        $file = $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."03NodeWithDirectives.pgql")->get();
        //eval(\Psy\sh());
        //print_r($ast);
    }

    public function test01JustAnEdge() {
        //$compiler = new Compiler(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."01JustAnEdge.pgql");

        //print_r($ast);
    }

    public function test02MultipleEntitiesInSingleFile() {
        //$compiler = new Compiler(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."02MultipleEntitiesInSingleFile.pgql");

    }

    public function test03NodeWithDirectives() {
        //$compiler = new Compiler(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."03NodeWithDirectives.pgql");

    }

    public function test04EdgeWithDirectives() {
        //$compiler = new Compiler(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."04EdgeWithDirectives.pgql");

    }

    public function test05DirectiveModifications() {
        //$compiler = new Compiler(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."05DirectiveModifications.pgql");

    }

}