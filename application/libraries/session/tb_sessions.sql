
CREATE TABLE IF NOT EXISTS `{sess_table_name}` (
`session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
`ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
`user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`last_activity` int(10) unsigned NOT NULL DEFAULT '0',
`user_data` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

ALTER TABLE `{sess_table_name}`
 ADD PRIMARY KEY (`session_id`);
