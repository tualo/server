<?php

namespace Tualo\Office\Server;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\TualoDBSessionHandler;
use tualo\Office\Basic\Middleware\Maintaince;

class Server
{
    public static function loadIniFile()
    {
        $settings = parse_ini_file((string)TualoApplication::get('configurationFile'), true);



        if (!isset($settings['database']))  $settings['php-server'] = [];
        ini_set('mysqli.default_host', '127.0.0.1');
        if (!isset($settings['database']['db_name'])      && ($db_name =      getenv('TUALO_DB_NAME', true)))          $settings['database']['db_name']      = $db_name;
        if (!isset($settings['database']['db_host'])      && ($host_name =    getenv('TUALO_DB_HOST', true)))          $settings['database']['db_host']      = $host_name;
        if (!isset($settings['database']['db_user'])      && ($user_name =    getenv('TUALO_DB_USER', true)))          $settings['database']['db_user']      = $user_name;
        if (!isset($settings['database']['db_pass'])        && ($password =     getenv('TUALO_DB_PASSWORD', true)))      $settings['database']['db_pass']        = $password;
        if (!isset($settings['database']['db_port'])      && ($port =         getenv('TUALO_DB_PORT', true)))          $settings['database']['db_port']      = $port;
        if (!isset($settings['database']['key_file'])     && ($key_file =     getenv('TUALO_DB_KEY_FILE', true)))      $settings['database']['key_file']     = $key_file;
        if (!isset($settings['database']['cert_file'])    && ($cert_file =    getenv('TUALO_DB_CERT_FILE', true)))     $settings['database']['cert_file']    = $cert_file;
        if (!isset($settings['database']['ca_file'])      && ($ca_file =      getenv('TUALO_DB_CA_FILE', true)))       $settings['database']['ca_file']      = $ca_file;
        if (!isset($settings['database']['ca_path'])      && ($ca_path =      getenv('TUALO_DB_CA_PATH', true)))       $settings['database']['ca_path']      = $ca_path;
        if (!isset($settings['database']['cipher_algos']) && ($cipher_algos = getenv('TUALO_DB_CIPHER_ALGOS', true)))  $settings['database']['cipher_algos'] = $cipher_algos;



        // for backward compatibility
        if (
            isset($settings["__SESSION_DSN__"]) &&
            isset($settings["__SESSION_USER__"]) &&
            isset($settings["__SESSION_PASSWORD__"]) &&
            isset($settings["__SESSION_HOST__"]) &&
            isset($settings["__SESSION_PORT__"])

        ) {
            $settings['database']['db_host'] = $settings["__SESSION_HOST__"];
            $settings['database']['db_pass']   = $settings["__SESSION_PASSWORD__"];
            $settings['database']['db_user'] = $settings["__SESSION_USER__"];
            $settings['database']['db_name'] = $settings["__SESSION_DSN__"];
            $settings['database']['db_port'] = $settings["__SESSION_PORT__"];
        }

        if (isset($config["FORCE_DB_HOST"])) $settings['database']['force_db_host'] = $settings["FORCE_DB_HOST"];
        if (isset($config["FORCE_DB_PORT"])) $settings['database']['force_db_port'] = $settings["FORCE_DB_PORT"];


        // for backward compatibility
        if (
            isset($settings["__DB_SSL_KEY__"]) &&
            isset($settings["__DB_SSL_CERT__"]) &&
            isset($settings["__DB_SSL_CA__"])
        ) {
            $settings['database']['key_file']  = $settings["__DB_SSL_KEY__"];
            $settings['database']['cert_file'] = $settings["__DB_SSL_CERT__"];
            $settings['database']['ca_file']   = $settings["__DB_SSL_CA__"];
        }

        TualoApplication::set('configuration', $settings);
    }
    public function run()
    {

        //TualoApplication::set('requestPath', dirname($_SERVER["REQUEST_URI"]));
        TualoApplication::set('requestPath', dirname($_SERVER["SCRIPT_NAME"]));
        TualoApplication::set('basePath', dirname($_SERVER['SCRIPT_FILENAME']));
        TualoApplication::set('cachePath', TualoApplication::get('basePath') . '/cache/');
        TualoApplication::set('configurationFile', TualoApplication::get('basePath') . '/configuration/.htconfig');

        self::loadIniFile();



        if (isset($settings['php-server'])) {
            foreach ($settings['php-server'] as $key => $value) {
                ini_set($key, $value);
            }
        }
        //foreach($settings as $key=>$value){ if(!defined($key)){ define($key,$value); } TualoApplication::set($key,$value);}
        TualoApplication::runHeaders();
        TualoApplication::run(); // run all middlewares
    }
}
