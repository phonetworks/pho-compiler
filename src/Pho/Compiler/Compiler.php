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

use Pho\Lib\GraphQL\Parser\Parse;
use Pho\Compiler\Prototypes\PrototypeList;
use Pho\Compiler\Transcoders\TranscoderFactory;
use Psr\Log\LoggerInterface;
use kyeates\PSRLoggers\MockLogger;

/**
 * Pho Compiler
 * 
 * Compiler is an extensible GraphQL schema evaluation and compilation
 * engine. It converts GraphQL files with Pho-compatible schema format
 * into Pho-executable PHP classes.
 * 
 * For more information on Pho schema, check out ...
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Compiler {

    protected static $logger;
    protected static $timer;

    protected $analyzer;
    protected $file_version = -1;
    protected $prototypes;

    /**
     * Starts the compilaton process by analyzing given
     * input files.
     * 
     * @param string $input_file
     */
    public function __construct(?LoggerInterface $logger = null)
    {
        self::$logger = $logger;
        $this->prototypes = new PrototypeList;
    }

    public static function logger(): LoggerInterface
    {
        if(isset($logger))
            return self::$logger;
        else {
            return new MockLogger();
        }
    }

    protected static function startTimer(): void
    {
        self::$timer = microtime(true);
    }

    /**
     * Returns the compilation time in milliseconds
     *
     * @return string
     */
    public static function endTimer(): int
    {
        return (int) round( (microtime(true) - self::$timer) * 1000 );
    }

    public function compile(string $input_file): Compiler
    {
        self::startTimer();
        $this->analyzer = new InputFileAnalyzer($input_file);
        if($this->checkSupport()) {
            $this->analyzer->process($this->prototypes);
        }
        return $this;
    }

    protected function version(): int
    {
        if($this->file_version == -1) { // -1 means, it has not been initialized yet
            $this->file_version = $this->analyzer->getVersion();
        }
        return (int) $this->file_version;
    }

    protected function checkSupport(): bool
    {
        return in_array($this->version(), Types::SUPPORTED);
    }

/*
    public function save(string $output_file_or_dir): bool
    {
        if(!file_exists($output_dir)) {
            mkdir($output_dir);
        }
        if(!is_writeable($output_dir)) {
            if(chmod($output_dir, $TO_COME)) {

            }
        }
    }

*/
    public function ast(): array
    {
        $ast = array();
        $prototypes = $this->prototypes->toArray();
        foreach($prototypes as $prototype) {
            $ast[] = $prototype->toArray();
        }
        return $ast;
    }

    public function get(): string
    {
        $transcoder = null;
        $prototypes = $this->prototypes->toArray();
        foreach($prototypes as $prototype) {
            $transcoder = TranscoderFactory::transcode($prototype);
        }
        return $transcoder->run();
    }

    public function dump(): void
    {
        echo $this->get();
    }

}