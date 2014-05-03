<?php

define("DB_HOST", "127.0.0.1");
define("DB_NAME", "xxx");
define("DB_USER", "xxx");
define("DB_PASS", "xxx");
define("TBL_PREFIX", "ourlibdl_");

define("PROJECT_NAME","OurLib Demo Library");
define("EMAIL_URL_ROOT","http://localhost/ourlib/demolib/");

/**
 * Configuration for: Email server credentials
 *
 * Here you can define how you want to send emails.
 * If you have successfully set up a mail server on your linux server and you know
 * what you do, then you can skip this section. Otherwise please set EMAIL_USE_SMTP to true
 * and fill in your SMTP provider account data.
 *
 * An example setup for using gmail.com [Google Mail] as email sending service,
 * works perfectly in August 2013. Change the "xxx" to your needs.
 * Please note that there are several issues with gmail, like gmail will block your server
 * for "spam" reasons or you'll have a daily sending limit. See the readme.md for more info.
 *
 * define("EMAIL_USE_SMTP", true);
 * define("EMAIL_SMTP_HOST", "ssl://smtp.gmail.com");
 * define("EMAIL_SMTP_AUTH", true);
 * define("EMAIL_SMTP_USERNAME", "xxxxxxxxxx@gmail.com");
 * define("EMAIL_SMTP_PASSWORD", "xxxxxxxxxxxxxxxxxxxx");
 * define("EMAIL_SMTP_PORT", 465);
 * define("EMAIL_SMTP_ENCRYPTION", "ssl");
 *
 * It's really recommended to use SMTP!
 *
 */
define("EMAIL_USE_SMTP", true);
define("EMAIL_SMTP_HOST", "ssl://smtp.gmail.com");
define("EMAIL_SMTP_AUTH", true);
define("EMAIL_SMTP_USERNAME", "xxx@gmail.com");
define("EMAIL_SMTP_PASSWORD", "xxx");
define("EMAIL_SMTP_PORT", 465);
define("EMAIL_SMTP_ENCRYPTION", "ssl");
define("ADMIN_EMAIL_FROM", "xxx@gmail.com");
define("ADMIN_EMAIL_FROM_NAME", "OurLib Demo Admin");

?>