#!/usr/bin/env bash

sudo -H -u composer global require fxp/composer-asset-plugin --no-plugins

chown -R vagrant /home/vagrant/.config/composer

sudo -H -u vagrant composer global require "codeception/codeception=2.1.*"

sudo ln -s ~/.composer/vendor/codeception/codeception/codecept    /usr/local/bin/codecept

composer install
bower install

php yii migrate