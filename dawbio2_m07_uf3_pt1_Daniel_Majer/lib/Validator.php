<?php
namespace proven\lib\views;
require_once 'model/User.php';
use proven\store\model\User;

class Validator {

    public static function validateUser(int $method) {
        $obj = null;
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT); 
        $username = static::cleanAndValidate($method, 'username'); 
        $password = static::cleanAndValidate($method, 'password'); 
        $firstname = static::cleanAndValidate($method, 'firstname'); 
        $lastname = static::cleanAndValidate($method, 'lastname'); 
        $role = static::cleanAndValidate($method, 'role'); 
        $obj = new User($id, $username, $password, $firstname, $lastname, $role);
        return $obj;        
    }

    public static function cleanAndValidate(int $method, string $variable, int $filter=\FILTER_SANITIZE_FULL_SPECIAL_CHARS) {
        $clean = null;
        if (\filter_has_var($method, $variable)) {
            $clean = \filter_input($method, $variable, $filter); 
        }
        return $clean;
    }
    
}