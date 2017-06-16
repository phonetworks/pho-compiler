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

/**
 * A static class (struct) that keeps track of 
 * schema versions and types.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Types
{
    /**
     * Current version number.
     */
    const NUM = 1;

    /**
     * Backwards-compatibility. An array of all supported versions,
     * historical and present.
     */
    const SUPPORTED = [1];

    /** 
     * Version patterns in graphql schema files, arranged by version
     * number as key and the pattern as value in a PHP array.
     */
    const PATTERNS = [
        1 => "/^#( )*pho\-graphql\-v1(\r\n|\r|\n| )+/i"
    ];
}