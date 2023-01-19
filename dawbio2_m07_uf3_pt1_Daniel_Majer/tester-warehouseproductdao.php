<?php
require_once "lib/Debug.php";
use proven\lib\debug;
debug\Debug::iniset();

require_once "model/persist/WarehouseProductDao.php";

use proven\store\model\persist\WarehouseProductDao;

$dao = new WarehouseProductDao();
debug\Debug::vardump($dao->selectAll());
debug\Debug::vardump($dao->selectWhere('product_id', '2'));
debug\Debug::vardump($dao->selectWhere('warehouse_id', '2'));


