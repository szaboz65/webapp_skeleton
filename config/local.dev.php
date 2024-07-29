<?php

// Dev environment

$settings['error']['display_error_details'] = true;
$settings['logger']['level'] = Monolog\Logger::DEBUG;

// Database
$settings['db']['database'] = 'webapp_skeleton_dev';

// Mailer config
$settings['mail']['mail_host'] = 'your mail_host';
$settings['mail']['mail_port'] = 465;
$settings['mail']['mail_user'] = 'your mail_user';
$settings['mail']['mail_pass'] = 'your mail_pass base64 encoded';

$settings['mail']['send_mail_sender'] = 'your sender';
$settings['mail']['send_mail_enable'] = true;
