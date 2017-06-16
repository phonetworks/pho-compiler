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

class NodeTranscoder extends AbstractTranscoder {

    const TRAIT_VOLATILE_NODE = "Traits\VolatileNodeTrait";
    const TRAIT_EDITABLE_GRAPH = "Traits\EditableGraphTrait";
    const TRAIT_EDITABLE_NODE = "Traits\EditableNodeTrait";
    const TRAIT_PERSISTENT_GRAPH = "Traits\PersistentGraphTrait";
    const TRAIT_PERSISTENT_NODE = "Traits\PersistentNodeTrait";
    const SUBTYPES = [
            "actor" => "Framework\Actor",
            "graph" => "Framework\Frame",
            "object" => "Framework\Object"
    ];

    protected function mapPrototypeVars(): array
    {
        $prototype_vars = $this->prototype->toArray();
        $new_array = [];
        $may_be_persistent = true;
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
                // mod: we don't touch 
                // mask: we don't touch
                case "volatile":
                    if(!$val) break;
                    $new_array["traits"][] = self::TRAIT_VOLATILE_NODE;
                    $may_be_persistent = false;
                    $is_volatile = true;
                    break;
                case "editable":
                    if(!$val) break;
                    $new_array["traits"][] = 
                        ( $prototype_vars["subtype"] == "graph" ) 
                            ? self::TRAIT_EDITABLE_GRAPH
                            : self::TRAIT_EDITABLE_NODE
                    ; 
                    $may_be_persistent = false;
                    $is_editable = true;
                    break;
                // revisionable?
                // expires?

                // incoming_edges, no touch
                // outgoing_edges, no use.
                case "incoming_edges":
                    //$new_array["incoming_edges"] =
                        switch($prototype_vars["subtype"]) {
                            case "actor":
                                $new_array["incoming_edges"] = "ActorOut\Read::class, ActorOut\Subscribe::class, ObjectOut\Transmit::class, ".$val;
                                break;
                            case "graph":
                                $new_array["incoming_edges"] = "ActorOut\Read::class, ActorOut\Subscribe::class, ObjectOut\Transmit::class, ".$val;
                                break;
                            case "object":
                                $new_array["incoming_edges"] = "ActorOut\Read::class, ActorOut\Subscribe::class, ObjectOut\Transmit::class, ".$val;
                                break;
                        }
            }
        }
        if($may_be_persistent) {
            $new_array["traits"][] = 
                    ( $prototype_vars["subtype"] == "graph" ) 
                        ? self::TRAIT_PERSISTENT_GRAPH 
                        : self::TRAIT_PERSISTENT_NODE
            ;
        }
        if($is_volatile&&$is_editable) {
            Compiler::logger()->warning("A node can't be both volatile and editable at the same time.");
        }

        return $new_array;
    }
}