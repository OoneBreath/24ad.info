<?php

return [
    'installer' => 'Installer',
    'select' => 'Select',
    'optional' => ' (Optional)',
    'back' => 'Back',
    'next' => 'Next',
    'try_again' => 'Try again',
    'button_processing' => 'Processing',
    'save' => 'Save',
    'requirements_checking' => 'System Requirements',
    'requirements_checking_title' => 'System requirements',
    'requirements_checking_required' => 'The requirements checking is required to continue.',
    'requirements' => 'Requirements',
    'requirement' => 'Requirement',
    'permissions' => 'Permissions',
    'permission' => 'Permission',
    'site_info' => 'Site Info',
    'site_info_title' => 'Site info',
    'site_info_required' => 'The website information are required to continue.',
    'app_info' => 'Website information',
    'admin_info' => 'Administrator information',
    'settings_app_name' => 'Site name',
    'settings_app_slogan' => 'Site slogan',
    'settings_localization_default_country_code' => 'Default country',
    'settings_app_purchase_code' => 'Purchase code',
    'user_name' => 'Name',
    'user_email' => 'Email',
    'user_password' => 'Password',
    'database_info' => 'Database Connection',
    'database_info_title' => 'Database configuration',
    'database_info_required' => 'The database parameters are required to continue.',
    'database_env_file_required' => 'The .env file is not found. The database parameters are required to continue.',
    'database_driver' => 'Driver',
    'database_driver_hint' => 'Drivers supported: MySQL, MariaDB',
    'mysql' => 'MySQL',
    'mariadb' => 'MariaDB',
    'database_host' => 'Host',
    'database_host_hint' => 'Required when the :socket field is not filled',
    'database_port' => 'Port',
    'database_port_hint' => 'Required when the :socket field is not filled',
    'database_username' => 'Username',
    'database_password' => 'Password',
    'database_name' => 'Database name',
    'database_tables_prefix' => 'Database tables prefix',
    'database_tables_prefix_hint' => 'Note: The prefix will be added to the app\'s table names in the database',
    'database_socket' => 'Unix socket',
    'database_socket_hint' => 'Note: If filled, makes the :host and the :port fields optional - <a href="https://php.net/manual/en/ref.pdo-mysql.connection.php" target="_blank">More information</a>',
    'database_overwrite_tables' => 'Overwrite tables (Not recommended)',
    'database_overwrite_tables_hint' => 'Overwrite the database tables when tables with same name and same prefix exist.
<br><span class="fw-bold">WARNING:</span> When no prefix is filled, all the database existing tables will be dropped.',
    'database_connect_btn_label' => 'Connect',
    'database_connection_success' => 'Successful database connection!',
    'database_connection_failed' => 'Database connection failed.',
    'database_pdo_connection_failed' => 'Can not connect to the database.',
    'database_import' => 'Database Tables',
    'database_import_title' => 'Database tables configuration',
    'database_import_required' => 'The database tables creation and data import are required to continue.',
    'database_import_hint' => 'Click on the <span class="fw-bold">:btnLabel</span> button below to start importing data to the "<span class="fw-bold">:database</span>" database.
<br>Note: The process may take some time based on the selected country. Please avoid closing or refreshing this window during this time.',
    'database_import_btn_label' => 'Create tables & Import data',
    'database_tables_configuration_success' => 'Database tables and seeds successfully imported.',
    'database_tables_with_same_prefix_exist' => 'Tables with the "<span class="fw-bold">:prefix</span>" prefix exist in the "<span class="fw-bold">:database</span>" database. Please <a href=":databaseInfoUrl">go back</a> to use another prefix for the new tables or to specify another database. You may also check the "<span class="fw-bold">Overwrite tables</span>" option below, allowing these tables dropping',
    'database_not_empty_and_prefix_not_filled' => 'The "<span class="fw-bold">:database</span>" database is not empty and tables prefix is not filled for the app installation. Please <a href=":databaseInfoUrl">go back</a> to fill a prefix for the new tables or to specify another database. You may also check the "<span class="fw-bold">Overwrite tables</span>" option below, allowing the database emptying.',
    'database_tables_dropping_failed' => 'Can not DROP certain tables. Please try again. If issue persists please clean up your database manually to continue.',
    'database_tables_creation_failed' => 'Can not create certain tables. Please make sure you have full privileges on the database and try again.',
    'database_data_import_failed' => 'Can not import all the app required data. Please try again.',
    'cron_jobs' => 'Cron Jobs',
    'cron_jobs_title' => 'Cron jobs',
    'cron_jobs_required' => 'Configuring "Cron Jobs" is important if you want to automate listings expiration.',
    'setting_up_cron_jobs' => 'Setting Up Cron Jobs',
    'cron_jobs_guide' => 'Insert the command lines below to your server crontab (If it is not already done).<br>NOTE: Below timings for running the cron jobs are the recommended, you can change it if you want. Click <a href=":articleUrl" target="_blank">here</a> for more information.',
    'cron_jobs_hint' => '<span class="fw-bold">Note:</span> The path to the PHP CLI binary, typically located at <code>/usr/bin/php</code>, may vary depending on your hosting provider. Common examples include <code>/usr/bin/php:phpVersion</code>, <code>/usr/bin/php</code>, or <code>/usr/lib/php</code>.',
    'finish' => 'Finish',
    'finish_title' => 'Finish',
    'finish_btn_label' => 'Finish',
    'finish_success' => 'Congratulations, you\'ve successfully installed :itemName (:itemTitle)',
    'finish_env_file_hint' => 'Remember that all your configurations were saved in <strong class="text-bold">[APP_ROOT]/.env</strong> file. You can change it when needed.',
    'finish_site_hint' => 'Now, you can go to your Admin Panel with link: <a class="text-bold" href=":adminLoginUrl">:adminLoginUrl</a>. Visit your website: <a class="text-bold" href=":homePageUrl" target="_blank">:homePageUrl</a>',
    'finish_help_hint' => 'If you\'re facing any issue, please visit our <a class="text-bold" href=":supportUrl" target="_blank">Help Center</a>',
    'finish_thanks' => 'Thank you for choosing :itemName - <a class="text-bold" href=":itemUrl" target="_blank">:itemLinkLabel</a>',
    'mail_sending_configuration' => 'Mail sending configuration',
    'mail_driver' => 'Mail Driver',
    'mail_driver_test' => 'Validate the Mail Driver\'s parameters',
    'from_email' => 'From email',
    'from_name' => 'From name',
    'php_mail' => 'PHP mail()',
    'sendmail' => 'Sendmail',
    'sendmail_path' => 'Sendmail path',
    'sendmail_path_hint' => 'Sendmail path (Can be updated later)',
    'smtp' => 'SMTP',
    'smtp_host' => 'SMTP Host',
    'smtp_port' => 'SMTP Port',
    'smtp_port_hint' => 'e.g. 25, 587, ...',
    'smtp_username' => 'SMTP Username',
    'smtp_password' => 'SMTP Password',
    'smtp_encryption' => 'SMTP Encryption',
    'smtp_encryption_hint' => 'e.g. tls, ssl, starttls',
    'smtp_protocol' => 'SMTP Protocol',
    'mailgun' => 'Mailgun',
    'mailgun_domain' => 'Mailgun Domain',
    'mailgun_secret' => 'Mailgun Secret',
    'mailgun_endpoint' => 'Mailgun Endpoint',
    'mailgun_host' => 'Mailgun Host',
    'mailgun_port' => 'Mailgun Port',
    'mailgun_username' => 'Mailgun Username',
    'mailgun_password' => 'Mailgun Password',
    'mailgun_encryption' => 'Mailgun Encryption',
    'postmark' => 'Postmark',
    'postmark_token' => 'Postmark Token',
    'postmark_host' => 'Postmark Host',
    'postmark_port' => 'Postmark Port',
    'postmark_username' => 'Postmark Username',
    'postmark_password' => 'Postmark Password',
    'postmark_encryption' => 'Postmark Encryption',
    'ses' => 'Amazon SES',
    'ses_key' => 'SES Key',
    'ses_secret' => 'SES Secret',
    'ses_region' => 'SES Region',
    'ses_token' => 'SES Token',
    'ses_token_hint' => '(Optional) To utilize AWS <a href="https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_temp_use-resources.html" target="_blank">temporary credentials</a> via a session token',
    'ses_host' => 'SES Host',
    'ses_port' => 'SES Port',
    'ses_username' => 'SES Username',
    'ses_password' => 'SES Password',
    'ses_encryption' => 'SES Encryption',
    'sparkpost' => 'Sparkpost',
    'sparkpost_secret' => 'Sparkpost Secret',
    'sparkpost_host' => 'Sparkpost Host',
    'sparkpost_port' => 'Sparkpost Port',
    'sparkpost_username' => 'Sparkpost Username',
    'sparkpost_password' => 'Sparkpost Password',
    'sparkpost_encryption' => 'Sparkpost Encryption',
    'resend' => 'Resend',
    'resend_api_key' => 'Resend API Key',
    'mailersend' => 'MailerSend',
    'mailersend_api_key' => 'MailerSend API Key',
    'brevo' => 'Brevo',
    'brevo_api_key' => 'Brevo API Key',
];
