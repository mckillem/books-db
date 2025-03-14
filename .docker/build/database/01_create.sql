CREATE TABLE `book`
(
    `id`       INT(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `isbn` varchar(128) NOT NULL,
    `pages` smallint(5) NOT NULL,
    `date` year NOT NULL,
    `language` varchar(128) NOT NULL,
    `read` boolean NOT NULL default 0,
    `own` boolean NOT NULL default 0,
    `description` VARCHAR(255) NOT NULL
) ENGINE = InnoDB
  CHARSET = utf8;

# login: test, heslo: test (hashed)
INSERT INTO `book` (`id`, `title`, `isbn`, `pages`, `date`, `language`, `read`, `own`, `description`)
VALUES (1, 'Atomic habits', 'koko', 100, '2013', 'English', true, false, 'něco o knize'),
       (2, 'Ultralearning', 'koko', 100, '2013', 'English', true, false, 'něco o knize'),
       (3, 'The answer', 'koko', 100, '2013', 'English', true, false, 'něco o knize'),
       (4, 'Scattered minds', 'koko', 100, '2013', 'English', true, false, 'něco o knize'),
       (5, 'Hledání knih', 'koko', 342, '1992', 'Česky', true, false, 'něco o knize');

CREATE TABLE `author`
(
       `id` int(11) NOT NULL AUTO_INCREMENT,
       `name` varchar(255) NOT NULL,
       PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

INSERT INTO `author` (`id`, `name`)
VALUES (1,	'Ľuboslav Lacko'),
       (2,	'Anna Havlíčková'),
       (3,	'Petr Roudenský'),
       (4,	'Stephen Prata');

CREATE TABLE `book_author`
(
        `id` int(11) NOT NULL AUTO_INCREMENT,
         `book_id` int(11) NOT NULL,
         `author_id` int(11) NOT NULL,
         PRIMARY KEY (`id`),
         KEY `book_id` (`book_id`),
         KEY `author_id` (`author_id`),
         CONSTRAINT `book_author_ibfk_11` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
         CONSTRAINT `book_author_ibfk_10` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

INSERT INTO `book_author` (`id`, `book_id`, `author_id`)
VALUES (20,	1,	2),
       (21,	2,	3),
       (22,	3,	1),
       (23,	4,	4),
       (24,	5,	2);

CREATE TABLE IF NOT EXISTS `user` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `firstname` varchar(255) COLLATE utf8_czech_ci NOT NULL,
                                      `lastname` varchar(255) COLLATE utf8_czech_ci NOT NULL,
                                      `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
                                      `password` varchar(60) COLLATE utf8_czech_ci NOT NULL,
                                      `role` enum('member','admin') COLLATE utf8_czech_ci NOT NULL DEFAULT 'member',
                                      PRIMARY KEY (`id`),
                                      UNIQUE KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

# login cms@itnetwork.cz
# heslo itnetwork
INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `password`, `role`) VALUES
    (1, 'it', 'network', 'cms@itnetwork.cz', '$2y$10$arJIJie/xGoqZayCro4yZ.pPEkt9Ps4DJBNZAHSZ/rvbOkj//K/tq', 'admin');
