CREATE TABLE `book`
(
    `id`       INT(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL
) ENGINE = InnoDB
  CHARSET = utf8;

# login: test, heslo: test (hashed)
INSERT INTO `book` (`id`, `title`)
VALUES (1, 'Atomic habits'),
       (2, 'Ultralearning'),
       (3, 'The answer'),
       (4, 'Scattered minds');