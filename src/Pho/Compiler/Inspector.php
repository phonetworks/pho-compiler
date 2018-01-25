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

use Zend\File\ClassFileLocator;

/**
 * Inspects the schema directory.
 * 
 * The following checks are performed:
 * 
 * 1. Make sure there is at least one object
 * 2. Make sure there is at least one actor
 * 3. Node - Edge harmony
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Inspector
{

    protected $folder;

    /**
     * Constructor.
     *
     * @param string $folder where the **compiled** schema files (PHP) reside
     */
    public function __construct(string $folder)
    {
        $this->folder = $folder;
    }

    /**
     * Documents all available objects and their methods.
     * 
     * @todo incomplete
     *
     * @return string
     */
    public function document(): string
    {
        // this will use an outside library
    }

    /**
     * Validates the compiled schema directory.
     * 
     * @return void
     * 
     * @throws Exceptions\MissingObjectImparityException if there is no object node found.
     * @throws Exceptions\MissingActorImparityException if there is no actor node found.
     * @throws Exceptions\AbsentEdgeDirImparityException if the node does not have a directory for its edges.
     */
    public function assertParity(): void
    {
        $ignored_classes = [];
        $object_exists = false;
        $actor_exists = false;
        $locator = new ClassFileLocator($this->folder);
        foreach ($locator as $file) {
            $filename = str_replace($this->folder . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            foreach ($file->getClasses() as $class) {
                $reflector = new \ReflectionClass($class);
                $parent = $reflector->getParentClass()->getName();
                $class_name = $reflector->getShortName();
                switch($parent) {
                case "Pho\Framework\Object":
                    $object_exists = true;
                    try {
                        $this->checkEdgeDir($class_name);
                    }
                    catch(Exceptions\AbsentEdgeDirImparityException $e) {
                        throw $e;
                    }
                    break;
                case "Pho\Framework\Actor":
                    $actor_exists = true;
                    try {
                        $this->checkEdgeDir($class_name);
                    }
                    catch(Exceptions\AbsentEdgeDirImparityException $e) {
                        throw $e;
                    }
                    break;
                case "Pho\Framework\Frame":
                    try {
                        $this->checkEdgeDir($class_name);
                    }
                    catch(Exceptions\AbsentEdgeDirImparityException $e) {
                        throw $e;
                    }
                    break;
                default:
                    $ignored_classes[] = [
                        "filename" => $filename, 
                        "classname" => $class_name
                    ];
                    break;
                }
            }
        }
        if(!$object_exists) {
            throw new Exceptions\MissingObjectImparityException($this->folder);
        }
        if(!$actor_exists) {
            throw new Exceptions\MissingActorImparityException($this->folder);
        }
    }  

    /**
     * Checks if the node has a directory for its edges.
     *
     * @param string $node_name The name of the node.
     * 
     * @return void
     * 
     * @throws Exceptions\AbsentEdgeDirImparityException if the node does not have a directory for its edges.
     */
    protected function checkEdgeDir(string $node_name): void
    {
        $dirname = $this->folder.DIRECTORY_SEPARATOR.$node_name."Out";
        if(!file_exists($dirname)) {
            throw new Exceptions\AbsentEdgeDirImparityException($dirname, $node_name);
        }
    }

}