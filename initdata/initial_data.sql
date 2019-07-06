CREATE DATABASE IF NOT EXISTS htc DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
use htc;

CREATE TABLE posts (
  id varchar(40) NOT NULL,
  user_id varchar(40) NOT NULL,
  body text NOT NULL,
  name varchar(255) NOT NULL,
  color varchar(32) NOT NULL,
  background varchar(1024),
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO posts (id, user_id, body, name, color) VALUES
('pst-c1e02ba6-e17e-410c-9491-40186878e7bb', 'usr-eae3110e-ce08-4c72-8b79-c0fdb3a93340', '焼肉が食べたい', 'お肉が食べたい', 'green'),
('pst-b4bbc0ab-f461-483c-8c08-14d8d6fdd090', 'usr-83899cac-8f78-4805-aaad-bededabad527', 'ハンズオンが無事おわりますように', '村田 司', 'red'),
('pst-df4d6b62-2be1-4790-8b75-11dbf13c0ad9', 'usr-60f0a2ce-e223-4888-bd98-b05f3c6cc906', '晴れますように', 'メア', 'yellow'),
('pst-2a84b883-e630-45db-bc79-b5585f7d44eb', 'usr-44124d42-9f3b-49fa-bb52-332bc090d944', '掘りが沼りませんように', '瑞鳳', 'blue');