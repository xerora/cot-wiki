CREATE TABLE IF NOT EXISTS `cot_wiki_history` (
  `history_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `history_language` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `history_added` datetime NOT NULL,
  `history_revision` bigint(20) NOT NULL,
  `history_page_id` int(11) NOT NULL,
  `history_author` int(11) NOT NULL,
  `history_ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `cot_wiki_revisions` (
  `rev_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rev_text` mediumblob NOT NULL,
  `rev_parser` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`rev_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
