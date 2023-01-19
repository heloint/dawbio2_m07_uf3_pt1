<?php
require_once "lib/Debug.php";
use proven\lib\debug;
debug\Debug::iniset();

require_once "model/persist/ProductDao.php";
require_once "model/Product.php";

use proven\store\model\persist\ProductDao;
//use proven\store\model\Product;

$dao = new ProductDao();
debug\Debug::display($dao->selectAll());
debug\Debug::display($dao->selectWhere("code", "prodcode03"));

