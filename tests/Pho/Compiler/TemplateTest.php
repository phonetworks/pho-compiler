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

    private $tmp_dir;
    private $tmp_file;

    public function setUp() {
        parent::setUp();
        $this->tmp_file = tempnam(sys_get_temp_dir(),"");
        $tmp_dir = tempnam(sys_get_temp_dir(),"");
        unlink($tmp_dir);
        mkdir($tmp_dir);
        $this->tmp_dir = $tmp_dir;
    }

    protected static function _delTree($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) { 
            if(is_dir("$dir/$file")) 
                self::_delTree("$dir/$file");
            else if(substr($file, -4)==".php")
                unlink("$dir/$file"); 
        } 
        return rmdir($dir); 
    }

    public function tearDown() {
        parent::tearDown();
        self::_delTree($this->tmp_dir);
        unset($this->tmp_dir);
        unlink($this->tmp_file);
        unset($this->tmp_file);
    }

    public function test03NodeWithDirectives() {
        //$file = $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."03NodeWithDirectives.pgql")->dump();
        /*$this->assertRegExp('/^.+classBlogPostextendsFramework\Object{useTraits\VolatileNodeTraituseTraits\EditableNodeTraitconstDEFAULT_MOD=0x1e754;constDEFAULT_MASK=0xeeeea;pu
blicfunction__construct(Kernel$kernel,Framework\Actor$actor,Framework\ContextInterface$context){$this->registerIncomingEdges(Actor:Read,Actor:Write,Actor:Subscribe);parent::__construct($actor,$context);
$this->loadNodeTrait($kernel);}}.+$/', str_replace([" ","\n","\r"],"", $file));
   */ }

    public function test04EdgeWithDirectives() {
        //$file = $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."04EdgeWithDirectives.pgql")->dump();
        //eval(\Psy\sh());
        //print_r($ast);
    }

    public function test_CannotSave_04EdgeWithDirectives() {
        $this->expectException(\Pho\Compiler\Exceptions\NodeEdgeMismatchImparityException::class);
        $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."04EdgeWithDirectives.pgql")->save($this->tmp_dir);
    }

    public function test06NodeWithOutgoingEdgesSet() {
        $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."06NodeWithOutgoingEdgesSet.pgql")->save($this->tmp_dir);
        $this->assertFileExists($this->tmp_dir."/BlogPost.php");
        $this->assertDirectoryExists($this->tmp_dir."/BlogPostOut");
        $this->assertFileExists($this->tmp_dir."/BlogPostOut/Do.php");
    }

    public function test_CannotSave_07Dir() {
        $this->expectException(\Pho\Compiler\Exceptions\NodeEdgeMismatchImparityException::class);
        $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."07Dir".DIRECTORY_SEPARATOR."Edge.pgql")->save($this->tmp_dir);
    }

    public function test07Dir() {
        $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."07Dir".DIRECTORY_SEPARATOR."Node.pgql");
        $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."07Dir".DIRECTORY_SEPARATOR."Edge.pgql");
        $this->compiler->save($this->tmp_dir);
        $this->assertFileExists($this->tmp_dir."/BlogPost.php");
        $this->assertDirectoryExists($this->tmp_dir."/BlogPostOut");
        $this->assertFileExists($this->tmp_dir."/BlogPostOut/Do.php");
    }

    // works when edge's @nodes(tail=) is set wrong
    public function test08ConflictingDirWith_Edge_Nodes_Tail() {
        $this->expectException(\Pho\Compiler\Exceptions\NodeEdgeMismatchImparityException::class);
        $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."08ConflictingDirWith_Edge_Nodes_Tail".DIRECTORY_SEPARATOR."Node.pgql");
        $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."08ConflictingDirWith_Edge_Nodes_Tail".DIRECTORY_SEPARATOR."Edge.pgql");
        $this->compiler->save($this->tmp_dir);
    }

    // works when node's @edges(out=) is set wrong
    public function test09ConflictingDirWith_Node_Edges_Out() {
        $this->expectException(\Pho\Compiler\Exceptions\NodeEdgeMismatchImparityException::class);
        $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."09ConflictingDirWith_Node_Edges_Out".DIRECTORY_SEPARATOR."Node.pgql");
        $this->compiler->compile(__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."09ConflictingDirWith_Node_Edges_Out".DIRECTORY_SEPARATOR."Edge.pgql");
        $this->compiler->save($this->tmp_dir);
    }


}