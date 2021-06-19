#!/usr/bin/env bash
killall php
vendor/bin/php-watcher bin/console --arguments loop:runner --arguments -vvv
