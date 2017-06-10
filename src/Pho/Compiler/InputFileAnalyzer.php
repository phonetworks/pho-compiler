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

use Pho\Compiler\Prototypes\PrototypeList;

class InputFileAnalyzer {

    protected $file;
    protected $contents;
    protected $version = -1;
    //protected $parse;

    public function __construct(string $input_file)
    {
        $this->file = $input_file;
        $this->contents = file_get_contents($input_file);
        $this->fetchVersion();
    }

    protected function fetchVersion()
    {
        foreach(Types::SUPPORTED as $supported_type) {
            if(preg_match(Types::PATTERNS[$supported_type], $this->contents)) {
                $this->version = $supported_type;
                break;
            }
        }
    }

    public function getVersion(): int
    {
        return (int) $this->version;
    }

    protected function getProcessor(): string
    {
        $engine =  sprintf("V%s", (string) $this->getVersion());
        if(!file_exists(__DIR__.DIRECTORY_SEPARATOR.$engine)) {
            throw new Exceptions\InvalidGraphQLTypeException($this->file);
        }
        return $engine;
    }

    public function process(PrototypeList $prototypes): void
    {
       try {
            $file_analyzer = "\\".__NAMESPACE__."\\".$this->getProcessor()."\\FileAnalyzer";
        }
        catch(Exceptions\InvalidGraphQLTypeException $e) {
            throw $e;
        }
        $file_analyzer::process($this->file, $prototypes);
    }

}