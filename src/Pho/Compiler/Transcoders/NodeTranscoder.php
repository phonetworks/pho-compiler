<?php

namespace Pho\Compiler\Transcoders;

use Pho\Compiler\Prototypes\NodePrototype;

class NodeTranscoder implements TranscoderInterface {

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
    
    public function transcode(): string
    {
        $vars = $this->prototype->toArray();
    }
}