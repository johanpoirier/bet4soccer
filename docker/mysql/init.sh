#!/bin/bash

/etc/init.d/mysql start
mysql -uroot -proot -e "CREATE DATABASE bets CHARACTER SET utf8 COLLATE utf8_general_ci;"
mysql -uroot -proot bets < /tmp/mysql/schema.sql
