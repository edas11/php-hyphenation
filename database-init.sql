ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'172.17.0.2' IDENTIFIED BY 'password' WITH GRANT OPTION;
FLUSH PRIVILEGES;

CREATE DATABASE hyph;

USE hyph;

CREATE TABLE patterns
(
  pattern_id int auto_increment
    primary key,
  pattern    varchar(50) not null,
  constraint patterns_pattern_uindex
  unique (pattern)
);

CREATE TABLE words
(
  word_id int auto_increment
    primary key,
  word_h  varchar(50) null,
  word    varchar(50) not null,
  constraint words_word_uindex
  unique (word)
);

CREATE TABLE word_patterns
(
  id         int auto_increment
    primary key,
  word_id    int null,
  pattern_id int null,
  constraint word_patterns_unique
  unique (word_id, pattern_id),
  constraint word_patterns_patterns_pattern_id_fk
  foreign key (pattern_id) references patterns (pattern_id)
    on update cascade
    on delete cascade,
  constraint word_patterns_words_word_id_fk
  foreign key (word_id) references words (word_id)
    on update cascade
    on delete cascade
);