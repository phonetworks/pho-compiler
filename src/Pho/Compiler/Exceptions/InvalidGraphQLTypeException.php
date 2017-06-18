<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Compiler\Exceptions;

/**
 * Thrown when the document declares an unrecognized type.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class InvalidGraphQLTypeException extends \Exception
{
    public function __construct(string $file)
    {
        parent::__construct();
        $this->message = sprintf(
            "No valid GraphQL schema type identified in the schema file: \"%s\". 
                                    The file may be invalid/corrupt 
                                        or you may be running an older version of Pho-Compiler.", 
            $file
        );
    }
}