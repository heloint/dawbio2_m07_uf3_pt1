<?php

namespace proven\lib\debug;

class Debug {

    static function iniset(): void {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);   
        ini_set('error_reporting', E_ALL);        
    }
    
    static function display(array $data): void {
        foreach ($data as $value) {
            echo $value . "<br/>";
        }
    }

    static function printr(array $data): void {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    static function vardump(array $data): void {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }

}
