CREATE TABLE `subshero`.`folder` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL COMMENT 'User -> ID' , `name` VARCHAR(255) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `subshero`.`plan` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL COMMENT 'User -> ID' , `type` TINYINT(1) NOT NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue' , `client_id` INT NOT NULL COMMENT 'Company -> ID' , `description` VARCHAR(255) NOT NULL , `frequency` TINYINT(1) NOT NULL DEFAULT '1' , `cycle` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' , `value` DOUBLE NOT NULL DEFAULT '0' , `currency_id` INT NOT NULL COMMENT 'Currency -> ID' , `recurring` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=No, 1=Yes' , `payment_date` DATE NULL DEFAULT NULL , `expiry_date` DATE NULL DEFAULT NULL , `contract_expiry` DATE NOT NULL , `homepage` VARCHAR(255) NULL DEFAULT NULL , `pay_gateway_id` INT NOT NULL DEFAULT '0' COMMENT '0=Free, Payment Gateway -> ID' , `folder_id` INT NOT NULL DEFAULT '0' COMMENT '0=No folder, Folder -> ID' , `notes` VARCHAR(255) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `subshero`.`alert` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL COMMENT 'User -> ID' , `plan_id` INT NOT NULL COMMENT 'Plan -> ID' , `title` VARCHAR(255) NULL DEFAULT NULL , `message` TEXT NULL DEFAULT NULL , `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `created_by` INT NOT NULL DEFAULT '0' COMMENT '0=System, User -> ID' , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `plan` ADD `created_at` DATETIME NULL DEFAULT NULL AFTER `notes`, ADD `created_by` INT NULL DEFAULT NULL COMMENT 'User -> ID' AFTER `created_at`;

ALTER TABLE `folder` ADD `created_at` DATETIME NOT NULL AFTER `name`, ADD `created_by` INT NOT NULL COMMENT 'User -> ID' AFTER `created_at`;

ALTER TABLE `alert` ADD `type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=Info, 1=Success, 2=Warning, 3=Danger' AFTER `plan_id`;

CREATE TABLE `subshero`.`invoice` ( `id` INT NOT NULL , `user_id` INT NOT NULL COMMENT 'User -> ID' , `plan_id` INT NOT NULL COMMENT 'Plan -> ID' , `inv_no` VARCHAR(20) NOT NULL COMMENT 'Invoice Number' , `total` DOUBLE NOT NULL DEFAULT '0' , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_by` INT NOT NULL DEFAULT '0' COMMENT '0=System, User -> ID' ) ENGINE = InnoDB;

ALTER TABLE `plan` CHANGE `contract_expiry` `contract_expiry` DATE NULL DEFAULT NULL;

CREATE TABLE `subshero`.`company` ( `id` INT NOT NULL , `name` VARCHAR(100) NOT NULL , `url` VARCHAR(255) NULL DEFAULT NULL , `logo` VARCHAR(255) NULL DEFAULT NULL , `created_at` DATETIME NULL DEFAULT NULL , `created_by` INT NULL DEFAULT NULL COMMENT 'User -> ID' ) ENGINE = InnoDB;

ALTER TABLE `users` ADD `role_id` INT NOT NULL COMMENT 'Role -> ID' AFTER `updated_at`, ADD `picture` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Picture path' AFTER `role_id`, ADD `created_by` INT NOT NULL DEFAULT '0' COMMENT '0=System, User -> ID' AFTER `picture`, ADD `description` VARCHAR(255) NULL DEFAULT NULL AFTER `created_by`;

CREATE TABLE `subshero`.`role` ( `id` INT NOT NULL AUTO_INCREMENT , `type` VARCHAR(100) NULL DEFAULT NULL , `name` VARCHAR(100) NOT NULL , `created_at` DATETIME NULL DEFAULT NULL , `created_by` INT NULL DEFAULT NULL COMMENT 'User -> ID' , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `subshero`.`permission` ( `id` INT NOT NULL AUTO_INCREMENT , `key` VARCHAR(100) NOT NULL , `val` VARCHAR(100) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `subshero`.`permission` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) NOT NULL , `title` VARCHAR(100) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;


ALTER TABLE `company`
ADD `client_id` int(11) NOT NULL COMMENT 'Company -> ID' AFTER `id`;


CREATE TABLE `category` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `client_id` int NOT NULL COMMENT 'Company -> ID',
  `user_id` int NOT NULL COMMENT 'User -> ID',
  `name` varchar(20) NOT NULL COMMENT 'Category name'
);

ALTER TABLE `plan`
ADD `company_name` varchar(50) COLLATE 'utf8_general_ci' NULL AFTER `notes`;

ALTER TABLE `plan`
CHANGE `value` `price` double NOT NULL DEFAULT '0' AFTER `cycle`;


CREATE TABLE `brand` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(20) NOT NULL,
  `description` varchar(50) NULL,
  `link` varchar(100) NULL,
  `picture` varchar(100) NULL COMMENT 'Picture path'
);



ALTER TABLE `plan`
ADD `category_id` int NOT NULL COMMENT 'Category -> ID' AFTER `user_id`;

ALTER TABLE `plan`
CHANGE `category_id` `category_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Category -> ID' AFTER `user_id`;

CREATE TABLE `tag` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `client_id` int NOT NULL DEFAULT '0' COMMENT 'Company -> ID',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'User -> ID',
  `name` varchar(20) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);


ALTER TABLE `plan`
RENAME TO `subscription`;

CREATE TABLE `subscription_tag` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `client_id` int NOT NULL DEFAULT '0' COMMENT 'Company -> ID',
  `user_id` int NOT NULL COMMENT 'User -> ID',
  `subscription_id` int NOT NULL COMMENT 'Subscription -> ID',
  `tag_id` int NOT NULL COMMENT 'Tag -> ID'
);

ALTER TABLE `subscription`
ADD `brand_id` int NOT NULL COMMENT 'Brand -> ID' AFTER `category_id`;







-- 1.0 ---------- New tables for Companies, Events ---------- Arabinda Ghosh ---------- 29 June, 2021 ----------
CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL COMMENT 'Picture path',
  `favicon` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'User -> ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `event_datetime` datetime NOT NULL,
  `event_type` tinyint(1) NOT NULL COMMENT '1=email, 2=admin email, 3=user registration',
  `event_type_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Initial, 1=Completed, 2=Updated, 3=Deleted',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) NOT NULL COMMENT '1=One time, 2=Recurring',
  `event_type_scdate` date DEFAULT NULL COMMENT 'Schedule date',
  `event_url` int(11) NOT NULL,
  `event_cron` tinyint(1) NOT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) NOT NULL COMMENT '1=Completed, 2=Error',
  `event_product_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- CREATE TABLE `config` (
--   `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
--   `key` varchar(30) NOT NULL,
--   `val` text NULL
-- );

CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `smtp_host` varchar(30) DEFAULT NULL,
  `smtp_port` varchar(5) DEFAULT NULL,
  `smtp_encryption` varchar(4) DEFAULT NULL,
  `smtp_username` varchar(30) DEFAULT NULL,
  `smtp_password` varchar(30) DEFAULT NULL,
  `smtp_sender_name` varchar(30) DEFAULT NULL,
  `smtp_sender_email` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `email_types` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NULL
);





-- 0.2 ---------- Events table ---------- Arabinda Ghosh ---------- 04 Aug, 2021 ----------
ALTER TABLE `events`
CHANGE `user_id` `user_id` INT(11) NULL DEFAULT NULL,
CHANGE `admin_id` `admin_id` INT(11) NULL DEFAULT NULL,
CHANGE `event_datetime` `event_datetime` DATETIME NULL DEFAULT NULL,
CHANGE `event_type` `event_type` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'email, admin_email, user_registration, user_payment, plan_change, subscriptions, products, customer_products, user_alert, import_export, user_settings, generic, app_update',
CHANGE `event_type_status` `event_type_status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'create, update, delete',
CHANGE `event_type_color` `event_type_color` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `event_type_schedule` `event_type_schedule` TINYINT(1) NULL DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
CHANGE `event_type_scdate` `event_type_scdate` DATE NULL DEFAULT NULL COMMENT 'Schedule date',
CHANGE `event_type_description` `event_type_description` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `event_url` `event_url` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `event_cron` `event_cron` TINYINT(1) NULL DEFAULT NULL COMMENT '1=to be processed by cron',
CHANGE `event_status` `event_status` TINYINT(1) NULL DEFAULT NULL COMMENT '1=Completed, 2=Error',
CHANGE `event_migrate` `event_migrate` TINYINT(1) NULL DEFAULT NULL COMMENT '0=No, 1=Yes',
CHANGE `event_product_id` `event_product_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `events`
ADD `event_timezone` varchar(30) NULL AFTER `event_datetime`,
ADD `table_name` varchar(30) NULL,
ADD `table_row_id` int(11) NULL AFTER `table_name`;



-- 0.2 ---------- Change existing clients to role_id = 2 ---------- Arabinda Ghosh ---------- 04 Aug, 2021 ----------
UPDATE `users` SET `role_id` = '2' WHERE `id` != '1';










-- 0.3 ---------- Category to Folder change in the entire app ---------- Arabinda Ghosh ---------- 05 Aug, 2021 ----------
ALTER TABLE `brands`
CHANGE `category` `folder` int(11) NULL COMMENT 'folder -> id' AFTER `tags`;

ALTER TABLE `category`
CHANGE `name` `name` varchar(20) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Folder name' AFTER `user_id`,
CHANGE `color` `color` varchar(10) COLLATE 'utf8_general_ci' NULL COMMENT 'Folder color' AFTER `name`,
RENAME TO `folder`;

ALTER TABLE `subscriptions`
CHANGE `category_id` `folder_id` int(11) NULL DEFAULT '0' COMMENT 'Folder -> id' AFTER `user_id`;


-- 0.3 ---------- Email templates changes ---------- Arabinda Ghosh ---------- 05 Aug, 2021 ----------
TRUNCATE TABLE `email_types`;
INSERT INTO `email_types` (`field_name`, `field_value`, `template_name`) VALUES
('User first name',	'user_first_name',	'user_registration'),
('User last name',	'user_last_name',	'user_registration'),
('User full name',	'user_full_name',	'user_registration'),
('User email',	'user_email',	'user_registration'),
('App url',	'app_url',	'user_registration'),
('Email verify url',	'email_verify_url',	'user_registration'),
('User first name',	'user_first_name',	'user_welcome'),
('User last name',	'user_last_name',	'user_welcome'),
('User full name',	'user_full_name',	'user_welcome'),
('User email',	'user_email',	'user_welcome'),
('App url',	'app_url',	'user_welcome'),
('User first name',	'user_first_name',	'password_reset_request'),
('User last name',	'user_last_name',	'password_reset_request'),
('User full name',	'user_full_name',	'password_reset_request'),
('User email',	'user_email',	'password_reset_request'),
('App url',	'app_url',	'password_reset_request'),
('Password reset url',	'password_reset_url',	'password_reset_request'),
('User first name',	'user_first_name',	'password_reset_success'),
('User last name',	'user_last_name',	'password_reset_success'),
('User full name',	'user_full_name',	'password_reset_success'),
('User email',	'user_email',	'password_reset_success'),
('App url',	'app_url',	'password_reset_success'),
('User first name',	'user_first_name',	'subscription_renew'),
('User last name',	'user_last_name',	'subscription_renew'),
('User full name',	'user_full_name',	'subscription_renew'),
('User email',	'user_email',	'subscription_renew'),
('App url',	'app_url',	'subscription_renew'),
('Subscription url',	'subscription_url',	'subscription_renew'),
('Subscription image url',	'subscription_image_url',	'subscription_renew'),
('Subscription renew date',	'subscription_renew_date',	'subscription_renew'),
('Subscription price',	'subscription_price',	'subscription_renew'),
('Subscription payment mode',	'subscription_payment_mode',	'subscription_renew'),
('Product name',	'product_name',	'subscription_renew'),
('Product type',	'product_type',	'subscription_renew'),
('Product description',	'product_description',	'subscription_renew'),
('User first name',	'user_first_name',	'plan_subscribe'),
('User last name',	'user_last_name',	'plan_subscribe'),
('User full name',	'user_full_name',	'plan_subscribe'),
('User email',	'user_email',	'plan_subscribe'),
('App url',	'app_url',	'plan_subscribe'),
('Plan name',	'plan_name',	'plan_subscribe'),
('Plan price',	'plan_price',	'plan_subscribe'),
('Plan type',	'plan_type',	'plan_subscribe'),
('Plan type',	'plan_type',	'plan_subscribe'),
('Plan expire date',	'plan_expire_date',	'plan_subscribe'),
('User first name',	'user_first_name',	'plan_expire'),
('User last name',	'user_last_name',	'plan_expire'),
('User full name',	'user_full_name',	'plan_expire'),
('User email',	'user_email',	'plan_expire'),
('App url',	'app_url',	'plan_expire'),
('Plan name',	'plan_name',	'plan_expire'),
('Plan price',	'plan_price',	'plan_expire'),
('Plan type',	'plan_type',	'plan_expire'),
('User first name',	'user_first_name',	'plan_payment_success'),
('User last name',	'user_last_name',	'plan_payment_success'),
('User full name',	'user_full_name',	'plan_payment_success'),
('User email',	'user_email',	'plan_payment_success'),
('App url',	'app_url',	'plan_payment_success'),
('Plan name',	'plan_name',	'plan_payment_success'),
('Plan price',	'plan_price',	'plan_payment_success'),
('Plan type',	'plan_type',	'plan_payment_success'),
('Plan expire date',	'plan_expire_date',	'plan_payment_success'),
('Transaction id',	'transaction_id',	'plan_payment_success'),
('Invoice url',	'invoice_url',	'plan_payment_success'),
('User first name',	'user_first_name',	'plan_payment_failure'),
('User last name',	'user_last_name',	'plan_payment_failure'),
('User full name',	'user_full_name',	'plan_payment_failure'),
('User email',	'user_email',	'plan_payment_failure'),
('App url',	'app_url',	'plan_payment_failure'),
('Plan name',	'plan_name',	'plan_payment_failure'),
('Plan price',	'plan_price',	'plan_payment_failure'),
('Plan type',	'plan_type',	'plan_payment_failure'),
('Transaction id',	'transaction_id',	'plan_payment_failure');


-- 0.3 ---------- Email templates body changes ---------- Arabinda Ghosh ---------- 06 Aug, 2021 ----------
ALTER TABLE `email_templates`
CHANGE `message` `body` mediumtext COLLATE 'utf8_general_ci' NULL AFTER `subject`;


-- 0.3 ---------- Email templates status changes ---------- Arabinda Ghosh ---------- 10 Aug, 2021 ----------
ALTER TABLE `email_templates`
ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Disabled, 1=Enabled' AFTER `is_default`;


-- 0.3 ---------- Email logs table ---------- Arabinda Ghosh ---------- 11 Aug, 2021 ----------
CREATE TABLE `email_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_name` tinytext,
  `from_email` tinytext,
  `to_name` tinytext,
  `to_email` tinytext,
  `subject` text,
  `body` mediumtext,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Pending, 1=Sent, 2=Failed',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT '0=Cron',
  `created_timezone` tinytext,
  `sent_at` datetime DEFAULT NULL,
  `sent_by` int(11) DEFAULT NULL COMMENT '0=Cron',
  `sent_timezone` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 0.3 ---------- Event table function field ---------- Arabinda Ghosh ---------- 11 Aug, 2021 ----------
ALTER TABLE `events`
ADD `event_type_function` tinytext COLLATE 'utf8_general_ci' NULL COMMENT 'Called function' AFTER `event_type_description`;









-- 0.4 ---------- Email types for subscription delete ---------- Arabinda Ghosh ---------- 16 Aug, 2021 ----------
INSERT INTO `email_types` (`field_name`, `field_value`, `template_name`) VALUES
('User first name',	'user_first_name',	'subscription_delete'),
('User last name',	'user_last_name',	'subscription_delete'),
('User full name',	'user_full_name',	'subscription_delete'),
('User email',	'user_email',	'subscription_delete'),
('App url',	'app_url',	'subscription_delete'),
('Subscription url',	'subscription_url',	'subscription_delete'),
('Subscription image url',	'subscription_image_url',	'subscription_delete'),
('Subscription renew date',	'subscription_delete_date',	'subscription_delete'),
('Subscription price',	'subscription_price',	'subscription_delete'),
('Subscription payment mode',	'subscription_payment_mode',	'subscription_delete'),
('Product name',	'product_name',	'subscription_delete'),
('Product type',	'product_type',	'subscription_delete'),
('Product description',	'product_description',	'subscription_delete');


-- 0.4 ---------- Subscription upcoming payment date ---------- Arabinda Ghosh ---------- 18 Aug, 2021 ----------
ALTER TABLE `subscriptions`
ADD `next_payment_date` date NULL COMMENT 'Upcoming payment date' AFTER `payment_date`;


-- 0.4 ---------- Subscription upcoming payment date ---------- Arabinda Ghosh ---------- 18 Aug, 2021 ----------
ALTER TABLE `events`
CHANGE `event_status` `event_status` tinyint(1) NULL COMMENT '0=Pending, 1=Completed, 2=Error' AFTER `event_cron`;


-- 0.4 ---------- Subscription timezone field ---------- Arabinda Ghosh ---------- 18 Aug, 2021 ----------
ALTER TABLE `subscriptions`
ADD `timezone` tinytext NULL AFTER `status`;


-- 0.4 ---------- Subscription scheduled date ---------- Arabinda Ghosh ---------- 20 Aug, 2021 ----------
ALTER TABLE `events`
CHANGE `event_type_scdate` `event_type_scdate` datetime NULL COMMENT 'Schedule date' AFTER `event_type_schedule`;










-- 0.4.1 ---------- Subscription upcoming payment date time ---------- Arabinda Ghosh ---------- 23 Aug, 2021 ----------
ALTER TABLE `subscriptions`
CHANGE `next_payment_date` `next_payment_date` datetime NULL COMMENT 'Upcoming payment date time' AFTER `payment_date`;










-- 0.6 ---------- Plan limit changes ---------- Arabinda Ghosh ---------- 24 Aug, 2021 ----------
ALTER TABLE `plans`
ADD `limit_subs` smallint NULL DEFAULT '0' AFTER `ltd_price_date`,
ADD `limit_folders` smallint NULL DEFAULT '0' AFTER `limit_subs`,
ADD `limit_tags` smallint NULL DEFAULT '0' AFTER `limit_folders`,
ADD `limit_contacts` smallint NULL DEFAULT '0' AFTER `limit_tags`,
ADD `limit_pmethods` smallint NULL DEFAULT '0' COMMENT 'payment methods' AFTER `limit_contacts`;

UPDATE `plans` SET `limit_subs` = '20', `limit_folders` = '3', `limit_tags` = '3', `limit_contacts` = '1', `limit_pmethods` = '3' WHERE `id` = '1';

UPDATE `plans` SET `number_of_users` = '1' WHERE `id` = '1';
UPDATE `plans` SET `number_of_users` = '1' WHERE `id` = '2';


-- 0.6 ---------- Webhook field ---------- Arabinda Ghosh ---------- 24 Aug, 2021 ----------
ALTER TABLE `config`
ADD `webhook_key` varchar(30) COLLATE 'utf8_general_ci' NULL;


-- 0.6 ---------- Plan total changes ---------- Arabinda Ghosh ---------- 25 Aug, 2021 ----------
ALTER TABLE `users_plans`
ADD `total_subs` smallint NOT NULL DEFAULT '0',
ADD `total_folders` smallint NOT NULL DEFAULT '0' AFTER `total_subs`,
ADD `total_tags` smallint NOT NULL DEFAULT '0' AFTER `total_folders`,
ADD `total_contacts` smallint NOT NULL DEFAULT '0' AFTER `total_tags`,
ADD `total_pmethods` smallint NOT NULL DEFAULT '0' COMMENT 'payment methods' AFTER `total_contacts`;


-- 0.6 ---------- SMTP credentials change ---------- Arabinda Ghosh ---------- 26 Aug, 2021 ----------
UPDATE `config` SET
`id` = '1',
`smtp_host` = 'mail.subshero.com',
`smtp_port` = '587',
`smtp_encryption` = 'tls',
`smtp_username` = 'notify@subshero.com',
`smtp_password` = 'TDcswj2fqK4oYwl',
`smtp_sender_name` = 'Subshero App',
`smtp_sender_email` = 'notify@subshero.com'
WHERE `id` = '1';


-- 0.6 ---------- User status ---------- Arabinda Ghosh ---------- 27 Aug, 2021 ----------
ALTER TABLE `users`
CHANGE `status` `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Inactive, 1=Active, 2=Disabled' AFTER `updated_at`;


-- 0.6 ---------- Email type for webhook user create ---------- Arabinda Ghosh ---------- 27 Aug, 2021 ----------
INSERT INTO `email_types` (`field_name`, `field_value`, `template_name`) VALUES
('User first name',	'user_first_name',	'webhook_user_create'),
('User last name',	'user_last_name',	'webhook_user_create'),
('User full name',	'user_full_name',	'webhook_user_create'),
('User email',	'user_email',	'webhook_user_create'),
('App url',	'app_url',	'webhook_user_create'),
('New password url',	'new_password_url',	'webhook_user_create');


-- 0.6 ---------- Tokens table ---------- Arabinda Ghosh ---------- 27 Aug, 2021 ----------
CREATE TABLE `tokens` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int NOT NULL,
  `type` tinytext NOT NULL,
  `token` tinytext NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active, 2=Used',
  `created_at` datetime NOT NULL,
  `created_by` int NOT NULL,
  `used_at` datetime NULL,
  `used_by` int NULL,
  `expire_at` datetime NULL
);


-- 0.6 ---------- Default in folder table ---------- Arabinda Ghosh ---------- 01 Sep, 2021 ----------
ALTER TABLE `folder`
ADD `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=Default' AFTER `color`;


-- 0.6 ---------- Default role change for users ---------- Arabinda Ghosh ---------- 01 Sep, 2021 ----------
ALTER TABLE `users`
CHANGE `role_id` `role_id` int(11) NOT NULL DEFAULT '2' COMMENT 'Role -> id' AFTER `id`;


ALTER TABLE `webhook_logs`
ADD `created_at` datetime NULL;

ALTER TABLE `plans`
ADD `product_id` int NULL COMMENT 'WooCommerce product id' AFTER `role_id`;




-- 0.6 ---------- Default role change for users ---------- Arabinda Ghosh ---------- 06 Sep, 2021 ----------
ALTER TABLE `users_billing`
DROP `total_subs`,
DROP `total_folders`,
DROP `total_tags`,
DROP `total_contacts`,
DROP `total_pmethods`;










-- 0.7 ---------- Token changes ---------- Arabinda Ghosh ---------- 13 Sep, 2021 ----------
ALTER TABLE `tokens`
ADD `table_name` tinytext NULL,
ADD `table_row_id` int NULL AFTER `table_name`,
ADD `email` varchar(50) NULL AFTER `user_id`;


-- 0.7 ---------- Subscription extra fields remove ---------- Arabinda Ghosh ---------- 13 Sep, 2021 ----------
ALTER TABLE `subscriptions`
DROP `frequency`,
DROP `cycle`,
CHANGE `billing_frequency` `billing_frequency` tinyint(1) NULL AFTER `tags`,
CHANGE `billing_cycle` `billing_cycle` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `billing_frequency`,
CHANGE `status` `status` tinyint(1) NULL DEFAULT '0' AFTER `billing_cycle`;


-- 0.7 ---------- Template name for email logs ---------- Arabinda Ghosh ---------- 13 Sep, 2021 ----------
ALTER TABLE `email_logs`
ADD `template_name` varchar(50) NULL COMMENT 'email_templates -> name' AFTER `id`;


-- 0.7 ---------- Event recurring for subscriptions ---------- Arabinda Ghosh ---------- 14 Sep, 2021 ----------
ALTER TABLE `events`
ADD `recurring` tinyint(1) NULL DEFAULT '0' COMMENT '0=No, 1=Yes';


-- 0.7 ---------- Subscription history table ---------- Arabinda Ghosh ---------- 14 Sep, 2021 ----------
CREATE TABLE `subscriptions_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User -> id',
  `folder_id` int(11) DEFAULT '0' COMMENT 'Folder -> id',
  `brand_id` int(11) NOT NULL COMMENT 'Brand -> id',
  `type` tinyint(1) DEFAULT NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue',
  `image` varchar(100) DEFAULT NULL COMMENT 'Image path',
  `description` varchar(255) DEFAULT NULL,
  `price` double(10,2) NOT NULL DEFAULT '0.00',
  `price_type` varchar(20) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL COMMENT 'Currency -> id',
  `recurring` tinyint(1) DEFAULT '0' COMMENT '0=No, 1=Yes',
  `payment_date` date DEFAULT NULL,
  `next_payment_date` datetime DEFAULT NULL COMMENT 'Upcoming payment date',
  `expiry_date` date DEFAULT NULL,
  `contract_expiry` date DEFAULT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `pay_gateway_id` int(11) DEFAULT '0' COMMENT '0=Free, Payment Gateway -> id',
  `note` varchar(255) DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  `discount_voucher` varchar(20) DEFAULT NULL,
  `payment_mode` tinyint(4) DEFAULT NULL,
  `include_notes` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `alert_type` tinyint(1) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `support_details` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `billing_frequency` tinyint(1) DEFAULT NULL,
  `billing_cycle` tinyint(1) DEFAULT NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly',
  `status` tinyint(1) DEFAULT '0',
  `timezone` tinytext,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'User -> id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 0.7 ---------- Subscription data table delete ---------- Arabinda Ghosh ---------- 14 Sep, 2021 ----------
DROP TABLE `subscriptions_data`;














CREATE TABLE `user_subs_cal` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userid` int NULL,
  `subsid` int NULL,
  `folder` tinytext NULL,
  `prodid` int NULL,
  `date` date NULL,
  `srvTzone` tinytext NULL,
  `month` tinytext NULL,
  `yr` tinytext NULL,
  `value` tinytext NULL,
  `cur` tinytext NULL,
  `b_value` tinytext NULL,
  `b_cur` tinytext NULL
);


CREATE TABLE `user_LTD_cal` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userid` int NULL,
  `subsid` int NULL,
  `folder` tinytext NULL,
  `prodid` int NULL,
  `date` date NULL,
  `srvTzone` tinytext NULL,
  `month` tinytext NULL,
  `yr` tinytext NULL,
  `value` tinytext NULL,
  `cur` tinytext NULL,
  `b_value` tinytext NULL,
  `b_cur` tinytext NULL
);






-- 0.8 ---------- plan_id field ---------- Arabinda Ghosh ---------- 22 Sep, 2021 ----------
ALTER TABLE `users` ADD `plan_id` INT NOT NULL DEFAULT '1' COMMENT 'plans -> id' AFTER `role_id`;



-- 0.9 ---------- Plan rename ---------- Arabinda Ghosh ---------- 23 Sep, 2021 ----------
UPDATE `plans` SET `name` = 'Free' WHERE `id` = '1';
UPDATE `plans` SET `name` = 'Pro' WHERE `id` = '2';
UPDATE `plans` SET `name` = 'Fam' WHERE `id` = '3';



-- 0.9 ---------- Token user_id ---------- Arabinda Ghosh ---------- 24 Sep, 2021 ----------
ALTER TABLE `tokens`
CHANGE `user_id` `user_id` int(11) NULL AFTER `id`;



-- 0.9 ---------- Subcription reports tables for Dahsboard ---------- Arabinda Ghosh ---------- 24 Sep, 2021 ----------
CREATE TABLE `user_ltd_cal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL,
  `folder` tinytext,
  `product_id` int(11) DEFAULT NULL,
  `server_timezone` tinytext,
  `date` date DEFAULT NULL,
  `month` varchar(10) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `value` decimal(10,0) DEFAULT NULL,
  `currency_code` varchar(3) DEFAULT NULL,
  `base_value` decimal(10,0) DEFAULT NULL,
  `base_currency` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_subs_cal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL,
  `folder` tinytext,
  `product_id` int(11) DEFAULT NULL,
  `server_timezone` tinytext,
  `date` date DEFAULT NULL,
  `month` varchar(10) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `value` decimal(10,0) DEFAULT NULL,
  `currency_code` varchar(3) DEFAULT NULL,
  `base_value` decimal(10,0) DEFAULT NULL,
  `base_currency` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





-- 0.9 ---------- reCAPTCHA v3 fields ---------- Arabinda Ghosh ---------- 26 Sep, 2021 ----------
ALTER TABLE `config`
ADD `recaptcha_site_key` varchar(50) COLLATE 'utf8_general_ci' NULL COMMENT 'reCAPTCHA v3 site key',
ADD `recaptcha_secret_key` varchar(50) COLLATE 'utf8_general_ci' NULL COMMENT 'reCAPTCHA v3 secret key' AFTER `recaptcha_site_key`;


-- 0.9 ---------- Subscription new fields ---------- Arabinda Ghosh ---------- 27 Sep, 2021 ----------
ALTER TABLE `subscriptions`
ADD `product_name` tinytext COLLATE 'utf8_general_ci' NULL AFTER `image`;

ALTER TABLE `subscriptions_history`
ADD `product_name` tinytext COLLATE 'utf8_general_ci' NULL AFTER `image`;


-- 0.9 ---------- Subscription status update ---------- Arabinda Ghosh ---------- 28 Sep, 2021 ----------
ALTER TABLE `subscriptions`
CHANGE `status` `status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Draft, 1=Active, 2=Cancel' AFTER `billing_cycle`;

ALTER TABLE `subscriptions_history`
CHANGE `status` `status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Draft, 1=Active, 2=Cancel' AFTER `billing_cycle`;


-- 0.9 ---------- Subscription new fields from products table ---------- Arabinda Ghosh ---------- 28 Sep, 2021 ----------
ALTER TABLE `subscriptions`
ADD `favicon` varchar(100) COLLATE 'utf8_general_ci' NULL AFTER `image`,
ADD `brandname` varchar(30) COLLATE 'utf8_general_ci' NULL AFTER `product_name`,
ADD `product_type` tinyint(1) NULL COMMENT '1=saas, 2=wordpress, 3=desktop app, ' AFTER `brandname`,
ADD `pricing_type` tinyint(1) NULL COMMENT '1=ltd, 2=subscription, 3=free account, 4=trial account' AFTER `timezone`,
ADD `currency_code` varchar(3) COLLATE 'utf8_general_ci' NULL AFTER `pricing_type`,
ADD `refund_days` smallint NULL AFTER `currency_code`;

ALTER TABLE `subscriptions_history`
ADD `favicon` varchar(100) COLLATE 'utf8_general_ci' NULL AFTER `image`,
ADD `brandname` varchar(30) COLLATE 'utf8_general_ci' NULL AFTER `product_name`,
ADD `product_type` tinyint(1) NULL COMMENT '1=saas, 2=wordpress, 3=desktop app, ' AFTER `brandname`,
ADD `pricing_type` tinyint(1) NULL COMMENT '1=ltd, 2=subscription, 3=free account, 4=trial account' AFTER `timezone`,
ADD `currency_code` varchar(3) COLLATE 'utf8_general_ci' NULL AFTER `pricing_type`,
ADD `refund_days` smallint NULL AFTER `currency_code`;


-- 0.9 ---------- Default products ---------- Arabinda Ghosh ---------- 29 Sep, 2021 ----------
INSERT INTO `products` (`id`, `admin_id`, `product_name`, `brandname`, `product_type`, `description`, `url`, `image`, `favicon`, `status`, `pricing_type`, `currency_code`, `price1_name`, `price1_value`, `price2_name`, `price2_value`, `price3_name`, `price3_value`, `refund_days`, `created_at`, `created_by`) VALUES
(1,	1,	'Subscription',	'Subscription',	1,	NULL,	NULL,	NULL,	NULL,	1,	2,	'USD',	NULL,	0,	NULL,	0,	NULL,	0,	NULL,	'2021-09-27 02:02:50',	1),
(2,	1,	'Trial',	'Trial',	1,	NULL,	NULL,	NULL,	NULL,	1,	4,	'USD',	NULL,	0,	NULL,	0,	NULL,	0,	NULL,	'2021-09-27 02:03:46',	1),
(3,	1,	'LTD',	'LTD',	1,	NULL,	NULL,	NULL,	NULL,	1,	1,	'USD',	NULL,	0,	NULL,	0,	NULL,	0,	NULL,	'2021-09-27 02:03:30',	1),
(4,	1,	'Revenue',	'Revenue',	1,	NULL,	NULL,	NULL,	NULL,	1,	3,	'USD',	NULL,	0,	NULL,	0,	NULL,	0,	NULL,	'2021-09-27 02:03:10',	1);


-- 0.9 ---------- reCAPTCHA v3 status field ---------- Arabinda Ghosh ---------- 01 Oct, 2021 ----------
ALTER TABLE `config`
ADD `recaptcha_status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Disabled, 1=Enabled' AFTER `webhook_key`;


-- 0.9 ---------- LTD history table ---------- Arabinda Ghosh ---------- 04 Oct, 2021 ----------
CREATE TABLE `ltd_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User -> id',
  `folder_id` int(11) DEFAULT '0' COMMENT 'Folder -> id',
  `brand_id` int(11) NOT NULL COMMENT 'Brand -> id',
  `type` tinyint(1) DEFAULT NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue',
  `image` varchar(100) DEFAULT NULL COMMENT 'Image path',
  `favicon` varchar(100) DEFAULT NULL,
  `product_name` tinytext,
  `brandname` varchar(30) DEFAULT NULL,
  `product_type` tinyint(1) DEFAULT NULL COMMENT '1=saas, 2=wordpress, 3=desktop app, ',
  `description` varchar(255) DEFAULT NULL,
  `price` double(10,2) NOT NULL DEFAULT '0.00',
  `price_type` varchar(20) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL COMMENT 'Currency -> id',
  `recurring` tinyint(1) DEFAULT '0' COMMENT '0=No, 1=Yes',
  `payment_date` date DEFAULT NULL,
  `next_payment_date` datetime DEFAULT NULL COMMENT 'Upcoming payment date',
  `expiry_date` date DEFAULT NULL,
  `contract_expiry` date DEFAULT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `pay_gateway_id` int(11) DEFAULT '0' COMMENT '0=Free, Payment Gateway -> id',
  `note` varchar(255) DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  `discount_voucher` varchar(20) DEFAULT NULL,
  `payment_mode` tinyint(4) DEFAULT NULL,
  `include_notes` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `alert_type` tinyint(1) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `support_details` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `billing_frequency` tinyint(1) DEFAULT NULL,
  `billing_cycle` tinyint(1) DEFAULT NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly',
  `status` tinyint(1) DEFAULT '0' COMMENT '0=Draft, 1=Active, 2=Cancel',
  `timezone` tinytext,
  `pricing_type` tinyint(1) DEFAULT NULL COMMENT '1=ltd, 2=subscription, 3=free account, 4=trial account',
  `currency_code` varchar(3) DEFAULT NULL,
  `refund_days` smallint(6) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'User -> id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 0.9 ---------- Currency conversion table ---------- Arabinda Ghosh ---------- 04 Oct, 2021 ----------
CREATE TABLE `currency_conversion` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `currency_code` varchar(3) NOT NULL,
  `conversion_rate` double NOT NULL
);


-- 0.10 ---------- Product types table ---------- Arabinda Ghosh ---------- 05 Oct, 2021 ----------
CREATE TABLE `product_types` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(50) NULL
);


-- 0.10 ---------- History id link ---------- Arabinda Ghosh ---------- 05 Oct, 2021 ----------
ALTER TABLE `user_subs_cal`
ADD `subscription_history_id` int NULL AFTER `subscription_id`;
ALTER TABLE `user_ltd_cal`
ADD `subscription_history_id` int NULL AFTER `subscription_id`;


-- 0.10 ---------- Billing fields on products ---------- Arabinda Ghosh ---------- 06 Oct, 2021 ----------
ALTER TABLE `products`
ADD `billing_frequency` tinyint(1) NULL AFTER `refund_days`,
ADD `billing_cycle` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `billing_frequency`;


-- 0.11 ---------- Base currency for subscription ---------- Arabinda Ghosh ---------- 07 Oct, 2021 ----------
ALTER TABLE `subscriptions`
ADD `base_value` decimal NULL COMMENT 'Base currency value' AFTER `refund_days`,
ADD `base_currency` varchar(3) NULL COMMENT 'Base currency code' AFTER `base_value`;

ALTER TABLE `subscriptions_history`
ADD `base_value` decimal NULL COMMENT 'Base currency value' AFTER `refund_days`,
ADD `base_currency` varchar(3) NULL COMMENT 'Base currency code' AFTER `base_value`;

ALTER TABLE `subscriptions`
DROP `currency_id`;

ALTER TABLE `subscriptions_history`
DROP `currency_id`;

ALTER TABLE `subscriptions`
CHANGE `brand_id` `brand_id` int(11) NOT NULL COMMENT 'Product -> id' AFTER `folder_id`,
CHANGE `favicon` `favicon` varchar(100) COLLATE 'utf8_general_ci' NULL COMMENT 'Product -> favicon' AFTER `image`,
CHANGE `brandname` `brandname` varchar(30) COLLATE 'utf8_general_ci' NULL COMMENT 'Product -> brandname' AFTER `product_name`,
CHANGE `product_type` `product_type` tinyint(1) NULL COMMENT 'Product -> product_type' AFTER `brandname`,
CHANGE `pricing_type` `pricing_type` tinyint(1) NULL COMMENT 'Product -> pricing_type; 1=ltd, 2=subscription, 3=free account, 4=trial account' AFTER `timezone`,
CHANGE `currency_code` `currency_code` varchar(3) COLLATE 'utf8_general_ci' NULL COMMENT 'Product -> currency_code' AFTER `pricing_type`,
CHANGE `refund_days` `refund_days` smallint(6) NULL COMMENT 'Product -> refund_days' AFTER `currency_code`;

ALTER TABLE `subscriptions_history`
CHANGE `brand_id` `brand_id` int(11) NOT NULL COMMENT 'Product -> id' AFTER `folder_id`,
CHANGE `favicon` `favicon` varchar(100) COLLATE 'utf8_general_ci' NULL COMMENT 'Product -> favicon' AFTER `image`,
CHANGE `brandname` `brandname` varchar(30) COLLATE 'utf8_general_ci' NULL COMMENT 'Product -> brandname' AFTER `product_name`,
CHANGE `product_type` `product_type` tinyint(1) NULL COMMENT 'Product -> product_type' AFTER `brandname`,
CHANGE `pricing_type` `pricing_type` tinyint(1) NULL COMMENT 'Product -> pricing_type; 1=ltd, 2=subscription, 3=free account, 4=trial account' AFTER `timezone`,
CHANGE `currency_code` `currency_code` varchar(3) COLLATE 'utf8_general_ci' NULL COMMENT 'Product -> currency_code' AFTER `pricing_type`,
CHANGE `refund_days` `refund_days` smallint(6) NULL COMMENT 'Product -> refund_days' AFTER `currency_code`;


-- 0.11 ---------- Folder field change ---------- Arabinda Ghosh ---------- 08 Oct, 2021 ----------
ALTER TABLE `user_subs_cal`
CHANGE `folder` `folder_id` int NULL AFTER `subscription_history_id`;

ALTER TABLE `user_ltd_cal`
CHANGE `folder` `folder_id` int NULL AFTER `subscription_history_id`;



-- 0.11 ---------- All currency conversion rate ---------- Arabinda Ghosh ---------- 08 Oct, 2021 ----------
TRUNCATE TABLE `currency_conversion`;
INSERT INTO `currency_conversion` (`id`, `currency_code`, `conversion_rate`) VALUES
(1,	'USD',	1),
(2,	'AUD',	0.73),
(3,	'BRL',	0.18),
(4,	'CAD',	0.8),
(5,	'CHF',	1.08),
(6,	'CZK',	0.045),
(7,	'DKK',	0.16),
(8,	'EUR',	1.15),
(9,	'GBP',	1.36),
(10,	'HKD',	0.13),
(11,	'HUF',	0.0032),
(12,	'ILS',	0.31),
(13,	'INR',	0.013),
(14,	'JPY',	0.0089),
(15,	'MXN',	0.048),
(16,	'MYR',	0.24),
(17,	'NOK',	0.12),
(18,	'NZD',	0.69),
(19,	'PHP',	0.02),
(20,	'PLN',	0.25),
(21,	'RUB',	0.014),
(22,	'SEK',	0.11),
(23,	'SGD',	0.74),
(24,	'THB',	0.029),
(25,	'TWD',	0.036);





-- 0.11 ---------- payment_date_upd field ---------- Arabinda Ghosh ---------- 08 Oct, 2021 ----------
ALTER TABLE `subscriptions`
ADD `payment_date_upd` datetime NULL AFTER `next_payment_date`;

ALTER TABLE `subscriptions_history`
ADD `payment_date_upd` datetime NULL AFTER `next_payment_date`;


ALTER TABLE `ltd_history`
ADD `payment_date_upd` datetime NULL AFTER `next_payment_date`;


ALTER TABLE `ltd_history`
ADD `base_value` decimal NULL COMMENT 'Base currency value' AFTER `refund_days`,
ADD `base_currency` varchar(3) NULL COMMENT 'Base currency code' AFTER `base_value`;




-- 0.96 ---------- CDN in config ---------- Arabinda Ghosh ---------- 27 Oct, 2021 ----------
ALTER TABLE `config`
ADD `cdn_base_url` varchar(50) COLLATE 'utf8_general_ci' NULL;








-- 0.98 ---------- Schedule datetime ---------- Arabinda Ghosh ---------- 15 Nov, 2021 ----------
ALTER TABLE `events`
CHANGE `event_type_scdate` `event_type_scdate` datetime NULL COMMENT 'Schedule date' AFTER `event_type_schedule`;



-- 0.993 ---------- User preferences ---------- Arabinda Ghosh ---------- 23 Nov, 2021 ----------
ALTER TABLE `users_profile`
ADD `billing_frequency` tinyint(1) NULL,
ADD `billing_cycle` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `billing_frequency`,
ADD `payment_mode` int NULL AFTER `billing_cycle`;


-- 0.993 ---------- Payment method ---------- Arabinda Ghosh ---------- 24 Nov, 2021 ----------
ALTER TABLE `users_payment_methods`
ADD `payment_type` varchar(20) NULL AFTER `user_id`;

ALTER TABLE `subscriptions`
CHANGE `payment_mode` `payment_mode` varchar(20) NULL;

ALTER TABLE `ltd_history`
CHANGE `payment_mode` `payment_mode` varchar(20) NULL;

ALTER TABLE `subscriptions_history`
CHANGE `payment_mode` `payment_mode` varchar(20) NULL;



-- 0.994 ---------- Payment mode fix ---------- Arabinda Ghosh ---------- 25 Nov, 2021 ----------
ALTER TABLE `users_profile`
CHANGE `payment_mode` `payment_mode` varchar(20) NULL;



-- 0.995 ---------- Tour status ---------- Arabinda Ghosh ---------- 25 Nov, 2021 ----------
ALTER TABLE `users_tour_status`
ADD `status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Incomplete, 1=Finished',
ADD `updated_at` datetime NULL AFTER `status`;


-- 0.996 ---------- Product type ---------- Arabinda Ghosh ---------- 27 Nov, 2021 ----------
ALTER TABLE `products`
CHANGE `pricing_type` `pricing_type` tinyint(1) NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue' AFTER `status`;


-- 0.997 ---------- Subscription type ---------- Arabinda Ghosh ---------- 27 Nov, 2021 ----------
ALTER TABLE `subscriptions`
CHANGE `pricing_type` `pricing_type` tinyint(1) NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue' AFTER `status`;

ALTER TABLE `subscriptions_history`
CHANGE `pricing_type` `pricing_type` tinyint(1) NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue' AFTER `status`;

ALTER TABLE `ltd_history`
CHANGE `pricing_type` `pricing_type` tinyint(1) NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue' AFTER `status`;



-- 0.999 ---------- Plan upgrade ---------- Arabinda Ghosh ---------- 30 Nov, 2021 ----------
ALTER TABLE `plans`
ADD `is_upgradable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=No, 1=Yes' AFTER `is_default`;


-- 1.1 ---------- Free plan is upgradable ---------- Arabinda Ghosh ---------- 02 Dec, 2021 ----------
UPDATE `plans` SET `is_upgradable` = '1' WHERE `id` = '1';



-- 1.11 ---------- Product price accept decimal ---------- Arabinda Ghosh ---------- 03 Dec, 2021 ----------
ALTER TABLE `products`
CHANGE `price1_value` `price1_value` decimal(10,2) NULL AFTER `price1_name`,
CHANGE `price2_value` `price2_value` decimal(10,2) NULL AFTER `price2_name`,
CHANGE `price3_value` `price3_value` decimal(10,2) NULL AFTER `price3_name`;

ALTER TABLE `ltd_history`
CHANGE `base_value` `base_value` decimal(10,2) NULL COMMENT 'Base currency value' AFTER `refund_days`;

ALTER TABLE `subscriptions`
CHANGE `base_value` `base_value` decimal(10,2) NULL COMMENT 'Base currency value' AFTER `refund_days`;

ALTER TABLE `subscriptions_history`
CHANGE `base_value` `base_value` decimal(10,2) NULL COMMENT 'Base currency value' AFTER `refund_days`;

ALTER TABLE `user_ltd_cal`
CHANGE `value` `value` decimal(10,2) NULL AFTER `year`,
CHANGE `base_value` `base_value` decimal(10,2) NULL AFTER `currency_code`;

ALTER TABLE `user_subs_cal`
CHANGE `value` `value` decimal(10,2) NULL AFTER `year`,
CHANGE `base_value` `base_value` decimal(10,2) NULL AFTER `currency_code`;




-- 1.11 ---------- Subscription ratings ---------- Arabinda Ghosh ---------- 03 Dec, 2021 ----------
ALTER TABLE `subscriptions`
ADD `rating` tinyint(1) NULL DEFAULT '0' COMMENT '1-10 ratings' AFTER `base_currency`;

ALTER TABLE `subscriptions_history`
ADD `rating` tinyint(1) NULL DEFAULT '0' COMMENT '1-10 ratings' AFTER `base_currency`;

ALTER TABLE `ltd_history`
ADD `rating` tinyint(1) NULL DEFAULT '0' COMMENT '1-10 ratings' AFTER `base_currency`;



-- 1.11 ---------- Subscription payment_mode_id ---------- Arabinda Ghosh ---------- 04 Dec, 2021 ----------
ALTER TABLE `subscriptions`
ADD `payment_mode_id` int NULL COMMENT 'users_payment_methods -> id' AFTER `payment_mode`;

ALTER TABLE `subscriptions_history`
ADD `payment_mode_id` int NULL COMMENT 'users_payment_methods -> id' AFTER `payment_mode`;

ALTER TABLE `ltd_history`
ADD `payment_mode_id` int NULL COMMENT 'users_payment_methods -> id' AFTER `payment_mode`;

ALTER TABLE `users_profile`
ADD `payment_mode_id` int NULL COMMENT 'users_payment_methods -> id';





-- 1.12 ---------- Indonesian Rupiah currency ---------- Arabinda Ghosh ---------- 06 Dec, 2021 ----------
INSERT INTO `currency_conversion` (`currency_code`, `conversion_rate`)
VALUES ('IDR', '0.000069');




-- 1.13 ---------- Account reset datetime ---------- Arabinda Ghosh ---------- 11 Dec, 2021 ----------
ALTER TABLE `users`
ADD `reset_at` datetime NULL COMMENT 'Last reset datetime' AFTER `updated_at`;




-- 1.16 ---------- Subscription payment date changes ---------- Arabinda Ghosh ---------- 14 Dec, 2021 ----------
ALTER TABLE `subscriptions`
CHANGE `next_payment_date` `next_payment_date` date NULL COMMENT 'Upcoming payment date' AFTER `payment_date`,
CHANGE `payment_date_upd` `payment_date_upd` date NULL AFTER `next_payment_date`;

ALTER TABLE `subscriptions_history`
CHANGE `next_payment_date` `next_payment_date` date NULL COMMENT 'Upcoming payment date' AFTER `payment_date`,
CHANGE `payment_date_upd` `payment_date_upd` date NULL AFTER `next_payment_date`;

ALTER TABLE `ltd_history`
CHANGE `next_payment_date` `next_payment_date` date NULL COMMENT 'Upcoming payment date' AFTER `payment_date`,
CHANGE `payment_date_upd` `payment_date_upd` date NULL AFTER `next_payment_date`;




-- 1.17 ---------- Subscription billing date calculation type ---------- Arabinda Ghosh ---------- 15 Dec, 2021 ----------
ALTER TABLE `subscriptions`
ADD `billing_type` tinyint(1) NULL DEFAULT '1' COMMENT '1=Calculate by days, 2=Calculate by date' AFTER `billing_cycle`;

ALTER TABLE `subscriptions_history`
ADD `billing_type` tinyint(1) NULL DEFAULT '1' COMMENT '1=Calculate by days, 2=Calculate by date' AFTER `billing_cycle`;

ALTER TABLE `ltd_history`
ADD `billing_type` tinyint(1) NULL DEFAULT '1' COMMENT '1=Calculate by days, 2=Calculate by date' AFTER `billing_cycle`;

ALTER TABLE `users_profile`
ADD `billing_type` tinyint(1) NULL DEFAULT '1' COMMENT '1=Calculate by days, 2=Calculate by date' AFTER `billing_cycle`;



-- 1.20 ---------- Contact status ---------- Arabinda Ghosh ---------- 21 Dec, 2021 ----------
ALTER TABLE `users_contacts`
ADD `status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Inactive, 1=Active';

-- 1.20 ---------- Email variables for new mail template ---------- Arabinda Ghosh ---------- 21 Dec, 2021 ----------
INSERT INTO `email_types` (`field_name`, `field_value`, `template_name`) VALUES
('Contact full name',	'contact_full_name',	'subscription_renew_contact'),
('Contact email',	'contact_email',	'subscription_renew_contact'),
('App url',	'app_url',	'subscription_renew_contact'),
('Subscription url',	'subscription_url',	'subscription_renew_contact'),
('Subscription image url',	'subscription_image_url',	'subscription_renew_contact'),
('Subscription renew date',	'subscription_renew_contact_date',	'subscription_renew_contact'),
('Subscription price',	'subscription_price',	'subscription_renew_contact'),
('Subscription payment mode',	'subscription_payment_mode',	'subscription_renew_contact'),
('Product name',	'product_name',	'subscription_renew_contact'),
('Product type',	'product_type',	'subscription_renew_contact'),
('Product description',	'product_description',	'subscription_renew_contact');



-- 1.21 ---------- Refund status for subscription ---------- Arabinda Ghosh ---------- 22 Dec, 2021 ----------
ALTER TABLE `subscriptions`
CHANGE `status` `status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Draft, 1=Active, 2=Cancel, 3=Refund' AFTER `billing_type`;

ALTER TABLE `ltd_history`
CHANGE `status` `status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Draft, 1=Active, 2=Cancel, 3=Refund' AFTER `billing_type`;

ALTER TABLE `subscriptions_history`
CHANGE `status` `status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Draft, 1=Active, 2=Cancel, 3=Refund' AFTER `billing_type`;




-- 1.21 ---------- account_reset event type ---------- Arabinda Ghosh ---------- 22 Dec, 2021 ----------
ALTER TABLE `events`
CHANGE `event_type` `event_type` varchar(30) COLLATE 'utf8_general_ci' NULL COMMENT 'email, admin_email, user_registration, user_payment, plan_change, subscriptions, products, customer_products, user_alert, import_export, user_settings, generic, app_update, account_reset' AFTER `event_timezone`;



-- 1.22 ---------- New currency ---------- Arabinda Ghosh ---------- 23 Dec, 2021 ----------
INSERT INTO `currency_conversion` (`currency_code`, `conversion_rate`) VALUES
('ARS', '0.0098'),
('TRY', '0.086'),
('CLP', '0.0012');




-- 1.23 ---------- Cron misc config ---------- Arabinda Ghosh ---------- 24 Dec, 2021 ----------
ALTER TABLE `config`
ADD `cron_misc_days` smallint NULL DEFAULT '0' COMMENT 'Miscellaneous cron days before';




-- 1.25 ---------- Product categories ---------- Arabinda Ghosh ---------- 30 Dec, 2021 ----------
CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `products`
ADD `category_id` int(11) NULL COMMENT 'product_categories -> id' AFTER `admin_id`;



-- 1.25 ---------- Subscription categories from products ---------- Arabinda Ghosh ---------- 31 Dec, 2021 ----------
ALTER TABLE `subscriptions`
ADD `category_id` int(11) NULL COMMENT 'product_categories -> id' AFTER `brand_id`;

ALTER TABLE `subscriptions_history`
ADD `category_id` int(11) NULL COMMENT 'product_categories -> id' AFTER `brand_id`;

ALTER TABLE `ltd_history`
ADD `category_id` int(11) NULL COMMENT 'product_categories -> id' AFTER `brand_id`;






-- 1.26 ---------- Product categories changes ---------- Arabinda Ghosh ---------- 03 Jan, 2022 ----------
ALTER TABLE `product_categories`
ADD `user_id` int NULL AFTER `id`;



-- 1.26 ---------- Product platform changes ---------- Arabinda Ghosh ---------- 03 Jan, 2022 ----------
CREATE TABLE `product_platforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `products`
ADD `sub_ltd` tinyint(1) NULL COMMENT '0=Subscription, 1=Lifetime' AFTER `status`,
ADD `launch_year` varchar(4) NULL AFTER `sub_ltd`,
ADD `sub_platform` int NULL COMMENT 'product_platforms -> id' AFTER `launch_year`;



-- 1.26 ---------- Subscription default category ---------- Arabinda Ghosh ---------- 03 Jan, 2022 ----------
ALTER TABLE `subscriptions`
CHANGE `category_id` `category_id` int(11) NULL DEFAULT '1' COMMENT 'product_categories -> id' AFTER `brand_id`;
ALTER TABLE `subscriptions_history`
CHANGE `category_id` `category_id` int(11) NULL DEFAULT '1' COMMENT 'product_categories -> id' AFTER `brand_id`;
ALTER TABLE `ltd_history`
CHANGE `category_id` `category_id` int(11) NULL DEFAULT '1' COMMENT 'product_categories -> id' AFTER `brand_id`;

UPDATE `subscriptions` SET `category_id` = '1';
UPDATE `subscriptions_history` SET `category_id` = '1';
UPDATE `ltd_history` SET `category_id` = '1';






-- 1.29 ---------- Alert time_cycle ---------- Arabinda Ghosh ---------- 07 Jan, 2022 ----------
ALTER TABLE `users_alert_preferences`
CHANGE `time_period` `time_period` tinyint(1) NULL AFTER `user_id`,
CHANGE `time_cycle` `time_cycle` tinyint(1) NULL COMMENT '1=Days Before, 2=Days After, 3=Day Before Refund date' AFTER `time_period`,
CHANGE `monthly_report` `monthly_report` tinyint(1) NULL AFTER `time`;



-- 1.29 ---------- Subscription refund days ---------- Arabinda Ghosh ---------- 07 Jan, 2022 ----------
INSERT INTO `email_types` (`field_name`, `field_value`, `template_name`) VALUES
('User first name',	'user_first_name',	'subscription_refund'),
('User last name',	'user_last_name',	'subscription_refund'),
('User full name',	'user_full_name',	'subscription_refund'),
('User email',	'user_email',	'subscription_refund'),
('App url',	'app_url',	'subscription_refund'),
('Subscription url',	'subscription_url',	'subscription_refund'),
('Subscription image url',	'subscription_image_url',	'subscription_refund'),
('Subscription renew date',	'subscription_renew_date',	'subscription_refund'),
('Subscription price',	'subscription_price',	'subscription_refund'),
('Subscription payment mode',	'subscription_payment_mode',	'subscription_refund'),
('Subscription refund date',	'subscription_refund_date',	'subscription_refund'),
('Subscription refund days left',	'subscription_refund_days_left',	'subscription_refund'),
('Product name',	'product_name',	'subscription_refund'),
('Product type',	'product_type',	'subscription_refund'),
('Product description',	'product_description',	'subscription_refund');


ALTER TABLE `events`
CHANGE `event_type` `event_type` varchar(30) COLLATE 'utf8_general_ci' NULL COMMENT 'email, admin_email, user_registration, user_payment, plan_change, subscriptions, products, customer_products, user_alert, import_export, user_settings, generic, app_update, account_reset, email_refund' AFTER `event_timezone`;


ALTER TABLE `subscriptions`
ADD `refund_date` date NULL AFTER `refund_days`;

ALTER TABLE `subscriptions_history`
ADD `refund_date` date NULL AFTER `refund_days`;

ALTER TABLE `ltd_history`
ADD `refund_date` date NULL AFTER `refund_days`;



-- 1.29 ---------- Subscription refund days ---------- Arabinda Ghosh ---------- 08 Jan, 2022 ----------
ALTER TABLE `products`
ADD `app_url` varchar(100) COLLATE 'utf8_general_ci' NULL AFTER `url`;









-- 1.30 ---------- Alert profile limit ---------- Arabinda Ghosh ---------- 07 Jan, 2022 ----------
ALTER TABLE `plans`
ADD `limit_alert_profile` smallint(6) NULL DEFAULT '0' COMMENT 'Alert profiles' AFTER `limit_pmethods`;


-- 1.30 ---------- Alert profile new fields ---------- Arabinda Ghosh ---------- 07 Jan, 2022 ----------
ALTER TABLE `users_alert`
ADD `time_period` tinyint(1) NULL COMMENT 'Number of days',
ADD `time_cycle` tinyint(1) NULL COMMENT '1=Days Before' AFTER `time_period`,
ADD `time` time NULL COMMENT 'Time to send the alert' AFTER `time_cycle`,
ADD `alert_condition` tinyint(1) NULL COMMENT '1=Day before due date, 2=Before refund period of lifetime' AFTER `time`,
ADD `alert_contact` tinyint(1) NULL COMMENT 'users_contacts -> id' AFTER `alert_condition`,
ADD `alert_type` tinyint(1) NULL COMMENT '1=Email, 2=Browser, 3=Both' AFTER `alert_contact`,
ADD `alert_name` varchar(30) NULL COMMENT 'Name of the alert' AFTER `alert_type`,
ADD `timezone` varchar(50) NULL COMMENT 'For future use' AFTER `alert_name`,
ADD `alert_subs_type` tinyint(1) NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue' AFTER `timezone`;


-- 1.30 ---------- Alert profile limit ---------- Arabinda Ghosh ---------- 10 Jan, 2022 ----------
ALTER TABLE `users_plans`
ADD `total_alert_profile` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Alert profiles';


ALTER TABLE `users_alert`
CHANGE `time_period` `time_period` smallint NULL COMMENT 'Number of days' AFTER `user_id`;
ALTER TABLE `users_alert`
CHANGE `alert_contact` `alert_contact` int NULL COMMENT 'users_contacts -> id' AFTER `alert_condition`;
ALTER TABLE `users_alert`
CHANGE `alert_subs_type` `alert_subs_type` tinyint(1) NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime' AFTER `timezone`;


-- 1.30 ---------- Alert profile default record ---------- Arabinda Ghosh ---------- 12 Jan, 2022 ----------
TRUNCATE `users_alert`;
INSERT INTO `users_alert` (`user_id`, `time_period`, `time_cycle`, `time`, `alert_condition`, `alert_contact`, `alert_type`, `alert_name`, `timezone`, `alert_subs_type`)
VALUES ('0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);










-- 1.30 ---------- Alert profile limit ---------- Arabinda Ghosh ---------- 12 Jan, 2022 ----------
ALTER TABLE `products`
ADD `ltdval_price` decimal(10,2) NULL AFTER `billing_cycle`,
ADD `ltdval_cycle` tinyint(1) NULL AFTER `ltdval_price`,
ADD `ltdval_frequency` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `ltdval_cycle`;




ALTER TABLE `subscriptions`
ADD `alert_id` int(11) NULL DEFAULT '1' COMMENT 'users_alert -> id' AFTER `category_id`;

ALTER TABLE `subscriptions_history`
ADD `alert_id` int(11) NULL DEFAULT '1' COMMENT 'users_alert -> id' AFTER `category_id`;

ALTER TABLE `ltd_history`
ADD `alert_id` int(11) NULL DEFAULT '1' COMMENT 'users_alert -> id' AFTER `category_id`;






-- 1.30 ---------- Alert profile default ---------- Arabinda Ghosh ---------- 14 Jan, 2022 ----------
ALTER TABLE `users_alert`
ADD `is_default` tinyint(1) NULL DEFAULT '0' COMMENT '1=Default' AFTER `user_id`;

ALTER TABLE `users_alert`
CHANGE `time_period` `time_period` int NULL COMMENT 'Number of days' AFTER `is_default`;




-- 1.30 ---------- Product characters limit increase ---------- Arabinda Ghosh ---------- 15 Jan, 2022 ----------
ALTER TABLE `products`
CHANGE `product_name` `product_name` varchar(50) COLLATE 'utf8_general_ci' NOT NULL AFTER `category_id`,
CHANGE `brandname` `brandname` varchar(50) COLLATE 'utf8_general_ci' NULL AFTER `product_name`;




-- 1.31 ---------- alert_subs_type comment fix ---------- Arabinda Ghosh ---------- 18 Jan, 2022 ----------
ALTER TABLE `users_alert`
CHANGE `alert_subs_type` `alert_subs_type` tinyint(1) NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime' AFTER `timezone`;

ALTER TABLE `users_alert`
CHANGE `alert_condition` `alert_condition` tinyint(1) NULL COMMENT '1=Before Due Date, 2=Before Refund Date' AFTER `time`;





-- 1.32 ---------- Alert default data ---------- Arabinda Ghosh ---------- 20 Jan, 2022 ----------
ALTER TABLE `users_alert`
CHANGE `alert_condition` `alert_condition` tinyint(1) NULL COMMENT '1=All, 2=Before Due Date, 3=Before Refund Date' AFTER `time`,
CHANGE `alert_type` `alert_type` tinyint(1) NULL COMMENT '1=All, 2=Email, 3=Browser' AFTER `alert_contact`;

-- INSERT INTO `users_alert` (`id`, `user_id`, `is_default`, `time_period`, `time_cycle`, `time`, `alert_condition`, `alert_contact`, `alert_type`, `alert_name`, `timezone`, `alert_subs_type`) VALUES
-- (1,	0,	1,	7,	NULL,	'10:00:00',	1,	NULL,	1,	'System Defaut',	'America/New_York',	1);

UPDATE `users_alert` SET
`id` = '1',
`user_id` = '0',
`is_default` = '1',
`time_period` = '7',
`time_cycle` = NULL,
`time` = '10:00:00',
`alert_condition` = '1',
`alert_contact` = NULL,
`alert_type` = '1',
`alert_name` = 'System Default',
`timezone` = 'America/New_York',
`alert_subs_type` = '1'
WHERE ((`id` = '1'));





-- 1.35 ---------- Alert profiles ---------- Arabinda Ghosh ---------- 25 Jan, 2022 ----------
ALTER TABLE `plans`
CHANGE `limit_alert_profile` `limit_alert_profiles` smallint(6) NULL DEFAULT '0' COMMENT 'Alert profiles' AFTER `limit_pmethods`;
ALTER TABLE `users_plans`
CHANGE `total_alert_profile` `total_alert_profiles` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Alert profiles' AFTER `total_pmethods`;




-- 1.36 ---------- Product new fields ---------- Arabinda Ghosh ---------- 26 Jan, 2022 ----------
ALTER TABLE `products`
ADD `featured` tinyint(1) NULL COMMENT '0=No, 1=Yes' AFTER `description`,
ADD `url_app` varchar(100) COLLATE 'utf8_general_ci' NULL AFTER `url`;

ALTER TABLE `products`
ADD `rating` tinyint(1) NULL COMMENT '1-10 ratings' AFTER `featured`;

ALTER TABLE `products`
ADD `pop_factor` int NULL COMMENT 'Popularity factor' AFTER `rating`;




-- 1.37 ---------- Subscription ltdval fields ---------- Arabinda Ghosh ---------- 27 Jan, 2022 ----------
ALTER TABLE `subscriptions`
ADD `ltdval_price` decimal(10,2) NULL AFTER `billing_type`,
ADD `ltdval_frequency` tinyint(1) NULL AFTER `ltdval_price`,
ADD `ltdval_cycle` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `ltdval_frequency`;

ALTER TABLE `subscriptions_history`
ADD `ltdval_price` decimal(10,2) NULL AFTER `billing_type`,
ADD `ltdval_frequency` tinyint(1) NULL AFTER `ltdval_price`,
ADD `ltdval_cycle` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `ltdval_frequency`;

ALTER TABLE `ltd_history`
ADD `ltdval_price` decimal(10,2) NULL AFTER `billing_type`,
ADD `ltdval_frequency` tinyint(1) NULL AFTER `ltdval_price`,
ADD `ltdval_cycle` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `ltdval_frequency`;


-- 1.37 ---------- Product ltdval fields ---------- Arabinda Ghosh ---------- 29 Jan, 2022 ----------
ALTER TABLE `products`
CHANGE `ltdval_frequency` `ltdval_frequency` tinyint(1) NULL AFTER `ltdval_price`,
CHANGE `ltdval_cycle` `ltdval_cycle` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `ltdval_frequency`;





-- 1.51 ---------- API token table ---------- Arabinda Ghosh ---------- 02 Mar, 2022 ----------
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret_key` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- 1.51 ---------- Config length changes ---------- Arabinda Ghosh ---------- 10 Mar, 2022 ----------
ALTER TABLE `config`
CHANGE `smtp_host` `smtp_host` varchar(50) COLLATE 'utf8_general_ci' NULL AFTER `id`,
CHANGE `smtp_username` `smtp_username` varchar(50) COLLATE 'utf8_general_ci' NULL AFTER `smtp_encryption`,
CHANGE `smtp_password` `smtp_password` varchar(50) COLLATE 'utf8_general_ci' NULL AFTER `smtp_username`,
CHANGE `smtp_sender_name` `smtp_sender_name` varchar(50) COLLATE 'utf8_general_ci' NULL AFTER `smtp_password`,
CHANGE `smtp_sender_email` `smtp_sender_email` varchar(50) COLLATE 'utf8_general_ci' NULL AFTER `smtp_sender_name`,
CHANGE `webhook_key` `webhook_key` varchar(50) COLLATE 'utf8_general_ci' NULL AFTER `smtp_sender_email`;


-- 1.51 ---------- Extension settings ---------- Arabinda Ghosh ---------- 12 Mar, 2022 ----------
CREATE TABLE `extension_settings` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int NULL,
  `auto_detect_subscriptions` int(11) NULL COMMENT '0=No, 1=Yes',
  `browser_notifications` int NULL COMMENT '0=No, 1=Yes',
  `notify_before` int NULL COMMENT 'Notify before days',
  `created_at` datetime NULL,
  `updated_at` datetime NULL
);






-- 1.52 ---------- Extension notifications ---------- Arabinda Ghosh ---------- 21 Mar, 2022 ----------
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `subscription_id` int(11) DEFAULT NULL COMMENT 'subscriptions -> id',
  `alert_id` int(11) DEFAULT NULL COMMENT 'users_alert -> id',
  `type` tinyint(1) DEFAULT NULL,
  `title` text,
  `message` text,
  `image` text,
  `read` tinyint(1) DEFAULT '0' COMMENT '0=No, 1=Yes',
  `created_at` datetime DEFAULT NULL,
  `send_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;








-- 1.53 ---------- Extension settings changes ---------- Arabinda Ghosh ---------- 24 Mar, 2022 ----------
ALTER TABLE `extension_settings`
CHANGE `notify_before` `notify_before_days` int(11) NULL COMMENT 'Notify before days' AFTER `browser_notifications`;



-- 1.55 ---------- Products description length changes ---------- Arabinda Ghosh ---------- 28 Mar, 2022 ----------
ALTER TABLE `products`
CHANGE `description` `description` varchar(150) COLLATE 'utf8_general_ci' NULL AFTER `product_type`;



-- 1.56 ---------- Plan users team ---------- Arabinda Ghosh ---------- 29 Mar, 2022 ----------
ALTER TABLE `user_team`
CHANGE `id` `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST,
CHANGE `team_id` `team_user_id` int(11) NULL COMMENT 'users -> id' AFTER `id`,
CHANGE `user_id` `pro_user_id` int(11) NULL COMMENT 'users -> id' AFTER `team_user_id`,
ADD `created_at` datetime NULL,
ADD `updated_at` datetime NULL AFTER `created_at`,
RENAME TO `users_teams`;



-- 1.56 ---------- Plan type for team ---------- Arabinda Ghosh ---------- 29 Mar, 2022 ----------
ALTER TABLE `plans`
ADD `limit_teams` smallint(6) NULL DEFAULT '0' COMMENT 'Team accounts' AFTER `limit_alert_profiles`;

ALTER TABLE `users_plans`
ADD `total_teams` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Team accounts';

ALTER TABLE `users`
ADD `team_user_id` int(11) NULL COMMENT 'users -> id' AFTER `plan_id`;

ALTER TABLE `users_teams`
ADD `pro_user_email` varchar(50) NULL COMMENT 'users -> email' AFTER `pro_user_id`;

ALTER TABLE `users_teams`
ADD `status` tinyint(1) NULL COMMENT '1=Sent, 2=Accepted' AFTER `pro_user_email`;

ALTER TABLE `users_teams`
ADD `created_by` int NULL COMMENT 'users -> id' AFTER `created_at`;

ALTER TABLE `events`
CHANGE `event_type` `event_type` varchar(30) COLLATE 'utf8_general_ci' NULL COMMENT 'email, admin_email, user_registration, user_payment, plan_change, subscriptions, products, customer_products, user_alert, import_export, user_settings, generic, app_update, account_reset, email_refund, team_user_invite' AFTER `event_timezone`;



-- 1.56 ---------- Team plan invite email ---------- Arabinda Ghosh ---------- 30 Mar, 2022 ----------
INSERT INTO `email_types` (`field_name`, `field_value`, `template_name`) VALUES
('User first name',	'user_first_name',	'team_user_invite'),
('User last name',	'user_last_name',	'team_user_invite'),
('User full name',	'user_full_name',	'team_user_invite'),
('User email',	'user_email',	'team_user_invite'),
('App url',	'app_url',	'team_user_invite'),
('Invitation url',	'invitation_url',	'team_user_invite');

INSERT INTO `email_templates` (`user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(1,	'team_user_invite',	'Welcome To Subshero',	'<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                        <tbody>\r\n                                                            <tr>\r\n                                                                <td style=\"width: 152px;\">\r\n                                                                    <img\r\n                                                                        height=\"auto\"\r\n                                                                        src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                        style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                        width=\"152\"\r\n                                                                    />\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </tbody>\r\n                                                    </table>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 12px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 12px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 12px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 20px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Welcome</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:540px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 16px; font-family: \'DM Sans\';\"><strong>Hello {user_first_name},&nbsp;</strong></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p>\r\n                                                        <span style=\"font-family: \'DM Sans\'; font-size: 16px;\">To accept the invitation, just press the button below.<br /></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"center\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 4px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 4px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{invitation_url}\" style=\"font-family: \'DM Sans\'; font-size: 18px;\"><strong>Accept The Invitation</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">If it doesn\'t work, copy paste the following link in your browser</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{invitation_url}</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p>\r\n                                                        <span style=\"font-family: Roboto; font-size: 16px;\"><span style=\"font-family: \'DM Sans\';\">If you did not request this invitation, you can safely ignore this email.</span><br /></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Cheers,</span></p>\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Team Subshero</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>',	1,	1,	'2021-08-31 08:45:45',	1);







-- 1.57 ---------- Gravitec.net ---------- Arabinda Ghosh ---------- 06 Apr, 2022 ----------
ALTER TABLE `config`
ADD `gravitec_app_key` varchar(32) NULL COMMENT 'Gravitec.net App key',
ADD `gravitec_app_secret` varchar(32) NULL COMMENT 'Gravitec.net App secret' AFTER `gravitec_app_key`;

ALTER TABLE `config`
ADD `gravitec_status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Disabled, 1=Enabled' AFTER `cron_misc_days`;





-- 1.57 ---------- Gravitec.net registers table ---------- Arabinda Ghosh ---------- 07 Apr, 2022 ----------
CREATE TABLE `push_notification_registers` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int NULL COMMENT 'users -> id',
  `service_provider` varchar(30) NULL COMMENT 'Gravitec.net',
  `auth` varchar(255) NULL COMMENT 'Authentication key',
  `browser` varchar(50) NULL,
  `endpoint` varchar(255) NULL COMMENT 'PushSubscription URL',
  `lang` varchar(5) NULL,
  `p256dh` varchar(255) NULL COMMENT 'Public key',
  `reg_id` varchar(255) NULL COMMENT 'Registration token',
  `subscription_spec` int NULL,
  `subscription_strategy` int NULL,
  `created_at` datetime NULL,
  `updated_at` datetime NULL
);

CREATE TABLE `push_notification_queue` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int NULL COMMENT 'users -> id',
  `title` tinytext NULL COMMENT 'Title or header of the message',
  `message` tinytext NULL COMMENT 'Push message text',
  `icon` tinytext NULL COMMENT 'URL of notification\'s icon',
  `image` tinytext NULL COMMENT 'URL of big picture to display below the notification main body. Ratio = 1.5:1, 360x240 pixels',
  `redirect_url` tinytext NULL COMMENT 'URL which will be opened in user’s browser if user clicks on the notification.',
  `buttons` text NULL COMMENT 'One or two action buttons in JSON array format with title and url fields.',
  `status` tinyint(1) NULL COMMENT '1=Pending, 2=Sent, 3=Failed',
  `scheduled_at` datetime NULL COMMENT 'Immediate or schedule at specific time',
  `created_at` datetime NULL,
  `updated_at` datetime NULL
);
-- '

ALTER TABLE `push_notification_queue`
ADD `type` varchar(30) NULL COMMENT 'subscription_delete' AFTER `user_id`;

ALTER TABLE `push_notification_queue`
CHANGE `status` `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Pending, 1=Sent, 2=Failed' AFTER `buttons`;






-- 1.58 ---------- Pro plan changes ---------- Arabinda Ghosh ---------- 11 Apr, 2022 ----------
UPDATE `plans` SET `is_upgradable` = '1' WHERE `id` = '2';




-- 1.59 ---------- Events changes for notification ---------- Arabinda Ghosh ---------- 12 Apr, 2022 ----------
ALTER TABLE `events`
ADD `event_type_source` tinyint(1) NULL DEFAULT '0' COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app' AFTER `event_type`;

ALTER TABLE `events`
CHANGE `event_type` `event_type` varchar(30) COLLATE 'utf8_general_ci' NULL COMMENT 'email, admin_email, user_registration, user_payment, plan_change, subscriptions, products, customer_products, user_alert, import_export, user_settings, generic, app_update, account_reset, email_refund, team_user_invite, extension_notifications, push_notifications' AFTER `event_timezone`;

ALTER TABLE `notifications`
RENAME TO `notifications_etx`;






-- 1.60 ---------- New table for Webhook ---------- Arabinda Ghosh ---------- 15 Apr, 2022 ----------
CREATE TABLE `webhooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `name` varchar(50) DEFAULT NULL,
  `endpoint_url` tinytext COMMENT 'Webhook URL',
  `events` text COMMENT 'Comma-separated values',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Inactive, 1=Active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `webhooks`
ADD `type` tinyint(1) NULL COMMENT '1=Incoming, 2=Outgoing' AFTER `user_id`;



-- 1.60 ---------- Events changes for webhook ---------- Arabinda Ghosh ---------- 19 Apr, 2022 ----------
ALTER TABLE `events`
CHANGE `event_type` `event_type` varchar(30) COLLATE 'utf8_general_ci' NULL COMMENT 'email, admin_email, user_registration, user_payment, plan_change, subscriptions, products, customer_products, user_alert, import_export, user_settings, generic, app_update, account_reset, email_refund, team_user_invite, notifications, webhook' AFTER `event_timezone`;



-- 1.60 ---------- User webhook logs ---------- Arabinda Ghosh ---------- 20 Apr, 2022 ----------
ALTER TABLE `webhook_logs`
ADD `webhook_id` int NULL COMMENT 'webhooks -> id' AFTER `id`,
ADD `user_id` int NULL COMMENT 'users -> id' AFTER `webhook_id`,
ADD `type` tinyint(1) NULL COMMENT '1=Incoming, 2=Outgoing' AFTER `user_id`,
ADD `event` tinytext NULL COMMENT 'webhooks -> events' AFTER `type`;



-- 1.60 ---------- Webhook token ---------- Arabinda Ghosh ---------- 21 Apr, 2022 ----------
ALTER TABLE `webhooks`
ADD `token` varchar(40) COLLATE 'utf8_general_ci' NULL COMMENT 'Token for incoming request' AFTER `events`;





-- 1.61 ---------- Plan changes for webhook ---------- Arabinda Ghosh ---------- 23 Apr, 2022 ----------
ALTER TABLE `plans`
ADD `limit_webhooks` smallint(6) NULL DEFAULT '0' COMMENT 'Webhooks' AFTER `limit_alert_profiles`;

ALTER TABLE `users_plans`
ADD `total_webhooks` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Webhooks' AFTER `total_alert_profiles`;





-- 1.63 ---------- Plan changes for team ---------- Arabinda Ghosh ---------- 02 May, 2022 ----------
INSERT INTO `plans` (`id`, `role_id`, `product_id`, `type`, `name`, `description`, `price_monthly`, `price_annually`, `ltd_price`, `ltd_price_date`, `limit_subs`, `limit_folders`, `limit_tags`, `limit_contacts`, `limit_pmethods`, `limit_alert_profiles`, `limit_webhooks`, `limit_teams`, `is_default`, `is_upgradable`, `trial_days`, `number_of_users`, `currency`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(4,	1,	NULL,	2,	'Team',	'Team Plan',	0,	0,	0,	NULL,	9999,	50,	200,	3,	10,	10,	10,	5,	0,	0,	30,	1,	'USD',	4,	1,	'2022-05-02 10:59:00',	'2022-05-02 10:59:00');





-- 1.65 ---------- Lifetime addon ---------- Arabinda Ghosh ---------- 16 May, 2022 ----------
ALTER TABLE `subscriptions`
ADD `sub_addon` tinyint(1) NULL DEFAULT '0' COMMENT '1=Addon' AFTER `rating`,
ADD `sub_id` int NULL COMMENT 'subscriptions -> id' AFTER `sub_addon`;

ALTER TABLE `subscriptions_history`
ADD `sub_addon` tinyint(1) NULL DEFAULT '0' COMMENT '1=Addon' AFTER `rating`,
ADD `sub_id` int NULL COMMENT 'subscriptions -> id' AFTER `sub_addon`;

ALTER TABLE `ltd_history`
ADD `sub_addon` tinyint(1) NULL DEFAULT '0' COMMENT '1=Addon' AFTER `rating`,
ADD `sub_id` int NULL COMMENT 'subscriptions -> id' AFTER `sub_addon`;






-- 1.67 ---------- Eamil template for Expired subscription ---------- Arabinda Ghosh ---------- 03 Jan, 2022 ----------
ALTER TABLE `events`
CHANGE `event_type` `event_type` varchar(30) COLLATE 'utf8_general_ci' NULL COMMENT 'email, admin_email, user_registration, user_payment, plan_change, subscriptions, products, customer_products, user_alert, import_export, user_settings, generic, app_update, account_reset, email_refund, email_expire, team_user_invite, notifications, webhook' AFTER `event_timezone`;

INSERT INTO `email_types` (`field_name`, `field_value`, `template_name`) VALUES
('User first name',	'user_first_name',	'subscription_expire'),
('User last name',	'user_last_name',	'subscription_expire'),
('User full name',	'user_full_name',	'subscription_expire'),
('User email',	'user_email',	'subscription_expire'),
('App url',	'app_url',	'subscription_expire'),
('Subscription url',	'subscription_url',	'subscription_expire'),
('Subscription image url',	'subscription_image_url',	'subscription_expire'),
('Subscription expire date',	'subscription_expire_date',	'subscription_expire'),
('Subscription price',	'subscription_price',	'subscription_expire'),
('Subscription payment mode',	'subscription_payment_mode',	'subscription_expire'),
('Product name',	'product_name',	'subscription_expire'),
('Product type',	'product_type',	'subscription_expire'),
('Product description',	'product_description',	'subscription_expire');

INSERT INTO `email_templates` (`user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(1,	'subscription_expire',	'Subscription Expired',	'<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n\r\n                .mj-column-per-75 {\r\n                    width: 75% !important;\r\n                    max-width: 75%;\r\n                }\r\n\r\n                .mj-column-per-25 {\r\n                    width: 25% !important;\r\n                    max-width: 25%;\r\n                }\r\n\r\n                .mj-column-per-50 {\r\n                    width: 50% !important;\r\n                    max-width: 50%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 152px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"152\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 0px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 0px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 10px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Subscription Alert</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 10px 30px 10px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:405px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-75 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <strong><span style=\"font-size: 16px; font-family: \'DM Sans\';\">Hello {user_first_name},&nbsp;</span></strong>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 14px; font-family: Roboto;\">\r\n                                                            <span style=\"font-size: 16px; font-family: \'DM Sans\';\">Your subscription expired for {product_name}.</span><br />\r\n                                                        </span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td><td class=\"\" style=\"vertical-align:top;width:135px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-25 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"right\" class=\"mobile_hidden\" style=\"font-size: 0px; padding: 0px 0px 0px 40px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 95px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"{product_image}\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"95\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>',	1,	1,	'2022-06-03 09:12:44',	1);




-- 1.72 ---------- Subscription attachments ---------- Arabinda Ghosh ---------- 23 Jun, 2022 ----------
CREATE TABLE `subscriptions_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `subscription_id` int(11) DEFAULT NULL COMMENT 'subscriptions -> id',
  `file_name` tinytext NULL,
  `file_path` text NULL,
  `file_size` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- 1.73 ---------- Storage limits ---------- Arabinda Ghosh ---------- 28 Jun, 2022 ----------
ALTER TABLE `plans`
ADD `limit_storage` bigint NOT NULL DEFAULT '0' COMMENT 'Storage in bytes' AFTER `limit_teams`;
ALTER TABLE `users_plans`
ADD `total_storage` bigint NOT NULL DEFAULT '0' COMMENT 'Storage in bytes' AFTER `total_teams`;

-- 1 GB limit for paid plans
UPDATE `plans` SET `limit_storage` = '1073741824' WHERE `id` = '2';
UPDATE `plans` SET `limit_storage` = '1073741824' WHERE `id` = '3';
UPDATE `plans` SET `limit_storage` = '1073741824' WHERE `id` = '4';

ALTER TABLE `subscriptions_attachments`
ADD `file_type` varchar(50) NULL AFTER `file_size`;






-- 1.75 ---------- WordPress user id from webhook ---------- Arabinda Ghosh ---------- 09 Jul, 2022 ----------
ALTER TABLE `users`
ADD `wp_user_id` int(11) NULL COMMENT 'WordPress user id' AFTER `team_user_id`;




-- 1.86 ---------- updated_at fields ---------- Arabinda Ghosh ---------- 16 Sep, 2022 ----------
ALTER TABLE `folder`
ADD `updated_at` datetime NULL;

ALTER TABLE `users_alert`
ADD `created_at` datetime NULL,
ADD `updated_at` datetime NULL;

ALTER TABLE `tags`
ADD `created_at` datetime NULL,
ADD `updated_at` datetime NULL;

ALTER TABLE `users_payment_methods`
ADD `created_at` datetime NULL,
ADD `updated_at` datetime NULL;

ALTER TABLE `products`
ADD `updated_at` datetime NULL;
































------------------------------------------------------------------------------------------------------------------------
-- 1. ---------- Marketing price fields in calc tables ---------- Arabinda Ghosh ---------- 28 Mar, 2022 ----------
ALTER TABLE `user_ltd_cal`
ADD `ltdval_price` decimal(10,2) NULL AFTER `base_currency`,
ADD `ltdval_cycle` tinyint(1) NULL AFTER `ltdval_price`,
ADD `ltdval_frequency` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `ltdval_cycle`;

ALTER TABLE `user_subs_cal`
ADD `ltdval_price` decimal(10,2) NULL AFTER `base_currency`,
ADD `ltdval_cycle` tinyint(1) NULL AFTER `ltdval_price`,
ADD `ltdval_frequency` tinyint(1) NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly' AFTER `ltdval_cycle`;



-- 1. ---------- ProductRelatedEntities ---------- Yaroslav Moroz ---------- 18 May, 2022 ----------
ALTER TABLE `product_categories`
ADD `created_at` datetime NULL AFTER `name`,
ADD `updated_at` datetime NULL AFTER `created_at`;

ALTER TABLE `product_platforms`
ADD `created_at` datetime NULL AFTER `name`,
ADD `updated_at` datetime NULL AFTER `created_at`;

ALTER TABLE `product_types`
ADD `created_at` datetime NULL AFTER `name`,
ADD `updated_at` datetime NULL AFTER `created_at`;

ALTER TABLE `files`
ADD `created_at` datetime NULL AFTER `is_deleted`,
ADD `updated_at` datetime NULL AFTER `created_at`;

------------------------------------------------------------------------------------------------------------------------
-- 1. ---------- Xeno integration ---------- Yaroslav Moroz ---------- 23 Jul, 2022 ----------
ALTER TABLE `config`
ADD `xeno_send_data` tinyint(1) NULL DEFAULT '0' COMMENT '0=Disabled, 1=Enabled' AFTER `cdn_base_url`,
ADD `xeno_public_key` varchar(50) COLLATE 'utf8_general_ci' NULL COMMENT 'Xeno public key' AFTER `xeno_send_data`;


-- 1. ---------- Woo webhook ---------- Yaroslav Moroz ---------- 23 Sep, 2022 ----------
ALTER TABLE `plans`
ADD `variation_id` int(11) DEFAULT NULL COMMENT 'WooCommerce product variation id' AFTER `product_id`;

INSERT INTO `plans` (`role_id`, `product_id`, `variation_id`, `type`, `name`, `description`, `price_monthly`, `price_annually`, `ltd_price`, `ltd_price_date`, `limit_subs`, `limit_folders`, `limit_tags`, `limit_contacts`, `limit_pmethods`, `limit_alert_profiles`, `limit_webhooks`, `limit_teams`, `limit_storage`, `is_default`, `is_upgradable`, `trial_days`, `number_of_users`, `currency`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(1,2696,2747,3,'Pro LTD','Pro LTD',0,0,0,NULL,250,20,20,5,10,10,0,0,1073741824,0,1,30,1,'USD',5,1,'2020-07-28 18:25:39','2020-07-28 18:25:39'),
(1,2696,2748,3,'Pro Plus LTD','Pro Plus LTD',0,0,0,NULL,250,20,20,5,10,10,0,0,1073741824,0,1,30,1,'USD',6,1,'2020-07-28 18:25:39','2020-07-28 18:25:39'),
(1,2696,2749,3,'Teams LTD','Teams LTD',0,0,0,NULL,9999,50,200,3,10,10,10,5,1073741824,0,0,30,1,'USD',7,1,'2022-05-02 10:59:00','2022-05-02 10:59:00');

-- 1. ---------- File paths migration ---------- Yaroslav Moroz ---------- 01 Oct, 2022 ----------
CREATE TABLE `tmp_migrate_paths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` ENUM('File', 'Subscription', 'SubscriptionAttachment', 'SubscriptionHistory', 'LtdHistory', 'User') COLLATE 'utf8_general_ci' NOT NULL,
  `row_id` int(11) NOT NULL,
  `old_file_type` varchar(50) COLLATE 'utf8_general_ci' NOT NULL,
  `old_file_path` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `old_file_exists` tinyint(1) NOT NULL,
  `new_file_type` varchar(50) COLLATE 'utf8_general_ci' NOT NULL,
  `new_file_path` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `new_file_exists` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `old_file_exists` (`old_file_exists`),
  KEY `new_file_exists` (`new_file_exists`)
) ENGINE='InnoDB' AUTO_INCREMENT=1 DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

-- 2. ---------- Cron flags ---------- Yaroslav Moroz ---------- 31 Oct, 2022 ----------
CREATE TABLE `cron_flags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE 'utf8_general_ci' NOT NULL,
  `value` tinyint(1) NOT NULL,
  `counter` int(11) NOT NULL,
  `created_at` datetime,
  `updated_at` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE='InnoDB' AUTO_INCREMENT=1 DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

INSERT INTO `cron_flags` (`name`, `value`, `counter`) VALUES
('schedule', false, 0),
('mail', false, 0),
('plan', false, 0),
('report', false, 0),
('misc', false, 0),
('notification', false, 0);


-- 2. ---------- Clear DB ---------- Yaroslav Moroz ---------- 20 Nov, 2022 ----------
DROP TABLE `user_ltd_cal`;
DROP TABLE `user_subs_cal`;


-- 2.07 ---------- Marketplace table ---------- Arabinda Ghosh ---------- 07 Nov, 2022 ----------
CREATE TABLE `subscription_cart` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product_category_id` int NULL,
  `product_platform_id` int NULL,
  `product_description` text NULL,
  `product_name` varchar(50) NULL,
  `product_logo` tinytext NULL,
  `sale_price` double NULL,
  `plan_name` varchar(50) NULL,
  `product_url` tinytext NULL,
  `created_at` datetime NULL,
  `updated_at` datetime NULL
);
ALTER TABLE `subscription_cart`
ADD `subscription_id` int NOT NULL COMMENT 'subscriptions -> id' AFTER `id`;

ALTER TABLE `subscription_cart`
ADD `user_id` int NOT NULL COMMENT 'users -> id' AFTER `id`;
ALTER TABLE `subscription_cart`
ADD `notes` tinytext COLLATE 'utf8_general_ci' NULL AFTER `product_url`;
ALTER TABLE `subscription_cart`
ADD `currency_code` varchar(5) NULL AFTER `sale_price`;

ALTER TABLE `subscription_cart`
CHANGE `user_id` `user_id` int(11) NULL COMMENT 'users -> id' AFTER `id`,
CHANGE `subscription_id` `subscription_id` int(11) NULL COMMENT 'subscriptions -> id' AFTER `user_id`,
ADD `product_id` int NULL AFTER `subscription_id`;

ALTER TABLE `subscription_cart`
ADD `status` tinyint(1) NULL COMMENT '0=Draft, 1=Active' AFTER `notes`;



-- 2.07 ---------- Marketplace fileds for user ---------- Arabinda Ghosh ---------- 17 Nov, 2022 ----------
ALTER TABLE `users`
ADD `marketplace_token` varchar(100) COLLATE 'utf8mb4_unicode_ci' NULL COMMENT 'Marketplace user page link' AFTER `remember_token`,
ADD `paypal_email` varchar(50) COLLATE 'utf8mb4_unicode_ci' NULL COMMENT 'PayPal email address' AFTER `marketplace_token`;

ALTER TABLE `users`
ADD `marketplace_status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Inactive, 1=Active' AFTER `remember_token`;




-- 2.07 ---------- Marketplace order tables ---------- Arabinda Ghosh ---------- 18 Nov, 2022 ----------
CREATE TABLE `marketplace_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marketplace_item_id` int(11) DEFAULT NULL COMMENT 'subscription_cart -> id',
  `seller_user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `seller_paypal_email` varchar(50) DEFAULT NULL,
  `buyer_user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `buyer_first_name` varchar(50) DEFAULT NULL,
  `buyer_last_name` varchar(50) DEFAULT NULL,
  `buyer_email` varchar(50) DEFAULT NULL,
  `buyer_phone` varchar(30) DEFAULT NULL,
  `buyer_company` varchar(50) DEFAULT NULL,
  `buyer_country` varchar(10) DEFAULT NULL,
  `payment_method` varchar(10) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL COMMENT 'subscriptions -> id',
  `product_id` int(11) DEFAULT NULL COMMENT 'products -> id',
  `product_name` varchar(50) DEFAULT NULL,
  `product_logo` tinytext,
  `sale_price` double DEFAULT NULL,
  `currency_code` varchar(5) DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `charges` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `paypal_response` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `subscription_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `subscription_id` int(11) DEFAULT NULL COMMENT 'subscriptions -> id',
  `product_id` int(11) DEFAULT NULL,
  `product_category_id` int(11) DEFAULT NULL,
  `product_platform_id` int(11) DEFAULT NULL,
  `product_description` text,
  `product_name` varchar(50) DEFAULT NULL,
  `product_logo` tinytext,
  `sale_price` double DEFAULT NULL,
  `currency_code` varchar(5) DEFAULT NULL,
  `plan_name` varchar(50) DEFAULT NULL,
  `product_url` tinytext,
  `notes` tinytext,
  `status` tinyint(1) DEFAULT NULL COMMENT '0=Draft, 1=Active, 2=Sold',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
ADD `company_name` varchar(50) COLLATE 'utf8mb4_unicode_ci' NULL AFTER `paypal_email`,
ADD `facebook_username` varchar(50) COLLATE 'utf8mb4_unicode_ci' NULL AFTER `company_name`;

ALTER TABLE `subscriptions`
CHANGE `status` `status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Draft, 1=Active, 2=Cancel, 3=Refund, 4=Expired, 5=Sold' AFTER `ltdval_cycle`;
ALTER TABLE `subscriptions_history`
CHANGE `status` `status` tinyint(1) NULL DEFAULT '0' COMMENT '0=Draft, 1=Active, 2=Cancel, 3=Refund, 4=Expired, 5=Sold' AFTER `ltdval_cycle`;

ALTER TABLE `config`
ADD `paypal_environment` tinyint(1) NULL DEFAULT '0' COMMENT '0=Sandbox, 1=Live';






-- 2.12 ---------- Marketplace fields in users table ---------- Arabinda Ghosh ---------- 22 Nov, 2022 ----------
ALTER TABLE `users`
ADD `phone` varchar(20) COLLATE 'utf8mb4_unicode_ci' NULL AFTER `facebook_username`;

ALTER TABLE `marketplace_orders`
CHANGE `buyer_company` `buyer_company_name` varchar(50) COLLATE 'utf8_general_ci' NULL AFTER `buyer_phone`;

ALTER TABLE `subscriptions`
ADD `platform_id` int(11) NULL COMMENT 'product_platforms -> id' AFTER `alert_id`;

ALTER TABLE `subscriptions_history`
ADD `platform_id` int(11) NULL COMMENT 'product_platforms -> id' AFTER `alert_id`;

ALTER TABLE `ltd_history`
ADD `platform_id` int(11) NULL COMMENT 'product_platforms -> id' AFTER `alert_id`;

ALTER TABLE `subscription_cart`
ADD `sales_url` tinytext COLLATE 'utf8_general_ci' NULL AFTER `product_url`;

INSERT INTO `users_alert` (`id`, `user_id`, `is_default`, `time_period`, `time_cycle`, `time`, `alert_condition`, `alert_contact`, `alert_type`, `alert_name`, `timezone`, `alert_subs_type`, `created_at`, `updated_at`) VALUES
(-1,	0,	1,	7,	NULL,	'10:00:00',	1,	NULL,	1,	'System Default (LTD)',	'America/New_York',	3,	NULL,	NULL);

UPDATE `users_alert` SET `alert_name` = 'System Default (Sub)' WHERE `id` = '1';







-- 2.13 ---------- PayPal fields changes in users table ---------- Arabinda Ghosh ---------- 25 Nov, 2022 ----------
ALTER TABLE `users`
CHANGE `paypal_email` `paypal_api_username` tinytext COLLATE 'utf8mb4_unicode_ci' NULL AFTER `marketplace_token`,
ADD `paypal_api_password` tinytext COLLATE 'utf8mb4_unicode_ci' NULL AFTER `paypal_api_username`,
ADD `paypal_api_secret` tinytext COLLATE 'utf8mb4_unicode_ci' NULL AFTER `paypal_api_password`;

ALTER TABLE `marketplace_orders`
CHANGE `seller_paypal_email` `seller_paypal_api_username` tinytext COLLATE 'utf8_general_ci' NULL AFTER `seller_user_id`;

ALTER TABLE `marketplace_orders`
ADD `paypal_token` text COLLATE 'utf8_general_ci' NULL AFTER `status`;

ALTER TABLE `marketplace_orders`
DROP `paypal_response`;





-- 2.18 ---------- Coupons table ---------- Arabinda Ghosh ---------- 06 Dec, 2022 ----------
CREATE TABLE `plan_coupons` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int NULL COMMENT 'users -> id',
  `coupon` varchar(30) NOT NULL COMMENT 'Coupon code',
  `status` tinyint(1) NOT NULL COMMENT '1=Active, 2=Claimed',
  `created_at` datetime NULL,
  `updated_at` datetime NULL
);

ALTER TABLE `plan_coupons`
CHANGE `created_at` `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
CHANGE `status` `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Claimed' AFTER `coupon`;






-- 2.22 ---------- Product adapt feature ---------- Arabinda Ghosh ---------- 22 Dec, 2022 ----------
ALTER TABLE `subscriptions`
ADD `product_avail` tinyint NULL DEFAULT '0' COMMENT '0=No, 1=Yes' AFTER `sub_id`;
ALTER TABLE `subscriptions_history`
ADD `product_avail` tinyint NULL DEFAULT '0' COMMENT '0=No, 1=Yes' AFTER `sub_id`;
ALTER TABLE `ltd_history`
ADD `product_avail` tinyint NULL DEFAULT '0' COMMENT '0=No, 1=Yes' AFTER `sub_id`;

ALTER TABLE `subscriptions`
ADD `product_submission_id` int NULL DEFAULT '0' COMMENT '0=No, products -> id' AFTER `product_avail`;
ALTER TABLE `subscriptions_history`
ADD `product_submission_id` int NULL DEFAULT '0' COMMENT '0=No, products -> id' AFTER `product_avail`;
ALTER TABLE `ltd_history`
ADD `product_submission_id` int NULL DEFAULT '0' COMMENT '0=No, products -> id' AFTER `product_avail`;

ALTER TABLE `products`
CHANGE `status` `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active, 2=User submitted' AFTER `favicon`;


-- 2.28 ---------- Improve user plane model usage ---------- Yaroslav Moroz ---------- 12 Jan, 2023 ----------
ALTER TABLE `users` DROP `plan_id`;


-- 2.29 ---------- Currency per folder ---------- Yaroslav Moroz ---------- 18 Jan, 2023 ----------
ALTER TABLE `folder` ADD `price_type` varchar(3) DEFAULT NULL AFTER `is_default`;


-- 2.31 ---------- Update folder currency ---------- Yaroslav Moroz ---------- 25 Jan, 2023 ----------
UPDATE `folder` SET `price_type` = 'All' WHERE `price_type` IS NULL;

-- 2.30 ---------- Streamline history tables ---------- Yaroslav Moroz ---------- 24 Jan, 2023 ----------
DROP TABLE `ltd_history`;
ALTER TABLE `subscriptions_history` DROP `image`, DROP `next_payment_date`, DROP `payment_date_upd`, DROP `folder_id`, DROP `price_type`, DROP `base_value`, DROP `base_currency`, DROP `brand_id`, DROP `recurring`, DROP `alert_type`, DROP `status`, DROP `sub_addon`, DROP `product_name`, DROP `type`, DROP `category_id`, DROP `alert_id`, DROP `favicon`, DROP `brandname`, DROP `product_type`, DROP `description`, DROP `expiry_date`, DROP `contract_expiry`, DROP `homepage`, DROP `pay_gateway_id`, DROP `note`, DROP `company_name`, DROP `discount_voucher`, DROP `include_notes`, DROP `url`, DROP `support_details`, DROP `tags`, DROP `billing_frequency`, DROP `billing_cycle`, DROP `billing_type`, DROP `ltdval_price`, DROP `ltdval_frequency`, DROP `ltdval_cycle`, DROP `pricing_type`, DROP `timezone`, DROP `currency_code`, DROP `refund_days`, DROP `refund_date`, DROP `rating`, DROP `sub_id`, DROP `platform_id`, DROP `product_avail`, DROP `product_submission_id`, DROP `created_at`, DROP `created_by`;
-- 2.35 ---------- Alert profile changes ---------- Arabinda Ghosh ---------- 30 Jan, 2023 ----------
ALTER TABLE `users_alert`
CHANGE `alert_type` `alert_type` tinyint(1) NULL DEFAULT '1' COMMENT '1=All, 2=Email, 3=Browser' AFTER `alert_contact`,
ADD `alert_type_json` tinytext NULL AFTER `alert_type`;
UPDATE `users_alert` SET `alert_type_json` = '[\"1\",\"2\",\"3\",\"4\"]' WHERE `id` = '-1';
UPDATE `users_alert` SET `alert_type_json` = '[\"1\",\"2\",\"3\",\"4\"]' WHERE `id` = '1';



-- 2.35 ---------- New tables for events ---------- Arabinda Ghosh ---------- 30 Jan, 2023 ----------
CREATE TABLE `event_emails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, subscriptions, user_alert, email_refund, email_expire, team_user_invite',
  `event_type_source` tinyint(1) DEFAULT '0' COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext COMMENT 'Caller',
  `event_url` tinytext,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

ALTER TABLE `push_notification_queue`
RENAME TO `event_browser_notify`;

ALTER TABLE `notifications_etx`
RENAME TO `event_chrome_extn`;

CREATE TABLE `event_webhook` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, subscriptions, user_alert, email_refund, email_expire, team_user_invite',
  `event_type_source` tinyint(1) DEFAULT '0' COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext COMMENT 'Caller',
  `event_url` tinytext,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `events_subscription` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, subscriptions, user_alert, email_refund, email_expire, team_user_invite',
  `event_type_source` tinyint(1) DEFAULT '0' COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext COMMENT 'Caller',
  `event_url` tinytext,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;






-- 2.37 ---------- Streamline history tables ---------- Yaroslav Moroz ---------- 09 Feb, 2023 ----------
ALTER TABLE `subscriptions_history`
ADD `next_payment_date` date NULL COMMENT 'Upcoming payment date' AFTER `payment_date`,
ADD`payment_date_upd` date NULL AFTER `next_payment_date`;



-- revert 2.35 e107b353 ---------- Alert profile changes ---------- Arabinda Ghosh ---------- 30 Jan, 2023 ----------
ALTER TABLE `users_alert`
CHANGE `alert_type` `alert_type` tinyint(1) NULL COMMENT '1=All, 2=Email, 3=Browser' AFTER `alert_contact`,
DROP `alert_type_json`;


-- revert 2.35 e107b353 ---------- New tables for events ---------- Arabinda Ghosh ---------- 30 Jan, 2023 ----------
DROP TABLE `event_emails`;

ALTER TABLE `event_browser_notify`
RENAME TO `push_notification_queue`;

ALTER TABLE `event_chrome_extn`
RENAME TO `notifications_etx`;

DROP TABLE `event_webhook`;

DROP TABLE `events_subscription`;

-- 2.45 ---------- Fix calendar ----------------------- Yaroslav Moroz ---------- 04 April, 2023 ------------
INSERT INTO `cron_flags` (`name`, `value`, `counter`) VALUES
('fix_subscription_history', false, 0);


-- 2.46 ---------- Complete notification engine ------- Yaroslav Moroz ---------- 14 April, 2023 ------------
DROP TABLE `events_subscription`;
UPDATE `users_alert` SET `alert_type_json` = '';
ALTER TABLE `users_alert`
CHANGE COLUMN `alert_type_json` `alert_types` SET('email', 'browser', 'extension', 'mobile', 'webhook') NOT NULL;
UPDATE `users_alert` SET `alert_types` = 'email,browser,extension' WHERE `id` = -1 OR `id` = 1;

CREATE INDEX `idx_event_type` ON `events` (`event_type`);
CREATE INDEX `idx_table_name` ON `events` (`table_name`);
CREATE INDEX `idx_table_row_id` ON `events` (`table_row_id`);
CREATE INDEX `idx_event_cron` ON `events` (`event_cron`);
CREATE INDEX `idx_user_id` ON `events` (`user_id`);
CREATE INDEX `idx_event_type_status` ON `events` (`event_type_status`);
CREATE INDEX `idx_event_status` ON `events` (`event_status`);
CREATE INDEX `idx_event_datetime` ON `events` (`event_datetime`);

CREATE INDEX `idx_event_type` ON `event_emails` (`event_type`);
CREATE INDEX `idx_table_name` ON `event_emails` (`table_name`);
CREATE INDEX `idx_table_row_id` ON `event_emails` (`table_row_id`);
CREATE INDEX `idx_user_id` ON `event_emails` (`user_id`);
CREATE INDEX `idx_event_cron` ON `event_emails` (`event_cron`);
CREATE INDEX `idx_event_status` ON `event_emails` (`event_status`);
CREATE INDEX `idx_event_type_status` ON `event_emails` (`event_type_status`);
CREATE INDEX `idx_event_datetime` ON `event_emails` (`event_datetime`);
CREATE INDEX `idx_event_type_schedule` ON `event_emails` (`event_type_schedule`);

CREATE INDEX `idx_user_id` ON `event_browser_notify` (`user_id`);

CREATE INDEX `idx_user_id` ON `event_chrome_extn` (`user_id`);
CREATE INDEX `idx_read` ON `event_chrome_extn` (`read`);

CREATE TABLE `event_browser` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, subscriptions, user_alert, email_refund, email_expire, team_user_invite',
  `event_type_source` tinyint(1) DEFAULT '0' COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext COMMENT 'Caller',
  `event_url` tinytext,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `event_chrome` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, subscriptions, user_alert, email_refund, email_expire, team_user_invite',
  `event_type_source` tinyint(1) DEFAULT '0' COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext COMMENT 'Caller',
  `event_url` tinytext,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `cron_flags` (`name`, `value`, `counter`) VALUES
('messages', false, 0);

CREATE INDEX `idx_event_type` ON `event_browser` (`event_type`);
CREATE INDEX `idx_table_name` ON `event_browser` (`table_name`);
CREATE INDEX `idx_table_row_id` ON `event_browser` (`table_row_id`);
CREATE INDEX `idx_user_id` ON `event_browser` (`user_id`);
CREATE INDEX `idx_event_cron` ON `event_browser` (`event_cron`);
CREATE INDEX `idx_event_status` ON `event_browser` (`event_status`);
CREATE INDEX `idx_event_type_status` ON `event_browser` (`event_type_status`);
CREATE INDEX `idx_event_datetime` ON `event_browser` (`event_datetime`);
CREATE INDEX `idx_event_type_schedule` ON `event_browser` (`event_type_schedule`);

CREATE INDEX `idx_event_type` ON `event_chrome` (`event_type`);
CREATE INDEX `idx_table_name` ON `event_chrome` (`table_name`);
CREATE INDEX `idx_table_row_id` ON `event_chrome` (`table_row_id`);
CREATE INDEX `idx_user_id` ON `event_chrome` (`user_id`);
CREATE INDEX `idx_event_cron` ON `event_chrome` (`event_cron`);
CREATE INDEX `idx_event_status` ON `event_chrome` (`event_status`);
CREATE INDEX `idx_event_type_status` ON `event_chrome` (`event_type_status`);
CREATE INDEX `idx_event_datetime` ON `event_chrome` (`event_datetime`);
CREATE INDEX `idx_event_type_schedule` ON `event_chrome` (`event_type_schedule`);

ALTER TABLE `event_emails`
ADD `admin_id` INT(11) NULL DEFAULT NULL AFTER `user_id`;

ALTER TABLE `event_browser`
ADD `admin_id` INT(11) NULL DEFAULT NULL AFTER `user_id`;

ALTER TABLE `event_chrome`
ADD `admin_id` INT(11) NULL DEFAULT NULL AFTER `user_id`;

-- 2.47 ---------- Product notification email ---------- Luan Pham ---------- 10 May, 2022 ----------
INSERT INTO `email_types` (`field_name`, `field_value`, `template_name`) VALUES
('Product name',	'product_name',	'product_notify'),
('Product price',	'product_price',	'product_notify'),
('Product type',	'product_type',	'product_notify');

INSERT INTO `email_templates` (`id`, `user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(12, 1, 'product_notify', 'Product Notification', '<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\n    <head>\n        <title></title>\n        <!--[if !mso]><!-- -->\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\n        <!--<![endif]-->\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n        <style type=\"text/css\">\n            #outlook a {\n                padding: 0;\n            }\n\n            body {\n                margin: 0;\n                padding: 0;\n                -webkit-text-size-adjust: 100%;\n                -ms-text-size-adjust: 100%;\n            }\n\n            table,\n            td {\n                border-collapse: collapse;\n                mso-table-lspace: 0pt;\n                mso-table-rspace: 0pt;\n            }\n\n            img {\n                border: 0;\n                height: auto;\n                line-height: 100%;\n                outline: none;\n                text-decoration: none;\n                -ms-interpolation-mode: bicubic;\n            }\n\n            p {\n                display: block;\n                margin: 13px 0;\n            }\n        </style>\n        <!--[if mso]>\n            <xml>\n                <o:OfficeDocumentSettings>\n                    <o:AllowPNG />\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\n                </o:OfficeDocumentSettings>\n            </xml>\n        <![endif]-->\n        <!--[if lte mso 11]>\n            <style type=\"text/css\">\n                .mj-outlook-group-fix {\n                    width: 100% !important;\n                }\n            </style>\n        <![endif]-->\n        <!--[if !mso]><!-->\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\n        <style type=\"text/css\">\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\n        </style>\n        <!--<![endif]-->\n        <style type=\"text/css\">\n            @media only screen and (min-width: 480px) {\n                .mj-column-per-100 {\n                    width: 100% !important;\n                    max-width: 100%;\n                }\n            }\n        </style>\n        <style type=\"text/css\">\n            @media only screen and (max-width: 480px) {\n                table.mj-full-width-mobile {\n                    width: 100% !important;\n                }\n\n                td.mj-full-width-mobile {\n                    width: auto !important;\n                }\n            }\n        </style>\n        <style type=\"text/css\">\n            .desktop_hidden {\n                display: none;\n                max-height: 0px;\n            }\n\n            @media (max-width: 660px) {\n                .mobile_hidden {\n                    min-height: 0px;\n                    max-height: 0px;\n                    max-width: 0px;\n                    display: none;\n                    overflow: hidden;\n                    font-size: 0px;\n                }\n\n                .desktop_hidden {\n                    display: block !important;\n                    max-height: none !important;\n                }\n            }\n        </style>\n    </head>\n    <body style=\"background-color: #efefef;\">\n        <div style=\"background-color: #efefef;\">\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\n                <div style=\"line-height: 0; font-size: 0;\">\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\n                        <tbody>\n                            <tr>\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\n                                            <tr>\n                                                <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\n                                                        <tbody>\n                                                            <tr>\n                                                                <td style=\"width: 152px;\">\n                                                                    <img\n                                                                        height=\"auto\"\n                                                                        src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\n                                                                        style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\n                                                                        width=\"152\"\n                                                                    />\n                                                                </td>\n                                                            </tr>\n                                                        </tbody>\n                                                    </table>\n                                                </td>\n                                            </tr>\n                                        </table>\n                                    </div>\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\n                                </td>\n                            </tr>\n                        </tbody>\n                    </table>\n                </div>\n            </div>\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 12px;\">\n                <tbody>\n                    <tr>\n                        <td>\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\n                            <div style=\"margin: 0px auto; border-radius: 12px; max-width: 600px;\">\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 12px;\">\n                                    <tbody>\n                                        <tr>\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\n                                                        <tr>\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 20px 10px; word-break: break-word;\">\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\n                                                                    <p style=\"text-align: center;\">\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">New Product Submitted !</span><br /></span>\n                                                                    </p>\n                                                                </div>\n                                                            </td>\n                                                        </tr>\n                                                    </table>\n                                                </div>\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\n                                            </td>\n                                        </tr>\n                                    </tbody>\n                                </table>\n                            </div>\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\n                        </td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\n                    <tbody>\n                        <tr>\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:540px;\" ><![endif]-->\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\n                                        <tr>\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\n                                                    <p style=\"text-align: left;\">\n                                                        <span style=\"font-size: 16px; font-family: \'DM Sans\';\"><strong>New Product Added:&nbsp;</strong></span>\n                                                    </p>\n                                                </div>\n                                            </td>\n                                        </tr>\n                                        \n                                        \n                                        <tr>\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\n                                                    <p>\n                                                        <span style=\"font-family: Roboto; font-size: 16px;\">\n                                                            <span style=\"font-family: \'DM Sans\';\">\n                                                                <table><tr><th>Product name:</th><td>&nbsp;&nbsp;&nbsp; {product_name}</td></tr><tr><th>Product price:</th><td>&nbsp;&nbsp;&nbsp; {product_price}</td></tr><tr><th>Product type:</th><td>&nbsp;&nbsp;&nbsp; {product_type}</td></tr>\n                                                                </table>\n                                                            </span>\n                                                            <br />\n                                                        </span>\n                                                    </p>\n                                                </div>\n                                            </td>\n                                        </tr>\n                                        <tr>\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\n                                                <div style=\"height: 20px;\">&nbsp;</div>\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\n                                            </td>\n                                        </tr>\n                                        <tr>\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Cheers,</span></p>\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Team Subshero</span></p>\n                                                </div>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                </div>\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\n                            </td>\n                        </tr>\n                    </tbody>\n                </table>\n            </div>\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\n                <div style=\"line-height: 0; font-size: 0;\">\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\n                        <tbody>\n                            <tr>\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\n                                            <tr>\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\n                                                        <p style=\"text-align: center;\">\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\n                                                            </span>\n                                                        </p>\n                                                    </div>\n                                                </td>\n                                            </tr>\n                                        </table>\n                                    </div>\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\n                                </td>\n                            </tr>\n                        </tbody>\n                    </table>\n                </div>\n            </div>\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\n        </div>\n    </body>\n</html>', 1, 1, '2021-08-31 08:45:45', 1);