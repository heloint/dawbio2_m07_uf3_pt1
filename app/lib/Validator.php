<?php
namespace proven\lib\views;

require_once "model/User.php";
require_once "model/Category.php";
require_once "model/Product.php";
require_once "model/Warehouse.php";

use proven\store\model\User;
use proven\store\model\Category;
use proven\store\model\Product;
use proven\store\model\Warehouse;

class Validator
{
    /* Validates the in-coming values from
     * the request and initializes a User object from them.
     * @param method array<str, mixed> POST / GET
     * @return User | null
     * */
    public static function validateUser(int $method): ?User
    {
        $obj = null;
        $id = static::cleanAndValidate($method, "id", FILTER_VALIDATE_INT);
        $username = static::cleanAndValidate($method, "username");
        $password = static::cleanAndValidate($method, "password");
        $firstname = static::cleanAndValidate($method, "firstname");
        $lastname = static::cleanAndValidate($method, "lastname");
        $role = static::cleanAndValidate($method, "role");
        $obj = new User(
            $id,
            $username,
            $password,
            $firstname,
            $lastname,
            $role
        );
        return $obj;
    }

    /* Validates the in-coming values from
     * the request and initializes a Category object from them.
     * @param method array<str, mixed> POST / GET
     * @return Category | null
     * */
    public static function validateCategory(int $method): ?Category
    {
        $obj = null;
        $id = static::cleanAndValidate($method, "id", FILTER_VALIDATE_INT);
        $code = static::cleanAndValidate($method, "code");
        $description = static::cleanAndValidate($method, "description");
        $obj = new Category($id, $code, $description);
        return $obj;
    }

    /* Validates the in-coming values from
     * the request and initializes a Product object from them.
     * @param method array<str, mixed> POST / GET
     * @return Product | null
     * */
    public static function validateProduct(int $method): ?Product
    {
        $obj = null;
        $id = static::cleanAndValidate($method, "id", FILTER_VALIDATE_INT);
        $code = static::cleanAndValidate($method, "code");
        $description = static::cleanAndValidate($method, "description");
        $price = static::cleanAndValidate(
            $method,
            "price",
            FILTER_VALIDATE_FLOAT
        );
        $categoryId = static::cleanAndValidate(
            $method,
            "categoryId",
            FILTER_VALIDATE_INT
        );
        $obj = new Product($id, $code, $description, $price, $categoryId);
        return $obj;
    }

    /* Validates the in-coming values from
     * the request and initializes a Warehouse object from them.
     * @param method array<str,mixed> POST / GET
     * @return Warehouse | null
     * */
    public static function validateWarehouse(int $method): ?Warehouse
    {
        $obj = null;
        $id = static::cleanAndValidate($method, "id", FILTER_VALIDATE_INT);
        $code = static::cleanAndValidate($method, "code");
        $address = static::cleanAndValidate($method, "address");
        $obj = new Warehouse($id, $code, $address);
        return $obj;
    }

    /* Sanitizes / filters the incoming request value.
     * @param method array<str, mixed> POST / GET
     * @param variable string
     * @return mixed | null
     * */
    public static function cleanAndValidate(
        int $method,
        string $variable,
        int $filter = \FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ) {
        $clean = null;
        if (\filter_has_var($method, $variable)) {
            $clean = \filter_input($method, $variable, $filter);
        }
        return $clean;
    }
}
