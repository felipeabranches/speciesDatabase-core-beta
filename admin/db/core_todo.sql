--
-- Table structure for `config_globals`
--
CREATE TABLE `config_globals` (
  `author` VARCHAR(255) NOT NULL,
  `site_name` VARCHAR(255) NOT NULL,
  `bootstrap_cdn` BOOLEAN NOT NULL,
  `bootstrap_vsn` VARCHAR(255) NOT NULL,
  `tinymce_vsn` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------
