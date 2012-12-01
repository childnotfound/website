
--
-- 資料庫: `missing`
--

-- --------------------------------------------------------

--
-- 表的結構 `missing_children`
--

CREATE TABLE IF NOT EXISTS `missing_children` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_id` int(10) unsigned NOT NULL,
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
  `update_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `source_id` (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

