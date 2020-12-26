<?php

// DB接続の情報
define('DSN', 'mysql:host=db;dbname=sales_db;charset=utf8');
define('DB_USER', 'sales_admin');
define('DB_PASSWORD', '9876');

// エラー表示の設定(Noticeが表示されなくなる)
error_reporting(E_ALL & ~E_NOTICE);
