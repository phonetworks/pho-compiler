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

class EdgeTranscoder extends AbstractTranscoder
{

    const SUBTYPES = [
            "read" => "Framework\ActorOut\Read",
            "write" => "Framework\ActorOut\Write",
            "subscribe" => "Framework\ActorOut\Subscribe",
            "publish" => "Framework\ObjectOut\Publish"
    ];


    protected function mapPrototypeVars(): array
    {
        $prototype_vars = $this->prototype->toArray();
        $new_array = [];
        $may_be_persistent = true;

        foreach($prototype_vars as $key=>$val) {
            // "type" determines whether it's  node or edge in the first place.
            switch($key) {
            case "name":
                $new_array["class_name"] = $val;
                break;
            case "subtype":
                $new_array["extends"] = self::SUBTYPES[$val];
                break;
            case "binding":
                $new_array["is_binding"] = $val ? "true" : "false";
                break;
            case "tail_nodes":
                $new_array[$key] = array_map("trim", explode(",", $val));
                break;

                    
                // volatile should be OK.


                /*default:
                    if(strlen($key)>6 && substr($key,0,6)=="label_");
                        $new_array[$key] = '"'.$val.'"';
                    break;*/
                
                // head_nodes should be ok, because they override parent
                // tail_nodes ??
            default:
                $new_array[$key] = $val;
                break;
            }
        }
        return $new_array;
    }
}