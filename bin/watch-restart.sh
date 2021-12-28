#!/usr/bin/env bash
killall php
bin/php-watcher bin/console --arguments loop:runner --arguments -vvv
