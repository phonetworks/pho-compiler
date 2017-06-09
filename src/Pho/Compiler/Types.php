<?php

namespace Pho\Compiler;

/**
 * A static class regarding schema types
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