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
        $compiler = new Compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."00JustANode.pgql");
        $ast = $compiler->ast();
        $this->assertEquals("ExtendedObject", $ast[0]["name"]);
        $this->assertEquals("object", $ast[0]["subtype"]);
        $this->assertEquals("node", $ast[0]["type"]);
        $this->assertEquals("id", $ast[0]["fields"][0]["name"]);
        $this->assertEquals("ID", $ast[0]["fields"][0]["type"]);
        $this->assertEquals(true, $ast[0]["fields"][0]["nullable"]);
        $this->assertEquals("custom_field", $ast[0]["fields"][1]["name"]);
        $this->assertEquals("String", $ast[0]["fields"][1]["type"]);
        $this->assertEquals(true, $ast[0]["fields"][1]["nullable"]);
        //print_r($ast);
    }

    public function test01JustAnEdge() {
        $compiler = new Compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."01JustAnEdge.pgql");
        $ast = $compiler->ast();
        $this->assertEquals("ExtendedEdge", $ast[0]["name"]);
        $this->assertEquals("subscribe", $ast[0]["subtype"]);
        $this->assertEquals("edge", $ast[0]["type"]);
        $this->assertEquals("id", $ast[0]["fields"][0]["name"]);
        $this->assertEquals("ID", $ast[0]["fields"][0]["type"]);
        $this->assertEquals(false, $ast[0]["fields"][0]["nullable"]);
        //print_r($ast);
    }

    public function test02MultipleEntitiesInSingleFile() {
        $compiler = new Compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."02MultipleEntitiesInSingleFile.pgql");
        $ast = $compiler->ast();
        $this->assertEquals("ExtendedObject", $ast[0]["name"]);
        $this->assertEquals("ExtendedEdge", $ast[1]["name"]);
    }

    public function test03NodeWithDirectives() {
        $compiler = new Compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."03NodeWithDirectives.pgql");
        $ast = $compiler->ast();
        $this->assertEquals("BlogPost", $ast[0]["name"]);
        $this->assertEquals("0x1e754", $ast[0]["mod"]);
        $this->assertEquals("0xeeeea", $ast[0]["mask"]);
        $this->assertEquals(600, $ast[0]["expires"]);
        $this->assertEquals(true, $ast[0]["volatile"]);
        $this->assertEquals(false, $ast[0]["revisionable"]);
        $this->assertEquals("Actor:Read,Actor:Write,Actor:Subscribe", $ast[0]["incoming_edges"]);
        $this->assertEquals("", $ast[0]["outgoing_edges"]);
        //print_r($ast);
    }

    public function test04EdgeWithDirectives() {
        $compiler = new Compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."04EdgeWithDirectives.pgql");
        $ast = $compiler->ast();
        $this->assertEquals("Do", $ast[0]["name"]);
        $this->assertEquals(true, $ast[0]["binding"]);
        $this->assertEquals("Actor", $ast[0]["head_nodes"]);
        $this->assertEquals("Object", $ast[0]["tail_nodes"]);
        $this->assertEquals("done", $ast[0]["label_head_singular"]);
        $this->assertEquals("doer", $ast[0]["label_tail_singular"]);
        $this->assertEquals("dones", $ast[0]["label_head_plural"]);
        $this->assertEquals("doers", $ast[0]["label_tail_plural"]);
        //print_r($ast);
    }

    public function test05DirectiveModifications() {
        $compiler = new Compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."05DirectiveModifications.pgql");
        $ast = $compiler->ast();
        $this->assertEquals("DoWeirdo", $ast[0]["name"]);
        $this->assertEquals(true, $ast[0]["binding"]);
        $this->assertEquals("Actor", $ast[0]["head_nodes"]);
        $this->assertEquals("Object", $ast[0]["tail_nodes"]);
        //print_r($ast);
    }

}