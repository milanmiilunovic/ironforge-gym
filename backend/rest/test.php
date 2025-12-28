<?php
require_once 'Database.php';


require_once __DIR__ . '/dao/CategoryDao.php';



$test = new CategoryDao();

print_r($test->getAll());


