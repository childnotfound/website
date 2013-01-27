<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/kevinsperrine/idiorm/src/Orm.php';
require __DIR__ . '/../config.php';

ORM::configure('sqlite:'.$config['db']['filename']);


$db = ORM::getDatabase();
$db->exec("CREATE TABLE IF NOT EXISTS `missing_children` (
  `id` int(10) NOT NULL,
  `source_id` int(10) NOT NULL,
  `source_url` varchar(50) NOT NULL,
  `photo_url` varchar(50) NOT NULL,
  `name` varchar(15) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `current_age` varchar(15) NOT NULL,
  `lost_age` varchar(15) NOT NULL,
  `lost_date` date NOT NULL,
  `description` varchar(100) NOT NULL,
  `area` varchar(15) NOT NULL,
  `location` varchar(15) NOT NULL,
  `reason` varchar(50) NOT NULL,
  `update_at` datetime NOT NULL
);");

$db->exec("INSERT INTO missing_children (id, source_id, source_url, photo_url,
    name, gender, current_age, lost_age, lost_date, description, area, location, reason, update_at)
    VALUES(1, 10, 'http://www.missingkids.org.tw/', 'http://placehold.it/135x180',
    '王小明', '男生', '10歲', '15歲', '1999-12-23', '王小明描述。', '台北', '車站', '假資料', 0);
  ");