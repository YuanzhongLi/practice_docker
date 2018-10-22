cd wordpress_nginx_JP_initial_created
mkdir db
touch mysql.dump.sql

# docker exec -it dbコンテナ名 sh -c 'mysqldump データベース名 -u データベースユーザ名 -pデータベースパスワード 2> /dev/null' > ホスト側保存先ディレクトリ/mysql.dump.sql
$ docker exec -it db sh -c 'mysqldump wordpress -u root -ppassword 2> /dev/null' > db/mysql.dump.sql

cd wordpress
mkdir php && cd php
touch uploads.ini

wordpress image を日本語用に作り変える  
cd ../
touch Dockerfile  

Dockerfileに以下を追加

FROM wordpress:4.9-php7.2-fpm-alpine

# 日本語化するために WordPress 日本語版をインストールして展開
ENV WORDPRESS_TAR_FILE wordpress-4.9-ja.tar.gz
RUN rm -fr /usr/src/wordpress \
 && curl -O https://ja.wordpress.org/${WORDPRESS_TAR_FILE} \
 && tar -xzf ${WORDPRESS_TAR_FILE} -C /usr/src/ \
 && rm ${WORDPRESS_TAR_FILE} \
 && chown -R www-data:www-data /usr/src/wordpress

# ボリューム化
VOLUME /var/www/html
