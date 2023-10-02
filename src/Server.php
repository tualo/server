<?php

namespace Tualo\Office\Server;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\TualoDBSessionHandler;
use tualo\Office\Basic\Middleware\Maintaince;

class Server
{
    public function run()
    {
        TualoApplication::set('requestPath', dirname($_SERVER["SCRIPT_NAME"]));
        TualoApplication::set('basePath', dirname($_SERVER['SCRIPT_FILENAME']));
        TualoApplication::set('cachePath', TualoApplication::get('basePath') . '/cache/');
        TualoApplication::set('configurationFile', TualoApplication::get('basePath') . '/configuration/.htconfig');
        $settings = parse_ini_file((string)TualoApplication::get('configurationFile'), true);
        TualoApplication::set('configuration', $settings);
        if (isset($settings['php-server'])) {
            foreach ($settings['php-server'] as $key => $value) {
                ini_set($key, $value);
            }
        }
        //foreach($settings as $key=>$value){ if(!defined($key)){ define($key,$value); } TualoApplication::set($key,$value);}
        TualoApplication::run(); // run all middlewares
    }
}
