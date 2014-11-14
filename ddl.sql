create database tngworkshop;

CREATE USER 'board'@'localhost' IDENTIFIED BY 'board';

GRANT ALL PRIVILEGES ON tngworkshop.* TO 'board'@'localhost';

FLUSH PRIVILEGES;

use tngworkshop;

create table comments (
  id int primary key auto_increment,
  user varchar(40) not null,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  text TEXT not null
);

create table tags (
  id int primary key auto_increment,
  tag varchar(40) not null unique
);

create table tags_comments (
  tagId int not null,
  commentId int not null,
  primary key(tagId, commentId)
);
