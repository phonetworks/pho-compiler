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
class Compiler
{

    const NO_VALUE_SET = "|_~_~NO!-!VALUE!-!SET~_~_|";

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
        if(isset(self::$logger)) {
            return self::$logger;
        } else {
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
        return (int) round((microtime(true) - self::$timer) * 1000);
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

    public function save(string $output_dir): void
    {
        $this->logger()->info("Saving begins"); 
        if(!file_exists($output_dir)) {
            mkdir($output_dir);
        }
        if(!is_writeable($output_dir) || !is_dir($output_dir)) {
            throw new Exceptions\DestinationNotWriteableException($output_dir);
        }
        $output = $this->get();
        $this->logger()->info(sprintf("%d node(s)", $output["node"])); 
        $this->logger()->info(sprintf("%d edge(s)", $output["edge"])); 
        foreach($output["node"] as $node) {
            $file_name = $output_dir.DIRECTORY_SEPARATOR.$node["name"].".php";
            $edges_dir = $output_dir.DIRECTORY_SEPARATOR.$node["name"]."Out";
            file_put_contents($file_name, $node["file"]);
            if(!is_array($node["out"])) {
                continue;
            }
            foreach($node["out"] as $out) {
                @mkdir($edges_dir);
                touch($edges_dir.DIRECTORY_SEPARATOR.$out.".php");
            }
        }
        foreach($output["edge"] as $edge) {
                $tails = explode(",", $edge["tail"]);
                $multiple = ( count($tails) > 1 );
                foreach($tails as $tail) {
                    $tail = trim($tail);
                    if($multiple || in_array($tail, ["ActorNode", "ObjectNode", "GraphNode"])) {
                        @mkdir($output_dir.DIRECTORY_SEPARATOR."Edges");
                        $edge_file = $output_dir.DIRECTORY_SEPARATOR."Edges".DIRECTORY_SEPARATOR.$edge["name"].".php";
                    }
                    else {
                        $node_file = $output_dir.DIRECTORY_SEPARATOR.$tail.".php";
                        $edges_dir = $output_dir.DIRECTORY_SEPARATOR.$tail."Out";
                        if(!file_exists($node_file)) { 
                            throw new Exceptions\NodeEdgeMismatchImparityException($node_file);
                        }
                        if(!file_exists($edges_dir)) { 
                            throw new Exceptions\NodeEdgeMismatchImparityException($edges_dir);
                        }
                        $edge_file = $edges_dir.DIRECTORY_SEPARATOR.$edge["name"].".php";
                        if(!file_exists($edge_file)) {
                            throw new Exceptions\NodeEdgeMismatchImparityException($edge_file);
                        }
                    }
                    file_put_contents($edge_file, $edge["file"]);
                }    
        }

        # https://stackoverflow.com/questions/24783862/list-all-the-files-and-folders-in-a-directory-with-php-recursive-function
        $getDirContents = function (string $dir, array &$results = []) use (&$getDirContents)
        {
            $files = scandir($dir);
            foreach($files as $key => $value){
                $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
                if(!is_dir($path)) {
                    $results[] = $path;
                } else if($value != "." && $value != "..") {
                    $getDirContents($path, $results);
                    $results[] = $path;
                }
            }

            return $results;
        };

        $res = $getDirContents($output_dir);
        foreach($res as $r) {
            if(!is_dir($r) && empty(file_get_contents($r)))
                @unlink($r);
        }
        foreach($res as $r) {
            if(is_dir($r) && count(scandir($r))==2)
                @rmdir($r);
        }

    }

    public function ast(): array
    {
        $ast = array();
        $prototypes = $this->prototypes->toArray();
        foreach($prototypes as $prototype) {
            $ast[] = $prototype->toArray();
        }
        return $ast;
    }

    protected function getNode(Prototypes\PrototypeInterface $prototype): array
    {
        $transcoder = TranscoderFactory::transcode($prototype);
        return [
                "name" => $prototype->name,
                "file" => $transcoder->run(),
                "out" => $transcoder->toArray()["outgoing_edges"],
                "_in" => $prototype->incoming_edges

        ];
    }

    protected function getEdge(Prototypes\PrototypeInterface $prototype): array
    {
        $transcoder = TranscoderFactory::transcode($prototype);
        return [
                "name" => $prototype->name,
                "file" => $transcoder->run(),
                "tail" => $transcoder->toArray()["tail_node"],
                "_heads" => $prototype->head_nodes
        ];
    }

    public function get(): array
    {
        $tree = ["node"=>[], "edge"=>[]];
        $prototypes = $this->prototypes->toArray();
        foreach($prototypes as $prototype) {
            $func = sprintf("get%s", ucfirst($prototype->type));
            $tree[$prototype->type][] = $this->$func($prototype);
        }
        return $tree;
    }

    public function dump(): string
    {
        $output = "";
        $prototypes = $this->prototypes->toArray();
        foreach($prototypes as $prototype) {
            $output .= 
                (TranscoderFactory::transcode($prototype))->run();
        }
        return $output;
    }

    public function _prototypes() 
    {
        return $this->prototypes;
    }

}