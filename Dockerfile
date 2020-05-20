FROM circleci/php:latest

RUN cd /home/circleci/NovelSpider \
    && git clone https://github.com/suhanyujie/NovelSpider.git \
    && composer config repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && composer install --no-progress

CMD ["/bin/sh"]
