<?php

namespace Pho\Compiler\Transcoders;

use Pho\Compiler\Prototypes\NodePrototype;

class NodeTranscoder implements TranscoderInterface {

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

    protected $prototype;
    protected $tpl;

    public function __construct(NodePrototype $prototype) {
        $this->prototype = $prototype;
        // https://github.com/bobthecow/mustache.php/wiki
        $mustache = new \Mustache_Engine(array(
            //'template_class_prefix' => '__MyTemplates_',
            'cache' => dirname(__FILE__).'/tmp/cache/mustache',
            //'cache_file_mode' => 0666, // Please, configure your umask instead of doing this :)
            //'cache_lambda_templates' => true,
            'loader' => new \Mustache_Loader_FilesystemLoader(dirname(__FILE__).DIRECTORY_SEPARATOR.'templates')
            //'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views/partials'),
            //'helpers' => array('i18n' => function($text) {
                // do something translatey here...
            //}),
            /*'escape' => function($value) {
                return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            },
            'charset' => 'ISO-8859-1',
            'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
            'strict_callables' => true,
            'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],*/
        ));
        $this->tpl = $mustache->loadTemplate("Node");
    }  
    
    public function run(): string
    {
        $vars = $this->mapPrototypeVars($this->prototype->toArray());
        return $this->tpl->render($vars);
    }

    protected function mapPrototypeVars(array $prototype_vars): array
    {
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
            // Log ...
        }
        return $new_array;
    }
}