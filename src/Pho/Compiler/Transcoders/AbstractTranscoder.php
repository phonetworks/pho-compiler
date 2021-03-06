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

use Pho\Compiler\Compiler;
use Pho\Compiler\Prototypes\PrototypeInterface;

abstract class AbstractTranscoder implements TranscoderInterface
{

    protected $new_array;

    public function __construct(PrototypeInterface $prototype) 
    {
        $this->prototype = $prototype;
        $mustache = new \Mustache_Engine(
            array(
            'loader' => new \Mustache_Loader_FilesystemLoader(dirname(__FILE__).DIRECTORY_SEPARATOR.'templates'),
            'logger' => Compiler::logger(),
            )
        );
        $this->tpl = $mustache->loadTemplate($this->getTemplateName());
    }  
    
    protected function getTemplateName(): string
    {
        return substr((new \ReflectionClass($this))->getShortName(), 0, -1 * strlen("Transcoder"));
    }


    public function run(): string
    {
        $this->generateVars();
        $compiled = $this->tpl->render($this->toArray());
        $compiled = str_replace(
            [ "-|-SIZE_IN_BYTES-|-", "-|-HASH-|-" ],
            [ strlen($compiled), md5($compiled) ],
            $compiled
        );
        return $compiled;
    }

    protected function generateVars(): void
    {
        $this->new_array = $this->mapPrototypeVars();
        $this->new_array["timestamp"] = (string) time();
        $this->new_array["compilation_time"] = (string) Compiler::endTimer();
    }

    public function toArray(): array
    {
        return $this->new_array;
    }
}