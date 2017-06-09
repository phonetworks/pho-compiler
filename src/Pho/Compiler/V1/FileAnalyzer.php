<?php

namespace Pho\Compiler\V1;

use Pho\Lib\GraphQL\Parser;

class FileAnalyzer {

    protected $ast;

    public function __construct(string $file)
    {
        try {
            $this->ast = new Parser\Parse($file);
        } catch(\Exception $e) {
            throw $e;
        }
        $this->process();
    }

    public function process(): void
    {
        foreach($this->ast->entities() as $entity) {
            new EntityAnalyzer($entity);
        }
    }

}