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
use Pho\Compiler\Exceptions;

class NodeTranscoder extends AbstractTranscoder
{

    const SUBTYPES = [
            "actor" => "Foundation\AbstractActor",
            "graph" => "Foundation\AbstractGraph",
            "object" => "Foundation\AbstractObject"
    ];

    const MUST_HAVES = [
        "class_name",
        "extends"
    ];

    protected function mapPrototypeVars(): array
    {
        $prototype_vars = $this->prototype->toArray();
        $new_array = [];
        $is_volatile = false;
        $is_editable = false;

        foreach($prototype_vars as $key=>$val) {
            // "type" determines whether it's  node or edge in the first place.
            switch($key) {
            case "name":
                $new_array["class_name"] = $val;
                break;
            case "subtype":
                $new_array["extends"] = self::SUBTYPES[$val];
                break; 
            case "mod":
            case "mask": 
                if(!preg_match("/^0x[0-9a-f]{5}$/i", $val)) {
                    Compiler::logger()->warning("%s is not a hexadecimal number. The value was %s.", [$key, $val]);
                    throw new Exceptions\ImproperValueException($key);
                }
                $new_array[$key] = $val;
                break;
            case "volatile":
                $is_volatile = (bool) $val;
                $new_array["persistent"] = $is_volatile ? "false" : "true";
                break;
            case "editable":
                $is_editable = (bool) $val;
                $new_array[$key] = $is_editable ? "true" : "false";
                break;
            case "versionable":
                // we skip for the moment.
                break;
            case "expires":
                $new_array["expiration"] = (int) $val;
                if($new_array["expiration"]<0) 
                    $new_array["expiration"] = 0;
                if($new_array["expiration"] < 60) {
                    Compiler::logger()->warning(
                        sprintf("Expiration time %d is too low in entity in entity %s.", 
                            $new_array["expiration"], 
                            $prototype_vars["name"]
                        )
                    );
                }
                break;
            case "outgoing_edges":
                $new_array[$key] = array_map("trim", explode(",", $val));
                break;
            case "incoming_edges":
                $new_array[$key] = array_map(function(string $x) {
                    return str_replace(":", "Out\\", trim($x));
                }, explode(",", $val));
                break;
            default:
                if($key=="persistent")
                    break;
                $new_array[$key] = $val;
                break;
            }
        }
        if($is_volatile&&$is_editable) {
            Compiler::logger()->warning("A node can't be both volatile and editable at the same time.");
        }

        $this->completeMap($new_array);
        return $new_array;
    }

    protected function completeMap(&$array): void
    {
        $check_for_must_have = function(string $key) use($array)
        {
            if(!isset($array[$key])) {
                throw new Exceptions\MissingValueException($key);
            }
        };

        $check_with_fallback = function(string $key, /* mixed */ $fallback) use(&$array)
        {
            if(!isset($array[$key])) {
                $array[$key] = $fallback;
            }
        };

        $check_for_must_have("class_name");
        $check_for_must_have("extends");

        $check_with_fallback("editable", "false");
        $check_with_fallback("persistent", "true");
        $check_with_fallback("expiration", 0);
        
    }
}