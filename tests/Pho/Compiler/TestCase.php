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

    # http://php.net/manual/en/function.scandir.php#110570
    protected function _dirToArray($dir) { 
        $result = array(); 
        $cdir = scandir($dir); 
        foreach ($cdir as $key => $value) 
        { 
            if (!in_array($value,array(".","..", "README.md"))) 
            { 
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
                { 
                    foreach($this->_dirToArray($dir . DIRECTORY_SEPARATOR . $value) as $_res) {
                        $result[] = $_res;
                    } 
                } 
                else 
                { 
                    $result[] = $dir.DIRECTORY_SEPARATOR.$value; 
                } 
            } 
        } 
        return $result; 
    } 

}