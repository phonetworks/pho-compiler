<?php

namespace Pho\Compiler\Transcoders;

use Pho\Compiler\Prototypes\EdgePrototype;

class EdgeTranscoder implements TranscoderInterface {

    const SUBTYPES = [
            "read" => "Framework\ActorOut\Read",
            "write" => "Framework\ActorOut\Write",
            "subscribe" => "Framework\ActorOut\Subscribe",
            "publish" => "Framework\ActorOut\Publish"
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
        $this->tpl = $mustache->loadTemplate("Edge");
    }  
    
    public function transcode(): string
    {
        $vars = $this->mapPrototypeVars($this->prototype->toArray());
    }

    protected function mapPrototypeVars(array $prototype_vars): array
    {
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
                
                // binding ? predicate!!!
                // volatile should be OK.


                /*default:
                    if(strlen($key)>6 && substr($key,0,6)=="label_");
                        $new_array[$key] = '"'.$val.'"';
                    break;*/
                
                // head_nodes should be ok
                // tail_nodes ??
            }
        }
    }
}