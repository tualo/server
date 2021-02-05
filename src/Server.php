<?php

namespace Tualo\Office\Server;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\TualoDBSessionHandler;
use tualo\Office\Basic\Middleware\Maintaince;

class Server{
    public function run(){
        TualoApplication::set('requestPath',dirname($_SERVER["SCRIPT_NAME"]));
        TualoApplication::set('basePath', dirname($_SERVER['SCRIPT_FILENAME']) );
        TualoApplication::set('cachePath', TualoApplication::get('basePath').'/cache/' );
        TualoApplication::set('configurationFile',TualoApplication::get('basePath').'/configuration/.htconfig');

        $settings = parse_ini_file(TualoApplication::get('configurationFile'));
        foreach($settings as $key=>$value){ if(!defined($key)){ define($key,$value); } TualoApplication::set($key,$value);}
        if (!defined('BASIC_COMPONENTS')) define('BASIC_COMPONENTS','cmp_bsc cmp_ext_6 cmp_ext cmp_tualo cmp_tualo_theme cmp_ds cmp_dashboard cmp_ext_6_ext-theme-tualo');

        if (defined('__USE_DB_SESSION_HANLDER__')&&(__USE_DB_SESSION_HANLDER__==1)) session_set_save_handler(new TualoDBSessionHandler(), true);

        
        TualoApplication::run(); // run all middlewares
    
    }
}
