cd wordpress_nginx_JP_initial_created
mkdir db
touch mysql.dump.sql

# docker exec -it dbコンテナ名 sh -c 'mysqldump データベース名 -u データベースユーザ名 -pデータベースパスワード 2> /dev/null' > ホスト側保存先ディレクトリ/mysql.dump.sql
$ docker exec -it db sh -c 'mysqldump wordpress -u root -ppassword 2> /dev/null' > db/mysql.dump.sql

cd wordpress
mkdir php
touch uploads.ini
