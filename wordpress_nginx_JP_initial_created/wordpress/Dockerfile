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
