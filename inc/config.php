<?php
define("ENV", "dev");

switch (ENV) {
  case 'dev':
    define("DB_HOST", "localhost");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "");
    define("DB_DATABASE_NAME", "apitestdb");
    define("DB_PORT", 3306);
    break;

  case 'lan':
    define("DB_HOST", "192.168.1.11");
    define("DB_USERNAME", "abraham");
    define("DB_PASSWORD", "Admin123");
    define("DB_DATABASE_NAME", "hotelarenasspa");
    define("DB_PORT", 3307);
    break;

  case 'cloud':
    define("DB_HOST", "mysql-dev-control.alwaysdata.net");
    define("DB_USERNAME", "323003");
    define("DB_PASSWORD", "tomascito97A");
    define("DB_DATABASE_NAME", "dev-control_spa-arenas");
    define("DB_PORT", 3306);
    break;

  case 'server':
    define("DB_HOST", "localhost");
    define("DB_USERNAME", "abraham");
    define("DB_PASSWORD", "Admin123");
    define("DB_DATABASE_NAME", "hotelarenasspa");
    define("DB_PORT", 3307);
    break;

  case 'vm-prod':
    define("DB_HOST", "localhost");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "@dm1n123");
    define("DB_DATABASE_NAME", "hotelarenasspa");
    define("DB_PORT", 3306);
    break;

  default:
    break;
}

// establece la zona horaria
date_default_timezone_set("America/Lima");
?>