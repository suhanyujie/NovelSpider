FROM circleci/php:latest

RUN cd /home/circleci \
    && git clone https://github.com/suhanyujie/NovelSpider.git \
    && cd /home/circleci/NovelSpider \
    && composer config repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && composer install --no-progress

CMD ["/bin/sh"]
