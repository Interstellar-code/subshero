-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 01, 2025 at 10:23 AM
-- Server version: 10.5.28-MariaDB-0+deb11u1
-- PHP Version: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `subsadmin_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `alert_type`
--

CREATE TABLE `alert_type` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User -> id',
  `plan_id` int(11) NOT NULL COMMENT 'Plan -> id',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Info, 1=Success, 2=Warning, 3=Danger',
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0 COMMENT '0=System, User -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '0=Default, User -> id',
  `name` varchar(20) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL COMMENT 'Picture path',
  `favicon` varchar(100) DEFAULT NULL,
  `subs_price` double DEFAULT NULL,
  `subs_frequency` tinyint(4) DEFAULT NULL,
  `subs_currency` varchar(100) DEFAULT NULL,
  `subs_cycle` varchar(100) DEFAULT NULL,
  `tags` varchar(100) DEFAULT NULL,
  `folder` int(11) DEFAULT NULL COMMENT 'folder -> id',
  `status` tinyint(4) DEFAULT NULL COMMENT '0=Inactive, 1=Active',
  `is_new` tinyint(4) DEFAULT NULL COMMENT '0=Old, 1=New',
  `refund` tinyint(4) DEFAULT NULL COMMENT '0=False, 1=True',
  `refund_period` tinyint(4) DEFAULT NULL COMMENT 'Refund days',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'User -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `smtp_host` varchar(50) DEFAULT NULL,
  `smtp_port` varchar(5) DEFAULT NULL,
  `smtp_encryption` varchar(4) DEFAULT NULL,
  `smtp_username` varchar(50) DEFAULT NULL,
  `smtp_password` varchar(50) DEFAULT NULL,
  `smtp_sender_name` varchar(50) DEFAULT NULL,
  `smtp_sender_email` varchar(50) DEFAULT NULL,
  `webhook_key` varchar(50) DEFAULT NULL,
  `recaptcha_status` tinyint(1) DEFAULT 0 COMMENT '0=Disabled, 1=Enabled',
  `recaptcha_site_key` varchar(50) DEFAULT NULL COMMENT 'reCAPTCHA v3 site key',
  `recaptcha_secret_key` varchar(50) DEFAULT NULL COMMENT 'reCAPTCHA v3 secret key',
  `cdn_base_url` varchar(50) DEFAULT NULL,
  `xeno_send_data` tinyint(1) DEFAULT 0 COMMENT '0=Disabled, 1=Enabled',
  `xeno_public_key` varchar(50) DEFAULT NULL COMMENT 'Xeno public key',
  `cron_misc_days` smallint(6) DEFAULT 0 COMMENT 'Miscellaneous cron days before',
  `gravitec_status` tinyint(1) DEFAULT 0 COMMENT '0=Disabled, 1=Enabled',
  `gravitec_app_key` varchar(32) DEFAULT NULL COMMENT 'Gravitec.net App key',
  `gravitec_app_secret` varchar(32) DEFAULT NULL COMMENT 'Gravitec.net App secret',
  `paypal_environment` tinyint(1) DEFAULT 0 COMMENT '0=Sandbox, 1=Live'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `smtp_host`, `smtp_port`, `smtp_encryption`, `smtp_username`, `smtp_password`, `smtp_sender_name`, `smtp_sender_email`, `webhook_key`, `recaptcha_status`, `recaptcha_site_key`, `recaptcha_secret_key`, `cdn_base_url`, `xeno_send_data`, `xeno_public_key`, `cron_misc_days`, `gravitec_status`, `gravitec_app_key`, `gravitec_app_secret`, `paypal_environment`) VALUES
(1, 'smtp.larksuite.com', '465', 'ssl', 'notify@subshero.com', '23pmWjDOqSk8PoGb', 'Subshero App', 'notify@subshero.com', 'MjvBa1BwT2YJtS6Qs', 1, '6LfSX5AcAAAAAF5SNPW50UaidtoJcgc68dxlYDal', '6LfSX5AcAAAAAMyc3w7mfCTkt2buD1UyOEdmgwDA', 'https://appcdn.subshero.com/', 1, 'xpk-e367c46e-5636-4c4a-9506-adc801c2d92c', 14, 1, 'f5d577056361dc3533e205fffcf5859d', 'c7f69d79e1e153f966a4c0ad1997a5ba', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cron_flags`
--

CREATE TABLE `cron_flags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` tinyint(1) NOT NULL,
  `counter` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency_conversion`
--

CREATE TABLE `currency_conversion` (
  `id` int(11) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `conversion_rate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int(11) NOT NULL,
  `template_name` varchar(50) DEFAULT NULL COMMENT 'email_templates -> name',
  `from_name` tinytext DEFAULT NULL,
  `from_email` tinytext DEFAULT NULL,
  `to_name` tinytext DEFAULT NULL,
  `to_email` tinytext DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `body` mediumtext DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Pending, 1=Sent, 2=Failed',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT '0=Cron',
  `created_timezone` tinytext DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `sent_by` int(11) DEFAULT NULL COMMENT '0=Cron',
  `sent_timezone` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_notifications`
--

CREATE TABLE `email_notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `body` mediumtext DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=Default',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Disabled, 1=Enabled',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'User -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(1, 1, 'user_registration', 'Welcome To Subshero', '<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                        <tbody>\r\n                                                            <tr>\r\n                                                                <td style=\"width: 152px;\">\r\n                                                                    <img\r\n                                                                        height=\"auto\"\r\n                                                                        src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                        style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                        width=\"152\"\r\n                                                                    />\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </tbody>\r\n                                                    </table>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 12px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 12px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 12px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 20px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Welcome !</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:540px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 16px; font-family: \'DM Sans\';\"><strong>Hello {user_first_name},&nbsp;</strong></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 16px; font-family: \'DM Sans\';\">Thanks for Joining. We are really excited to have you on board. You need to confirm your account.</span>\r\n                                                    </p>\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 14px; font-family: Roboto;\"><span style=\"font-size: 16px; font-family: \'DM Sans\';\">Just press the button below to confirm your registration.</span><br /></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"center\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 4px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 4px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{email_verify_url}\" style=\"font-family: \'DM Sans\'; font-size: 18px;\"><strong>Confirm Account</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">If it doesn\'t work, copy paste the following link in your browser</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{email_verify_url}</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p>\r\n                                                        <span style=\"font-family: Roboto; font-size: 16px;\">\r\n                                                            <span style=\"font-family: \'DM Sans\';\">\r\n                                                                If you have any quiries, contact us at <a href=\"mailto:help@subshero.com\"><span style=\"text-decoration: underline; color: #34495e;\">hi@subshero.com</span></a>\r\n                                                            </span>\r\n                                                            <br />\r\n                                                        </span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Cheers,</span></p>\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Team Subshero</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>', 1, 1, '2021-08-12 01:24:54', 1);
INSERT INTO `email_templates` (`id`, `user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(2, 1, 'password_reset_request', 'Password Reset', '<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                        <tbody>\r\n                                                            <tr>\r\n                                                                <td style=\"width: 152px;\">\r\n                                                                    <img\r\n                                                                        height=\"auto\"\r\n                                                                        src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                        style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                        width=\"152\"\r\n                                                                    />\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </tbody>\r\n                                                    </table>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 12px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 12px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 12px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 20px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Password Reset</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:540px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 16px; font-family: \'DM Sans\';\"><strong>Hello {user_first_name},&nbsp;</strong></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p>\r\n                                                        <span style=\"font-family: \'DM Sans\'; font-size: 16px;\">If you have lost your password or wish to reset it, just press the button below to get started.<br /></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"center\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 4px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 4px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{password_reset_url}\" style=\"font-family: \'DM Sans\'; font-size: 18px;\"><strong>Reset Password</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">If it doesn\'t work, copy paste the following link in your browser</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{password_reset_url}</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p>\r\n                                                        <span style=\"font-family: Roboto; font-size: 16px;\"><span style=\"font-family: \'DM Sans\';\">If you didnot request a password reset, you can safely ignore this email.</span><br /></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Cheers,</span></p>\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Team Subshero</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>', 1, 1, '2021-08-12 01:29:13', 1);
INSERT INTO `email_templates` (`id`, `user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(3, 1, 'subscription_renew', 'Subscription Alert - {product_name}', '<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n\r\n                .mj-column-per-75 {\r\n                    width: 75% !important;\r\n                    max-width: 75%;\r\n                }\r\n\r\n                .mj-column-per-25 {\r\n                    width: 25% !important;\r\n                    max-width: 25%;\r\n                }\r\n\r\n                .mj-column-per-50 {\r\n                    width: 50% !important;\r\n                    max-width: 50%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 152px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"152\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 0px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 0px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 10px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Subscription Alert</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 10px 30px 10px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:405px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-75 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <strong><span style=\"font-size: 16px; font-family: \'DM Sans\';\">Hello {user_first_name},&nbsp;</span></strong>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 14px; font-family: Roboto;\">\r\n                                                            <span style=\"font-size: 16px; font-family: \'DM Sans\';\">Your subscription for {product_name} will renew in {subscription_renew_days} days. Renew your subscription or manage it on Subshero</span><br />\r\n                                                        </span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td><td class=\"\" style=\"vertical-align:top;width:135px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-25 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"right\" class=\"mobile_hidden\" style=\"font-size: 0px; padding: 0px 0px 0px 40px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 95px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"{subscription_image_url}\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"95\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"width:540px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0; line-height: 0; text-align: left; display: inline-block; width: 100%; direction: ltr;\">\r\n                                    <!--[if mso | IE]><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" ><tr><td style=\"vertical-align:top;width:270px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 50%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" width=\"100%\">\r\n                                            <tbody>\r\n                                                <tr>\r\n                                                    <td style=\"vertical-align: top; padding: 0px 0px 0px 0px;\">\r\n                                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" width=\"100%\">\r\n                                                            <tr>\r\n                                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                                        <p>\r\n                                                                            <span style=\"font-family: \'DM Sans\'; font-size: 14px;\"><strong>Total Sum:</strong></span>\r\n                                                                        </p>\r\n                                                                    </div>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                            <tr>\r\n                                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                                        <p>\r\n                                                                            <strong><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">Renews On</span><br /></strong>\r\n                                                                        </p>\r\n                                                                    </div>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                            <tr>\r\n                                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                                        <p>\r\n                                                                            <strong><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">Payment Method</span><br /></strong>\r\n                                                                        </p>\r\n                                                                    </div>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </table>\r\n                                                    </td>\r\n                                                </tr>\r\n                                            </tbody>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td><td style=\"vertical-align:top;width:270px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 50%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                        <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{subscription_price}</span></p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                        <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{subscription_renew_date}</span></p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                        <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{subscription_payment_mode}</span></p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 20px 10px 20px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:280px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background-color: ; vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 3px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 3px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{subscription_url}\" style=\"font-family: \'DM Sans\'; font-size: 16px; text-decoration: none;\"><strong style=\"color: black;\">View Subscription</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td><td class=\"\" style=\"vertical-align:top;width:280px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 3px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 3px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{subscription_url}\" style=\"font-family: \'DM Sans\'; font-size: 16px; text-decoration: none;\"><strong style=\"color: black;\">Renew Subscription</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>', 1, 1, '2021-08-13 09:59:11', 1);
INSERT INTO `email_templates` (`id`, `user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(4, 1, 'subscription_delete', 'Subscription Deleted', '<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n\r\n                .mj-column-per-75 {\r\n                    width: 75% !important;\r\n                    max-width: 75%;\r\n                }\r\n\r\n                .mj-column-per-25 {\r\n                    width: 25% !important;\r\n                    max-width: 25%;\r\n                }\r\n\r\n                .mj-column-per-50 {\r\n                    width: 50% !important;\r\n                    max-width: 50%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 152px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"152\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 0px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 0px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 10px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Subscription Alert</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 10px 30px 10px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:405px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-75 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <strong><span style=\"font-size: 16px; font-family: \'DM Sans\';\">Hello {user_first_name},&nbsp;</span></strong>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 14px; font-family: Roboto;\">\r\n                                                            <span style=\"font-size: 16px; font-family: \'DM Sans\';\">Your subscription deleted for {product_name}.</span><br />\r\n                                                        </span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td><td class=\"\" style=\"vertical-align:top;width:135px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-25 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"right\" class=\"mobile_hidden\" style=\"font-size: 0px; padding: 0px 0px 0px 40px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 95px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"{product_image}\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"95\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>', 0, 0, '2021-08-13 12:30:22', 1),
(5, 1, 'webhook_user_create', 'Your App Login - Credentials', '<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                        <tbody>\r\n                                                            <tr>\r\n                                                                <td style=\"width: 152px;\">\r\n                                                                    <img\r\n                                                                        height=\"auto\"\r\n                                                                        src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                        style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                        width=\"152\"\r\n                                                                    />\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </tbody>\r\n                                                    </table>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 12px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 12px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 12px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 0px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\"><strong>Set Your Password</strong></span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:540px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 16px; font-family: \'DM Sans\';\"><strong>Hi {user_first_name},&nbsp;</strong></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p>\r\n                                                        <span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Thanks for Creating an account with Subshero. Please use the link below to set your password.<br /><br />Once your password is set, you can use your credentials to login to your account. </span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"center\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 4px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 4px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{new_password_url}\" style=\"font-family: \'DM Sans\'; font-size: 18px; text-decoration:none;\"><strong style=\"color: #000000\">Set Password</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">If the above button doesn\'t work, copy paste the following link in your browser</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{new_password_url}</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p>\r\n                                                        <span style=\"font-family: Roboto; font-size: 16px;\"><span style=\"font-family: \'DM Sans\';\">Looking forward to seeing you.</span><br /></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Cheers,</span></p>\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Team Subshero</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://app.subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>', 1, 1, '2021-08-31 08:45:45', 1);
INSERT INTO `email_templates` (`id`, `user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(6, 1, 'subscription_renew_contact', 'Subscription(C) Alert- {product_name}', '<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n\r\n                .mj-column-per-75 {\r\n                    width: 75% !important;\r\n                    max-width: 75%;\r\n                }\r\n\r\n                .mj-column-per-25 {\r\n                    width: 25% !important;\r\n                    max-width: 25%;\r\n                }\r\n\r\n                .mj-column-per-50 {\r\n                    width: 50% !important;\r\n                    max-width: 50%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 152px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"152\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 0px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 0px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 10px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Subscription Alert</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 10px 30px 10px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:405px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-75 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <strong><span style=\"font-size: 16px; font-family: \'DM Sans\';\">Hello {contact_full_name},&nbsp;</span></strong>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 14px; font-family: Roboto;\">\r\n                                                            <span style=\"font-size: 16px; font-family: \'DM Sans\';\">Your subscription for {product_name} will renew in {subscription_renew_days} days. Renew your subscription or manage it on Subshero</span><br />\r\n                                                        </span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td><td class=\"\" style=\"vertical-align:top;width:135px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-25 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"right\" class=\"mobile_hidden\" style=\"font-size: 0px; padding: 0px 0px 0px 40px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 95px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"{subscription_image_url}\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"95\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"width:540px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0; line-height: 0; text-align: left; display: inline-block; width: 100%; direction: ltr;\">\r\n                                    <!--[if mso | IE]><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" ><tr><td style=\"vertical-align:top;width:270px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 50%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" width=\"100%\">\r\n                                            <tbody>\r\n                                                <tr>\r\n                                                    <td style=\"vertical-align: top; padding: 0px 0px 0px 0px;\">\r\n                                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" width=\"100%\">\r\n                                                            <tr>\r\n                                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                                        <p>\r\n                                                                            <span style=\"font-family: \'DM Sans\'; font-size: 14px;\"><strong>Total Sum:</strong></span>\r\n                                                                        </p>\r\n                                                                    </div>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                            <tr>\r\n                                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                                        <p>\r\n                                                                            <strong><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">Renews On</span><br /></strong>\r\n                                                                        </p>\r\n                                                                    </div>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                            <tr>\r\n                                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                                        <p>\r\n                                                                            <strong><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">Payment Method</span><br /></strong>\r\n                                                                        </p>\r\n                                                                    </div>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </table>\r\n                                                    </td>\r\n                                                </tr>\r\n                                            </tbody>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td><td style=\"vertical-align:top;width:270px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 50%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                        <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{subscription_price}</span></p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                        <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{subscription_renew_date}</span></p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                        <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{subscription_payment_mode}</span></p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 20px 10px 20px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:280px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background-color: ; vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 3px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 3px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{subscription_url}\" style=\"font-family: \'DM Sans\'; font-size: 16px; text-decoration: none;\"><strong style=\"color: black;\">View Subscription</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td><td class=\"\" style=\"vertical-align:top;width:280px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 3px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 3px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{subscription_url}\" style=\"font-family: \'DM Sans\'; font-size: 16px; text-decoration: none;\"><strong style=\"color: black;\">Renew Subscription</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>', 1, 1, '2021-12-28 08:32:58', 1);
INSERT INTO `email_templates` (`id`, `user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(7, 1, 'subscription_refund', 'Subscription Refund Alert - {product_name}', '<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n\r\n                .mj-column-per-75 {\r\n                    width: 75% !important;\r\n                    max-width: 75%;\r\n                }\r\n\r\n                .mj-column-per-25 {\r\n                    width: 25% !important;\r\n                    max-width: 25%;\r\n                }\r\n\r\n                .mj-column-per-50 {\r\n                    width: 50% !important;\r\n                    max-width: 50%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 152px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"152\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 0px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 0px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 10px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Subscription Refund Alert</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 10px 30px 10px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:405px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-75 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <strong><span style=\"font-size: 16px; font-family: \'DM Sans\';\">Hello {user_first_name},&nbsp;</span></strong>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 14px; font-family: Roboto;\">\r\n                                                            <span style=\"font-size: 16px; font-family: \'DM Sans\';\">Your subscription refund date for {product_name} is due in {subscription_refund_days_left} days</span><br />\r\n                                                        </span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td><td class=\"\" style=\"vertical-align:top;width:135px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-25 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"right\" class=\"mobile_hidden\" style=\"font-size: 0px; padding: 0px 0px 0px 40px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 95px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"{subscription_image_url}\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"95\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"width:540px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0; line-height: 0; text-align: left; display: inline-block; width: 100%; direction: ltr;\">\r\n                                    <!--[if mso | IE]><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" ><tr><td style=\"vertical-align:top;width:270px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 50%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" width=\"100%\">\r\n                                            <tbody>\r\n                                                <tr>\r\n                                                    <td style=\"vertical-align: top; padding: 0px 0px 0px 0px;\">\r\n                                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" width=\"100%\">\r\n                                                            <tr>\r\n                                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                                        <p>\r\n                                                                            <span style=\"font-family: \'DM Sans\'; font-size: 14px;\"><strong>Total Sum:</strong></span>\r\n                                                                        </p>\r\n                                                                    </div>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                            <tr>\r\n                                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                                        <p>\r\n                                                                            <strong><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">Refund Date</span><br /></strong>\r\n                                                                        </p>\r\n                                                                    </div>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                            <tr>\r\n                                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                                        <p>\r\n                                                                            <strong><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">Refund Days Left</span><br /></strong>\r\n                                                                        </p>\r\n                                                                    </div>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </table>\r\n                                                    </td>\r\n                                                </tr>\r\n                                            </tbody>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td><td style=\"vertical-align:top;width:270px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 50%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                        <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{subscription_price}</span></p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                        <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{subscription_refund_date}</span></p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                        <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{subscription_refund_days_left} days</span></p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 20px 10px 20px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:280px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background-color: ; vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 3px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 3px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{subscription_url}\" style=\"font-family: \'DM Sans\'; font-size: 16px;\"><strong>View Subscription</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td><td class=\"\" style=\"vertical-align:top;width:280px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-50 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 3px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 3px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{subscription_url}\" style=\"font-family: \'DM Sans\'; font-size: 16px;\"><strong>Renew Subscription</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:540px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.6; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 16px; font-family: \'DM Sans\';\">\r\n                                             \r\n                                                        </span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>', 1, 1, '2022-01-25 09:57:57', 1);
INSERT INTO `email_templates` (`id`, `user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(8, 1, 'team_user_invite', 'Welcome To Subshero', '<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                        <tbody>\r\n                                                            <tr>\r\n                                                                <td style=\"width: 152px;\">\r\n                                                                    <img\r\n                                                                        height=\"auto\"\r\n                                                                        src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                        style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                        width=\"152\"\r\n                                                                    />\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </tbody>\r\n                                                    </table>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 12px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 12px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 12px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 20px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Welcome</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:540px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 16px; font-family: \'DM Sans\';\"><strong>Hello {user_first_name},&nbsp;</strong></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p>\r\n                                                        <span style=\"font-family: \'DM Sans\'; font-size: 16px;\">To accept the invitation, just press the button below.<br /></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"center\" vertical-align=\"middle\" style=\"font-size: 0px; padding: 10px 10px 10px 10px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: separate; line-height: 100%;\">\r\n                                                    <tr>\r\n                                                        <td\r\n                                                            align=\"center\"\r\n                                                            bgcolor=\"#F9C916\"\r\n                                                            role=\"presentation\"\r\n                                                            style=\"border: none; border-radius: 4px; cursor: auto; mso-padding-alt: 18px 35px 18px 35px; background: #f9c916;\"\r\n                                                            valign=\"middle\"\r\n                                                        >\r\n                                                            <p\r\n                                                                style=\"\r\n                                                                    display: inline-block;\r\n                                                                    background: #f9c916;\r\n                                                                    color: #000000;\r\n                                                                    font-family: Ubuntu, Helvetica, Arial, sans-serif;\r\n                                                                    font-size: 13px;\r\n                                                                    font-weight: normal;\r\n                                                                    line-height: 120%;\r\n                                                                    margin: 0;\r\n                                                                    text-decoration: none;\r\n                                                                    text-transform: none;\r\n                                                                    padding: 18px 35px 18px 35px;\r\n                                                                    mso-padding-alt: 0px;\r\n                                                                    border-radius: 4px;\r\n                                                                \"\r\n                                                            >\r\n                                                                <a href=\"{invitation_url}\" style=\"font-family: \'DM Sans\'; font-size: 18px;\"><strong>Accept The Invitation</strong></a><br />\r\n                                                            </p>\r\n                                                        </td>\r\n                                                    </tr>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">If it doesn\'t work, copy paste the following link in your browser</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"background: #efefef; font-size: 0px; padding: 10px 25px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 14px;\">{invitation_url}</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p>\r\n                                                        <span style=\"font-family: Roboto; font-size: 16px;\"><span style=\"font-family: \'DM Sans\';\">If you did not request this invitation, you can safely ignore this email.</span><br /></span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\r\n                                                <div style=\"height: 20px;\">&nbsp;</div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Cheers,</span></p>\r\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Team Subshero</span></p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>', 1, 1, '2021-08-31 08:45:45', 1),
(9, 1, 'subscription_expire', 'Subscription Expired', '<!DOCTYPE html>\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\r\n    <head>\r\n        <title></title>\r\n        <!--[if !mso]><!-- -->\r\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\r\n        <!--<![endif]-->\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\r\n        <style type=\"text/css\">\r\n            #outlook a {\r\n                padding: 0;\r\n            }\r\n\r\n            body {\r\n                margin: 0;\r\n                padding: 0;\r\n                -webkit-text-size-adjust: 100%;\r\n                -ms-text-size-adjust: 100%;\r\n            }\r\n\r\n            table,\r\n            td {\r\n                border-collapse: collapse;\r\n                mso-table-lspace: 0pt;\r\n                mso-table-rspace: 0pt;\r\n            }\r\n\r\n            img {\r\n                border: 0;\r\n                height: auto;\r\n                line-height: 100%;\r\n                outline: none;\r\n                text-decoration: none;\r\n                -ms-interpolation-mode: bicubic;\r\n            }\r\n\r\n            p {\r\n                display: block;\r\n                margin: 13px 0;\r\n            }\r\n        </style>\r\n        <!--[if mso]>\r\n            <xml>\r\n                <o:OfficeDocumentSettings>\r\n                    <o:AllowPNG />\r\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\r\n                </o:OfficeDocumentSettings>\r\n            </xml>\r\n        <![endif]-->\r\n        <!--[if lte mso 11]>\r\n            <style type=\"text/css\">\r\n                .mj-outlook-group-fix {\r\n                    width: 100% !important;\r\n                }\r\n            </style>\r\n        <![endif]-->\r\n        <!--[if !mso]><!-->\r\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\r\n        <style type=\"text/css\">\r\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\r\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\r\n        </style>\r\n        <!--<![endif]-->\r\n        <style type=\"text/css\">\r\n            @media only screen and (min-width: 480px) {\r\n                .mj-column-per-100 {\r\n                    width: 100% !important;\r\n                    max-width: 100%;\r\n                }\r\n\r\n                .mj-column-per-75 {\r\n                    width: 75% !important;\r\n                    max-width: 75%;\r\n                }\r\n\r\n                .mj-column-per-25 {\r\n                    width: 25% !important;\r\n                    max-width: 25%;\r\n                }\r\n\r\n                .mj-column-per-50 {\r\n                    width: 50% !important;\r\n                    max-width: 50%;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            @media only screen and (max-width: 480px) {\r\n                table.mj-full-width-mobile {\r\n                    width: 100% !important;\r\n                }\r\n\r\n                td.mj-full-width-mobile {\r\n                    width: auto !important;\r\n                }\r\n            }\r\n        </style>\r\n        <style type=\"text/css\">\r\n            .desktop_hidden {\r\n                display: none;\r\n                max-height: 0px;\r\n            }\r\n\r\n            @media (max-width: 660px) {\r\n                .mobile_hidden {\r\n                    min-height: 0px;\r\n                    max-height: 0px;\r\n                    max-width: 0px;\r\n                    display: none;\r\n                    overflow: hidden;\r\n                    font-size: 0px;\r\n                }\r\n\r\n                .desktop_hidden {\r\n                    display: block !important;\r\n                    max-height: none !important;\r\n                }\r\n            }\r\n        </style>\r\n    </head>\r\n    <body style=\"background-color: #efefef;\">\r\n        <div style=\"background-color: #efefef;\">\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 152px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"152\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 0px;\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td>\r\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n                            <div style=\"margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 0px;\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\r\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                                        <tr>\r\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 10px 10px; word-break: break-word;\">\r\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\r\n                                                                    <p style=\"text-align: center;\">\r\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">Subscription Alert</span><br /></span>\r\n                                                                    </p>\r\n                                                                </div>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n                                                </div>\r\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                            </td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\r\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"direction: ltr; font-size: 0px; padding: 10px 30px 10px 30px; text-align: center;\">\r\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:405px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-75 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <strong><span style=\"font-size: 16px; font-family: \'DM Sans\';\">Hello {user_first_name},&nbsp;</span></strong>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                        <tr>\r\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\r\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\r\n                                                    <p style=\"text-align: left;\">\r\n                                                        <span style=\"font-size: 14px; font-family: Roboto;\">\r\n                                                            <span style=\"font-size: 16px; font-family: \'DM Sans\';\">Your subscription expired for {product_name}.</span><br />\r\n                                                        </span>\r\n                                                    </p>\r\n                                                </div>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td><td class=\"\" style=\"vertical-align:top;width:135px;\" ><![endif]-->\r\n                                <div class=\"mj-column-per-25 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                        <tr>\r\n                                            <td align=\"right\" class=\"mobile_hidden\" style=\"font-size: 0px; padding: 0px 0px 0px 40px; word-break: break-word;\">\r\n                                                <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\r\n                                                    <tbody>\r\n                                                        <tr>\r\n                                                            <td style=\"width: 95px;\">\r\n                                                                <img\r\n                                                                    height=\"auto\"\r\n                                                                    src=\"{product_image}\"\r\n                                                                    style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\r\n                                                                    width=\"95\"\r\n                                                                />\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </tbody>\r\n                                                </table>\r\n                                            </td>\r\n                                        </tr>\r\n                                    </table>\r\n                                </div>\r\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </div>\r\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\r\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 10px; max-width: 600px;\">\r\n                <div style=\"line-height: 0; font-size: 0;\">\r\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 10px;\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\r\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\r\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\r\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\r\n                                            <tr>\r\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\r\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\r\n                                                        <p style=\"text-align: center;\">\r\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\r\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\r\n                                                            </span>\r\n                                                        </p>\r\n                                                    </div>\r\n                                                </td>\r\n                                            </tr>\r\n                                        </table>\r\n                                    </div>\r\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\r\n        </div>\r\n    </body>\r\n</html>', 1, 1, '2022-06-03 09:12:44', 1);
INSERT INTO `email_templates` (`id`, `user_id`, `type`, `subject`, `body`, `is_default`, `status`, `created_at`, `created_by`) VALUES
(12, 1, 'product_notify', 'Product Notification', '<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">\n    <head>\n        <title></title>\n        <!--[if !mso]><!-- -->\n        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\n        <!--<![endif]-->\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n        <style type=\"text/css\">\n            #outlook a {\n                padding: 0;\n            }\n\n            body {\n                margin: 0;\n                padding: 0;\n                -webkit-text-size-adjust: 100%;\n                -ms-text-size-adjust: 100%;\n            }\n\n            table,\n            td {\n                border-collapse: collapse;\n                mso-table-lspace: 0pt;\n                mso-table-rspace: 0pt;\n            }\n\n            img {\n                border: 0;\n                height: auto;\n                line-height: 100%;\n                outline: none;\n                text-decoration: none;\n                -ms-interpolation-mode: bicubic;\n            }\n\n            p {\n                display: block;\n                margin: 13px 0;\n            }\n        </style>\n        <!--[if mso]>\n            <xml>\n                <o:OfficeDocumentSettings>\n                    <o:AllowPNG />\n                    <o:PixelsPerInch>96</o:PixelsPerInch>\n                </o:OfficeDocumentSettings>\n            </xml>\n        <![endif]-->\n        <!--[if lte mso 11]>\n            <style type=\"text/css\">\n                .mj-outlook-group-fix {\n                    width: 100% !important;\n                }\n            </style>\n        <![endif]-->\n        <!--[if !mso]><!-->\n        <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\n        <link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700\" rel=\"stylesheet\" type=\"text/css\" />\n        <link href=\"https://fonts.googleapis.com/css2?family=DM%20Sans:ital,wght@0,400;0,700;1,400;1,700\" rel=\"stylesheet\" type=\"text/css\" />\n        <style type=\"text/css\">\n            @import url(https://fonts.googleapis.com/css?family=Roboto:300, 400, 500, 700);\n            @import url(https://fonts.googleapis.com/css?family=Ubuntu:300, 400, 500, 700);\n            @import url(https://fonts.googleapis.com/css2?family=DM%20Sans:ital, wght@0, 400;0, 700;1, 400;1, 700);\n        </style>\n        <!--<![endif]-->\n        <style type=\"text/css\">\n            @media only screen and (min-width: 480px) {\n                .mj-column-per-100 {\n                    width: 100% !important;\n                    max-width: 100%;\n                }\n            }\n        </style>\n        <style type=\"text/css\">\n            @media only screen and (max-width: 480px) {\n                table.mj-full-width-mobile {\n                    width: 100% !important;\n                }\n\n                td.mj-full-width-mobile {\n                    width: auto !important;\n                }\n            }\n        </style>\n        <style type=\"text/css\">\n            .desktop_hidden {\n                display: none;\n                max-height: 0px;\n            }\n\n            @media (max-width: 660px) {\n                .mobile_hidden {\n                    min-height: 0px;\n                    max-height: 0px;\n                    max-width: 0px;\n                    display: none;\n                    overflow: hidden;\n                    font-size: 0px;\n                }\n\n                .desktop_hidden {\n                    display: block !important;\n                    max-height: none !important;\n                }\n            }\n        </style>\n    </head>\n    <body style=\"background-color: #efefef;\">\n        <div style=\"background-color: #efefef;\">\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\n                <div style=\"line-height: 0; font-size: 0;\">\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\n                        <tbody>\n                            <tr>\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\n                                            <tr>\n                                                <td align=\"center\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"border-collapse: collapse; border-spacing: 0px;\">\n                                                        <tbody>\n                                                            <tr>\n                                                                <td style=\"width: 152px;\">\n                                                                    <img\n                                                                        height=\"auto\"\n                                                                        src=\"https://cdn.dragit.io/file-manager/files/users/793/logo-light.png\"\n                                                                        style=\"border: 0; display: block; outline: none; text-decoration: none; height: auto; width: 100%; font-size: 13px;\"\n                                                                        width=\"152\"\n                                                                    />\n                                                                </td>\n                                                            </tr>\n                                                        </tbody>\n                                                    </table>\n                                                </td>\n                                            </tr>\n                                        </table>\n                                    </div>\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\n                                </td>\n                            </tr>\n                        </tbody>\n                    </table>\n                </div>\n            </div>\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\n            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #efefef; background-color: #efefef; width: 100%; border-radius: 12px;\">\n                <tbody>\n                    <tr>\n                        <td>\n                            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\n                            <div style=\"margin: 0px auto; border-radius: 12px; max-width: 600px;\">\n                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"width: 100%; border-radius: 12px;\">\n                                    <tbody>\n                                        <tr>\n                                            <td style=\"direction: ltr; font-size: 0px; padding: 0px 0px 0px 0px; text-align: center;\">\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\n                                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\n                                                        <tr>\n                                                            <td align=\"left\" style=\"background: #ffffff; font-size: 0px; padding: 30px 10px 20px 10px; word-break: break-word;\">\n                                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #373737;\">\n                                                                    <p style=\"text-align: center;\">\n                                                                        <span style=\"font-family: \'arial black\', \'avant garde\'; font-size: 24pt;\"><span style=\"font-size: 34px; font-family: \'DM Sans\';\">New Product Submitted !</span><br /></span>\n                                                                    </p>\n                                                                </div>\n                                                            </td>\n                                                        </tr>\n                                                    </table>\n                                                </div>\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\n                                            </td>\n                                        </tr>\n                                    </tbody>\n                                </table>\n                            </div>\n                            <!--[if mso | IE]></td></tr></table><![endif]-->\n                        </td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--[if mso | IE]><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><![endif]-->\n            <div style=\"background: #ffffff; background-color: #ffffff; margin: 0px auto; border-radius: 0px; max-width: 600px;\">\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #ffffff; background-color: #ffffff; width: 100%; border-radius: 0px;\">\n                    <tbody>\n                        <tr>\n                            <td style=\"direction: ltr; font-size: 0px; padding: 30px 30px 30px 30px; text-align: center;\">\n                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:540px;\" ><![endif]-->\n                                <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\n                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\n                                        <tr>\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\n                                                    <p style=\"text-align: left;\">\n                                                        <span style=\"font-size: 16px; font-family: \'DM Sans\';\"><strong>New Product Added:&nbsp;</strong></span>\n                                                    </p>\n                                                </div>\n                                            </td>\n                                        </tr>\n                                        \n                                        \n                                        <tr>\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.4; text-align: left; color: #000000;\">\n                                                    <p>\n                                                        <span style=\"font-family: Roboto; font-size: 16px;\">\n                                                            <span style=\"font-family: \'DM Sans\';\">\n                                                                <table><tr><th>Product name:</th><td>&nbsp;&nbsp;&nbsp; {product_name}</td></tr><tr><th>Product price:</th><td>&nbsp;&nbsp;&nbsp; {product_price}</td></tr><tr><th>Product type:</th><td>&nbsp;&nbsp;&nbsp; {product_type}</td></tr>\n                                                                </table>\n                                                            </span>\n                                                            <br />\n                                                        </span>\n                                                    </p>\n                                                </div>\n                                            </td>\n                                        </tr>\n                                        <tr>\n                                            <td style=\"font-size: 0px; word-break: break-word;\">\n                                                <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td height=\"20\" style=\"height:20px;\"><![endif]-->\n                                                <div style=\"height: 20px;\">&nbsp;</div>\n                                                <!--[if mso | IE]></td></tr></table><![endif]-->\n                                            </td>\n                                        </tr>\n                                        <tr>\n                                            <td align=\"left\" style=\"font-size: 0px; padding: 0px 10px 0px 0px; word-break: break-word;\">\n                                                <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;\">\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Cheers,</span></p>\n                                                    <p><span style=\"font-family: \'DM Sans\'; font-size: 16px;\">Team Subshero</span></p>\n                                                </div>\n                                            </td>\n                                        </tr>\n                                    </table>\n                                </div>\n                                <!--[if mso | IE]></td></tr></table><![endif]-->\n                            </td>\n                        </tr>\n                    </tbody>\n                </table>\n            </div>\n            <!--[if mso | IE]></td></tr></table><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"width:600px;\" width=\"600\" ><tr><td style=\"line-height:0px;font-size:0px;mso-line-height-rule:exactly;\"><v:rect style=\"width:600px;\" xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"true\" stroke=\"false\"><v:fill origin=\"0.5, 0\" position=\"0.5, 0\" src=\"\" color=\"#004547\" type=\"tile\" /><v:textbox style=\"mso-fit-shape-to-text:true\" inset=\"0,0,0,0\"><![endif]-->\n            <div style=\"background: #004547; background-color: #004547; margin: 0px auto; border-radius: 8px; max-width: 600px;\">\n                <div style=\"line-height: 0; font-size: 0;\">\n                    <table align=\"center\" background=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"background: #004547; background-color: #004547; width: 100%; border-radius: 8px;\">\n                        <tbody>\n                            <tr>\n                                <td style=\"direction: ltr; font-size: 0px; padding: 20px 0; text-align: center;\">\n                                    <!--[if mso | IE]><table role=\"presentation\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"\" style=\"vertical-align:top;width:600px;\" ><![endif]-->\n                                    <div class=\"mj-column-per-100 mj-outlook-group-fix\" style=\"font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;\">\n                                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"vertical-align: top;\" width=\"100%\">\n                                            <tr>\n                                                <td align=\"left\" style=\"font-size: 0px; padding: 0px 0px 0px 0px; word-break: break-word;\">\n                                                    <div style=\"font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #ffffff;\">\n                                                        <p style=\"text-align: center;\">\n                                                            <span style=\"font-size: 14px; font-family: \'DM Sans\';\">\n                                                                Thank you for choosing <span style=\"color: #f1c40f;\"><a style=\"color: #f1c40f;\" href=\"https://subshero.com/\" target=\"_blank\" rel=\"noopener\">Subshero</a></span>\n                                                            </span>\n                                                        </p>\n                                                    </div>\n                                                </td>\n                                            </tr>\n                                        </table>\n                                    </div>\n                                    <!--[if mso | IE]></td></tr></table><![endif]-->\n                                </td>\n                            </tr>\n                        </tbody>\n                    </table>\n                </div>\n            </div>\n            <!--[if mso | IE]></v:textbox></v:rect></td></tr></table><![endif]-->\n        </div>\n    </body>\n</html>', 1, 1, '2021-08-31 08:45:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `email_types`
--

CREATE TABLE `email_types` (
  `id` int(11) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `field_value` varchar(50) DEFAULT NULL,
  `template_name` varchar(50) DEFAULT NULL COMMENT 'email_templates -> name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `email_types`
--

INSERT INTO `email_types` (`id`, `field_name`, `field_value`, `template_name`) VALUES
(1, 'User first name', 'user_first_name', 'user_registration'),
(2, 'User last name', 'user_last_name', 'user_registration'),
(3, 'User full name', 'user_full_name', 'user_registration'),
(4, 'User email', 'user_email', 'user_registration'),
(5, 'App url', 'app_url', 'user_registration'),
(6, 'Email verify url', 'email_verify_url', 'user_registration'),
(7, 'User first name', 'user_first_name', 'user_welcome'),
(8, 'User last name', 'user_last_name', 'user_welcome'),
(9, 'User full name', 'user_full_name', 'user_welcome'),
(10, 'User email', 'user_email', 'user_welcome'),
(11, 'App url', 'app_url', 'user_welcome'),
(12, 'User first name', 'user_first_name', 'password_reset_request'),
(13, 'User last name', 'user_last_name', 'password_reset_request'),
(14, 'User full name', 'user_full_name', 'password_reset_request'),
(15, 'User email', 'user_email', 'password_reset_request'),
(16, 'App url', 'app_url', 'password_reset_request'),
(17, 'Password reset url', 'password_reset_url', 'password_reset_request'),
(18, 'User first name', 'user_first_name', 'password_reset_success'),
(19, 'User last name', 'user_last_name', 'password_reset_success'),
(20, 'User full name', 'user_full_name', 'password_reset_success'),
(21, 'User email', 'user_email', 'password_reset_success'),
(22, 'App url', 'app_url', 'password_reset_success'),
(23, 'User first name', 'user_first_name', 'subscription_renew'),
(24, 'User last name', 'user_last_name', 'subscription_renew'),
(25, 'User full name', 'user_full_name', 'subscription_renew'),
(26, 'User email', 'user_email', 'subscription_renew'),
(27, 'App url', 'app_url', 'subscription_renew'),
(28, 'Subscription url', 'subscription_url', 'subscription_renew'),
(29, 'Subscription image url', 'subscription_image_url', 'subscription_renew'),
(30, 'Subscription renew date', 'subscription_renew_date', 'subscription_renew'),
(31, 'Subscription price', 'subscription_price', 'subscription_renew'),
(32, 'Subscription payment mode', 'subscription_payment_mode', 'subscription_renew'),
(33, 'Product name', 'product_name', 'subscription_renew'),
(34, 'Product type', 'product_type', 'subscription_renew'),
(35, 'Product description', 'product_description', 'subscription_renew'),
(36, 'User first name', 'user_first_name', 'plan_subscribe'),
(37, 'User last name', 'user_last_name', 'plan_subscribe'),
(38, 'User full name', 'user_full_name', 'plan_subscribe'),
(39, 'User email', 'user_email', 'plan_subscribe'),
(40, 'App url', 'app_url', 'plan_subscribe'),
(41, 'Plan name', 'plan_name', 'plan_subscribe'),
(42, 'Plan price', 'plan_price', 'plan_subscribe'),
(43, 'Plan type', 'plan_type', 'plan_subscribe'),
(44, 'Plan type', 'plan_type', 'plan_subscribe'),
(45, 'Plan expire date', 'plan_expire_date', 'plan_subscribe'),
(46, 'User first name', 'user_first_name', 'plan_expire'),
(47, 'User last name', 'user_last_name', 'plan_expire'),
(48, 'User full name', 'user_full_name', 'plan_expire'),
(49, 'User email', 'user_email', 'plan_expire'),
(50, 'App url', 'app_url', 'plan_expire'),
(51, 'Plan name', 'plan_name', 'plan_expire'),
(52, 'Plan price', 'plan_price', 'plan_expire'),
(53, 'Plan type', 'plan_type', 'plan_expire'),
(54, 'User first name', 'user_first_name', 'plan_payment_success'),
(55, 'User last name', 'user_last_name', 'plan_payment_success'),
(56, 'User full name', 'user_full_name', 'plan_payment_success'),
(57, 'User email', 'user_email', 'plan_payment_success'),
(58, 'App url', 'app_url', 'plan_payment_success'),
(59, 'Plan name', 'plan_name', 'plan_payment_success'),
(60, 'Plan price', 'plan_price', 'plan_payment_success'),
(61, 'Plan type', 'plan_type', 'plan_payment_success'),
(62, 'Plan expire date', 'plan_expire_date', 'plan_payment_success'),
(63, 'Transaction id', 'transaction_id', 'plan_payment_success'),
(64, 'Invoice url', 'invoice_url', 'plan_payment_success'),
(65, 'User first name', 'user_first_name', 'plan_payment_failure'),
(66, 'User last name', 'user_last_name', 'plan_payment_failure'),
(67, 'User full name', 'user_full_name', 'plan_payment_failure'),
(68, 'User email', 'user_email', 'plan_payment_failure'),
(69, 'App url', 'app_url', 'plan_payment_failure'),
(70, 'Plan name', 'plan_name', 'plan_payment_failure'),
(71, 'Plan price', 'plan_price', 'plan_payment_failure'),
(72, 'Plan type', 'plan_type', 'plan_payment_failure'),
(73, 'Transaction id', 'transaction_id', 'plan_payment_failure'),
(74, 'User first name', 'user_first_name', 'subscription_delete'),
(75, 'User last name', 'user_last_name', 'subscription_delete'),
(76, 'User full name', 'user_full_name', 'subscription_delete'),
(77, 'User email', 'user_email', 'subscription_delete'),
(78, 'App url', 'app_url', 'subscription_delete'),
(79, 'Subscription url', 'subscription_url', 'subscription_delete'),
(80, 'Subscription image url', 'subscription_image_url', 'subscription_delete'),
(81, 'Subscription renew date', 'subscription_delete_date', 'subscription_delete'),
(82, 'Subscription price', 'subscription_price', 'subscription_delete'),
(83, 'Subscription payment mode', 'subscription_payment_mode', 'subscription_delete'),
(84, 'Product name', 'product_name', 'subscription_delete'),
(85, 'Product type', 'product_type', 'subscription_delete'),
(86, 'Product description', 'product_description', 'subscription_delete'),
(87, 'User first name', 'user_first_name', 'webhook_user_create'),
(88, 'User last name', 'user_last_name', 'webhook_user_create'),
(89, 'User full name', 'user_full_name', 'webhook_user_create'),
(90, 'User email', 'user_email', 'webhook_user_create'),
(91, 'App url', 'app_url', 'webhook_user_create'),
(92, 'New password url', 'new_password_url', 'webhook_user_create'),
(93, 'Contact full name', 'contact_full_name', 'subscription_renew_contact'),
(94, 'Contact email', 'contact_email', 'subscription_renew_contact'),
(95, 'App url', 'app_url', 'subscription_renew_contact'),
(96, 'Subscription url', 'subscription_url', 'subscription_renew_contact'),
(97, 'Subscription image url', 'subscription_image_url', 'subscription_renew_contact'),
(98, 'Subscription renew date', 'subscription_renew_contact_date', 'subscription_renew_contact'),
(99, 'Subscription price', 'subscription_price', 'subscription_renew_contact'),
(100, 'Subscription payment mode', 'subscription_payment_mode', 'subscription_renew_contact'),
(101, 'Product name', 'product_name', 'subscription_renew_contact'),
(102, 'Product type', 'product_type', 'subscription_renew_contact'),
(103, 'Product description', 'product_description', 'subscription_renew_contact'),
(104, 'User first name', 'user_first_name', 'subscription_refund'),
(105, 'User last name', 'user_last_name', 'subscription_refund'),
(106, 'User full name', 'user_full_name', 'subscription_refund'),
(107, 'User email', 'user_email', 'subscription_refund'),
(108, 'App url', 'app_url', 'subscription_refund'),
(109, 'Subscription url', 'subscription_url', 'subscription_refund'),
(110, 'Subscription image url', 'subscription_image_url', 'subscription_refund'),
(111, 'Subscription renew date', 'subscription_renew_date', 'subscription_refund'),
(112, 'Subscription price', 'subscription_price', 'subscription_refund'),
(113, 'Subscription payment mode', 'subscription_payment_mode', 'subscription_refund'),
(114, 'Subscription refund date', 'subscription_refund_date', 'subscription_refund'),
(115, 'Subscription refund days left', 'subscription_refund_days_left', 'subscription_refund'),
(116, 'Product name', 'product_name', 'subscription_refund'),
(117, 'Product type', 'product_type', 'subscription_refund'),
(118, 'Product description', 'product_description', 'subscription_refund'),
(119, 'User first name', 'user_first_name', 'team_user_invite'),
(120, 'User last name', 'user_last_name', 'team_user_invite'),
(121, 'User full name', 'user_full_name', 'team_user_invite'),
(122, 'User email', 'user_email', 'team_user_invite'),
(123, 'App url', 'app_url', 'team_user_invite'),
(124, 'Invitation url', 'invitation_url', 'team_user_invite'),
(125, 'User first name', 'user_first_name', 'subscription_expire'),
(126, 'User last name', 'user_last_name', 'subscription_expire'),
(127, 'User full name', 'user_full_name', 'subscription_expire'),
(128, 'User email', 'user_email', 'subscription_expire'),
(129, 'App url', 'app_url', 'subscription_expire'),
(130, 'Subscription url', 'subscription_url', 'subscription_expire'),
(131, 'Subscription image url', 'subscription_image_url', 'subscription_expire'),
(132, 'Subscription expire date', 'subscription_expire_date', 'subscription_expire'),
(133, 'Subscription price', 'subscription_price', 'subscription_expire'),
(134, 'Subscription payment mode', 'subscription_payment_mode', 'subscription_expire'),
(135, 'Product name', 'product_name', 'subscription_expire'),
(136, 'Product type', 'product_type', 'subscription_expire'),
(137, 'Product description', 'product_description', 'subscription_expire'),
(138, 'Product name', 'product_name', 'product_notify'),
(139, 'Product price', 'product_price', 'product_notify'),
(140, 'Product type', 'product_type', 'product_notify');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, user_payment, plan_change, subscriptions, products, customer_products, user_alert, import_export, user_settings, generic, app_update, account_reset, email_refund, email_expire, team_user_invite, notifications, webhook',
  `event_type_source` tinyint(1) DEFAULT 0 COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext DEFAULT NULL COMMENT 'Caller',
  `event_url` tinytext DEFAULT NULL,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int(11) DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_browser`
--

CREATE TABLE `event_browser` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, subscriptions, user_alert, email_refund, email_expire, team_user_invite',
  `event_type_source` tinyint(1) DEFAULT 0 COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext DEFAULT NULL COMMENT 'Caller',
  `event_url` tinytext DEFAULT NULL,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int(11) DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_browser_notify`
--

CREATE TABLE `event_browser_notify` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `type` varchar(30) DEFAULT NULL COMMENT 'subscription_delete',
  `title` tinytext DEFAULT NULL COMMENT 'Title or header of the message',
  `message` tinytext DEFAULT NULL COMMENT 'Push message text',
  `icon` tinytext DEFAULT NULL COMMENT 'URL of notification''s icon',
  `image` tinytext DEFAULT NULL COMMENT 'URL of big picture to display below the notification main body. Ratio = 1.5:1, 360x240 pixels',
  `redirect_url` tinytext DEFAULT NULL COMMENT 'URL which will be opened in user’s browser if user clicks on the notification.',
  `buttons` text DEFAULT NULL COMMENT 'One or two action buttons in JSON array format with title and url fields.',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Pending, 1=Sent, 2=Failed',
  `scheduled_at` datetime DEFAULT NULL COMMENT 'Immediate or schedule at specific time',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_chrome`
--

CREATE TABLE `event_chrome` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, subscriptions, user_alert, email_refund, email_expire, team_user_invite',
  `event_type_source` tinyint(1) DEFAULT 0 COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext DEFAULT NULL COMMENT 'Caller',
  `event_url` tinytext DEFAULT NULL,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int(11) DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_chrome_extn`
--

CREATE TABLE `event_chrome_extn` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `subscription_id` int(11) DEFAULT NULL COMMENT 'subscriptions -> id',
  `alert_id` int(11) DEFAULT NULL COMMENT 'users_alert -> id',
  `type` tinyint(1) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `read` tinyint(1) DEFAULT 0 COMMENT '0=No, 1=Yes',
  `created_at` datetime DEFAULT NULL,
  `send_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_emails`
--

CREATE TABLE `event_emails` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, subscriptions, user_alert, email_refund, email_expire, team_user_invite',
  `event_type_source` tinyint(1) DEFAULT 0 COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext DEFAULT NULL COMMENT 'Caller',
  `event_url` tinytext DEFAULT NULL,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int(11) DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE `event_types` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_webhook`
--

CREATE TABLE `event_webhook` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `event_timezone` varchar(30) DEFAULT NULL,
  `event_type` varchar(30) DEFAULT NULL COMMENT 'email, admin_email, user_registration, subscriptions, user_alert, email_refund, email_expire, team_user_invite',
  `event_type_source` tinyint(1) DEFAULT 0 COMMENT '0=0, 1=Webapp, 2=API, 3=Chrome extension, 4=Mobile app',
  `event_type_status` varchar(30) DEFAULT NULL COMMENT 'create, update, delete',
  `event_type_color` varchar(10) DEFAULT NULL,
  `event_type_schedule` tinyint(1) DEFAULT NULL COMMENT '0=No schedule, 1=One time, 2=Recurring',
  `event_type_scdate` datetime DEFAULT NULL COMMENT 'Schedule date',
  `event_type_description` varchar(100) DEFAULT NULL,
  `event_type_function` tinytext DEFAULT NULL COMMENT 'Caller',
  `event_url` tinytext DEFAULT NULL,
  `event_cron` tinyint(1) DEFAULT NULL COMMENT '1=to be processed by cron',
  `event_status` tinyint(1) DEFAULT NULL COMMENT '1=Completed, 2=Error',
  `event_migrate` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `event_product_id` int(11) DEFAULT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `table_row_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extension_settings`
--

CREATE TABLE `extension_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `auto_detect_subscriptions` int(11) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `browser_notifications` int(11) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `notify_before_days` int(11) DEFAULT NULL COMMENT 'Notify before days',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `extension_settings`
--

INSERT INTO `extension_settings` (`id`, `user_id`, `auto_detect_subscriptions`, `browser_notifications`, `notify_before_days`, `created_at`, `updated_at`) VALUES
(3, 42, 1, 1, 8, '2022-05-02 10:42:38', '2022-06-27 05:43:41'),
(4, 322, 1, NULL, 1, '2022-05-13 13:32:10', '2022-05-13 13:32:10'),
(5, 112, 1, 0, 1, '2022-05-13 14:08:05', '2023-10-29 21:30:22'),
(6, 222, 0, 1, 7, '2022-05-16 01:04:06', '2022-05-16 01:04:10'),
(7, 350, 1, 1, 1, '2022-05-16 11:29:18', '2022-05-16 11:32:27'),
(8, 274, 1, 1, 3, '2022-05-22 21:04:03', '2022-05-22 21:04:15'),
(10, 406, 1, 1, 3, '2022-07-18 04:38:13', '2022-09-15 03:43:37'),
(11, 368, 1, 1, 2, '2022-09-01 17:23:06', '2022-09-01 17:23:10'),
(12, 375, 1, NULL, 1, '2022-09-24 19:08:19', '2022-09-24 19:08:19'),
(13, 283, 0, 1, 7, '2022-10-23 05:37:06', '2022-10-23 05:37:09'),
(15, 473, 1, NULL, 1, '2022-11-18 08:11:55', '2022-11-18 08:11:55'),
(16, 58, 1, NULL, 1, '2022-12-02 08:57:34', '2022-12-02 08:57:34'),
(17, 185, 1, 1, 3, '2022-12-11 11:04:18', '2022-12-12 13:25:03'),
(18, 514, 1, 1, 10, '2022-12-14 16:10:04', '2022-12-18 23:58:28'),
(19, 258, 1, NULL, 1, '2022-12-15 13:42:02', '2022-12-15 13:42:02'),
(20, 525, 1, 1, 1, '2022-12-20 18:37:48', '2022-12-20 18:37:52'),
(21, 534, 1, 1, 15, '2023-01-04 21:14:22', '2023-01-04 21:14:43'),
(22, 168, 1, 1, 4, '2023-01-07 18:20:53', '2023-01-07 18:21:04'),
(24, 583, 1, NULL, 1, '2023-02-14 07:34:30', '2023-02-14 07:34:30'),
(25, 620, 1, NULL, 1, '2023-04-06 22:00:15', '2023-04-06 22:00:15'),
(26, 492, 1, 1, 1, '2023-04-23 20:42:59', '2023-04-24 07:58:53'),
(27, 41, 1, 1, 1, '2023-04-24 07:22:52', '2023-04-24 07:42:34'),
(28, 657, 1, NULL, 1, '2023-05-18 01:08:42', '2023-05-18 01:08:42'),
(29, 658, 1, NULL, 1, '2023-05-18 13:52:44', '2023-05-18 13:52:44'),
(30, 493, 0, 0, 1, '2023-06-18 19:57:30', '2023-06-18 19:57:31'),
(31, 674, 1, 1, 1, '2023-08-03 10:13:04', '2023-08-03 10:13:05'),
(32, 608, 0, 1, 2, '2023-08-17 00:41:36', '2023-08-17 00:41:42'),
(33, 688, 0, 0, 7, '2023-10-14 10:26:13', '2023-10-14 10:26:17'),
(34, 704, 0, 1, 2, '2023-12-23 02:00:42', '2023-12-23 02:00:44'),
(35, 718, 0, 0, 1, '2024-01-18 13:29:01', '2024-01-18 13:29:02'),
(36, 719, 1, NULL, 1, '2024-01-19 07:35:21', '2024-01-19 07:35:21'),
(37, 722, 1, 1, 1, '2024-01-24 17:28:02', '2024-01-24 17:28:04'),
(38, 730, NULL, 1, 1, '2024-02-23 11:12:31', '2024-02-23 11:12:31'),
(39, 731, 1, NULL, 1, '2024-03-08 18:40:02', '2024-03-08 18:40:02'),
(40, 734, 0, 0, 1, '2024-03-27 03:36:20', '2024-03-27 03:36:20'),
(41, 360, 0, 1, 2, '2024-03-29 21:35:29', '2024-03-29 22:06:39'),
(42, 740, 1, 1, 1, '2024-04-26 07:39:52', '2024-04-26 07:40:03'),
(43, 745, 1, NULL, 1, '2024-06-07 06:18:25', '2024-06-07 06:18:25'),
(44, 748, 1, 1, 3, '2024-06-25 05:31:04', '2024-06-25 05:31:07'),
(45, 767, 1, 1, 1, '2024-09-04 09:42:10', '2024-09-04 09:42:16'),
(46, 768, 1, 1, 40, '2024-09-05 23:53:27', '2024-09-05 23:53:42'),
(47, 769, 1, 1, 2, '2024-09-08 13:53:11', '2024-09-08 13:53:15'),
(48, 775, 1, 1, 1, '2024-11-03 05:37:46', '2024-11-03 05:37:50'),
(49, 782, 1, 1, 1, '2025-01-06 17:45:05', '2025-01-06 17:46:44');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `relation` varchar(50) DEFAULT NULL COMMENT 'Table Name',
  `relation_id` int(11) DEFAULT NULL COMMENT 'Table -> id',
  `name` varchar(255) NOT NULL,
  `extension` varchar(50) DEFAULT NULL,
  `mime_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `size` bigint(20) NOT NULL DEFAULT 0,
  `path` text DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=Available, 1=Deleted',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `folder`
--

CREATE TABLE `folder` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User -> id',
  `name` varchar(20) NOT NULL COMMENT 'Folder name',
  `color` varchar(10) DEFAULT NULL COMMENT 'Folder color',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=Default',
  `price_type` varchar(3) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'User -> id',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User -> id',
  `plan_id` int(11) NOT NULL COMMENT 'Plan -> id',
  `inv_no` varchar(20) NOT NULL COMMENT 'Invoice Number',
  `total` double NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0 COMMENT '0=System, User -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_orders`
--

CREATE TABLE `marketplace_orders` (
  `id` int(11) NOT NULL,
  `marketplace_item_id` int(11) DEFAULT NULL COMMENT 'subscription_cart -> id',
  `seller_user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `seller_paypal_api_username` tinytext DEFAULT NULL,
  `buyer_user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `buyer_first_name` varchar(50) DEFAULT NULL,
  `buyer_last_name` varchar(50) DEFAULT NULL,
  `buyer_email` varchar(50) DEFAULT NULL,
  `buyer_phone` varchar(30) DEFAULT NULL,
  `buyer_company_name` varchar(50) DEFAULT NULL,
  `buyer_country` varchar(10) DEFAULT NULL,
  `payment_method` varchar(10) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL COMMENT 'subscriptions -> id',
  `product_id` int(11) DEFAULT NULL COMMENT 'products -> id',
  `product_name` varchar(50) DEFAULT NULL,
  `product_logo` tinytext DEFAULT NULL,
  `sale_price` double DEFAULT NULL,
  `currency_code` varchar(5) DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `charges` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `paypal_token` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `secret_key` varchar(60) DEFAULT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL COMMENT 'Routes -> id',
  `product_id` int(11) DEFAULT NULL COMMENT 'WooCommerce product id',
  `variation_id` int(11) DEFAULT NULL COMMENT 'WooCommerce product variation id',
  `type` tinyint(1) DEFAULT 2 COMMENT '1=Free, 2=Paid, 3=Lifetime',
  `name` varchar(99) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price_monthly` float DEFAULT NULL,
  `price_annually` float DEFAULT NULL,
  `ltd_price` float DEFAULT NULL,
  `ltd_price_date` date DEFAULT NULL,
  `limit_subs` smallint(6) DEFAULT 0,
  `limit_folders` smallint(6) DEFAULT 0,
  `limit_tags` smallint(6) DEFAULT 0,
  `limit_contacts` smallint(6) DEFAULT 0,
  `limit_pmethods` smallint(6) DEFAULT 0 COMMENT 'payment methods',
  `limit_alert_profiles` smallint(6) DEFAULT 0 COMMENT 'Alert profiles',
  `limit_webhooks` smallint(6) DEFAULT 0 COMMENT 'Webhooks',
  `limit_teams` smallint(6) DEFAULT 0 COMMENT 'Team accounts',
  `limit_storage` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Storage in bytes',
  `is_default` tinyint(1) DEFAULT 0 COMMENT '0=No, 1=Yes',
  `is_upgradable` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=No, 1=Yes',
  `trial_days` int(11) DEFAULT 0,
  `number_of_users` tinyint(1) NOT NULL DEFAULT 0,
  `currency` varchar(5) DEFAULT NULL,
  `sort` int(11) DEFAULT 1,
  `status` tinyint(1) DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `role_id`, `product_id`, `variation_id`, `type`, `name`, `description`, `price_monthly`, `price_annually`, `ltd_price`, `ltd_price_date`, `limit_subs`, `limit_folders`, `limit_tags`, `limit_contacts`, `limit_pmethods`, `limit_alert_profiles`, `limit_webhooks`, `limit_teams`, `limit_storage`, `is_default`, `is_upgradable`, `trial_days`, `number_of_users`, `currency`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 1, 'Free', 'Free', 0, 0, NULL, NULL, 20, 3, 3, 0, 3, 3, 0, 0, 0, 1, 1, 999, 1, 'USD', 1, 1, '2020-07-28 18:25:39', '2020-07-28 18:25:39'),
(2, 1, 3472, 3474, 2, 'Pro', 'Pro Recurring', 0, 39, 0, NULL, 250, 10, 10, 3, 5, 5, 10, 0, 262144000, 0, 1, 0, 1, 'USD', 5, 1, '2022-09-28 12:52:39', '2022-09-28 12:52:39'),
(3, 1, 1295, NULL, 3, 'Pro Legacy LTD', 'Pro-legacy', 0, 0, 49, NULL, 9999, 50, 200, 3, 10, 10, 10, 0, 1073741824, 0, 1, 30, 1, 'USD', 3, 1, '2021-07-31 13:24:26', '2021-07-31 13:24:26'),
(4, 1, 3472, 3476, 2, 'Teams', 'Teams Recurring', 0, 129, 0, NULL, 9999, 50, 50, 3, 20, 20, 10, 5, 1073741824, 0, 0, 0, 1, 'USD', 7, 1, '2022-09-28 12:52:39', '2022-09-28 12:52:39'),
(5, 1, 5333, 5340, 3, 'Pro LTD', 'Pro LTD', 0, 0, 49, NULL, 250, 10, 10, 3, 5, 5, 10, 0, 262144000, 0, 1, 0, 1, 'USD', 5, 1, '2022-09-28 12:52:39', '2022-09-28 12:52:39'),
(6, 1, 5333, 5341, 3, 'Pro Plus LTD', 'Pro+ LTD', 0, 0, 98, NULL, 9999, 50, 200, 3, 20, 20, 10, 0, 1073741824, 0, 1, 0, 1, 'USD', 6, 1, '2022-09-28 12:52:39', '2022-09-28 12:52:39'),
(7, 1, 5333, 5342, 3, 'Teams LTD', 'Teams LTD', 0, 0, 147, NULL, 9999, 50, 50, 3, 20, 20, 10, 5, 1073741824, 0, 0, 0, 1, 'USD', 7, 1, '2022-09-28 12:52:39', '2022-09-28 12:52:39'),
(8, 1, 3472, 3475, 2, 'Pro Plus', 'Pro+ Recurring', 0, 69, 0, NULL, 9999, 50, 200, 3, 20, 20, 10, 0, 1073741824, 0, 1, 0, 1, 'USD', 6, 1, '2022-09-28 12:52:39', '2022-09-28 12:52:39');

-- --------------------------------------------------------

--
-- Table structure for table `plan_coupons`
--

CREATE TABLE `plan_coupons` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `coupon` varchar(30) NOT NULL COMMENT 'Coupon code',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 2=Claimed',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plan_coupons`
--

INSERT INTO `plan_coupons` (`id`, `user_id`, `coupon`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'apsm-4bco5benu5tcu', 1, '2022-12-08 10:23:04', NULL),
(2, NULL, 'apsm-44mcj3bgarai6', 1, '2022-12-08 10:23:04', NULL),
(3, 690, 'apsm-1cma8ri7fv4oc', 2, '2022-12-08 10:23:04', '2023-10-27 19:04:13'),
(4, NULL, 'apsm-6ei8nhhjbcbg8', 1, '2022-12-08 10:23:04', NULL),
(5, NULL, 'apsm-6rph2tqlk2trd', 1, '2022-12-08 10:23:04', NULL),
(6, NULL, 'apsm-vmmj6ylyck6mz', 1, '2022-12-08 10:23:04', NULL),
(7, NULL, 'apsm-olgp3fb9zzff9', 1, '2022-12-08 10:23:04', NULL),
(8, NULL, 'apsm-1rjvxb8z31jcv', 1, '2022-12-08 10:23:04', NULL),
(9, NULL, 'apsm-lh298hcxm674c', 1, '2022-12-08 10:23:04', NULL),
(10, NULL, 'apsm-chd755o6xaa7i', 1, '2022-12-08 10:23:04', NULL),
(11, NULL, 'apsm-25g7by6ykg93e', 1, '2022-12-08 10:23:04', NULL),
(12, NULL, 'apsm-unwl7zdvhwxtv', 1, '2022-12-08 10:23:04', NULL),
(13, NULL, 'apsm-2pk2vxcegjoh4', 1, '2022-12-08 10:23:04', NULL),
(14, NULL, 'apsm-gb71h9xhj883i', 1, '2022-12-08 10:23:04', NULL),
(15, NULL, 'apsm-n7o41x2z7vs9e', 1, '2022-12-08 10:23:04', NULL),
(16, NULL, 'apsm-o52i3bf3u7o55', 1, '2022-12-08 10:23:04', NULL),
(17, NULL, 'apsm-3bviijqi1uvkw', 1, '2022-12-08 10:23:04', NULL),
(18, NULL, 'apsm-1dsjuyo4bwc79', 1, '2022-12-08 10:23:04', NULL),
(19, NULL, 'apsm-jwj7vm2q7y9rt', 1, '2022-12-08 10:23:04', NULL),
(20, NULL, 'apsm-biczele3fgkxu', 1, '2022-12-08 10:23:04', NULL),
(21, NULL, 'apsm-1xpknubo7omta', 1, '2022-12-08 10:23:04', NULL),
(22, NULL, 'apsm-5fxan9ir2s4vg', 1, '2022-12-08 10:23:04', NULL),
(23, NULL, 'apsm-cexphsd7675xi', 1, '2022-12-08 10:23:04', NULL),
(24, NULL, 'apsm-pbsn1gec9bijd', 1, '2022-12-08 10:23:04', NULL),
(25, NULL, 'apsm-d6irswhz3g5jx', 1, '2022-12-08 10:23:04', NULL),
(26, NULL, 'apsm-fmu9444aj1dd2', 1, '2022-12-08 10:23:04', NULL),
(27, NULL, 'apsm-el5ye4eng3en4', 1, '2022-12-08 10:23:04', NULL),
(28, NULL, 'apsm-emxw1fzndchu4', 1, '2022-12-08 10:23:04', NULL),
(29, NULL, 'apsm-k854ltt2uerj9', 1, '2022-12-08 10:23:04', NULL),
(30, NULL, 'apsm-p5cfuwjvgs1a8', 1, '2022-12-08 10:23:04', NULL),
(31, NULL, 'apsm-kkdj3nim4ij5q', 1, '2022-12-08 10:23:04', NULL),
(32, NULL, 'apsm-ijiw7wi4bath8', 1, '2022-12-08 10:23:04', NULL),
(33, NULL, 'apsm-q7porcfz7su2e', 1, '2022-12-08 10:23:04', NULL),
(34, NULL, 'apsm-zzlh2lp1nl3sv', 1, '2022-12-08 10:23:04', NULL),
(35, NULL, 'apsm-6gbwgusefroe5', 1, '2022-12-08 10:23:04', NULL),
(36, NULL, 'apsm-ca7epof5ymofr', 1, '2022-12-08 10:23:04', NULL),
(37, NULL, 'apsm-vinarc5a82s5s', 1, '2022-12-08 10:23:04', NULL),
(38, NULL, 'apsm-dqmf4vpnb22r6', 1, '2022-12-08 10:23:04', NULL),
(39, NULL, 'apsm-yhazedug194ha', 1, '2022-12-08 10:23:04', NULL),
(40, NULL, 'apsm-7kt8eypizw2ec', 1, '2022-12-08 10:23:04', NULL),
(41, NULL, 'apsm-2lc2diqng8xpu', 1, '2022-12-08 10:23:04', NULL),
(42, NULL, 'apsm-i9kjisqpdoerz', 1, '2022-12-08 10:23:04', NULL),
(43, NULL, 'apsm-hehqchmwi96of', 1, '2022-12-08 10:23:04', NULL),
(44, NULL, 'apsm-z6fja48fgi49k', 1, '2022-12-08 10:23:04', NULL),
(45, NULL, 'apsm-do7hp96bzhz49', 1, '2022-12-08 10:23:04', NULL),
(46, NULL, 'apsm-fkpuc369xgrdt', 1, '2022-12-08 10:23:04', NULL),
(47, NULL, 'apsm-w2vfapplg1ypu', 1, '2022-12-08 10:23:04', NULL),
(48, NULL, 'apsm-684t387yyywh6', 1, '2022-12-08 10:23:04', NULL),
(49, NULL, 'apsm-futeixnxlhwc7', 1, '2022-12-08 10:23:04', NULL),
(50, NULL, 'apsm-73j9yx3z4lyr9', 1, '2022-12-08 10:23:04', NULL),
(51, NULL, 'apsm-zncrdfgrsv1sx', 1, '2022-12-08 10:23:04', NULL),
(52, NULL, 'apsm-yaxtdqwl53lpr', 1, '2022-12-08 10:23:04', NULL),
(53, NULL, 'apsm-3g2vacngij544', 1, '2022-12-08 10:23:04', NULL),
(54, NULL, 'apsm-hkcobfsiibus9', 1, '2022-12-08 10:23:04', NULL),
(55, NULL, 'apsm-5qxrmhbzkhvu2', 1, '2022-12-08 10:23:04', NULL),
(56, NULL, 'apsm-42yeyojp5fzyv', 1, '2022-12-08 10:23:04', NULL),
(57, NULL, 'apsm-ge1774b3qdz5f', 1, '2022-12-08 10:23:04', NULL),
(58, NULL, 'apsm-8a99zwywfjl6z', 1, '2022-12-08 10:23:04', NULL),
(59, NULL, 'apsm-2kvcojzotls9u', 1, '2022-12-08 10:23:04', NULL),
(60, NULL, 'apsm-wqnuuztue2jzv', 1, '2022-12-08 10:23:04', NULL),
(61, NULL, 'apsm-fd7w71vtcfe5n', 1, '2022-12-08 10:23:04', NULL),
(62, NULL, 'apsm-so1hw5o66gxzv', 1, '2022-12-08 10:23:04', NULL),
(63, NULL, 'apsm-92n477yert73b', 1, '2022-12-08 10:23:04', NULL),
(64, NULL, 'apsm-jsxfdyyqvino5', 1, '2022-12-08 10:23:04', NULL),
(65, NULL, 'apsm-krcczzul4kug5', 1, '2022-12-08 10:23:04', NULL),
(66, NULL, 'apsm-q6hto5jd9pf43', 1, '2022-12-08 10:23:04', NULL),
(67, NULL, 'apsm-p95939aiwrka9', 1, '2022-12-08 10:23:04', NULL),
(68, NULL, 'apsm-w3hy3lwya64el', 1, '2022-12-08 10:23:04', NULL),
(69, NULL, 'apsm-wavcuei6tzeon', 1, '2022-12-08 10:23:04', NULL),
(70, NULL, 'apsm-8ge9db7agv5fn', 1, '2022-12-08 10:23:04', NULL),
(71, NULL, 'apsm-ekc77yyf2k9p2', 1, '2022-12-08 10:23:04', NULL),
(72, NULL, 'apsm-w26msfzqrbu18', 1, '2022-12-08 10:23:04', NULL),
(73, NULL, 'apsm-l83zf4ba8jqbf', 1, '2022-12-08 10:23:04', NULL),
(74, NULL, 'apsm-xheo63jf6n49d', 1, '2022-12-08 10:23:04', NULL),
(75, NULL, 'apsm-ltl828wd2a1sz', 1, '2022-12-08 10:23:04', NULL),
(76, NULL, 'apsm-f9mbtf8p686x8', 1, '2022-12-08 10:23:04', NULL),
(77, NULL, 'apsm-f1tv6se7w38sf', 1, '2022-12-08 10:23:04', NULL),
(78, NULL, 'apsm-jj7i1xksmkxzy', 1, '2022-12-08 10:23:04', NULL),
(79, NULL, 'apsm-l2ab54xvu85lw', 1, '2022-12-08 10:23:04', NULL),
(80, NULL, 'apsm-cypmbqi2bljmi', 1, '2022-12-08 10:23:04', NULL),
(81, NULL, 'apsm-orvitfgv2qkpa', 1, '2022-12-08 10:23:04', NULL),
(82, NULL, 'apsm-t7tdr4cwddufx', 1, '2022-12-08 10:23:04', NULL),
(83, NULL, 'apsm-vjxa5enajmj25', 1, '2022-12-08 10:23:04', NULL),
(84, NULL, 'apsm-1tovpfr8bxuvk', 1, '2022-12-08 10:23:04', NULL),
(85, NULL, 'apsm-rlsrlmmxrjt9v', 1, '2022-12-08 10:23:04', NULL),
(86, NULL, 'apsm-8xpqbzn5clg8a', 1, '2022-12-08 10:23:04', NULL),
(87, NULL, 'apsm-zms6kzebx1vvy', 1, '2022-12-08 10:23:04', NULL),
(88, NULL, 'apsm-pwmnbr1ylpvmm', 1, '2022-12-08 10:23:04', NULL),
(89, NULL, 'apsm-rc8ecafp646dg', 1, '2022-12-08 10:23:04', NULL),
(90, NULL, 'apsm-33zhwodgxa357', 1, '2022-12-08 10:23:04', NULL),
(91, NULL, 'apsm-t2z46448ilsmo', 1, '2022-12-08 10:23:04', NULL),
(92, NULL, 'apsm-qn4h8p6xkkkdm', 1, '2022-12-08 10:23:04', NULL),
(93, NULL, 'apsm-26fscykxlgcud', 1, '2022-12-08 10:23:04', NULL),
(94, NULL, 'apsm-nn3cic91r5ngc', 1, '2022-12-08 10:23:04', NULL),
(95, NULL, 'apsm-tl7usglqqzh8t', 1, '2022-12-08 10:23:04', NULL),
(96, NULL, 'apsm-svcay2dmoy98e', 1, '2022-12-08 10:23:04', NULL),
(97, NULL, 'apsm-76ep61qzfru55', 1, '2022-12-08 10:23:04', NULL),
(98, NULL, 'apsm-mbws1ue8arxc8', 1, '2022-12-08 10:23:04', NULL),
(99, NULL, 'apsm-jlknxbr6em6x5', 1, '2022-12-08 10:23:04', NULL),
(100, NULL, 'apsm-w5o6o2tkjcepc', 1, '2022-12-08 10:23:04', NULL),
(101, NULL, 'apsm-o4lii8mui6ihr', 1, '2022-12-08 10:23:04', NULL),
(102, NULL, 'apsm-83uz9zydyvsjt', 1, '2022-12-08 10:23:04', NULL),
(103, NULL, 'apsm-vdgmgev1b7ogq', 1, '2022-12-08 10:23:04', NULL),
(104, NULL, 'apsm-w3e4hyc7dsdhk', 1, '2022-12-08 10:23:04', NULL),
(105, NULL, 'apsm-vagen98up4jxn', 1, '2022-12-08 10:23:04', NULL),
(106, NULL, 'apsm-k9dk4gjmaesw4', 1, '2022-12-08 10:23:04', NULL),
(107, NULL, 'apsm-32sno58g3c141', 1, '2022-12-08 10:23:04', NULL),
(108, NULL, 'apsm-np15k5e9yjinx', 1, '2022-12-08 10:23:04', NULL),
(109, NULL, 'apsm-y2inuupt2rzxe', 1, '2022-12-08 10:23:04', NULL),
(110, NULL, 'apsm-dp8mte99oyxjm', 1, '2022-12-08 10:23:04', NULL),
(111, NULL, 'apsm-3tdn4na8mvoy8', 1, '2022-12-08 10:23:04', NULL),
(112, NULL, 'apsm-5f598bvt754y8', 1, '2022-12-08 10:23:04', NULL),
(113, NULL, 'apsm-ws46hqbcsc2ju', 1, '2022-12-08 10:23:04', NULL),
(114, NULL, 'apsm-3vmczue9rml8o', 1, '2022-12-08 10:23:04', NULL),
(115, NULL, 'apsm-3dc6wgb8xenbr', 1, '2022-12-08 10:23:04', NULL),
(116, 659, 'apsm-cnauwwkur8bxq', 2, '2022-12-08 10:23:04', '2023-05-21 11:16:07'),
(117, 659, 'apsm-chjg4znmgs7h5', 2, '2022-12-08 10:23:04', '2023-05-18 14:05:10'),
(118, 658, 'apsm-4e6uqhjzl82vj', 2, '2022-12-08 10:23:04', '2023-05-18 13:37:51'),
(119, 658, 'apsm-48fgtb62hxnnf', 2, '2022-12-08 10:23:04', '2023-05-18 13:38:15'),
(120, 657, 'apsm-383e2g8bf9c5w', 2, '2022-12-08 10:23:04', '2023-05-18 00:48:37'),
(121, 656, 'apsm-jx7e4eoqg9sgp', 2, '2022-12-08 10:23:04', '2023-05-17 18:20:33'),
(122, 656, 'apsm-4jz8udagvifkn', 2, '2022-12-08 10:23:04', '2023-05-17 18:21:01'),
(123, 655, 'apsm-3cjylaxo57j8v', 2, '2022-12-08 10:23:04', '2023-05-17 17:14:54'),
(124, 660, 'apsm-8ywrwbbw5ze12', 2, '2022-12-08 10:23:04', '2023-05-19 00:59:55'),
(125, 660, 'apsm-ppfg4te1nfeyp', 2, '2022-12-08 10:23:04', '2023-05-19 01:00:30'),
(126, NULL, 'apsm-lp2fhxx5ey23z', 1, '2022-12-08 10:23:04', NULL),
(127, 648, 'apsm-i2vxs7pvj9fbo', 2, '2022-12-08 10:23:04', '2023-05-11 20:13:57'),
(128, 643, 'apsm-lybio2o719imk', 2, '2022-12-08 10:23:04', '2023-05-09 09:08:41'),
(129, 647, 'apsm-78ctch4ts977z', 2, '2022-12-08 10:23:04', '2023-05-11 09:43:19'),
(130, NULL, 'apsm-wupmciicm4r8e', 1, '2022-12-08 10:23:04', NULL),
(131, 633, 'apsm-nuta6g5a1xx35', 2, '2022-12-08 10:23:04', '2023-04-22 02:07:37'),
(132, 633, 'apsm-92fyt5cxcbefk', 2, '2022-12-08 10:23:04', '2023-04-22 02:06:34'),
(133, 625, 'apsm-43f5u4d3jf2ef', 2, '2022-12-08 10:23:04', '2023-04-18 15:35:17'),
(134, 620, 'apsm-m4da8qditfch3', 2, '2022-12-08 10:23:04', '2023-04-06 21:57:52'),
(135, 661, 'apsm-cjgm7wyruapzf', 2, '2022-12-08 10:23:04', '2023-05-24 14:58:28'),
(136, 661, 'apsm-l9g3uchm3vn3r', 2, '2022-12-08 10:23:04', '2023-05-24 14:58:46'),
(137, 661, 'apsm-6kszw5fenfx3c', 2, '2022-12-08 10:23:04', '2023-05-24 14:59:03'),
(138, 608, 'apsm-168jx2uglv3sk', 2, '2022-12-08 10:23:04', '2023-03-25 10:02:30'),
(139, 608, 'apsm-65rksn9q5ymxf', 2, '2022-12-08 10:23:04', '2023-03-25 10:02:10'),
(140, 608, 'apsm-3hpxe4vho42z4', 2, '2022-12-08 10:23:04', '2023-03-25 10:02:20'),
(141, 651, 'apsm-tj42dd4qm2v8z', 2, '2022-12-08 10:23:04', '2023-05-14 02:13:43'),
(142, 605, 'apsm-j3wsah9e7zuzh', 2, '2022-12-08 10:23:04', '2023-03-26 02:13:18'),
(143, 605, 'apsm-twllzbx99l576', 2, '2022-12-08 10:23:04', '2023-03-26 02:13:51'),
(144, NULL, 'apsm-snmmwgwmow7dp', 1, '2022-12-08 10:23:04', NULL),
(145, 590, 'apsm-yicjpnwahesf5', 2, '2022-12-08 10:23:04', '2023-02-24 08:36:53'),
(146, 583, 'apsm-pa9b1zcbcfc6t', 2, '2022-12-08 10:23:04', '2023-02-10 02:27:32'),
(147, 565, 'apsm-z9npgfog4nkth', 2, '2022-12-08 10:23:04', '2023-01-28 22:02:48'),
(148, 565, 'apsm-yenwrmaedocun', 2, '2022-12-08 10:23:04', '2023-01-28 22:03:23'),
(149, 564, 'apsm-3x7lsfwkk53np', 2, '2022-12-08 10:23:04', '2023-01-28 01:41:48'),
(150, 555, 'apsm-wjums3g9mujau', 2, '2022-12-08 10:23:04', '2023-01-19 06:55:30'),
(151, 555, 'apsm-2n24kamhvom1t', 2, '2022-12-08 10:23:04', '2023-01-19 06:55:09'),
(152, 536, 'apsm-f6x2ypplw5vm2', 2, '2022-12-08 10:23:04', '2023-01-16 22:51:53'),
(153, 536, 'apsm-v7pst76535ebn', 2, '2022-12-08 10:23:04', '2023-01-16 22:45:24'),
(154, 536, 'apsm-2ff3yp8njsi8b', 2, '2022-12-08 10:23:04', '2023-01-16 22:52:23'),
(155, 551, 'apsm-le9iirs1a9a2h', 2, '2022-12-08 10:23:04', '2023-01-15 15:25:22'),
(156, 551, 'apsm-atcra79qbivy1', 2, '2022-12-08 10:23:04', '2023-01-15 14:39:21'),
(157, 545, 'apsm-mt1rxux1rorse', 2, '2022-12-08 10:23:04', '2023-01-12 06:19:12'),
(158, 637, 'apsm-975qm9yehjuek', 2, '2022-12-08 10:23:04', '2023-04-29 13:46:16'),
(159, 637, 'apsm-ki1qu4x5nxvop', 2, '2022-12-08 10:23:04', '2023-04-29 13:31:31'),
(160, 637, 'apsm-br6r6nzyg6rel', 2, '2022-12-08 10:23:04', '2023-04-29 13:45:01'),
(161, 545, 'apsm-iltwbpcxm3rfx', 2, '2022-12-08 10:23:04', '2023-01-11 15:35:03'),
(162, 545, 'apsm-cbjmx16wdhgfu', 2, '2022-12-08 10:23:04', '2023-01-11 15:27:31'),
(163, 597, 'apsm-wpik6ztplx4zj', 2, '2022-12-08 10:23:04', '2023-03-01 02:18:13'),
(164, 597, 'apsm-irixvxypto8nb', 2, '2022-12-08 10:23:04', '2023-03-01 02:18:04'),
(165, 597, 'apsm-cufligzsv22xr', 2, '2022-12-08 10:23:04', '2023-03-01 02:17:57'),
(166, NULL, 'apsm-va7n6n3znjbuh', 1, '2022-12-08 10:23:04', NULL),
(167, 534, 'apsm-9kgu4oz5nqfgb', 2, '2022-12-08 10:23:04', '2022-12-29 11:53:24'),
(168, 534, 'apsm-42845wlfxw6zu', 2, '2022-12-08 10:23:04', '2022-12-29 11:53:33'),
(169, 534, 'apsm-k8wcmqv5cig5r', 2, '2022-12-08 10:23:04', '2022-12-29 11:53:45'),
(170, 559, 'apsm-n9724om97hoeo', 2, '2022-12-08 10:23:04', '2023-01-24 03:40:44'),
(171, 559, 'apsm-7ege8uayhdjc1', 2, '2022-12-08 10:23:04', '2023-01-24 03:41:29'),
(172, 533, 'apsm-mx83eawcffnt5', 2, '2022-12-08 10:23:04', '2022-12-25 22:18:36'),
(173, NULL, 'apsm-npytzglsydypp', 1, '2022-12-08 10:23:04', NULL),
(174, 528, 'apsm-spb4z91cj618w', 2, '2022-12-08 10:23:04', '2022-12-22 11:41:55'),
(175, 528, 'apsm-s6nsr3b574ouk', 2, '2022-12-08 10:23:04', '2022-12-22 11:41:37'),
(176, 528, 'apsm-r9zppxtyzbbna', 2, '2022-12-08 10:23:04', '2022-12-22 11:41:46'),
(177, NULL, 'apsm-rhcyglcm64jbx', 1, '2022-12-08 10:23:04', NULL),
(178, 315, 'apsm-ffc3zd3elolna', 2, '2022-12-08 10:23:04', '2022-12-21 23:48:15'),
(179, NULL, 'apsm-s38jy9tq2m959', 1, '2022-12-08 10:23:04', NULL),
(180, 517, 'apsm-hvwkvip5xad9n', 2, '2022-12-08 10:23:04', '2022-12-21 16:09:30'),
(181, 47, 'apsm-3nqudu3mlala1', 2, '2022-12-08 10:23:04', '2022-12-21 13:37:32'),
(182, 47, 'apsm-ppui6fubk55vp', 2, '2022-12-08 10:23:04', '2022-12-21 13:37:14'),
(183, 524, 'apsm-t74yuqhe5gmv4', 2, '2022-12-08 10:23:04', '2022-12-19 23:37:01'),
(184, 582, 'apsm-oxlhmra2iepyr', 2, '2022-12-08 10:23:04', '2023-02-09 23:24:32'),
(185, 106, 'apsm-wg2gp7xdt4y5v', 2, '2022-12-08 10:23:04', '2023-02-11 16:26:18'),
(186, 483, 'apsm-y4hlam44qulvg', 2, '2022-12-08 10:23:04', '2022-12-15 18:52:29'),
(187, 483, 'apsm-3hpccvk7ui6kc', 2, '2022-12-08 10:23:04', '2022-12-15 18:52:45'),
(188, NULL, 'apsm-cij8tdygu4jr5', 1, '2022-12-08 10:23:04', NULL),
(189, NULL, 'apsm-ytz27v78gwu3c', 1, '2022-12-08 10:23:04', NULL),
(190, NULL, 'apsm-o56ippws2d5nb', 2, '2022-12-08 10:23:04', NULL),
(191, NULL, 'apsm-7fvqnnqi1hcv8', 2, '2022-12-08 10:23:04', NULL),
(192, 483, 'apsm-hn4hbm8zhejfv', 2, '2022-12-08 10:23:04', '2022-12-15 18:02:37'),
(193, 543, 'apsm-tlka7xg2ajvrv', 2, '2022-12-08 10:23:04', '2023-01-06 18:19:48'),
(194, 543, 'apsm-91k7rh1rpiwol', 2, '2022-12-08 10:23:04', '2023-01-06 18:19:36'),
(195, 168, 'apsm-kqse52p35lzyj', 2, '2022-12-08 10:23:04', '2022-12-13 14:13:04'),
(196, NULL, 'apsm-52k31jap7ny2k', 1, '2022-12-08 10:23:04', NULL),
(197, NULL, 'apsm-accygjl5aslei', 1, '2022-12-08 10:23:04', NULL),
(198, NULL, 'apsm-jm9pq2ywrssxb', 1, '2022-12-08 10:23:04', NULL),
(199, NULL, 'apsm-4oc8po4igu2mx', 1, '2022-12-08 10:23:04', NULL),
(200, NULL, 'apsm-pxk9rcjkmrimq', 1, '2022-12-08 10:23:04', NULL),
(201, NULL, 'apsm-oxlhmra2iepyr', 1, '2022-12-08 10:23:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL COMMENT 'product_categories -> id',
  `product_name` varchar(50) NOT NULL,
  `brandname` varchar(50) DEFAULT NULL,
  `product_type` tinyint(1) DEFAULT NULL COMMENT '1-SaaS\r\n2-Wordpress\r\n3-Desktop App\r\n4-Cloud Service\r\n5-Data Storage\r\n6-Online Service\r\n7-Domain Related\r\n8-PHP Script\r\n9-Open Source\r\n10-Graphic Assets\r\n11-Mobile App\r\n12-Others',
  `description` varchar(150) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `rating` tinyint(1) DEFAULT NULL COMMENT '1-10 ratings',
  `pop_factor` int(11) DEFAULT NULL COMMENT 'Popularity factor',
  `url` varchar(100) DEFAULT NULL,
  `url_app` varchar(100) DEFAULT NULL,
  `app_url` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL COMMENT 'Picture path',
  `favicon` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active, 2=User submitted',
  `sub_ltd` tinyint(1) DEFAULT NULL COMMENT '0=Subscription, 1=Lifetime',
  `launch_year` varchar(4) DEFAULT NULL,
  `sub_platform` int(11) DEFAULT NULL COMMENT 'product_platforms -> id',
  `pricing_type` tinyint(1) DEFAULT NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue',
  `currency_code` varchar(3) DEFAULT NULL,
  `price1_name` varchar(20) DEFAULT NULL,
  `price1_value` decimal(10,2) DEFAULT NULL,
  `price2_name` varchar(20) DEFAULT NULL,
  `price2_value` decimal(10,2) DEFAULT NULL,
  `price3_name` varchar(20) DEFAULT NULL,
  `price3_value` decimal(10,2) DEFAULT NULL,
  `refund_days` smallint(6) DEFAULT NULL,
  `billing_frequency` tinyint(1) DEFAULT NULL,
  `billing_cycle` tinyint(1) DEFAULT NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly',
  `ltdval_price` decimal(10,2) DEFAULT NULL,
  `ltdval_frequency` tinyint(1) DEFAULT NULL,
  `ltdval_cycle` tinyint(1) DEFAULT NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'User -> id',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_platforms`
--

CREATE TABLE `product_platforms` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `push_notification_registers`
--

CREATE TABLE `push_notification_registers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `service_provider` varchar(30) DEFAULT NULL COMMENT 'Gravitec.net',
  `auth` varchar(255) DEFAULT NULL COMMENT 'Authentication key',
  `browser` varchar(50) DEFAULT NULL,
  `endpoint` varchar(255) DEFAULT NULL COMMENT 'PushSubscription URL',
  `lang` varchar(5) DEFAULT NULL,
  `p256dh` varchar(255) DEFAULT NULL COMMENT 'Public key',
  `reg_id` varchar(255) DEFAULT NULL COMMENT 'Registration token',
  `subscription_spec` int(11) DEFAULT NULL,
  `subscription_strategy` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'User -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings_lang`
--

CREATE TABLE `settings_lang` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings_preferences`
--

CREATE TABLE `settings_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User -> id',
  `folder_id` int(11) DEFAULT 0 COMMENT 'Folder -> id',
  `brand_id` int(11) NOT NULL COMMENT 'Product -> id',
  `category_id` int(11) DEFAULT 1 COMMENT 'product_categories -> id',
  `alert_id` int(11) DEFAULT 1 COMMENT 'users_alert -> id',
  `platform_id` int(11) DEFAULT NULL COMMENT 'product_platforms -> id',
  `type` tinyint(1) DEFAULT NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue',
  `image` varchar(100) DEFAULT NULL COMMENT 'Image path',
  `favicon` varchar(100) DEFAULT NULL COMMENT 'Product -> favicon',
  `product_name` tinytext DEFAULT NULL,
  `brandname` varchar(30) DEFAULT NULL COMMENT 'Product -> brandname',
  `product_type` tinyint(1) DEFAULT NULL COMMENT 'Product -> product_type',
  `description` varchar(255) DEFAULT NULL,
  `price` double(10,2) NOT NULL DEFAULT 0.00,
  `price_type` varchar(20) DEFAULT NULL,
  `recurring` tinyint(1) DEFAULT 0 COMMENT '0=No, 1=Yes',
  `payment_date` date DEFAULT NULL,
  `next_payment_date` date DEFAULT NULL COMMENT 'Upcoming payment date',
  `payment_date_upd` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `contract_expiry` date DEFAULT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `pay_gateway_id` int(11) DEFAULT 0 COMMENT '0=Free, Payment Gateway -> id',
  `note` varchar(255) DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  `discount_voucher` varchar(20) DEFAULT NULL,
  `payment_mode` varchar(20) DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL COMMENT 'users_payment_methods -> id',
  `include_notes` tinyint(1) DEFAULT NULL COMMENT '0=No, 1=Yes',
  `alert_type` tinyint(1) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `support_details` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `billing_frequency` tinyint(1) DEFAULT NULL,
  `billing_cycle` tinyint(1) DEFAULT NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly',
  `billing_type` tinyint(1) DEFAULT 1 COMMENT '1=Calculate by days, 2=Calculate by date',
  `ltdval_price` decimal(10,2) DEFAULT NULL,
  `ltdval_frequency` tinyint(1) DEFAULT NULL,
  `ltdval_cycle` tinyint(1) DEFAULT NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly',
  `status` tinyint(1) DEFAULT 0 COMMENT '0=Draft, 1=Active, 2=Cancel, 3=Refund, 4=Expired, 5=Sold',
  `pricing_type` tinyint(1) DEFAULT NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime, 4=Revenue',
  `timezone` tinytext DEFAULT NULL,
  `currency_code` varchar(3) DEFAULT NULL COMMENT 'Product -> currency_code',
  `refund_days` smallint(6) DEFAULT NULL COMMENT 'Product -> refund_days',
  `refund_date` date DEFAULT NULL,
  `base_value` decimal(10,2) DEFAULT NULL COMMENT 'Base currency value',
  `base_currency` varchar(3) DEFAULT NULL COMMENT 'Base currency code',
  `rating` tinyint(1) DEFAULT 0 COMMENT '1-10 ratings',
  `sub_addon` tinyint(1) DEFAULT 0 COMMENT '1=Addon',
  `sub_id` int(11) DEFAULT NULL COMMENT 'subscriptions -> id',
  `product_avail` tinyint(4) DEFAULT 0 COMMENT '0=No, 1=Yes',
  `product_submission_id` int(11) DEFAULT 0 COMMENT '0=No, products -> id',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'User -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions_attachments`
--

CREATE TABLE `subscriptions_attachments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `subscription_id` int(11) DEFAULT NULL COMMENT 'subscriptions -> id',
  `file_name` tinytext DEFAULT NULL,
  `file_path` text DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions_history`
--

CREATE TABLE `subscriptions_history` (
  `id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User -> id',
  `price` double(10,2) NOT NULL DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `next_payment_date` date DEFAULT NULL COMMENT 'Upcoming payment date',
  `payment_date_upd` date DEFAULT NULL,
  `payment_mode` varchar(20) DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL COMMENT 'users_payment_methods -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions_tags`
--

CREATE TABLE `subscriptions_tags` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User -> id',
  `subscription_id` int(11) NOT NULL COMMENT 'Subscription -> id',
  `tag_id` int(11) NOT NULL COMMENT 'Tag -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_cart`
--

CREATE TABLE `subscription_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `subscription_id` int(11) DEFAULT NULL COMMENT 'subscriptions -> id',
  `product_id` int(11) DEFAULT NULL,
  `product_category_id` int(11) DEFAULT NULL,
  `product_platform_id` int(11) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `product_name` varchar(50) DEFAULT NULL,
  `product_logo` tinytext DEFAULT NULL,
  `sale_price` double DEFAULT NULL,
  `currency_code` varchar(5) DEFAULT NULL,
  `plan_name` varchar(50) DEFAULT NULL,
  `product_url` tinytext DEFAULT NULL,
  `sales_url` tinytext DEFAULT NULL,
  `notes` tinytext DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '0=Draft, 1=Active, 2=Sold',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'User -> id',
  `name` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_migrate_paths`
--

CREATE TABLE `tmp_migrate_paths` (
  `id` int(11) NOT NULL,
  `model` enum('File','Subscription','SubscriptionAttachment','SubscriptionHistory','LtdHistory','User') NOT NULL,
  `row_id` int(11) NOT NULL,
  `old_file_type` varchar(50) NOT NULL,
  `old_file_path` varchar(255) NOT NULL,
  `old_file_exists` tinyint(1) NOT NULL,
  `new_file_type` varchar(50) NOT NULL,
  `new_file_path` varchar(255) NOT NULL,
  `new_file_exists` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `type` tinytext NOT NULL,
  `token` tinytext NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active, 2=Used',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `used_by` int(11) DEFAULT NULL,
  `expire_at` datetime DEFAULT NULL,
  `table_name` tinytext DEFAULT NULL,
  `table_row_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 2 COMMENT 'Role -> id',
  `team_user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `wp_user_id` int(11) DEFAULT NULL COMMENT 'WordPress user id',
  `name` varchar(50) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `country` varchar(5) DEFAULT NULL COMMENT 'Country ISO code',
  `email` varchar(50) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL COMMENT 'Picture path',
  `remember_token` varchar(100) DEFAULT NULL,
  `marketplace_status` tinyint(1) DEFAULT 0 COMMENT '0=Inactive, 1=Active',
  `marketplace_token` varchar(100) DEFAULT NULL COMMENT 'Marketplace user page link',
  `paypal_api_username` tinytext DEFAULT NULL,
  `paypal_api_password` tinytext DEFAULT NULL,
  `paypal_api_secret` tinytext DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  `facebook_username` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0 COMMENT '0=System, User -> id',
  `updated_at` timestamp NULL DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL COMMENT 'Last reset datetime',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Inactive, 1=Active, 2=Disabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `team_user_id`, `wp_user_id`, `name`, `first_name`, `last_name`, `country`, `email`, `email_verified_at`, `password`, `description`, `image`, `remember_token`, `marketplace_status`, `marketplace_token`, `paypal_api_username`, `paypal_api_password`, `paypal_api_secret`, `company_name`, `facebook_username`, `phone`, `created_at`, `created_by`, `updated_at`, `reset_at`, `status`) VALUES
(1, 1, NULL, 4, 'Admin', 'Admin', NULL, 'US', 'admin@subshero.com', NULL, '$2y$10$8f/GTKkqDUkQ6TjsJXBOUeGFj6Z.CUNwHaIHp6UsRgF6RH03Gy3Mq', NULL, 'client/1/user/1/avatar.jpg', 'dxlDgyj6X05sUX5yc032RCXP3yRJRMBquxAxCnVCsy9R4wwO74FUIIi8J1nS', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-08-02 03:15:11', 0, '2022-10-13 13:19:40', NULL, 1),
(2, 2, NULL, NULL, 'Client test', 'Client', 'test', 'US', 'client@subshero.com', NULL, '$2y$10$2ynIY1X4frKpPNcGmboqM.Nhs9/LxlBDYcFsYN/sA6Tu5VyIlVtTG', NULL, 'client/1/user/2/avatar.jpg', 'arV4tcm1xhyYa8gfdg7yyxqrIFXCNBgUAidouAcqDrXqlHsOhfJ9SB5AD7WR', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-08-02 03:17:12', 0, '2022-10-13 13:19:40', NULL, 1),
(33, 2, NULL, 7, 'Arabinda Ghosh Ghosh', 'Arabinda Ghosh', 'Ghosh', 'US', 'arabinda@interstellarconsulting.com', '2021-09-24 04:35:08', '$2y$10$/oY61f1FDfs0Qy7db2G/nelwnRdDd5qznyOzTDCJ0nYvFVtI4CceS', NULL, 'client/1/user/33/avatar.jpg', 'WsuSruTTzr581ICy2MxHboDJLlurJ0lNCULs4vLhvYFI0y2idbhqHaMnsMDX', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-24 04:34:40', 0, '2021-12-17 16:22:06', NULL, 1),
(40, 2, NULL, 3, 'Rohit Sharma', 'Rohit', 'Sharma', 'DE', 'rohit@interstellarconsulting.com', '2021-11-26 14:10:09', '$2y$10$ZqQ2JLqaWQDE752DdkzsXundDwXH0mX2.5AkFzNGKPNZ8O1nBGl6.', NULL, 'client/1/user/40/avatar.jpg', 'MiE6lT7xLqtOdi9m8DaKegtwR3QvraHfwPKN5QGjVd2fqexuWq7fRpitICRN', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-07 03:24:02', 0, '2022-10-13 13:19:40', NULL, 1),
(41, 2, NULL, NULL, 'Saniya Gazala', 'Saniya', 'Gazala', 'IN', 'saniya@interstellarconsulting.com', '2021-10-07 08:38:51', '$2y$10$9OF70SoXYqh8FvUL9nH1weuSlXgb.Y/GorGN.NfKKRmQUZPxG6dOu', NULL, 'client/1/user/41/avatar.jpg', 'IPDqg5ly53hxpOm2ViIUF4vJHgzBp9LjwwjidVSBBd85EhNRGa7teP6dp7Uk', 1, 'vt4VApu7FDvmyQ3v', 'Saniya', 'Adf0uVC0y0gZz9fwCq1VjcxoEUk_taQsBLPdSXR4FoxMHrjLrA6J4p5zpQmGVrsa0Y7VBXfCqHn6-8sw', ': EOvblnJEA8_CqpTN8R1l-jTOtGEWpVq7DkYWerWkaENSSmakdL6tU9_wSuHj9Ud0lNunbuusl9ehDHcT', NULL, NULL, NULL, '2021-10-07 08:38:19', 0, '2023-03-06 05:23:18', NULL, 1),
(42, 2, NULL, 2, 'Sathwik Prabhu', 'Sathwik', 'Prabhu', 'IN', 'sathwik@interstellarconsulting.com', '2021-10-13 10:17:25', '$2y$10$yFxJouIixfhHT.vyd..lluVKiRn2m4DdvKyHKyWlHiB3MacHdgj2q', NULL, 'client/1/user/42/avatar.png', '24DYe3AICG8LIMhPEXomrHhF0giBVAhm0f0utI6o7lhCtHgI7Kvr38GWW1f5', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-03-27 04:33:24', NULL, 1),
(43, 2, NULL, NULL, 'Rohit Sharma', 'Rohit Sharma', NULL, 'US', 'info@interstellarconsulting.com', '2021-10-09 19:29:24', '$2y$10$EfH77w4bWtFC7Z9T99YUPeyr0y6K2OQIGPrP67lT0TnPNuaXkvzqO', NULL, 'client/1/user/43/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-09 19:28:46', 0, '2022-10-13 13:19:40', NULL, 1),
(44, 2, NULL, NULL, 'Ankush Chopra', 'Ankush', 'Chopra', 'IN', 'chopraankush@hotmail.com', '2021-10-31 06:27:58', '$2y$10$SqgE9qQeEbKDVY.2d78nLuas20G2fZK9KShcDwU7yo1vXf0hp8O7O', NULL, 'client/1/user/44/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-31 06:27:13', 0, '2022-10-13 13:19:40', NULL, 1),
(45, 2, NULL, NULL, 'Jijo Jose', 'Jijo Jose', NULL, 'US', 'jijo@aceboxmedia.com', '2021-11-01 07:06:37', '$2y$10$RP259g/D44zia4EWs7nl5OThsZ/qBGIy/A3x1Y1oDXSllI5c.TkNG', NULL, 'client/1/user/45/avatar.png', 'uGKDmtqRBY1yXNg7G5oNv8EzaB0rKBVh3MoG1xlBaMCRhhH85mKvUpGBaVTs', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-01 06:27:33', 0, '2022-10-13 13:19:40', NULL, 1),
(46, 2, NULL, NULL, 'Dote Othie', 'Dote', 'Othie', 'US', 'doted27391@ingfix.com', '2021-11-01 09:47:29', '$2y$10$7hCACAdJM8dWdbbrEctro.iF5tY3FW6NyFpH0iZkG5I0eDGRNrDJm', NULL, 'client/1/user/46/avatar.png', 'ahsWhLPNbbbpPD44At4vdfXE0WMmXC5uXaS0MYnmv7NAaF3WsBBAm39z4GgK', 1, 'hXtCXMhYmTLndIo7', 'dfgdf', 'dfgdf', 'fgdf', NULL, NULL, NULL, NULL, 0, '2022-12-23 15:28:10', NULL, 1),
(47, 2, NULL, NULL, 'Atley Joseph', 'Atley Joseph', NULL, 'US', 'xdesignsit@gmail.com', '2021-11-11 19:32:20', '$2y$10$whJ3mr.5ONSpy.GO2ddhhuLIW0PcmsBmoAUNxQdk7vX6e.4MIqXNi', NULL, 'client/1/user/47/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-11 19:31:59', 0, '2022-10-13 13:19:40', NULL, 1),
(48, 2, NULL, NULL, 'Duy Nguyen', 'Duy', 'Nguyen', 'US', 'duy@mightytools.co', '2021-11-18 16:31:17', '$2y$10$Sh7iCVjq7ljLV6LuKK0IfOFXYW7aymva24Pu7b8cNNQMptk9MzM2C', NULL, 'client/1/user/48/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-18 16:31:02', 0, '2022-10-13 13:19:40', NULL, 1),
(49, 2, NULL, NULL, 'Tayshiro', 'Tayshiro', NULL, 'US', 'tktrab@gmail.com', '2021-11-21 17:15:15', '$2y$10$mkgayt6/..7rUoY5DtJPmunyq0lovyHt4RpXHWDnWKqMV4MU5F4zW', NULL, 'client/1/user/49/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-21 17:05:28', 0, '2022-10-13 13:19:40', NULL, 1),
(50, 2, NULL, NULL, 'ken moo', 'ken moo', NULL, 'US', 'kenmootools@gmail.com', '2021-11-23 15:07:36', '$2y$10$LndStglk6cVcLMo7YDikvOwU.eNgA/e2BNHYReVBBn2OnaSIc/N5a', NULL, 'client/1/user/50/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-23 15:07:23', 0, '2022-10-13 13:19:40', NULL, 1),
(51, 2, NULL, 23, 'Sathwik Prabhu', 'Sathwik', 'Prabhu', NULL, 'sathvik@interstellarconsulting.com', NULL, '', NULL, 'client/1/user/51/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 0),
(52, 2, NULL, 24, 'Sathwik Prabhu', 'Sathwik', 'Prabhu', NULL, 'sathwikprabhu4470@gmail.com', '2021-11-25 19:47:05', '$2y$10$Ho6rowDa0G/szuQ6PqXpDOco3Kl85NKfrOQWkJqxfujFWWUCAlgce', NULL, 'client/1/user/52/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(53, 2, NULL, 25, 'Venkatesh B', 'Venkatesh', 'B', 'IN', 'venky@venkyb.com', '2021-11-26 12:03:36', '$2y$10$0q0dXoe7jUthmMi1a73RZO87EU9PJHx1nl4kQob2m7bk7dDHmCwS2', NULL, 'client/1/user/53/avatar.png', 'S2ydT6R8EE7ihW6bP28jfGMDEJHPmYHrLFHkWnhnbPG00ak13UY5lWoT6Ps7', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(54, 2, NULL, 26, 'Bjarne Eldhuset', 'Bjarne', 'Eldhuset', NULL, 'bjarne.eldhuset@gmail.com', '2021-11-26 12:34:17', '$2y$10$m8hVUPrXcMZfPLKe4SO4SOLwrWscWffkj4bd1IuU2Ypp.V7TwIY4e', NULL, 'client/1/user/54/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(55, 2, NULL, 27, 'Phuong Nguyen', 'Phuong', 'Nguyen', 'VN', 'nnbphuong@gmail.com', '2021-11-26 12:10:41', '$2y$10$Ch2K/JBH5.WNqQs4vW2NmeXo.mRURAEKbtM50HBxqRZ5RS93Uky8m', NULL, 'client/1/user/55/avatar.jpg', 'p5NfOmYjbNHPjBFNYeKZ4ro79CIrv5gcj4XPCxEIn2KleJRha2geHpuodrZA', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(56, 2, NULL, 28, 'Robert-Jan van de Moosdijk', 'Robert-Jan', 'van de Moosdijk', 'BE', 'rjvandemoosdijk@gmail.com', '2021-11-26 12:01:01', '$2y$10$f5rvwPeGDBSlEHQ8TfLgaubDxYHhNJJb8vxzgfH/1iDHMDHg2mFku', NULL, 'client/1/user/56/avatar.jpg', 'UUbKlXxmE867A6Sf4Hl3bp3D49JuGS7lrUjYHkNxQ2e1167ktQrYEdSnodg3', 0, NULL, NULL, NULL, NULL, 'JOA', NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(57, 2, NULL, NULL, 'Sulham Syahid', 'Sulham Syahid', NULL, 'US', 'hi@digiperth.com', NULL, '$2y$10$2.4VLzDvNqKlPjLni1E3kOMOklBsaO7lyN5rhYcd.qmqdpHtUHyXq', NULL, 'client/1/user/57/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 11:58:45', 0, '2022-10-13 13:19:40', NULL, 1),
(58, 2, NULL, 29, 'Neeraj Singh', 'Neeraj', 'Singh', NULL, 'neeraj@chhokars.co', '2021-11-26 17:07:05', '$2y$10$6YtNP3gXVjt9yH7WZXMcFuMa6H0uQAQkh/7p1EbbYcjPSYp.T2cPm', NULL, 'client/1/user/58/avatar.png', 'HEjyqrct1l5JdxXOEK1iMlO9FFOxWLk4it0wupvvBGIcCpJI6IG1UtDbpOif', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 07:55:29', NULL, 1),
(59, 2, NULL, 30, 'Ash S', 'Ash', 'S', NULL, 'skoopcapital@gmail.com', '2021-11-26 12:05:03', '$2y$10$J1MI88FfOC43usZOQ2ur3.9aINGnzoXhcVCYLjPs4zAHdpP99i7NO', NULL, 'client/1/user/59/avatar.png', 'O7AlZi3WNA0XXDCfQ7QNWuHRjbfN1LoYiiuEBlfkodtokZZk7VEDSZt148Dz', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(60, 2, NULL, NULL, 'Sulham Syahid', 'Sulham Syahid', NULL, 'US', 'syahidsulham@gmail.com', '2021-11-26 12:04:55', '$2y$10$zR6oJBPzyMJ2I.b8Mw3a8O3Km/nx0pq1AUXQqbpIpphjeX55v4TkC', NULL, 'client/1/user/60/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 12:04:14', 0, '2022-10-13 13:19:40', NULL, 1),
(61, 2, NULL, 31, 'Vijay Bharadwaj', 'Vijay', 'Bharadwaj', 'IN', 'vijay@vijaybharadwaj.com', '2021-11-26 12:12:56', '$2y$10$NpuvXIUIYIK4pr9S.E9pkOPBVL07Op13cmPtWa9LDe5CC.vrvUo3W', NULL, 'client/1/user/61/avatar.png', 'nGC9KiWXUkbTxHQB91htDLMIMMABVfLhVYxr762VQfenZH1YEXDf3QtFhU97', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(62, 2, NULL, 32, 'Sarang Gambhir', 'Sarang', 'Gambhir', NULL, 'moevebusiness@gmail.com', '2022-01-31 10:48:24', '$2y$10$N/AjVcdUSmOiCQ9DsmQtRuL3PSeUwgVHypH8Lulkry.ETO1HVegca', NULL, 'client/1/user/62/avatar.png', '18NhMpBq9D5thqFOXu6SDJOQTNL5HhO9nonig3zK4JcG9pTZK9foFpkhXg6N', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(63, 2, NULL, 33, 'rh HASSAN', 'rh', 'HASSAN', 'GB', 'healer412@gmail.com', '2021-11-26 13:44:14', '$2y$10$Cp3sjkzeBnlWHKoJJarwU.ehOPpSarGqazqKDD.EY/WgYa36p8tGO', NULL, 'client/1/user/63/avatar.png', 'bTiGf4A7WYDvf50DznBidv1GH7Rv5Bl9lhsyDMcDhmrBhJEH7XYOAXlH41kH', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(64, 2, NULL, 34, 'K A', 'K', 'A', NULL, 'subshero@onenonly.info', '2021-11-26 12:29:09', '$2y$10$qTGMpQ6C0DUoRKY6PYhiQOAlJdjEb.azU3E4aKqJVwF1NEG2/JLqO', NULL, 'client/1/user/64/avatar.png', 'Fm9A3w3HCEGzZKFZFz1mkGvNZVbqoAO9e8umXsIqz0lmBgCPuzyTytD09K2s', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(65, 2, NULL, 35, 'Kev Tan', 'Kev', 'Tan', 'MY', 'kevtan.me@gmail.com', '2021-11-26 12:21:25', '$2y$10$6q15qYs/UyfxacKiJU6Ut.Vu0.T8M4Oe.qH4MdDPMs9RFur7V4qQy', NULL, 'client/1/user/65/avatar.png', 'uwDadHw97d3B2yu2B3tGlcLmlTrTVL6ad561Grc4d3YRAbB5XfhBOOVuP9a2', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2024-08-26 12:27:38', NULL, 1),
(66, 2, NULL, 36, 'Sbs Trck', 'Sbs', 'Trck', 'CA', 'sbstrck@gmail.com', '2021-11-26 12:16:27', '$2y$10$U/ejV2aJNVnfHHvAUieqj.KUXw16b6.voRvBERSMfrf8Ew1zWACJK', NULL, 'client/1/user/66/avatar.png', 'k9ahEuQRq1TrCLr5aNEliUyB8wMGYMLQu7cjeEm4wQKeJLZkQd0jwPjG2vSg', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-02-20 20:53:06', NULL, 1),
(67, 2, NULL, 37, 'Ivan Cirlig', 'Ivan', 'Cirlig', 'MD', 'videoandesign@gmail.com', '2021-11-26 14:03:29', '$2y$10$FJJ1X1SD0DpuC8ajNEbUSufgy3K9HcKow01UkYMp8LvQkkRVjgso6', NULL, 'client/1/user/67/avatar.jpg', 'BfKkP4xzv1eO57yD8lgZEMjsmZcVWYIBCleQYUOlLBKOWls4CuJWP20M1eGZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 13:35:13', NULL, 1),
(68, 2, NULL, NULL, 'Mariusz Kurzyk', 'Mariusz Kurzyk', NULL, 'US', 'mariusz.kurzyk@gmail.com', '2021-11-26 12:18:49', '$2y$10$oz1WtltlGAw6NhNR1LPFRehiKlY7wYtV1YJRQ70JYAjhoWSGLzwSW', NULL, 'client/1/user/68/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 12:18:30', 0, '2022-10-13 13:19:40', NULL, 1),
(69, 2, NULL, 38, 'kai neo', 'kai', 'neo', NULL, 'nkx.neo@gmail.com', '2021-11-26 13:06:04', '$2y$10$Cs1GD0e20LV21MdVak0Efu6Hde/2yeHZeeBolYZPrbmkx5bAvlXKq', NULL, 'client/1/user/69/avatar.png', 'KPwGeJeeNUJlq0yimLM4cBNiMByvIZwL2tlXqVvnrLn1mwExfTWcM1OPfyxM', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(70, 2, NULL, 39, 'Dan Remon', 'Dan', 'Remon', NULL, 'thedanremon@gmail.com', '2021-11-26 15:25:14', '$2y$10$Aa.qSMjIJ6xMxKOdsRIhNOs2TNJo9NFF/Fzlr2K1tQe5G3KD5oaiW', NULL, 'client/1/user/70/avatar.png', '9uROxptVRYfbZwpBsT24MkLumbz2UT5Iz8EaTJEmIiK8qxhvAnvbuJF31FpG', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(71, 2, NULL, 41, 'Amit Parsotam', 'Amit', 'Parsotam', 'GB', 'hello@hittoku.com', '2021-12-28 11:12:38', '$2y$10$wvxUgA/Jm/UP3wRuYyM9a.AFA9GpedIbO9WtxvkOdPc0dz6Hz5G6i', NULL, 'client/1/user/71/avatar.png', 'nEoxwR13M2GRdIJvIL8zAektQKW90kannC3ocCNaePJqhV1rt9v9J3qwjzGw', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(72, 2, NULL, 40, 'abac', 'abac', NULL, 'US', 'abactech20@gmail.com', '2021-11-26 12:24:21', '$2y$10$M4/aHic9t/9UDbjaFAtkGuHULwkr3Ct1DO.0Fsxt6x2LwsxgFZEfK', NULL, 'client/1/user/72/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 12:24:11', 0, '2022-10-13 13:19:40', NULL, 1),
(73, 2, NULL, 42, 'Raju PP', 'Raju', 'PP', 'IN', 'raju@techpp.com', '2021-11-26 13:35:31', '$2y$10$0TljO4pE8hDfV1qYmOvsee0/LPEMK4PLRuRBeYtMSbglrcc92Jgjq', NULL, 'client/1/user/73/avatar.png', 'JxORqoL77jQ1goPi2ha0TvaAuoxfTK2ZxYyH1IDFPP7EDLmMpooP80tTlIhZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(74, 2, NULL, 43, 'Praveen Polavarapu', 'Praveen', 'Polavarapu', 'US', 'subshero@flares.top', '2021-11-26 12:40:15', '$2y$10$oD.6SmQDFO9pDVaaklj9WOV0ya3B9XHlrKACJpIFHW.JMKyy0qKLy', NULL, 'client/1/user/74/avatar.png', 'zBCxrjsV9XNVM7KJI9zw6ShTbreoRfnU0Q75udZjVdVXEpXez0DBEhHlc6Ih', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(75, 2, NULL, 44, 'Caio Oliveira', 'Caio', 'Oliveira', NULL, 'caiosa@gmail.com', '2021-11-26 12:34:23', '$2y$10$GnSKQn5XrIMt33jA5nUz8.e1PsHa4HUmN5pL9XFnF/rS9SK7ihZhS', NULL, 'client/1/user/75/avatar.png', 'X618Nt7fWuMdsLAjKQdraAoLNvGxp3oYxcuT8LvnFejATHkKMgObnATlXdZV', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(76, 2, NULL, 45, 'Sorin F', 'Sorin', 'F', NULL, 'login@gorilladigital.com', NULL, '$2y$10$X7KvMZuWIUxi3bop9Ycx.eiZbpAvFRogylytN1qllody1q17aKc2O', NULL, 'client/1/user/76/avatar.jpg', 'V081I3zF0ld6Y8WjtEeN0RMC2YMNK7ZxTjnapePUUKsj3DcKdEXfuBzDQTOZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(77, 2, NULL, 46, 'Thao Chau', 'Thao', 'Chau', 'VN', 'myblog.centre@gmail.com', '2021-11-26 13:34:57', '$2y$10$U2KU41eCod5Y4FGt1SKAKemCflv1p0Kzz5e4vS0Zn5BuIwQuqyaRy', NULL, 'client/1/user/77/avatar.png', 'BlKHQSOFOKjcDafkJY05tq730fMnvlpOfBSwPT1QTgcScWuenJw02wcs53Ml', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-17 15:03:03', NULL, 1),
(78, 2, NULL, 47, 'Ryan Dolan', 'Ryan', 'Dolan', NULL, 'digitaldiyninja@gmail.com', '2021-12-19 21:41:23', '$2y$10$9qzMLD5FAINrh0Q03GdbY.l9WZBeYGb2JiIYSL2VlFLRMyPNF3uBq', NULL, 'client/1/user/78/avatar.png', 'qooOpt7z2gSieL0OWK8AwWem3ZvQIbrHa9NSW9jKklkfMxqhRwo6Uq0o2AR1', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(79, 2, NULL, 48, 'Pravilz P', 'Pravilz', 'P', 'IN', 'Praviljithpv@gmail.com', '2021-11-26 12:44:32', '$2y$10$4rjV0Gr4cyzNkQIeVgvRB.1H0qhS8Qnw5Bqpj3WWLbtQzyNFjoooK', NULL, 'client/1/user/79/avatar.png', 'OXBJTK8F7DP0feugoyUstMgl0mK1gN29gcp4V5g0UkwEyT41QZemnM9a8P4s', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-15 16:30:27', NULL, 1),
(80, 2, NULL, 49, 'Sathwik Prabhu', 'Sathwik', 'Prabhu', NULL, 'solitaireseraph@gmail.com', '2021-11-30 11:55:27', '$2y$10$smcm3nXPqpClgX6Zydge3ePtSJ5qXZ6WTayLEpBlpTgotkW96/uuq', NULL, 'client/1/user/80/avatar.png', 'EFmJqROAHTLWkFXiPX4o7qycBAfa0QWmdYGOlZEVT5KnKT2ByvJ1cHgM9bTj', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(81, 2, NULL, 50, 'Fabrizio spanu', 'Fabrizio', 'spanu', 'IT', 'tech@digitalfastmind.com', '2021-11-27 09:50:33', '$2y$10$eXrCpADbR7GnbRQU1HXsj.QPqBOhbKXS.DAQ7XY20IYxES4xYiwXG', NULL, 'client/1/user/81/avatar.png', 'KqTOQLeRBQV36JERSUATf7KtUjlnvsOd498qTmqgSQShLAyy6zAg4DUAhnWO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(82, 2, NULL, 51, 'Kevin Saenz', 'Kevin', 'Saenz', 'CL', 'ksaenzmail@gmail.com', '2021-11-26 16:04:16', '$2y$10$f.MvPdX6KYazBZgOYgJv1unGsAQlmZbJQ5xucLFsRbaflcpcCyLxi', NULL, 'client/1/user/82/avatar.png', 'rU8ElMGzspB01KJMKBFh1RjOLfG2PjmnNWA9TZTjFNauaKJAYtvHFRhDtWra', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(83, 2, NULL, 52, 'Barum Jeffries', 'Barum', 'Jeffries', NULL, 'hello@reclearn.com', NULL, '$2y$10$G3nSR8syOQHHMb1soXGiXu/M6CtA4t5zs8qkCdFCk77jXKGDDf2pC', NULL, 'client/1/user/83/avatar.png', 'QZFOsmsJLJIe9RO3MP1RJqV88D7uNACWPwbBOvG506DxuuGBDuf2IXrzdIlB', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(84, 2, NULL, 53, 'Tom Fisher', 'Tom', 'Fisher', 'GB', 'tomfisher2017@gmail.com', '2021-11-26 12:54:53', '$2y$10$.I/8NtfaaIMPAKk/M5HLveAwTZPNyvwquSyQzbQjZ.sY.JqcUzjwm', NULL, 'client/1/user/84/avatar.png', 'yuoKs8rqY4pb9hShqLWsOjKYe6UHAbiYR4rUKGWVFfqHLHOfWjiBwOaziYae', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(85, 2, NULL, 54, 'Kumar Rahul', 'Kumar', 'Rahul', NULL, 'n6@outlook.in', NULL, '$2y$10$VDTqzrbiUqm5NpNO2JbdyuYzun2NKbl0hmhjXEHy6aJh57gEpvQ0S', NULL, 'client/1/user/85/avatar.png', 'GtG8NEiu6EPU19HjnCYXKCkDsWkBckp6c5wtHypH5DCEJ1Z2UY7AdEHFG5IC', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(86, 2, NULL, 55, 'Leo Schmahl', 'Leo', 'Schmahl', NULL, 'leo@leopage.de', '2021-11-26 13:10:09', '$2y$10$uJ2KBbW1CAIjbkF7QBtpm.ZGzSj51L.Jgt4fTBSVVV5yOgPGogn0C', NULL, 'client/1/user/86/avatar.png', '7lzgtWPGH8TajWVw2vvlqw2F2dIGRAhs6zS44e2TF3K3vGHdgvIEpb1aWwYI', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(87, 2, NULL, 56, 'Alex Lp', 'Alex', 'Lp', 'CR', 'alex.alp@gmail.com', '2021-11-26 15:06:10', '$2y$10$AipUsE5ZhMSUVPJaxasb.ucOg8g3FGg2x.DF4tAdh7RElagFVqUw.', NULL, 'client/1/user/87/avatar.png', 'HWgw31Tztf67J9gjItE2inScVDAaiGrs6Lb0EQAom7vRHT7jKfnNKtv9ABan', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(88, 2, NULL, 57, 'Xavier Tai', 'Xavier', 'Tai', NULL, 'xaviertwl@gmail.com', '2021-11-26 13:00:17', '$2y$10$dhqIEHtwGe2uwnK1hV.uXegosH8QDfhC8weebQfsOiyNnCUmAqYOK', NULL, 'client/1/user/88/avatar.png', '8vSmBqkHs97QHKKLyuoDMQJ2AcRRsbP7hKcfhFqnWeZIeP4MBIoVDxumhEFC', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(89, 2, NULL, 58, 'Shijor Shamsudeen', 'Shijor', 'Shamsudeen', NULL, 'shijor@outlook.com', '2021-11-26 13:03:58', '$2y$10$Q9BF.ByRzArHJyTiFzWm1ehKSATU8vN/M/t/LbBuI2URKGWFo8NGq', NULL, 'client/1/user/89/avatar.png', '6pws3YYjK8G5Y0n1whXChxRAamZ7LSl3nHdFySYcuonh9qPDKWcfnzCNNyl6', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(90, 2, NULL, 59, 'Andrian Rahardja', 'Andrian', 'Rahardja', 'ID', 'me@andrian.email', '2021-11-26 13:49:33', '$2y$10$OG7to1wSbHc/ltkpWRmFBOWb5kNs9dcXgS604Ev45sgjEfrSPXT.i', NULL, 'client/1/user/90/avatar.png', 'HMP55bgYCpohQj8G8Fr7ixfmHxhfSjFW1UFpzSHBTxCrQnXPAIOxIM1CI7BX', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(91, 2, NULL, 60, 'Ravi Kiran', 'Ravi', 'Kiran', NULL, 'ravikiran.cfp@gmail.com', '2021-11-26 13:02:19', '$2y$10$SPtSVz857gAw2fwWUcKx3.tJhxcYMU9CVYpA9Yt/VwRv.ptxU8VrW', NULL, 'client/1/user/91/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(92, 2, NULL, 61, 'ravi kumar', 'ravi', 'kumar', NULL, 'ravikckm@gmail.com', '2021-11-26 13:37:23', '$2y$10$5.9w2Avnjt.7NdTgKnp47e7p/HWho8UtkmUVsWHbMlKZ1U6cpNOp2', NULL, 'client/1/user/92/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(93, 2, NULL, NULL, 'Brian Tegridy', 'Brian', 'Tegridy', 'CA', 'app@elated.agency', '2021-11-26 13:40:54', '$2y$10$X.lRiUpLop1jjgDf/c0XvuJ2mMo/Z49wXbOU7PP8zgH1rSjK5AfDO', NULL, 'client/1/user/93/avatar.jpg', 's1gIGU5KpeGsrfopuE98vyANcg1HFJEpjq2MHPqSQiqH5vZte21CTq79Erck', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:40', NULL, 1),
(94, 2, NULL, 63, 'Jordi Torrijos Espert', 'Jordi', 'Torrijos Espert', 'ES', 'contacto@jorditorrijos.com', NULL, '$2y$10$YMuhfo9BsFmtNPEHoTib9OOMwAWmvR3pVwgynjINTOO7Te5I1U06S', NULL, 'client/1/user/94/avatar.png', 'NHamdUM369apvGWVuqwlITvTOtNHwphKSQSqOzh7HQjOLpgAa9WmuHfkWdUQ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 07:46:27', NULL, 1),
(95, 2, NULL, NULL, 'ppc', 'ppc', NULL, 'US', 'digibears.subsh@daily.paced.email', '2021-11-26 13:12:01', '$2y$10$GF6RtyK3bHaE.miZlGsyGe8x6zee.FGe3dGuWYghriM3tgmc99Yam', NULL, 'client/1/user/95/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 13:10:48', 0, '2022-10-13 13:19:40', NULL, 1),
(96, 2, NULL, 64, 'Azhari Dol Kadir', 'Azhari', 'Dol Kadir', 'MY', 'azharidolkadir@gmail.com', '2021-11-26 13:18:04', '$2y$10$mMfQXf4vLcFJGM8RyBBwVur8cXmazmVKJSf3btzMMMDcxHg5QN.M2', NULL, 'client/1/user/96/avatar.jpg', 'EVPvDvi3eycZOIxftnLOOetnNAahBaT0cgAPjI1gLTnuz42YyFJVkkSoZWLd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(97, 2, NULL, 65, 'Mario Marquez', 'Mario', 'Marquez', NULL, 'mariomarquezt@hotmail.com', '2022-12-02 06:22:32', '$2y$10$dayiVZxpfXnOefsbMMc7su23u2EWat4fRB8L8ToS5PK6wXG3BdkRK', NULL, 'client/1/user/97/avatar.png', '0raQtzm5WX8XYugkYd4vBVhIx9Z3G4UXLVFtGZsl5M24IZuxfON1vNJq8Hiw', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 22:12:21', NULL, 1),
(98, 2, NULL, 66, 'Saptaswa Dhang', 'Saptaswa', 'Dhang', NULL, 'saptaswadhang@gmail.com', '2021-11-26 13:16:58', '$2y$10$KGNVVvNpKJrTDxhd5Fv4redtNpl7LBLFOvWQN7kz7VRAkRHiNJvTy', NULL, 'client/1/user/98/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(99, 2, NULL, 67, 'Ivan Chan', 'Ivan', 'Chan', NULL, 'ivanckw@gmail.com', '2021-11-26 13:17:55', '$2y$10$ye1kzeOWGJ2YOkUZ0It0cuvu2fUbJvvdxJdku0.ek4g5A5/.zwmTK', NULL, 'client/1/user/99/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(100, 2, NULL, 68, 'co tv', 'co', 'tv', NULL, 'cotv.co@gmail.com', '2021-12-03 05:33:27', '$2y$10$S0D.evBh0p0ukG6s60CH8uQ/KWSG9E301ARvTAss4ZqybRExowa/a', NULL, 'client/1/user/100/avatar.png', 'UmXdo0UX0A9L7QPbhxGonWnh198tdZ7Ar5znFgfhHVpuIQ0SHB9wDF2zeSrI', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(101, 2, NULL, NULL, 'Mariela Casanova', 'Mariela', 'Casanova', 'GB', 'thecoolmktg@gmail.com', '2021-12-03 05:38:31', '$2y$10$dajFetwz/1ws6v/wGclmdOdApMbYBjljAE2.B/yaI657C1Zi3Q6Je', NULL, 'client/1/user/101/avatar.png', 'tcLHia85hyprwNu2LBjJO8Uq5Nfwot5UHyfFEXItzGI75E1PINbMQ2ZfuKUC', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(102, 2, NULL, NULL, 'torchminds digital', 'torchminds', 'digital', 'MY', 'appssubshero@torchminds.com', NULL, '$2y$10$r.5uMZtad1d9u7D1BojmkO94gVqYFLfE1eE4.2Qy6EBZkk0YQG9Ra', NULL, 'client/1/user/102/avatar.png', 'YfqEMZlAoQzbTfHPpuCzv774ImJAH8oDMndr6GkbFyiMvZEDLRycxLsq7ngB', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(103, 2, NULL, 71, 'Azhari DK', 'Azhari', 'DK', NULL, 'azhari.stj@gmail.com', '2021-11-26 13:31:16', '$2y$10$rueDA4gZcaaJ8s.bDyU.Ee5H87TqfD.fZaNYza0dHiEnqfhndFcMW', NULL, 'client/1/user/103/avatar.png', 'bFl8oYYcTtQuVB14TL2k1UanpxeXuUZ9u34ugVhVkPhfePYT8BrRUhBGiAGu', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(104, 2, NULL, 72, 'Jay Kay', 'Jay', 'Kay', NULL, 'info@syndicateblaque.com', NULL, '', NULL, 'client/1/user/104/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(105, 2, NULL, 73, 'A A', 'A', 'A', 'IN', 'digitalmarketing@ashtech.in', '2021-11-26 13:40:34', '$2y$10$u3.ag/KF.QCJaZ4zyRwYB.pEIUtWq/GPOm5YESW.oKNnpBJKGnDDW', NULL, 'client/1/user/105/avatar.png', 'VJgSMeCO0GgXWU9JVjWuf4agj2qLrvcmfiiXj7jHZRk8jxTXCphPtL43Egsl', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(106, 2, NULL, 74, 'Hoang Phan', 'Hoang', 'Phan', NULL, 'vuhoang02@gmail.com', '2022-12-02 17:13:01', '$2y$10$CXCaLpM1pKiIzhDulN7Lm.8ciRvTwqyTZNpGr6sjXvrCo5T4URdza', NULL, 'client/1/user/106/avatar.png', 'LOrWn52mwAUF7UUadfh4y1sviHeF8yR8XRjdzZUvgyMOkk1BDTbR4EyjHCGf', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(107, 2, NULL, 75, 'Shlomo Z', 'Shlomo', 'Z', 'US', 'szaga@zgiwireless.com', '2021-11-26 15:43:55', '$2y$10$9QT2b7YwIIU7GPlkWN8R2uYrUpaULKcO8NvzyO5gIU3iZcnsGRc3.', NULL, 'client/1/user/107/avatar.png', 'k5Qil8qzJvy2UCbDp5QHlQkxNmFhEBcHfdnCkQ8CRsE6yfDQnPnqS7lfXITZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(108, 2, NULL, 76, 'Pradeep Kariyawasam', 'Pradeep', 'Kariyawasam', NULL, 'kgpindika@gmail.com', '2021-11-26 13:44:08', '$2y$10$szXFQ4VC6jxdbJ9P4HKUieZ3UizuuK8oWBaj6CWAx0wTkF5xgVcuO', NULL, 'client/1/user/108/avatar.png', 'dhSLNN7GARxK2ZPELlKBjlLjhjxGkGuJnIgOoQwHTLl14LnQZKhZr4h7nwch', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(109, 2, NULL, 77, 'Dee Rk', 'Dee', 'Rk', NULL, 'sdw5262@gmail.com', '2021-11-27 12:08:25', '$2y$10$Dr5pNtc2kncJf8NOu64IYuLWKS8T2JJrCOBS93cDegDI4a9vHKWtq', NULL, 'client/1/user/109/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(110, 2, NULL, 78, 'Digital Ads8', 'Digital', 'Ads8', 'IN', 'digitalads8@gmail.com', '2021-11-26 16:20:10', '$2y$10$hLSG8CsoQQm/5Gc3T0Tdr.EGI9A.HdJVlRXJejw52q1iHeZkDXBHy', NULL, 'client/1/user/110/avatar.png', 'u1G3mTqWQemEp850UezQz8qpUdRY7KA2UhTJkoTgdnopZbKYpDVS6qkPvuyg', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(111, 2, NULL, 79, 'Vikram K', 'Vikram', 'K', 'IN', 'vikramkinkar@gmail.com', '2021-11-26 14:23:04', '$2y$10$VASpogE0NztRXEj9tKWKxe3F/KlyaKzhlVOlkDlX.DMcGgWhsOHzi', NULL, 'client/1/user/111/avatar.jpg', 'GlSDSMBZhIxUYrFDwrnoQBZcjgcNVPu3q8gsgPYjAY7TOCrNKXayjH0BHTph', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(112, 2, NULL, 80, 'Noureddine Dhouimer', 'Noureddine', 'Dhouimer', 'FR', 'dhouimer@gmail.com', '2021-11-26 14:09:13', '$2y$10$b3oWItlqtri88naM2OfIJeAyOVj9XVmXKqlQQneSDKLlhZq5kRPua', NULL, 'client/1/user/112/avatar.png', 'PeVoj9pS3VgsUuJZpR8yZBErs8uFwoUCDMzB9F11PVAzxwn2vDyUUZGtVMOH', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(113, 2, NULL, 81, 'Satish Singh', 'Satish', 'Singh', 'IN', 'satish.acil@gmail.com', '2021-11-26 14:07:42', '$2y$10$VQng.ZWhInEvabma144DbeQ0ZHcYbLL1Y5ogec85fK5KGuTvefFTa', NULL, 'client/1/user/113/avatar.png', 'sY7yAnDhbwU7hxZ0Uix4fKKP5Z1vSoRZWCwzvg3QGYFCz3nF3Wmzm4rCkbsZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(114, 2, NULL, 82, 'Muchammad Diponegoro', 'Muchammad', 'Diponegoro', 'ID', 'jepossible@gmail.com', '2021-11-26 14:23:56', '$2y$10$rIfFIn5Ec.MWXlZy2j5Nlut7SY1ICqMCZpETWI5DqY0pWbj7kQTce', NULL, 'client/1/user/114/avatar.jpg', 'NenV78ZzSEtqFsuJNHrd0ML7n2G9YpHBKADwHLSeq2w2yrY3x0W99cJg9SMh', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(115, 2, NULL, 83, 'Alex Sim', 'Alex', 'Sim', NULL, 'sohonetbizsolutions@gmail.com', '2021-11-26 14:49:27', '$2y$10$vxWj9RYSI7qoIAXN7sNZf.bRzTfuGYYQLjcMV.lRGdKxwmjns3aFu', NULL, 'client/1/user/115/avatar.png', 'DhdCGgKCvfezeK4Sz96ASj0MYWASq00m54IEdxfVCkBwCBZnKRN97qGhT8O6', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(116, 2, NULL, 85, 'Michael Clark', 'Michael', 'Clark', 'GB', 'michael@splice.agency', '2021-11-26 14:29:56', '$2y$10$HiEqkK2FeiLh2oStv1/nIef2AoLdKczInUvTU7ZVIOv3qj2KcO2Xa', NULL, 'client/1/user/116/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 14:28:20', 0, '2022-10-13 13:19:41', NULL, 1),
(118, 2, NULL, 84, 'Martin Broadhurst', 'Martin', 'Broadhurst', NULL, 'martin@broadhurst.digital', '2021-12-03 08:56:20', '$2y$10$UXeH9UnXdYgGibzIjKEC7uWPkX9dtP7Ev7ShiJi1ex4pqKBrnHs2G', NULL, 'client/1/user/118/avatar.png', 'mT1GB7K08e2Rf8LOOG1ymYFRLHrWvOE0hMrg3RGAB8Mi7rK4zPbl6yVn6Xcz', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(119, 2, NULL, NULL, 'Tim', 'Tim', NULL, 'US', 'subshero.baedad@mailbeaver.net', '2021-11-26 14:54:24', '$2y$10$i2q6XFha9Nw1CF9HcwI8Q.CgOQeylScX9Px0Ey.tk71Qj1R044Ck.', NULL, 'client/1/user/119/avatar.png', 'xH9WEJvVWNzh7Lp0cvtYeyjNZHDhfGQZJBXttWIJOebRqwQ38QyKLuC1PNu9', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 14:39:59', 0, '2023-07-17 14:08:52', NULL, 1),
(120, 2, NULL, 86, 'Gavin Edson', 'Gavin', 'Edson', 'GB', 'gavin@d5media.co.uk', '2021-11-26 14:41:34', '$2y$10$AX3c76eEKZYCXxVpoI20D.ty/GvHwTSXFDsTGDWzR6BJf.DqSrJmO', NULL, 'client/1/user/120/avatar.jpg', 'lFmSfsV8VbrNE7balwwiI16ngnhFfo8NhSV9POTWCDqKbwQYbDIioe2fB8KC', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(121, 2, NULL, 87, 'Kris Poore', 'Kris', 'Poore', NULL, 'kris@brandeqty.com', '2021-11-26 15:23:51', '$2y$10$0lB8I6PYhRS0m5A5j0S0Oun/cfqqId7.YA0MmrLjqRiwWffz900de', NULL, 'client/1/user/121/avatar.png', 'xuhUcGYhGnnG58DQ6v82kIULzk4wckzr0XjWyFWiotlwGbm5aAvMujxcLzwX', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(122, 2, NULL, 88, 'mohamed elkomy', 'mohamed', 'elkomy', NULL, 'gm25582@gmail.com', '2021-11-26 14:56:52', '$2y$10$MJNeBJXDEf/QKTAfEu/lCeycT5SkRpAzAkBJZs8z.dexewByJr2Vq', NULL, 'client/1/user/122/avatar.png', 'KmgM5Ye2HIh6iT22GpJjkmqji0exBKdEbJpYplCVi43EuESHRnFSGZdFM1vp', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(123, 2, NULL, NULL, 'Mk', 'Mk', NULL, 'US', 'monicak2109@gmail.com', '2021-11-26 15:17:29', '$2y$10$eAyNsbUPO4Caeo7gOHqEMuI1JfPq3tTei4y9uVJEkgwQbZ2Y7H.9W', NULL, 'client/1/user/123/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 15:11:57', 0, '2022-10-13 13:19:41', NULL, 1),
(124, 2, NULL, 89, 'Melinda Nota', 'Melinda', 'Nota', NULL, 'support@digitalpass.com.au', '2021-11-30 03:48:53', '$2y$10$GpB85FHKtlpxEwHr0V772OjCx2DH6AznIA68V2of3SbkX2FoOAdEC', NULL, 'client/1/user/124/avatar.png', 'xbRZkGGX7gNK8x3THg6hY5Lakkn5wepj4g3ib36VCpFhJJIgb9Lyvccd80Qq', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(125, 2, NULL, 90, 'Stefan Schuur', 'Stefan', 'Schuur', NULL, 'stefanschuur@protonmail.com', NULL, '', NULL, 'client/1/user/125/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(126, 2, NULL, 91, 'Rakhul Asok', 'Rakhul', 'Asok', NULL, 'rakhulp@gmail.com', '2021-11-27 00:47:06', '$2y$10$kVeLLcn7zTIASWvAkSPKGe/GxCvyPOVtjYi83BfpckWs4f2pYeEnG', NULL, 'client/1/user/126/avatar.png', 'KvhviMKC8AezLnkSqmzCuXbUMCoQ3heJxPWB1NWZIP8L49p8m7Cisho9YW3y', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(127, 2, NULL, 92, 'Alberto Olivera', 'Alberto', 'Olivera', 'US', 'alberto.olivera@live.com', '2021-12-09 13:20:35', '$2y$10$msvy2GYEWtp1AAaRZNSRpemywEiuhi9rQFJBydvDEjv2VScp98LHS', NULL, 'client/1/user/127/avatar.jpg', '6CTGznqTRPTkpZJlYJQuAml2DXwbk7ANQzPffGWuBpO6SnIqKRA1FzCJYY6A', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(128, 2, NULL, 93, 'Jessica Crane', 'Jessica', 'Crane', NULL, 'jessica@cranelaw.com', NULL, '', NULL, 'client/1/user/128/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(129, 2, NULL, 94, 'Jacques Vreugdenhil', 'Jacques', 'Vreugdenhil', 'FR', 'jmountain75@gmail.com', '2021-11-26 17:05:25', '$2y$10$v2zrwEb5Y1eDbCsaecOD5uWmw7horWfsj0wLcNlv7uppPvZtWIcCm', NULL, 'client/1/user/129/avatar.jpg', 'LRa18MutiCzObhn64qbGdMslCObNHnCjCKZIuRNZ41cYbD5zsZb40V1uL1y1', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 08:06:45', NULL, 1),
(130, 2, NULL, NULL, 'discourse setup', 'discourse setup', NULL, 'US', 'discourse.setup@gmail.com', '2021-11-26 17:20:40', '$2y$10$HeK8wLFDTgnaGFmvoj7RCOlLdPU.VzJA0rH7JiGlPkEfkPXm9pdBW', NULL, 'client/1/user/130/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 17:20:27', 0, '2022-10-13 13:19:41', NULL, 1),
(131, 2, NULL, 95, 'S Ommsharravana', 'S', 'Ommsharravana', NULL, 'director@jkkn.ac.in', '2021-11-26 17:29:23', '$2y$10$I6Y3BAw3hZQ9rD0a.0CMo.cLXV8rCCtGAmSTl6kyUpHx0cbnoL8bO', NULL, 'client/1/user/131/avatar.png', 'K2WEdZDN6hc3D92lTtfYoOJtiH2TtlY2rYAi2PFsRXwVnkfwndlzenAopukQ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(132, 2, NULL, 113, 'Ruben Guerrero', 'Ruben', 'Guerrero', 'CL', 'rubenguerrero@gmail.com', '2021-11-26 17:30:55', '$2y$10$/tR7uYKAiEgW9kQT0jXl8uLreBesqao4bDMaUpMFluZSldAJRd3tu', NULL, 'client/1/user/132/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 17:30:45', 0, '2022-10-13 13:19:41', NULL, 1),
(133, 2, NULL, 96, 'Nami Mirdamad', 'Nami', 'Mirdamad', 'GB', 'nami@nami.co.uk', '2021-11-26 17:47:19', '$2y$10$pZqs.K3ANoa8hef94zMaCOdD6G..YrjOSkTPM805guopEJHkQsqje', NULL, 'client/1/user/133/avatar.png', 'AyGwubodkaCPLVVY4zDmGpLqKp3Z1UcmroU57s6WMrWeBKmcwjaKWhGvzjH4', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(134, 2, NULL, 97, 'Jean-pierre Michael', 'Jean-pierre', 'Michael', 'FR', 'jpmfx1@gmail.com', '2021-11-26 17:56:32', '$2y$10$uuBpVrze5wLxCL7fGa42/.kvnVtnt4tjMcrVlUoksumL/U4nBSyY2', NULL, 'client/1/user/134/avatar.png', 'ddoSOZY11LmORgDTozsRS69TlbmUAKcJFaK0H5imHIIrSJaTDij1THYIiOSy', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(135, 2, NULL, 98, 'Gareth Hayter', 'Gareth', 'Hayter', 'NZ', 'gareth.hayter@slyce.com', NULL, '$2y$10$U3PqGTFqAIXTPmBhtWHLguxs5GxhHhBBogVt7n8zVR2CzkkhPs/Ii', NULL, 'client/1/user/135/avatar.jpg', 'HtO34WZOWBr4ZjXbaedv9ldhEbrmKx2T2XhF76BnqB3WiEWqquIfaMVomhYa', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(136, 2, NULL, 204, 'Dan Paulson', 'Dan Paulson', NULL, 'US', 'subshero@atomy.work', '2021-11-26 17:58:01', '$2y$10$8Vtll25qdczjk3CzIWtzY.MKLsId5s5PTyn.Fd8hRlQB6krt8M7bK', NULL, 'client/1/user/136/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-26 17:56:56', 0, '2022-10-13 13:19:41', NULL, 1),
(137, 2, NULL, 99, 'Saloomeh Mirdamad', 'Saloomeh', 'Mirdamad', 'GB', 'nsmgroup@nami.co.uk', '2021-11-26 18:02:33', '$2y$10$K18Y8eWW6U88EDoNoCRFMuxCDHgynKkXyRwXG0.Cy8gwnevVZUYDC', NULL, 'client/1/user/137/avatar.png', 'Pa6gKIdika43jb6T7AxTX9NjUgDTNxxn2vagFK3Fnx2LYhG3qRy30GSmMoO4', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(138, 2, NULL, 100, 'Franky Surroca', 'Franky', 'Surroca', 'US', 'makingonlineeasy@gmail.com', '2021-11-26 20:34:33', '$2y$10$tKWPME/Q5pSM2HZYLg4DxutrKk96vueKJ7XgQ/QzA/LJvDxi./ZjW', NULL, 'client/1/user/138/avatar.png', 'PWyuYkrBnqRlCP0WmkGRk22pIZLyOKSxcSy2xuBiUdbvYqHc11u3oMWlMgdT', 0, NULL, NULL, NULL, NULL, 'Making Online Easy', NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(139, 2, NULL, 101, 'JP Martinez', 'JP', 'Martinez', NULL, 'seopronyc@gmail.com', '2021-12-17 11:01:58', '$2y$10$LOwXJPX7GHwXzvi68uEeBuUnDbO.0wrCxTPR.nyf9fyRahfDVmWR.', NULL, 'client/1/user/139/avatar.png', 'AgbQPuPxuWVEGNuHWoMn8fseDtmloCgviJXuULuMrXeaCJL6x9ZCXuQXg1bi', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(140, 2, NULL, 102, 'Harry Chung', 'Harry', 'Chung', NULL, 'melx2live@outlook.com', '2021-11-27 00:34:51', '$2y$10$oKCy714f33o6r8J2icck4.kFkPm4oKX.iA.5JawPoHPhPD1QRduZm', NULL, 'client/1/user/140/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(141, 2, NULL, 105, 'Patrick', 'Patrick', NULL, 'US', 'patrick@specifictap.co', '2021-11-27 01:15:16', '$2y$10$Jr7pXj6zZVNr0e4FzteLZODkeqO3g0sH5JLu1Yzn3NGMCfzavuv4K', NULL, 'client/1/user/141/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-27 01:14:21', 0, '2022-10-13 13:19:41', NULL, 1),
(142, 2, NULL, 106, 'Josecar', 'Josecar', NULL, 'US', 'josecarpenietcorporate@gmail.com', '2021-11-27 03:16:42', '$2y$10$56PS78kGsEUMRm7JRJZU5OZlF.FJQbE6MbaWqax9NyZVpLpaBkon.', NULL, 'client/1/user/142/avatar.png', 'RKpnC5pqFBnc8Yy0h89osGmbwbtJ7kcH6zenHKMbQ8BkMuQ3i4glIkx9sHp1', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-27 03:15:21', 0, '2022-10-13 13:19:41', NULL, 1),
(143, 2, NULL, 103, 'kreatif digital', 'kreatif', 'digital', 'ID', 'kreatifdigitalsolution@gmail.com', '2021-11-27 05:09:38', '$2y$10$mXyyEK4Ss28Wdth.bpL7keh4H0exlYtsQxUJQmnm22D9174yHMaTW', NULL, 'client/1/user/143/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(144, 2, NULL, 104, 'Christine McElroy', 'Christine', 'McElroy', NULL, 'cmeknocks23@hotmail.com', '2021-11-27 05:16:01', '$2y$10$FTrdiPS8g33o/9kp3gKUa.cuVxrGBUKfZUDGB0A12WqRlgixGAk5K', NULL, 'client/1/user/144/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(145, 2, NULL, 107, 'Kris R', 'Kris', 'R', NULL, 'krishnaswami.r@gmail.com', '2021-11-27 12:59:02', '$2y$10$BxmLyM1jqbY3sapXDIV5jeX.d9eATdjNiuogioK6UXGBePtUgZhsi', NULL, 'client/1/user/145/avatar.png', 'iu3fwt1qwSzX5doVndKA081Uys0NhCGFflqO2a7rOYkhhS7t0Zpkb0nQREUK', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(146, 2, NULL, 108, 'Anik Joardar', 'Anik Joardar', NULL, 'US', 'anik@dreamlighter.org', '2021-11-27 17:22:58', '$2y$10$SHq8yov6vlZuY47Sxkrnce5t8m/H7DnxlpFuUIINO2o50yO0Eav7O', NULL, 'client/1/user/146/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-27 17:22:43', 0, '2022-10-13 13:19:41', NULL, 1),
(147, 2, NULL, 109, 'Korkut Duman', 'Korkut', 'Duman', 'SE', 'korkut@dman.se', '2021-11-27 18:19:06', '$2y$10$wvic.0JpAqj/jEzk4gGYDe35PnLIEcu95RZw.CplO6ipbaqyO88Eu', NULL, 'client/1/user/147/avatar.jpg', 'vOjViZqUHQOrxvu8Rd38DVoplPN1ubAcYdB8X6WeCOaAGAr7fkxG9ZMkhN2s', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(148, 2, NULL, NULL, 'Kevin Saenz', 'Kevin Saenz', NULL, 'US', 'ksaenz_as@hotmail.com', '2021-11-27 19:11:37', '$2y$10$2Tf1XUAh3M92z027W6Di4ORDANZ1C.UMrHT0VsOPkbbohrHsd4wUm', NULL, 'client/1/user/148/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-27 19:11:06', 0, '2022-10-13 13:19:41', NULL, 1),
(149, 2, NULL, 111, 'Wora', 'Wora', NULL, 'US', 'worawisut@gmail.com', '2021-11-28 01:40:10', '$2y$10$1I8XzhBmO4V2EZHUCvk24.99mKnXvZBv0qokGoHWhXKUqyjG.q9uu', NULL, 'client/1/user/149/avatar.png', 'vd5pIM3k35fdwEXzuTazAqunxOO6wrRsJ80rvwhxSsmFtRUXNfsFsl9uLGgZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-28 01:39:48', 0, '2022-10-13 13:19:41', NULL, 1),
(150, 2, NULL, 110, 'Kanesha Harper', 'Kanesha', 'Harper', 'US', 'choklatdiva@gmail.com', '2021-12-23 03:12:43', '$2y$10$ye8RMOc9SlcG5r9/dWaaXOhUm7PHLbjk4AFMrSl3AG7adbkPZh3De', NULL, 'client/1/user/150/avatar.png', 'qdZrh597NFEtp5SdADvcLvHYVtB9bXvcmLXAJiqijn9ZqODpUGejY8TmstPG', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(151, 2, NULL, 112, 'Chanakya Jonnalagadda', 'Chanakya', 'Jonnalagadda', 'ZA', 'drcstasks@gmail.com', NULL, '$2y$10$dd/cLMIiTVuInZ.i6G62/u7y6gl7.1zw31NwPh67FiC03zqDfHjL2', NULL, 'client/1/user/151/avatar.png', 'A0rSHsWlFYzwZ18AlmtpFgKsgrZTtwpy5bK44PgGKmIK92u5aDnH36xR4Zyw', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(152, 2, NULL, NULL, 'Carl Angelo Tardecilla', 'Carl Angelo Tardecilla', NULL, 'US', 'tardecillcarlangelo@gmail.com', NULL, '$2y$10$b5suixDiYVaUSufvs8XgwOiqy5Nr2dbG9WXD6yimEqUXNYdWswBQS', NULL, 'client/1/user/152/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 01:16:06', 0, '2022-10-13 13:19:41', NULL, 0),
(153, 2, NULL, NULL, 'Simon', 'Simon', NULL, 'US', 'subshero.7b82a1@maildepot.net', '2021-12-13 04:44:37', '$2y$10$vZJYsaDLBzW3wgjzQeh0tu9mLpa5sHY5kFfo3QbQS3VI11kGDq.r.', NULL, 'client/1/user/153/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 01:19:25', 0, '2022-10-13 13:19:41', NULL, 1),
(154, 2, NULL, NULL, 'Ronald DSouza', 'Ronald', 'DSouza', 'IN', 'ronald@meavita.in', '2021-12-27 01:14:03', '$2y$10$kzWSiSQQWdst6U1m8HM6xO6lgbUku46ihVDnpCG3L2g5tgdqPk./q', NULL, 'client/1/user/154/avatar.png', 'Uft69brozrx9FQ7IVx1iye8cV6ftDD4biRUYN7Je2Cv8ihQ8gXy8pnhyWMjY', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 01:32:11', 0, '2022-10-13 13:19:41', NULL, 1),
(155, 2, NULL, 114, 'Duy Nguyen', 'Duy', 'Nguyen', 'CA', 'n.haiduy@gmail.com', '2021-11-29 19:23:05', '$2y$10$MMwYzAWKw3DErheqDCiD/Oqvqyaorst1IOaK5si2hC3M2C6El2Hxm', NULL, 'client/1/user/155/avatar.jpg', 'MGutIBv7BJeWBNTM0KCO3nZlccSDGOPhZfyfhAZtFXuJQ9GdgA1IWRe5RhhB', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(156, 2, NULL, 115, 'Stefan Schuur', 'Stefan', 'Schuur', NULL, 'stefan@ictindeles.nl', '2021-11-29 15:31:11', '$2y$10$oyA9swOW4.KbvZE/Kvc9c.oxgLWISsfQ4/gq0q6Ky1b2CiA9iVwv6', NULL, 'client/1/user/156/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(157, 2, NULL, 297, 'Anocha Pardon', 'Anocha', 'Pardon', 'TH', 'bankxl@gmail.com', NULL, '$2y$10$b4eM0HmuhlRIJjCHkHTj4eOI9AizbYF4vH0AJ60et4vFf7z0wLKJi', NULL, 'client/1/user/157/avatar.jpg', '9wuUyKTxj6eFTFtFJ38nDvdeNLinjq2VSCgD2h8Ctp1hZ9rHJmusUh0YdDuz', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 04:52:27', 0, '2022-10-13 13:19:41', NULL, 1),
(158, 2, NULL, NULL, 'hopper', 'hopper', NULL, 'US', 'hopem28188@ineedsa.com', NULL, '$2y$10$tgfTb1kyACmDfVVFziC3Geo/NPjfvI43JI8Z.ytKWkE0nGbMWmL9y', NULL, 'client/1/user/158/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 06:09:55', 0, '2022-10-13 13:19:41', NULL, 1),
(159, 2, NULL, 116, 'Andres Sanchez Romero', 'Andres', 'Sanchez Romero', 'MX', 'subshero@mysaas.link', '2021-12-15 02:23:36', '$2y$10$0Xc2MFEXxTBgbRkxs5sBjeJHqIsx6Waxmmf1vk/j.178g2Vm21lt2', NULL, 'client/1/user/159/avatar.png', 'vgkWu0flT5X7rKMtjMj5RmwKTQnqo4aKjaAk4GSndxJuSetmGIpNqrm368kM', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(160, 2, NULL, NULL, 'Dev', 'Dev', NULL, 'US', 'xdevil@gmail.com', NULL, '$2y$10$5WTTHBrZ1V2z8SRwPKizu.gOOZPdZJdCKMYNV1RC/RghrQwsV26/m', NULL, 'client/1/user/160/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 06:59:52', 0, '2022-10-13 13:19:41', NULL, 1),
(161, 2, NULL, NULL, 'Jannick', 'Jannick', NULL, 'US', 'subsherotest@jannicknijholt.nl', NULL, '$2y$10$AoTka7MBCs7UutNehOvRlOXBk/WdyIC6JDn3HkkJOnfO3yGA2Ifx2', NULL, 'client/1/user/161/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 07:07:50', 0, '2022-10-13 13:19:41', NULL, 1),
(162, 2, NULL, NULL, 'Jannick', 'Jannick', NULL, 'US', 'subsherotrial@jannicknijholt.nl', NULL, '$2y$10$0/d6JCh94Z.jmAzO6QJ78uf7iWr8WJX.5sga0c7.Vlttjz1YX47Uu', NULL, 'client/1/user/162/avatar.png', 'o0QH4V7NydNqLvjt66Oe0HR6RAcHD97FB8regABcIrxdg2Hy8ij2raVTcSi5', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 07:13:29', 0, '2022-10-13 13:19:41', NULL, 1),
(163, 2, NULL, 117, 'Amin Ben', 'Amin', 'Ben', NULL, 'monproject@hotmail.com', NULL, '', NULL, 'client/1/user/163/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(164, 2, NULL, NULL, 'Ali', 'Ali', NULL, 'US', 'ali.shahjad@gmail.com', '2021-12-03 04:07:10', '$2y$10$cAD3VaXdUS5CUxMk7wKHhO3rufIPcrBnWimmSlX2uTsS8RoHQu82u', NULL, 'client/1/user/164/avatar.png', 'SJocWzsnkZtEqRKjvhNxsaleHH3Xk1eNGCTrKtQeFobQdXM7YJmnjIWtIQDW', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 10:12:00', 0, '2022-10-13 13:19:41', NULL, 1),
(165, 2, NULL, 118, 'Phil Tusing', 'Phil', 'Tusing', NULL, 'phil@dtalent.co', NULL, '$2y$10$1citsJAWjBoPDx7wCy0hXOp1.ICjo3i1DVMenQ7HL444lGoHEwLLa', NULL, 'client/1/user/165/avatar.png', 'LyCdX6Q9Jw88dDpddseSJJpB2qGEIlhMcALGjOnrxv1VrquuRJMHIWcHtKuc', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(166, 2, NULL, 119, 'Soniya Ahuja', 'Soniya', 'Ahuja', NULL, 'soniya.ahuja@gmail.com', NULL, '', NULL, 'client/1/user/166/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(167, 2, NULL, 120, 'Fajar Faezal', 'Fajar', 'Faezal', 'ID', 'fajar.faezal@gmail.com', NULL, '$2y$10$OOmL89ZtKQEzukECGxv2GeoX84puvfk4ez0KR6NN9h9dYp6sDHZQq', NULL, 'client/1/user/167/avatar.png', 'cm0Zk7ICOZqOdcTgpY0kti1ydnTW0ThM5UXRgd1LxnEFb2jMKPwfZGwf8HzZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(168, 2, NULL, 121, 'The Kickstart HQ', 'The Kickstart', 'HQ', NULL, 'bbennett@thekickstart.com', '2021-11-29 13:31:19', '$2y$10$KRvEU35v16gv.KEwdMNbkuCpzjP.Jl3hJCCITNBGiP5xLyhxOifdK', NULL, 'client/1/user/168/avatar.png', 'WCfXWXz9B4fS3zu79W9GLn7wUqOqxB0B7K3YJ3TSXx5Ei3rKUDFMk0nRA0Hi', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(169, 2, NULL, 122, 'Anunay Vij', 'Anunay', 'Vij', NULL, 'vijanunay@gmail.com', '2021-11-29 14:38:39', '$2y$10$p4xd4vPW6bROfQn9wIljUOVhPNhtTStxqufMd3I8A22abHc.T1Cf6', NULL, 'client/1/user/169/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(170, 2, NULL, 123, 'Francois Houte', 'Francois', 'Houte', 'FR', 'contact@visite360pro.com', '2021-11-29 15:20:46', '$2y$10$I1wl/dMWZqcB2/s9E3k14.D2Y7r99xHKLs7tMEWuiDMmmKtPZAn3i', NULL, 'client/1/user/170/avatar.jpg', '0kenKbHpWmQmplmAUKYYp4dtczR8uIh47btJLvKXmfONRZ6bPISAT2GJRyHt', 0, NULL, NULL, NULL, NULL, 'Visite 360 Pro', NULL, '+33608235378', NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(171, 2, NULL, 124, 'Yahya Zakir', 'Yahya', 'Zakir', NULL, 'softwares@gulfinfotech.com', '2021-11-29 15:40:51', '$2y$10$fI2fhli/rjttMwV.vm9mqOPN8xksIM7rpirk07ucHK7elnruNncp.', NULL, 'client/1/user/171/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(172, 2, NULL, 125, 'Gokul Nath', 'Gokul', 'Nath', 'IN', 'mailtogokulnath@gmail.com', '2021-11-29 16:03:51', '$2y$10$Exc2V7CJQ6NGmmRwVP.c8e88OMNRX5ma5P5eg79oHo0xPh5hPkEnC', NULL, 'client/1/user/172/avatar.png', '88Ik7rrbNgXmYRJjGKMHOZjL6ehhBwlvSVCWaKS3WNGLYsEwCY8YldqjfNTr', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 16:02:49', 0, '2022-10-13 13:19:41', NULL, 1),
(173, 2, NULL, 126, 'Darren Dudhill', 'Darren', 'Dudhill', NULL, 'info@hype-web.design', '2021-11-29 17:32:39', '$2y$10$47CeJCjzbAoE6fX/uz5YL.joCjAJf7rmVKjxUzg5ld56l2xsZuXuu', NULL, 'client/1/user/173/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(174, 2, NULL, 127, 'Yuvraj Ajay Singh', 'Yuvraj Ajay', 'Singh', NULL, 'yuvraj@thinkvedic.com', '2021-11-29 19:38:35', '$2y$10$d6G18KTTz6wLPEM5m.An8eCnV6auLrMyw8D5TZ7TW7ZgktoetoSOa', NULL, 'client/1/user/174/avatar.png', 'WpIOLQ9uBwhubAWmKIWerNAZ0lYafnWx6GQfBWa3DU3w2SKqXOO5Rg0vdqBQ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(175, 2, NULL, NULL, 'Aditya', 'Aditya', NULL, 'US', 'adityapc@outlook.com', '2021-11-29 20:31:18', '$2y$10$jC5XJ7DlSzV8drk92xKxtO/hlRNgtT.v6IEgXDg0e20cRN9rpaTfK', NULL, 'client/1/user/175/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 20:29:10', 0, '2022-10-13 13:19:41', NULL, 1),
(176, 2, NULL, NULL, 'oscar78@frwdmail.com', 'oscar78@frwdmail.com', NULL, 'US', 'oscar78@frwdmail.com', NULL, '$2y$10$Rb/Orpbkr.qqERXQhuzKzumBOJ3CYtGIdeLCOjnlplNQ6qgGKFyly', NULL, 'client/1/user/176/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 22:43:54', 0, '2022-10-13 13:19:41', NULL, 0),
(177, 2, NULL, NULL, 'johan.daugherty@delivrmail.com', 'johan.daugherty@delivrmail.com', NULL, 'US', 'johan.daugherty@delivrmail.com', NULL, '$2y$10$ZPGF3a1yLSG0WRXPQ5MDNebfPQk.ox/DHubGw48iZcmvFM4XccRbq', NULL, 'client/1/user/177/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-29 22:56:55', 0, '2022-10-13 13:19:41', NULL, 0),
(178, 2, NULL, NULL, 'gila@explorefoodie.com', 'gila@explorefoodie.com', NULL, 'US', 'gila@explorefoodie.com', '2021-11-30 00:51:20', '$2y$10$voNqYRgPtZ1ZlWtFMxJ8XefZ3wLOzF94y6MaXeVrEIWenMNns39iC', NULL, 'client/1/user/178/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-30 00:50:29', 0, '2022-10-13 13:19:41', NULL, 1),
(179, 2, NULL, NULL, 'Jono Husin', 'Jono', 'Husin', NULL, 'jonohusin@gmail.com', '2021-11-30 12:07:06', '$2y$10$WfRQvY2OInFg74Mqg0.LguvgJSU9NO2sMSQH7SllFHnKvajbuYmZe', NULL, 'client/1/user/179/avatar.png', 'TtfaRPWsaHqJimHhQEnW37Zlst4qc3PtzZummsm3IoN0hCF1RPr6NQVp82cf', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(180, 2, NULL, NULL, 'Sumit Pradhan', 'Sumit', 'Pradhan', 'US', 'sumit182@agfunnel.cyou', '2021-11-30 04:36:56', '$2y$10$iME0vZaY4sSuvdPbmETe4O2eNuRnt.Y3k87dCWNnnRGGkBCNB0lE6', NULL, 'client/1/user/180/avatar.jpg', 'CNDFgtrSqQbI7ywdxpqNZ8wjmfzu5Y1KxOrwmu4a6maUun9ijmnZvXy7hlcm', 0, NULL, NULL, NULL, NULL, 'Lalmon', NULL, NULL, '2021-11-30 04:36:09', 0, '2022-10-13 13:19:41', NULL, 1),
(181, 2, NULL, 131, 'Alex Lay', 'Alex', 'Lay', NULL, 'alexlay@yahoo.com', '2021-12-24 15:43:13', '$2y$10$YujaxmW4oH5.zoSR57/s5.JDemqltd7lXwQnPUU0TXZE5TurHmyaq', NULL, 'client/1/user/181/avatar.png', 'ju5hckNgChVs0KWm4KPBFHrs6jFPuzzlV9A4RHU7gnsm5mTfunFux6jIVc98', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-04-02 09:40:32', NULL, 1),
(182, 2, NULL, NULL, 'Ivan', 'Ivan', NULL, 'US', 'ivan@kardify.com', '2021-11-30 09:52:11', '$2y$10$72O8iaRh1.SJPbS.DESNFu5Sc/nnb/.RBeIGlT4spqxGmewQdlKMO', NULL, 'client/1/user/182/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-30 09:51:01', 0, '2022-10-13 13:19:41', NULL, 1);
INSERT INTO `users` (`id`, `role_id`, `team_user_id`, `wp_user_id`, `name`, `first_name`, `last_name`, `country`, `email`, `email_verified_at`, `password`, `description`, `image`, `remember_token`, `marketplace_status`, `marketplace_token`, `paypal_api_username`, `paypal_api_password`, `paypal_api_secret`, `company_name`, `facebook_username`, `phone`, `created_at`, `created_by`, `updated_at`, `reset_at`, `status`) VALUES
(183, 2, NULL, NULL, 'Asad Ur Rehman', 'Asad Ur Rehman', NULL, 'US', 'asad@pakwebhouse.com', '2021-11-30 10:14:17', '$2y$10$bQlqUkcCtOCiLQPdvJB/Ieg3P7uxVapF1Ksya02xarWGZ2XEb.tvq', NULL, 'client/1/user/183/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-30 10:13:38', 0, '2022-10-13 13:19:41', NULL, 1),
(184, 2, NULL, NULL, 'Hamza', 'Hamza', NULL, 'US', 'rohanramish@gmail.com', '2021-11-30 21:59:15', '$2y$10$t5DPY0NjUGn2lIo4.vAvK.lCqMjle74WWIACvO/joVrAeYCvBKs6.', NULL, 'client/1/user/184/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-30 10:15:39', 0, '2022-10-13 13:19:41', NULL, 1),
(185, 2, NULL, 132, 'Sergio Berzosa', 'Sergio', 'Berzosa', 'ES', 'berzosa@gmail.com', '2021-12-03 17:00:17', '$2y$10$K5ZzwFl0jRj/kTcToKK20eCGYqpRW60ukNVoY.bf8AkmZdf37/D6u', NULL, 'client/1/user/185/avatar.png', '9tyN1Onc3wDUqlynNkASrwHnvhdyB2SwYxNtZoy8QzF8yUUnpETmKRAhhOKX', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(186, 2, NULL, 133, 'Ankush Chopra', 'Ankush', 'Chopra', 'IN', 'ankushchopra1@gmail.com', '2021-12-01 02:45:57', '$2y$10$WH18NaEE0WLTRsJReMUeVurtyAsBPSefJ/1/D/zyHoOLf.Mv0bmQC', NULL, 'client/1/user/186/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(187, 2, NULL, 134, 'Jin Teck Chua', 'Jin Teck', 'Chua', 'SG', 'jtcrde@gmail.com', '2021-11-30 17:44:46', '$2y$10$Y.afUEfvI85ReyWGj3Ou/eoAYOGrMaJSXZ1NdAPRMUT5xHhdKxaE.', NULL, 'client/1/user/187/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(188, 2, NULL, 135, 'Abraham Antigua', 'Abraham', 'Antigua', NULL, 'abe@abrahamantigua.com', '2021-11-30 19:32:35', '$2y$10$3ufxArGyWmeyOq2SwBPHMuQngg3GrY1PEVKdIDnH14ExAWryIA51C', NULL, 'client/1/user/188/avatar.png', 'ueIaY0MhwxO3dmb2j9roqThIcw198XOZJl6zyrhBJ5wm90VjRPFwFCDydbkj', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(189, 2, NULL, 136, 'A Perona-Wright', 'A', 'Perona-Wright', NULL, 'A_Perona@yahoo.co.uk', '2021-11-30 21:35:30', '$2y$10$.LNl0knZQHf3iKqDCmdSY.sVQU8PYZdTJ6a5vWF/JbSzI2un0sDtS', NULL, 'client/1/user/189/avatar.png', 'RC93icn2BSJ3U5N0O7Mwv4f6fflQtD9mXPDlAmnlOJBmdYcg6hzOol4prGEr', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(190, 2, NULL, 137, 'Muhammad Zahid Sulaiman', 'Muhammad Zahid', 'Sulaiman', NULL, 'dihaz94@gmail.com', '2021-11-30 23:34:15', '$2y$10$tQLnJhs6XHl.HGvV0wZevOzQWRmFMtnc5zx6l4ffyrdD4ZD2ohlJG', NULL, 'client/1/user/190/avatar.png', 'dNkXlgdF99sY3z6JlFhACFI95d5gI6RIKpqfQsD2OtdjjfJ954y4cL2hWbRk', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(191, 2, NULL, NULL, 'John Mankin', 'John Mankin', NULL, 'US', 'jbmankin1@gmail.com', '2021-12-01 04:52:48', '$2y$10$U8cxXUONEgkLHk4dQGo7s.J0RyXxqDionZ4ZXeMMwmKDVYHU7eBaK', NULL, 'client/1/user/191/avatar.png', 'h9AhRw2DAMAPiP8laqQuIyvJiq8LyV86tJXWNJzNAvRnzjLizHPWzaLcLm2a', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-01 04:47:37', 0, '2022-10-13 13:19:41', NULL, 1),
(192, 2, NULL, 138, 'Swapnil J', 'Swapnil', 'J', 'IN', 'startupwebsolution@gmail.com', '2021-12-01 14:41:26', '$2y$10$RJn4eW0PrAkfJyjnGWNCQ.ef4vlT5uvsqFmD0c/L41u5lJQa82IqC', NULL, 'client/1/user/192/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(193, 2, NULL, 139, 'Eli Peker', 'Eli', 'Peker', 'US', 'elip.tlv@gmail.com', '2021-12-01 15:07:56', '$2y$10$Txd0agUL1mcy1oeiHu3aZuEmWUl9yqlGwyGIL1PBCYRlEEhmwyw8K', NULL, 'client/1/user/193/avatar.png', '9yxuDH4i9ix6hGJlxkZSozAEVAjeuCXYuGhak9kh8lsQuwaqf09V1EFOlbBf', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(194, 2, NULL, NULL, 'Sanal Adam', 'Sanal Adam', NULL, 'US', 'sanaloji@gmail.com', '2021-12-01 17:57:27', '$2y$10$mHZKsUDZ/Mt1w3kXP24xR.1/4P49wLWW5OKX3wkcJuM5d5i8eEoxu', NULL, 'client/1/user/194/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-01 17:57:08', 0, '2022-10-13 13:19:41', NULL, 1),
(195, 2, NULL, 140, 'Tobin Koshy', 'Tobin', 'Koshy', NULL, 'tobinkoshy@ymail.com', '2021-12-01 19:53:27', '$2y$10$UwAhTNX7wtZymSY1zwHSNOsT8jgeOov4Cwju96lY3HP91VkgzEmpe', NULL, 'client/1/user/195/avatar.png', 'hrvxKc3ctuZIgHhbJn6a6blhsLXJcgN7pJDijhM92mSTDZo5rVQ9sPSreKmP', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-25 12:40:05', NULL, 1),
(196, 2, NULL, 141, 'Jack Ghee', 'Jack', 'Ghee', NULL, 'jackghee@pm.me', '2021-12-02 05:21:58', '$2y$10$NgO3FGxW9fUjZDYL9n/x9ePCd2rk7SMGPPSWKGPhVlBPyp7yPIi1O', NULL, 'client/1/user/196/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(197, 2, NULL, 142, 'Florin Dragusin', 'Florin', 'Dragusin', 'MY', 'florin.dragusin@gmail.com', '2021-12-02 09:55:40', '$2y$10$4mNorzipLEYLTEmhjrBqsOWoBosEH8QvOIZnXm0FFxyP6O9PkNHai', NULL, 'client/1/user/197/avatar.png', 'WNrZM5tYNZpAjwB7RqsZ1kf4e3CoA2qmkrEASxTypQzw9A6mNdDKyXGhZHKx', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(198, 2, NULL, 143, 'Anita Siegismund', 'Anita', 'Siegismund', 'DE', 'info@digital-agency.one', '2021-12-02 10:34:27', '$2y$10$P3ObhtXcKJlt2uTTBxxoC.gb9XgK9H9GapRqiv7yeHhNMAHNn4iva', NULL, 'client/1/user/198/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-02 10:34:12', 0, '2022-10-13 13:19:41', NULL, 1),
(199, 2, NULL, 144, 'Sagar Mendiratta', 'Sagar', 'Mendiratta', NULL, 'sagarr86@gmail.com', '2021-12-02 11:12:04', '$2y$10$wk1GtnowcOXOzDxnMbmhTeHIVZqv2ZZRiOkpujJFl2Ge7EO4zRavK', NULL, 'client/1/user/199/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(200, 2, NULL, 145, 'Clarito Parsons', 'Clarito', 'Parsons', NULL, 'admin@digital.fyi.ac', '2021-12-02 12:55:52', '$2y$10$/2UK48GAV0/r7AUEkgFa1uFfSt25Xy.C2qRFwLpwI7egMZCNwTa72', NULL, 'client/1/user/200/avatar.png', 'uCS5p1FsvUBerkNoBpf1IeVmZUwGPdefaEjHThUEG4l6jMD5fszYWvAZv7Zz', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(201, 2, NULL, 146, 'Ashokkumar K', 'Ashokkumar', 'K', NULL, 'subscriptions@rgbdesigners.com', '2021-12-02 14:50:34', '$2y$10$M2Ocnindn15R0Rwidxd64O7EWgT8i6D7.4.PDd9oQC0rhaxnnvQju', NULL, 'client/1/user/201/avatar.png', 'Q1s6bt5zz6W7qm26dD6cY92M0qD8tioZcc4N3DL4wiTSpMhQuO1vPcnXU6LK', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(202, 2, NULL, 147, 'Rubén Lara', 'Rubén', 'Lara', 'ES', 'ruben.lara.martinez@gmail.com', '2021-12-02 20:47:59', '$2y$10$omZeHOKKRFhcRNxt9WnYKOaIHzJHY2fG3t5bHhlHUlGzYIclVosxS', NULL, 'client/1/user/202/avatar.jpg', '8SeZur9f3L6l1xuozNMqW7G2cgvorLRL9rsTk2dBKa8Dr1dwnVp7UjrDtTs7', 1, 'BVdml5b6j6McdYzj', 'ventas@soyguapo.com', 'AU3_itI2ZucZm8w01hOwxfdHEmv4cq4bseyoA5avpmDMYW-TBtwyukeRG6mfKm2gQue8cTL7puUyXH7k', 'EG0oMw5yROBx7ZS2-T76qvxkkxRinmf5K3-zMqYCUjBRaik1aAn9xhToNi0efe7vPMpVhK6qeCQslpHI', NULL, NULL, NULL, NULL, 0, '2023-03-18 14:47:15', NULL, 1),
(203, 2, NULL, NULL, 'Dat Nguyen', 'Dat Nguyen', NULL, 'US', 'dpn11+2test@googlegroups.com', NULL, '$2y$10$im4n06dUOvvjDPKQDBFV5eGXSUZYvM149V5jxME44IkeMItyyDQnW', NULL, 'client/1/user/203/avatar.png', 'qSrEMteGzaYA44qZ8T64vARMYDDnM2O0VonlrA5IxBzQbAYb05RVd6FKgaWB', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-03 00:27:42', 0, '2022-10-13 13:19:41', NULL, 0),
(205, 2, NULL, 148, 'Chigozie Udumaga', 'Chigozie', 'Udumaga', NULL, 'conekconsulting@gmail.com', '2021-12-03 16:30:33', '$2y$10$H7BayEc91BbHPmmeQrIQUuHvt/saM3Z1L11hgyJOfiS2txuV0yxdW', NULL, 'client/1/user/205/avatar.png', 'YFpIm83snUN0zfmvLMcYKW78XTwmrfKKznYUMZwaa9gFgfwz2HLHqkes8Ejq', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(206, 2, NULL, NULL, 'Farooq', 'Farooq', NULL, 'US', 'pagefright@gmail.com', '2021-12-03 08:28:16', '$2y$10$rLVBpw.h4hS3KA3eeQwmY.GQ1W6fTY2pvy0Wce3r/UlZm/AGnN8ty', NULL, 'client/1/user/206/avatar.png', 'KZWZ3l2935livqlB4K4eCIuPuvj8NzwJJOywac7SPfD4X9Hf3zTrh04KnVjM', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-03 08:27:44', 0, '2022-10-13 13:19:41', NULL, 1),
(207, 2, NULL, 149, 'Ivan Choe', 'Ivan', 'Choe', NULL, 'ivanchoe@gmail.com', NULL, '', NULL, 'client/1/user/207/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(208, 2, NULL, 150, 'Ryan Thian', 'Ryan', 'Thian', NULL, 'drthian@gmail.com', '2021-12-03 11:18:03', '$2y$10$6.g8HfuuxpaH9kPvsPAS1efWWbupT4l.K8jMMez80RMx5mY/0299m', NULL, 'client/1/user/208/avatar.png', 'JpQF9UT8bG7JjeFhElevOTwocC74CnkFu8yN58omuz5F5jgwjfnO1WaS325Z', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(209, 2, NULL, NULL, 'Webident Digital', 'Webident', 'Digital', 'AU', 'hello@webident.com.au', '2021-12-03 15:44:34', '$2y$10$wJR.QQJxz7DodDD.L3AWWOsQ7dRM6dIPciJBxPIwIz39grrlla0Te', NULL, 'client/1/user/209/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(210, 2, NULL, NULL, 'Daily Mind', 'Daily', 'Mind', 'GB', 'dailyminduk@gmail.com', '2021-12-03 19:19:07', '$2y$10$WuMtR0naFZ5zAaJ/nFVhzuD1N3QDaBM/7tFT55MpxVy1I1yz8FuhO', NULL, 'client/1/user/210/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-03 19:18:42', 0, '2022-10-13 13:19:41', NULL, 1),
(211, 2, NULL, 152, 'Purchasing Agent', 'Purchasing', 'Agent', NULL, 'connect@fangmedia.com', '2021-12-04 08:52:28', '$2y$10$nC4INrqrQ/a9FZEmUM72l..AoaLw8D63ZQd9ksQ5K7paHt1N/wNh6', NULL, 'client/1/user/211/avatar.png', 'YRXmElNvwYTJWSMXyJXTfVrXIrkg7gLtTsyGckpuSQ7fjbSg6Dq7kCeX4JPA', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2024-01-12 19:51:18', NULL, 1),
(212, 2, NULL, NULL, 'shahana tasneem', 'shahana tasneem', NULL, 'US', 'shahanatasneem001@gmail.com', '2021-12-04 11:55:50', '$2y$10$RQZS8OwZ/KmaClIlyvyb9u7B3l97ZNoUWsC1bilT4peRwg/Gg6glS', NULL, 'client/1/user/212/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-04 11:54:39', 0, '2022-10-13 13:19:41', NULL, 1),
(213, 2, NULL, 153, 'Mario Jäckel', 'Mario', 'Jäckel', NULL, 'apps+subshero.com@externadmin.se', NULL, '$2y$10$G6Fx4Y6K0KTB6iZUUx1DOujcoU2F7CBHqJ39Bwe4e4kgfWPCFeCfa', NULL, 'client/1/user/213/avatar.png', 'vZ0c6vpNO4kYorSbhNFFNymCDYNNP1W2dxyxEpQl0eAjMoypKPpgezn9qaom', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(214, 2, NULL, 154, 'Matthew Binder', 'Matthew', 'Binder', NULL, 'matt@xmattx.com', '2022-10-31 03:14:09', '$2y$10$R/H2dxm.YofSVrJz9/FDqeviwkD3kxWM1FgliDXN4m/CsVXxuTQUO', NULL, 'client/1/user/214/avatar.png', 'auygOmT2SlpnreTqW77H1vhua8uOwaQ0gbSfZ9vUzBdTLrz1pMcy6zxSENg0', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-31 03:13:06', NULL, 1),
(215, 2, NULL, 155, 'Jonathan Blouin', 'Jonathan', 'Blouin', NULL, 'info@hopsocial.ca', '2021-12-04 18:37:35', '$2y$10$0w0t4WgY53gycc9E/pj6eu4eNYXrVkAX1q3hevpGgo1yYl0FP8kLO', NULL, 'client/1/user/215/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(216, 2, NULL, 156, 'nicole gennetta', 'nicole', 'gennetta', NULL, 'admin@heritageacresmarket.com', '2021-12-05 16:15:37', '$2y$10$BkUGKAcK/Y8bvagjFBX.Suz4c0DhPUjF2lfaYrD07gUN9jrGw5fSG', NULL, 'client/1/user/216/avatar.png', 'uvTWFWqdzKMAIF0rm0idD3Y3Zq8rVOuAHKXPl5G1rbqcoXS3mopPOJtomD2D', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(217, 2, NULL, 157, 'FENG YIN TSENG', 'FENG YIN', 'TSENG', NULL, 'fengyintseng@gmail.com', '2021-12-06 10:00:16', '$2y$10$lMXBxubMYRj6VLH0UTTSNOBmMKH5TMDpcBDnptN05alT959OyKq/C', NULL, 'client/1/user/217/avatar.png', '5aoJ54yM7mi3rNDu3bbpAPGGuVTyV7sAnd1mwDYb5goeRtp3VljzSIgut0hx', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(218, 2, NULL, 215, 'Puwapat Pongpichtaya', 'Puwapat', 'Pongpichtaya', 'TH', 'kumphee@gmail.com', '2021-12-06 14:28:41', '$2y$10$5KrDe5jzrvhSHi0nlxHwsukXa0UOg9EbdUwGb3ecU7qXzwlQ2qmA2', NULL, 'client/1/user/218/avatar.png', 'fHScGxo8SLYKgBV5moUtIc9mPVeneatb2EpKGlrayFUCIpQsPYKX3K1XitUe', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-06 14:28:22', 0, '2022-10-13 13:19:41', NULL, 1),
(219, 2, NULL, 158, 'Francis Rios', 'Francis', 'Rios', NULL, 'frios@multiriver.com', '2021-12-06 15:53:40', '$2y$10$u2XLIQDgcMMb/AaG7lAzbeBbjD04h3OWVDVEG.gdSV6CqIBKBTsxO', NULL, 'client/1/user/219/avatar.png', '13GBpDO0fO33g2JVFp5xJL6hle9GOKwOCKSwgBIoGKXZDpKyFyg623pR7G5Z', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(220, 2, NULL, 159, 'Pankaj Khangwal', 'Pankaj', 'Khangwal', NULL, 'pkd12274@gmail.com', '2021-12-06 14:49:48', '$2y$10$FnnuxCj7J1uxr/xQujkKC.Ug4BajWHTa0lVejQZJQdwf2hoI8rSaK', NULL, 'client/1/user/220/avatar.png', '8W5HhqDQO5GuFvXWBkw6VZOWeMIsNWmMs74uT3MztjXPHDQJNfxd0ieOwgTQ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(221, 2, NULL, NULL, 'Aditya', 'Aditya', NULL, 'US', 'saioffering@outlook.com', '2021-12-06 16:29:52', '$2y$10$UJyXg60Gj8yEM7Pxt55zw.5zrOdpA/jsK/VEFLWL9qJ6vf4plXBvG', NULL, 'client/1/user/221/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-06 16:28:30', 0, '2022-10-13 13:19:41', NULL, 1),
(222, 2, NULL, 179, 'Dan Smith', 'Dan', 'Smith', 'CA', 'dansmith49@pobox.com', '2021-12-06 18:50:02', '$2y$10$JMcSz8fDoBs6GY2w9XcKjOMPWO1Czbz.EafGwA0UbVuyvyY5Yn2Wa', NULL, 'client/1/user/222/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-06 18:42:10', 0, '2022-10-13 13:19:41', NULL, 1),
(223, 2, NULL, 160, 'Hristo Kolev', 'Hristo', 'Kolev', NULL, 'saneofficial@gmail.com', '2021-12-06 22:25:12', '$2y$10$01wCIpMGbkPjH2TMR2as9.xS7c7OJBpl53jZt6kbTwTnpZkCwSIli', NULL, 'client/1/user/223/avatar.png', '0EYSbeNb29mz9LaxoQrW6W56XPDpEcmi9FrP8seWArm6dtbt8SGsaifTfEo3', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(224, 2, NULL, NULL, 'Sam Ung', 'Sam Ung', NULL, 'US', 'san.ywwp7e@daily.paced.email', '2021-12-07 13:49:09', '$2y$10$wdnK0PUbgh83wW6egyp0zuJkt/iq5PcQKKT0EMiTdDsnPLzrN3zMS', NULL, 'client/1/user/224/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-07 13:48:19', 0, '2022-10-13 13:19:41', NULL, 1),
(225, 2, NULL, 161, 'Gideon Leong', 'Gideon', 'Leong', NULL, 'leonggideon@gmail.com', '2021-12-08 07:49:27', '$2y$10$rJHvpfD0czYNevXqmJAY/u.0lpzHNq7l4qDVhzB.FrFkCdwDHvuCS', NULL, 'client/1/user/225/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(226, 2, NULL, 162, 'Tae Yun', 'Tae', 'Yun', NULL, 'tae@mmedia.us', '2021-12-18 06:22:39', '$2y$10$Ibp9vJW9zV7DV5G7OdY54e8.71d7QnuSHTMeVwuw3jLfKAvgArFQu', NULL, 'client/1/user/226/avatar.png', 'yMrDyEKg0d1VDrjBtJDWlSQOy7K8gTU49TIJWUItXKngcbtnblKBbSBK3ZjM', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(227, 2, NULL, 163, 'Erdal Bayram', 'Erdal', 'Bayram', 'US', 'erdalbayram@gmail.com', '2021-12-08 18:20:39', '$2y$10$/Gkifiuk4COg4.1sSIetKuFfWu35NJi23Yhfl13WeRS4isPZKSeUe', NULL, 'client/1/user/227/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(228, 2, NULL, NULL, 'Master', 'Master', NULL, 'US', 'magic.zoon@yahoo.com', '2021-12-08 19:23:20', '$2y$10$4id/0UbDN33E3Fr7ve1mmOR2EXIVPGUABgxy/hjLr7GYfoADo3a1e', NULL, 'client/1/user/228/avatar.png', 'BZnYaV2dNJfnI0EqPObWwOD7ComoRChF6ZZyJfagahFgH0CPhsXFuQwRHCKq', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-08 19:22:53', 0, '2022-10-13 13:19:41', NULL, 1),
(229, 2, NULL, 165, 'Terrell Ramsey', 'Terrell Ramsey', NULL, 'US', 'Terrell@ambitious.cloud', '2021-12-09 05:39:31', '$2y$10$8YtGhpz5wx8Cn477D/d.Ueh1PvRbNbhp4whxcxCm3HjzLgc8z2xvm', NULL, 'client/1/user/229/avatar.png', 'WqHlR3wAft2YHVMGjwAvJfW4uZMwhdXAsEk5LFe3QjAAoLVRzycFBaCDYTav', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-09 05:35:37', 0, '2022-10-13 13:19:41', NULL, 1),
(230, 2, NULL, 166, 'Dean Player', 'Dean', 'Player', NULL, 'dean@dino.media', '2021-12-13 14:41:11', '$2y$10$wAq5qULS1o8sG.hs0IjhY.ars05iutABVbQJm3ngc06CVZBtPYJ9C', NULL, 'client/1/user/230/avatar.png', 'M0RnMfF4nONqG6X6cN1idp6zJDinukgSSuEzip8y4zu43YnTIoljAzLnpLwU', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(231, 2, NULL, 167, 'roxana rodriguez', 'roxana', 'rodriguez', 'AW', 'roxana@dushirox.com', '2021-12-09 10:38:57', '$2y$10$sXDe8I6Nrq.3nSwUj7/Ex.kd/h8DSU4.fpgFwdl3n5Klv/h5G7vn2', NULL, 'client/1/user/231/avatar.jpg', '3D4H1V6iqLslyv8gISOug86E9T3tPeCTVSba2pV42GnO8uyweORKNvHTNaCx', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(232, 2, NULL, 168, 'Raj Esh', 'Raj', 'Esh', 'AU', 'Rajesh@autax.com.au', '2022-01-08 09:08:12', '$2y$10$SSg8UmplX9vijFMSIMXpf.s9tG9nVqPEt2T9MTsfUkPP/OlIzlH3u', NULL, 'client/1/user/232/avatar.png', 'siMoVauhXZwRF7Rys4atbyfntEl28jfMKg72tZJHcq2JpnfnxJg4siU5ewIK', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(233, 2, NULL, 169, 'Phil Good', 'Phil', 'Good', NULL, 'jaxnnux@gmail.com', '2021-12-10 04:15:35', '$2y$10$CcnU.v3hWnNI8PPVjqJic.UcT57yb3tU5Tj2HSibBLl51AV1mWxLu', NULL, 'client/1/user/233/avatar.png', 'oX85BngbXez3WC6Dlk8uLYDCmMNfONDMoqsfvyoZCBOEfesgCcOquTD42Xx8', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(234, 2, NULL, 170, 'Desmond Ng', 'Desmond', 'Ng', NULL, 'desmondng528@gmail.com', '2021-12-09 13:40:22', '$2y$10$FyDMSARaRwywlMwyf9/nyeqVa8rT3PL1RqRz8i/LExt596DgoMSpK', NULL, 'client/1/user/234/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(235, 2, NULL, NULL, 'Avtar', 'Avtar', NULL, 'US', 'subshero.ac4606@nicoric.com', '2021-12-09 16:45:59', '$2y$10$xGvm17IC5qW7usR60OMxT.Ir.TC.IC3TGAZp3bwrpwXngcJ6MdLxW', NULL, 'client/1/user/235/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-09 16:45:19', 0, '2022-10-13 13:19:41', NULL, 1),
(236, 2, NULL, 171, 'Mandar A Sahani', 'Mandar', 'A Sahani', NULL, 'sahanimandar@gmail.com', '2021-12-09 18:31:28', '$2y$10$Tw1f7mRf.L428eYPxrhMFu0EsC3NHiySRB8oqqPIrlLS2Fg1W4MpS', NULL, 'client/1/user/236/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(237, 2, NULL, 172, 'Mark S', 'Mark', 'S', NULL, 'relationshipfoundation@protonmail.com', '2021-12-10 02:43:44', '$2y$10$t7.i1dzWRRHRV/spkjDFI.iDCkmgx77YjjQURJEz06Di/9dw3zCma', NULL, 'client/1/user/237/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(238, 2, NULL, 173, 'Gene Rebell', 'Gene', 'Rebell', NULL, 'rebellgene@gmail.com', '2021-12-10 05:05:00', '$2y$10$7YbklKDQnzPJoEUZdhHpJehLeM21x4lCcUqCYGHzaUcRkEUegSAhu', NULL, 'client/1/user/238/avatar.png', 'VBJOHfI61YcKfoAvCjMzPzdweMjSVye7FTE2Vc3odp369JCdTnDVwOJTVRXn', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(239, 2, NULL, 174, 'Martin Mate', 'Martin', 'Mate', NULL, 'martinmate@gmail.com', '2021-12-10 23:05:18', '$2y$10$OHoC2DzbEGN6oEEvywBhvuOF3c9UY646zRZ3H3fdC3mHawCLJz52O', NULL, 'client/1/user/239/avatar.png', 'sxUNWapATz8WwJQK1FI6PJn9B3hcBEgPM6m78aNJyL1CRkluGjWos3qilzeg', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(240, 2, NULL, NULL, 'Kai', 'Kai', NULL, 'US', 'trang.social@gmail.com', '2021-12-10 17:05:37', '$2y$10$O0qpR6AWcTxR85lbDYfH0.eC0ZGkmWviYTDb5FlVPdPpRXukbb8rm', NULL, 'client/1/user/240/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-10 17:05:11', 0, '2022-10-13 13:19:41', NULL, 1),
(241, 2, NULL, NULL, 'Kevin Mk', 'Kevin Mk', NULL, 'US', 'mkev07@gmail.com', '2021-12-10 18:12:12', '$2y$10$MzBdHHwSJRO730QUnwxXYuMXFj3JxkQvPPNhCc9z74gr1dbhPTzvm', NULL, 'client/1/user/241/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-10 18:11:53', 0, '2022-10-13 13:19:41', NULL, 1),
(242, 2, NULL, 175, 'Hachim Benddane', 'Hachim', 'Benddane', NULL, 'benddanehachim@gmail.com', '2021-12-10 18:24:50', '$2y$10$zP0cEIPzR25y1BjPRFQtJu8wyDb7gX.KFLq4JvxccJewaBq0Kayd.', NULL, 'client/1/user/242/avatar.png', '7hcdUa24i4LyucJCJeYSPDllndYr1HfzuGN7jCd6zSLEOEtMEvU6FmkoFZoa', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(243, 2, NULL, NULL, 'lao', 'lao', NULL, 'US', 'lao207@outlook.fr', '2021-12-10 18:46:19', '$2y$10$hetncfqtioio/MVTV/ztduPanpnKCXoosR03VVkoBHMaNi9dMfTKe', NULL, 'client/1/user/243/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-10 18:45:32', 0, '2022-10-13 13:19:41', NULL, 1),
(244, 2, NULL, NULL, 'Joan', 'Joan', NULL, 'US', 'joanw2002@gmail.com', '2021-12-10 18:47:25', '$2y$10$/KpZYcZoGGlCaMaEz3Y81ebgSCqBIVMObiru6pLiuCQ9am4L97XD2', NULL, 'client/1/user/244/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-10 18:47:02', 0, '2022-10-13 13:19:41', NULL, 1),
(245, 2, NULL, 176, 'Hue Truong', 'Hue', 'Truong', 'US', 'hue@arcticfoxstudio.com', '2021-12-10 18:57:23', '$2y$10$mVFRVyFyyv5Gw12C080Vi.bbuOHqHZvoRCP2lR8UGe42HiNPkpeky', NULL, 'client/1/user/245/avatar.jpg', 'eUj8xOTiOPHwMDGmkyn2TnPOSz7vVfRnAq6WPf4q4cL6WyyF78FvmxnpMaa0', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-04-04 16:54:56', NULL, 1),
(246, 2, NULL, 177, 'bounsou kham', 'bounsou', 'kham', NULL, 'tiers.system@gmail.com', '2021-12-10 19:26:11', '$2y$10$Y0GQtsO7leWH51U2NvOxJukoMs3T32G86DxKKvHIUqtPRCV.3TW3i', NULL, 'client/1/user/246/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(247, 2, NULL, NULL, 'Kourosh', 'Kourosh', NULL, 'US', 'kourosh.jj@alldotted.com', '2021-12-10 19:45:46', '$2y$10$k29c1lqjQTYHyXt6mVzTMu38OiLeV7o5872CwgDRwY8voawMbYPxO', NULL, 'client/1/user/247/avatar.png', '5YlljpZMEL3sjd166Te850gzBGMWjw2JplBm8B7C5bEd4bdSL8GjNHmBit6G', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-10 19:44:52', 0, '2022-10-13 13:19:41', NULL, 1),
(248, 2, NULL, 178, 'deeb ghanma', 'deeb', 'ghanma', 'JO', 'dghanmma@gmail.com', '2021-12-10 21:05:52', '$2y$10$G8d2PGuR2FquyUwLP9yEt.t5OsIZ3UbseN9SFTIThM228nEGQxdqm', NULL, 'client/1/user/248/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(249, 2, NULL, 181, 'Martial VIOT', 'Martial', 'VIOT', 'FR', 'martial.viot@gmail.com', '2021-12-10 22:36:29', '$2y$10$HoLsCHvYgUjqZBVXeJy5x.pI5Z/.DhB1YzFBU1j.5IGG3irlJyLLa', NULL, 'client/1/user/249/avatar.jpg', 'URIdqUuWeWClfWfLNti9W98DiulFUQtwWoWMDbs1QSSwTGWUtJuDfpmay4t2', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-10 22:36:15', 0, '2022-10-13 13:19:41', NULL, 1),
(250, 2, NULL, NULL, 'Frederic', 'Frederic', NULL, 'US', 'contact@wpexpert.ca', '2021-12-11 02:38:32', '$2y$10$6QsE.zRsxtZPabUG4bXwCOXqHmzyKKW6fbTSjlZ7Prke30aOhlEfa', NULL, 'client/1/user/250/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-11 02:38:21', 0, '2022-10-13 13:19:41', NULL, 1),
(251, 2, NULL, 180, 'Farvin Kadir', 'Farvin', 'Kadir', 'MY', 'farvin@hey.com', '2021-12-11 04:03:15', '$2y$10$ID/yRgcxTqUZaOdeWHW0oeF58etSIHKLCpQjBRSqfhBiG87XJMG1O', NULL, 'client/1/user/251/avatar.png', 'R5fZ8hS76lJeQUAfSDOMCOUrsVAVQDMvJ1Zf7yweMf5g0fnYGfkJ1sUCMC9z', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(252, 2, NULL, NULL, 'Trevor Nicholas', 'Trevor Nicholas', NULL, 'US', 'itrnicholas@gmail.com', '2021-12-11 04:06:27', '$2y$10$dbU3RiSAOdyfeNyRrmMKWunqySl6567cQo5xDEV8MMGJstdC88f/C', NULL, 'client/1/user/252/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-11 04:06:00', 0, '2022-10-13 13:19:41', NULL, 1),
(253, 2, NULL, 182, 'Alvier Castillo', 'Alvier', 'Castillo', NULL, 'alvier@gmail.com', '2021-12-11 16:24:13', '$2y$10$T857JKmR6zkmho2MafT2WewBl2lhQFqsGVW8oXiAxPXWKg4mMhji2', NULL, 'client/1/user/253/avatar.png', 'jaLJtHLysBQc5TwBYZbBB7TDwHLLMuHTk1SdKf5hG5VIEh6dxqOeFAxeZaeu', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 07:12:55', NULL, 1),
(255, 2, NULL, 226, 'Ankit Sharma', 'Ankit Sharma', NULL, 'US', 'webgyan.info@gmail.com', '2021-12-11 18:36:06', '$2y$10$9nXBedi/fC3XN.umLTDGu.ieFPIe0O1apwwSO3v7cYJ.xN0ThyJJG', NULL, 'client/1/user/255/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-11 18:35:42', 0, '2022-10-13 13:19:41', NULL, 1),
(256, 2, NULL, NULL, 'martinnn', 'martinnn', NULL, 'US', 'martin@hotmail.red', '2021-12-11 19:03:09', '$2y$10$FjFelAeTLbyT7NaPxDiXWOyHY7.isuZT.1NXjdxNOFL2G0s/2FvF2', NULL, 'client/1/user/256/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-11 19:02:41', 0, '2022-10-13 13:19:41', NULL, 1),
(257, 2, NULL, 183, 'Nicolas Moisset', 'Nicolas', 'Moisset', 'FR', 'nicolas@moisset.net', '2021-12-12 13:50:10', '$2y$10$2SEcFugmN.qOviobn2RzkefHoXubMMLtIzAZPT5ooNgXKkCxPdIg6', NULL, 'client/1/user/257/avatar.jpg', 'RFZvqFeiyIBsQPsaPmCys2GhiNF6oUpQJB05ahLujHyk5ikBanLy65HwjyVA', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(258, 2, NULL, 234, 'Mahmoud Imran', 'Mahmoud', 'Imran', 'EG', 'mahmoudimran3@gmail.com', '2021-12-11 20:15:18', '$2y$10$WfFaK3A2Dj7s5h49AjzjAOw2H1gGeiLy54JOVSmoVeWdcOlWjsKr.', NULL, 'client/1/user/258/avatar.png', 'Tpe082TfzIPCNKFxoDgs0n3tE4KrKgcC4WXltU4rOz6IQi0f7GejDWXt9dwx', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-11 20:14:54', 0, '2022-10-13 13:19:41', NULL, 1),
(259, 2, NULL, 184, 'Olegs Alohins', 'Olegs', 'Alohins', 'NO', 'olegdcpv@gmail.com', '2021-12-11 22:36:57', '$2y$10$UTJKvPMs27X3t7D7fT1U5.VS1/4wOsnVizhzRQ/l41SE4F8Q8lHXi', NULL, 'client/1/user/259/avatar.png', 'LvawlXa4TzP6l9IuhzkJXQt61RZAD3KL1xLIMKtcpyEsOwETNd2zrKJ6Ibc7', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(260, 2, NULL, 185, 'Brian Grider', 'Brian', 'Grider', 'US', 'omnimediacompany@gmail.com', '2021-12-12 04:14:02', '$2y$10$t.F1/XoY5iw.WW.RE6Pm8ukiTqieSIa2Zc8WoJGP56IKqDi71c1cK', NULL, 'client/1/user/260/avatar.png', 'MsiFfA4AmGYeqNGJ5pRW0Owph2kYBkLvZaWPw3LfdV2jMtngZ4ZTKJl9sIwL', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(261, 2, NULL, 186, 'Avtar Singh', 'Avtar', 'Singh', NULL, 'avtaar.official@gmail.com', '2021-12-12 11:28:09', '$2y$10$Ray4Z.zhMGfWPI3iDIQaWOvD0o2PeSAu1XPxeGqE.WzWDhfDeIAHC', NULL, 'client/1/user/261/avatar.png', '8nUxtH6MLJoD6iFnatBxLLk66o7Rxc4Qw4VlgKVR2DUlXV4iPs3AQmyq9AuV', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-03 05:02:12', NULL, 1),
(262, 2, NULL, NULL, 'Robert Kok', 'Robert Kok', NULL, 'US', 'robertgkok@gmail.com', '2021-12-12 13:29:26', '$2y$10$e9bvtAzPaarKJ0QkGzL3peVb3Z/zvrkzu5dU022AVd1E48uEX2C9C', NULL, 'client/1/user/262/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-12 13:28:25', 0, '2022-10-13 13:19:41', NULL, 1),
(263, 2, NULL, NULL, 'Rachel Hardy', 'Rachel', 'Hardy', 'US', 'trout009@zoho.com', '2021-12-12 15:38:33', '$2y$10$Ehvfaw8hpvF0Dn0r/EUjweo1p0LKBm9TSjP71UhkFazs3c4ktqL8u', NULL, 'client/1/user/263/avatar.png', '3cGJvrV80QPrnQeHL4EwTHyTQSB54YBQpIJ5sBE2uc8cX3pTG308WNBG4EWD', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(264, 2, NULL, 188, 'benjamin PIERRE', 'benjamin', 'PIERRE', NULL, 'bpierre@ateliers-achats.fr', '2021-12-12 17:08:58', '$2y$10$Zqt.fDiqHSXRXySNZn68mu8CYtME9PiXcJduLGjLQwg1v4Ufze.Pu', NULL, 'client/1/user/264/avatar.png', 'eQlIETQe5K4Fap0O7LsAQgqGk1NpFkrNXTRseqx2uWci5ukE7if92nwaZOSj', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-14 04:09:17', NULL, 1),
(265, 2, NULL, 189, 'Muhammad Azwan Arjan', 'Muhammad Azwan', 'Arjan', 'MY', 'aerniescreative@gmail.com', '2021-12-12 19:05:01', '$2y$10$qu7WDscRdTIrrZYSSkjIpOi0dJrk76dSXH83cERiNUX0c2gQdljkq', NULL, 'client/1/user/265/avatar.jpg', 'ipQLDV5CjJ1UrrtAfiVVi7uZBBm5Aglhuwla0YldtttD4FADH62ZF2Ywhfre', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 06:21:37', NULL, 1),
(266, 2, NULL, NULL, 'JJ', 'JJ', NULL, 'US', 'jamesjehar@gmail.com', '2021-12-12 20:00:58', '$2y$10$U3PR0uyQkXrbTMKgLz45/eUnFQyd8S3NmIJSBEWSHe5z3X1Cy5BVi', NULL, 'client/1/user/266/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-12 19:59:38', 0, '2022-10-13 13:19:41', NULL, 1),
(267, 2, NULL, NULL, 'Lincoln Islam', 'Lincoln Islam', NULL, 'US', 'lincoln.aca@gmail.com', '2021-12-13 04:44:15', '$2y$10$HfA9JqQqW3lFqO5hFiTrse6CwjyspCIoCNmymEaC2a0B4GRgIks0y', NULL, 'client/1/user/267/avatar.png', '0znwNVhf4sMdo70HYXD76P0iBR4RcCxofshAERqgsbswAExd3FrpPylwneLo', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-13 04:43:37', 0, '2022-10-13 13:19:41', NULL, 1),
(268, 2, NULL, 190, 'Alban Taraire', 'Alban', 'Taraire', NULL, 'alban.taraire@k5technology.group', '2021-12-13 05:55:14', '$2y$10$4VMNWxGWsKRaoWiM8/jV8.x6tO4FlvyFGV.C5r9ygE2MiUcS5s4Pa', NULL, 'client/1/user/268/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(269, 2, NULL, 191, 'A Good World LLC', 'A Good World', 'LLC', NULL, 'vineet@teliportme.com', NULL, '', NULL, 'client/1/user/269/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(270, 2, NULL, NULL, 'Vineet Devaiah', 'Vineet Devaiah', NULL, 'US', 'VINAIAH@GMAIL.COM', '2021-12-13 07:39:11', '$2y$10$ws7iNHZD3hjUELwLCNVemuVp19LHq7mqBAJZs2t.0D8qrzUYB0Xvi', NULL, 'client/1/user/270/avatar.png', '1oz5B4vqNvPF0mVsUy1Mvo6FEZb9DQbsbjhplyzn7CfSImns8FdW48XufZvo', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-13 07:38:58', 0, '2024-06-13 13:31:07', NULL, 1),
(271, 2, NULL, 192, 'AJ Rollsy', 'AJ', 'Rollsy', 'AU', 'aj@coremarketingmethod.com', '2022-05-16 01:09:15', '$2y$10$arR4cInkWO62dPW5yYI8zOBOuPxo86LCkTWPXO7HiaQJr9cpjYcdm', NULL, 'client/1/user/271/avatar.png', 'oEHMo4gbDxTDe8FiHFLrwbLdMfVPLbIT9LNC11Rvaba7BePIJhm5TkEuZ8dZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-11-26 01:54:01', NULL, 1),
(272, 2, NULL, 193, 'Kivilcim Istanbulluoglu', 'Kivilcim', 'Istanbulluoglu', NULL, 'k@noktala.com', '2021-12-13 12:02:59', '$2y$10$BTqM0.J5iIPce5vXmdttD.8n/TbZBwhMntueGusgc.jp5hCGf4gxq', NULL, 'client/1/user/272/avatar.png', 'd4HQ0bSDUmDVAT1Y7LCNxplU1YpCUE53ezk2D80tXDPm5QQ4gfqnLrqD9lNL', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(273, 2, NULL, NULL, 'George', 'George', NULL, 'US', 'gdimitrov.nft4hh@monthly.paced.email', '2021-12-13 16:13:12', '$2y$10$AvddlK/z/AwiwNcWc5LPa.EXxnYFptFyDeHbkU0S/XBDiIg76dK5y', NULL, 'client/1/user/273/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-13 16:12:07', 0, '2022-10-13 13:19:41', NULL, 1),
(274, 2, NULL, 194, 'George Dimitrov', 'George', 'Dimitrov', NULL, 'workdesigneu@gmail.com', '2021-12-13 16:17:31', '$2y$10$HgG3ePejAc2fbFG6d1WrZ.7kA6fgiQ0cQqbdHD0RhnEqbVwEGd9Ya', NULL, 'client/1/user/274/avatar.png', 'HOztr05qRO7TLg0l6J0ZH6fHGSOst6gduDv6rRF5wh29bdZEKjVXXFRu2lMm', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-11-12 09:48:01', NULL, 1),
(275, 2, NULL, NULL, 'Just S', 'Just S', NULL, 'US', 'subshero.justzen@9ox.net', '2021-12-14 01:29:41', '$2y$10$xSOrmEe5WfcNXsDKfdGkj.o1OuSjVQuDx6DmIx8xNZEuFCHIjyexq', NULL, 'client/1/user/275/avatar.png', 'Myh9wySV1aUzVYOF9Xzw8dXoK2IGrPBBzzARfF5deKfHxRWTLgtgZx6aKHRF', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-14 01:01:27', 0, '2022-10-13 13:19:41', NULL, 1),
(276, 2, NULL, 274, 'Anil Agrawal', 'Anil', 'Agrawal', 'US', 'aagrawala@gmail.com', '2021-12-14 04:48:29', '$2y$10$StHxCo6D5ys0lBt2/OeSa.k0LK6TQUbg1fA8MBjxPaNyHjDoQWPGq', NULL, 'client/1/user/276/avatar.jpg', 'HA9RrY7ZiHeuH9Afx7xGSgW7n7sj5SJET7DokhMv21vUgxG9aEWGd4lC4dfr', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-14 04:15:37', 0, '2022-12-03 05:01:53', NULL, 1),
(277, 2, NULL, 228, 'Bernard W', 'Bernard', 'W', 'US', 'redimitube@gmail.com', '2021-12-14 05:54:09', '$2y$10$2Cuhu9cZdkF6r11HbXP9W.2onpBwUZ2liTIL/zXznY15s5Q0iEZfC', NULL, 'client/1/user/277/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-14 05:53:37', 0, '2022-10-13 13:19:41', NULL, 1),
(278, 2, NULL, 195, 'Hà Tuấn', 'Hà', 'Tuấn', NULL, 'onlinemarketing101.rob@gmail.com', NULL, '', NULL, 'client/1/user/278/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(279, 2, NULL, 196, 'Mark Su', 'Mark', 'Su', NULL, 'contact@mark.su', '2021-12-14 10:58:41', '$2y$10$hSvqRuWarXBu2nRpHuQuA.edoNKJD6QEPkr1xNFxMZJT/U8kFG3vO', NULL, 'client/1/user/279/avatar.png', 'ctdkS8SkPxCeOo6GdUlnnMLZdL9o5jSZWA9mv4lpL8axjUyypnzqW7Qmxtpz', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(280, 2, NULL, 197, 'Vishal Lamba', 'Vishal', 'Lamba', NULL, 'vishyim@gmail.com', '2021-12-15 12:00:43', '$2y$10$PI2VEFTxqUrGgsVjH0KHD.3X1uF9Tx5BZRvuBadNpF5qjMtcxGOX6', NULL, 'client/1/user/280/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(281, 2, NULL, 198, 'Tony Antony', 'Tony', 'Antony', 'IN', 'tonyantony@gmail.com', '2021-12-14 13:03:54', '$2y$10$SCg.z2S7JexCNCO3.x/zNergZvWMv6dHhC7bGVPjWH46HqgRWJVAq', NULL, 'client/1/user/281/avatar.png', 'lqMMdCnJBsV5wQjq0n4BQfafX9irk1Fodoz7HYgi1HEqz3AGgBchkSn8lFZb', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-08 05:43:09', NULL, 1),
(282, 2, NULL, 199, 'John Holdsworth', 'John', 'Holdsworth', NULL, 'jholdsworth@outlook.com', '2021-12-14 14:23:41', '$2y$10$5m6UILMkAK4by.QSLPV8eu32OyuLWwJV3yLvfYOkeo1yJMAcCZ4Ey', NULL, 'client/1/user/282/avatar.png', 'hMDwLus0exMGHum6POa45zMjiMdLmKloLbfkdeE1TuH9PYAjPU0uI0ecHbJJ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(283, 2, NULL, 164, 'Salim Khan', 'Salim', 'Khan', 'BD', 'mdsalimkhan712@gmail.com', '2021-12-14 15:38:01', '$2y$10$5u35WqCDQ1S9RHBfv79rBeOUVcyWFsZjYRiufbCckbWafOr7DGzSS', NULL, 'client/1/user/283/avatar.jpg', '8fD7lUr4N5JkL13HRVTntUYimAr4P677P6V4EMRFwN79nIQaucEVt6ABQbRZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-14 15:37:25', 0, '2022-10-25 05:31:09', NULL, 1),
(284, 2, NULL, 200, 'Amgalan Bat', 'Amgalan', 'Bat', NULL, 'solurise@gmail.com', '2021-12-14 16:00:22', '$2y$10$mkrcnmynxDL.N4V6f.pJpulqWncuV8N2Zovsv5DMCco18v5WSG8Y.', NULL, 'client/1/user/284/avatar.png', 'Ay6tNjEJGh02k3HltWltBpF0mLoZaH7vd0BWiyzKfLF0TECg9xSNuvyYOGVk', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(285, 2, NULL, 201, 'Ioannis Giftakis', 'Ioannis', 'Giftakis', 'GR', 'john.giftakis@gmail.com', '2021-12-14 15:59:20', '$2y$10$j8j4DF5kiRXuF7ntnFXpIuJtzaANclEsaFVcCHW0BPxbA88rtzObm', NULL, 'client/1/user/285/avatar.png', 'hLJbNZRzPwGvX2xrpShKXivn7Fy3HZ5BqWrmzhX4xqllTcTx2hFYL23Q5Xai', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(286, 2, NULL, 202, 'Marius Tyranowski', 'Marius', 'Tyranowski', NULL, 'm.tyranowski.1@gmail.com', '2021-12-17 09:53:19', '$2y$10$4UzyNCeVRx3TtpvdaQMnTeeKBC5bLHjjZ6fRH8IdA79C22duEUMmq', NULL, 'client/1/user/286/avatar.png', 'pCZxUPMpoOLiwRJRwDMk8Ex8h4DKn8DeCSDd4fFAvtT3BWofKq6kfzR7S3pT', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-02-04 16:11:17', NULL, 1),
(287, 2, NULL, 203, 'Jannick Nijholt', 'Jannick', 'Nijholt', 'NL', 'subshero@jannicknijholt.nl', '2021-12-14 20:37:57', '$2y$10$N.sbuFMtrb/8wPyNeeXXR.9zmMCa4rFlx52GB1asEiSh3l2GtJ9zK', NULL, 'client/1/user/287/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(288, 2, NULL, 210, 'Ragunaath Rathnam', 'Ragunaath', 'Rathnam', 'IN', 'ragu.techie@gmail.com', '2021-12-15 07:09:41', '$2y$10$Q0tolGnjjwmnnmCZKg0oNuh.oaa.qx85C54oWyw1Bc/.4Jbxi0Ycu', NULL, 'client/1/user/288/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-15 07:09:15', 0, '2022-10-13 13:19:41', NULL, 1),
(289, 2, NULL, 205, 'Norbert Enders', 'Norbert', 'Enders', NULL, 'norbend@icloud.com', '2021-12-15 07:22:32', '$2y$10$.OdPAj2lv49wpXvWonQPpOYsmgXkrnf3AWxnbIkLYh6pL4HW0jXX6', NULL, 'client/1/user/289/avatar.png', 'E3V9pkNoBMJmXS0TP7mq8F9aQG0HxqFsJRjs49212GRfSwDkuRPAk4BVJdvu', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(290, 2, NULL, 206, 'Berten Poleyn', 'Berten', 'Poleyn', 'BE', 'berten@designexposystems.be', '2021-12-15 08:17:40', '$2y$10$HVJD4Ic0SqRu.IRbI9FQv.ECqUVLL/1FPAqpBlILA64dFr0aKpPta', NULL, 'client/1/user/290/avatar.png', 'JrUKLwZ5efj5g3vIjqrGIjplQmHgA1nXXZzR91YL7uqsvTRdkWIHc0oaNH7M', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(291, 2, NULL, 207, 'Jacky Chiu', 'Jacky', 'Chiu', NULL, 'JackyChiu.Social@gmail.com', '2021-12-15 10:20:21', '$2y$10$SSLQfJlmeE3rZvOXS5dXwullXL/nY/b2cuF88LobmoP1OJy3RJDfm', NULL, 'client/1/user/291/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(292, 2, NULL, 208, 'Albert Tan', 'Albert', 'Tan', NULL, 'skstter@gmail.com', '2021-12-15 11:25:54', '$2y$10$Fq3vEuxNTy2fPwXMCPyd9uRZrqRYMWh8jz1BtT0cABZ2GO7KwCCXy', NULL, 'client/1/user/292/avatar.png', 'I94TqRorOkqLGKlErNY9xGMDVHwZv6LpwOh4sUma7Iz4QWYzmMWBWJXrMwE2', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(293, 2, NULL, 209, 'Jason Scully', 'Jason', 'Scully', NULL, 'info@theguidesites.com', '2021-12-15 12:18:16', '$2y$10$Ae4LIChVQOZxozFnuNYoJOttjAQ84FxKU4dl31dsV7UCjTgfq4DHi', NULL, 'client/1/user/293/avatar.png', 'kETbgZtIWgGPo07xkT0dtT6KRCMcd9lbLaXy8aDPCWykbztJPPBqXaipy8oH', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(294, 2, NULL, NULL, 'Adi', 'Adi', NULL, 'US', 'adi_149@ymail.com', '2021-12-15 12:44:33', '$2y$10$y7epBnYKIkHjQYGpyc8k3.SX28L1Gz9.1i5D5uvNmqZ1Yod1F3faW', NULL, 'client/1/user/294/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-15 12:44:11', 0, '2022-10-13 13:19:41', NULL, 1),
(295, 2, NULL, NULL, 'Egoist', 'Egoist', NULL, 'US', 'egoistry_unjovially@slmail.me', '2021-12-15 14:16:22', '$2y$10$QswoDGBJ2..SJPXJZFKRCesMDl.31HOJp2g3qxk/zJWEsYtm.PG2a', NULL, 'client/1/user/295/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-15 14:12:32', 0, '2022-10-13 13:19:41', NULL, 1),
(296, 2, NULL, 211, 'Jao Ortega', 'Jao', 'Ortega', 'PH', 'jaoortega.biz@gmail.com', '2021-12-15 14:14:55', '$2y$10$jAlB46KZQjeS.irg9Kcgb.yt8vB4vPCdWQMtCybwLYUgkLgdifMti', NULL, 'client/1/user/296/avatar.jpg', 'MgAeRiXAQ4xHF0UHU8znvLvVcQ3AzF9F4E56sJSgkJ3PQmcLgUnfkW4KAXiP', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-15 14:14:01', 0, '2022-10-13 13:19:41', NULL, 1),
(297, 2, NULL, NULL, 'Sfg Dfg', 'Sfg', 'Dfg', 'IN', 'lomew99362@swsguide.com', '2021-12-15 16:35:28', '$2y$10$ThmqsV/NHrPb5eXHvneOVuO1hpHk8nVyyhizFVx3nR5ZekrPSsoEG', NULL, 'client/1/user/297/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-15 16:35:10', 0, '2022-10-13 13:19:41', NULL, 1),
(298, 2, NULL, 212, 'Chris Esgate-Green', 'Chris', 'Esgate-Green', 'GB', 'bancroft28@gmail.com', '2021-12-15 20:11:44', '$2y$10$SkEeSdFOaAL5dFsgHjkjM.o92kiwrtMIH2FlBGq3j5VEd/yUdXqzK', NULL, 'client/1/user/298/avatar.png', '0DGRwYs99DjAq7NOEdObZxc17kLwLkYgMfgaNGzm3wR7uJSjbQwfRB8AM8Js', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-08-23 21:59:26', NULL, 1),
(299, 2, NULL, 213, 'Marius Ghitulescu', 'Marius', 'Ghitulescu', NULL, 'ghitulescu.marius@yahoo.com', '2021-12-15 20:33:02', '$2y$10$J6WcYQKAM9a/s3Mo4z0pu.mwmI4LsbX4EAysdEHzTjiVGM3txg4fm', NULL, 'client/1/user/299/avatar.png', 'qfUVhP6fFAlwZw0PeMUhdVnio6bRuuQzTCvELeBswt9mcmRkFbqmE2Bko5uk', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 09:02:46', NULL, 1),
(300, 2, NULL, NULL, 'Patricia Cupra', 'Patricia', 'Cupra', 'AU', 'me@trishacupra.com', '2021-12-16 03:25:15', '$2y$10$n0L3K3pFaAckDiCGlrlNj.L.6iJH7o1HfZlDzKYtcR1jVakW9IGDW', NULL, 'client/1/user/300/avatar.jpg', 'OsZPG6NOj85XDfGOW4HyHe0zhoVNeYlInGE11CUwK5cJiPTrj4ynjLbVbsqp', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(301, 2, NULL, NULL, 'cxexc', 'cxexc', NULL, 'US', 'sered57264@wolfpat.com', '2021-12-16 05:25:25', '$2y$10$SDtNaj4H5C.8FDgevFMZrumhqtxsT3XSWNK2.bj2oeVqkPYHy0NDG', NULL, 'client/1/user/301/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 05:24:58', 0, '2022-10-13 13:19:41', NULL, 1),
(302, 2, NULL, 216, 'Howard Aronesti', 'Howard', 'Aronesti', NULL, 'haronesti@hotmail.com', '2021-12-16 07:19:36', '$2y$10$tflqY9tB53t4WGBYamfmYu4z9PLx8lpYMDPQeIOQ1Rdiig7q12dzC', NULL, 'client/1/user/302/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(303, 2, NULL, NULL, 'Arjun Arjun', 'Arjun Arjun', NULL, 'US', 'arjunescabarte2017@gmail.com', '2021-12-16 07:35:12', '$2y$10$27N8fCVWzs9v8GIK3rgQNOJ0RdaojtdBQpgevMvOpNUV8fWTX01oS', NULL, 'client/1/user/303/avatar.png', 'zLCyOp97KIIY8bS4cBwOTxOjg1B6OQJq3VjJnPmL0ZRIywMv1TRyGQf229UZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 07:33:46', 0, '2022-10-13 13:19:41', NULL, 1),
(304, 2, NULL, 218, 'Ivica Delic', 'Ivica Delic', NULL, 'US', 'ivica.delic@gmail.com', '2021-12-16 07:44:32', '$2y$10$OzR/mqvMBEcgMZf5angzy.TxXpxCdXVXfddlc3LYFLyky.DPu.qQm', NULL, 'client/1/user/304/avatar.png', 'BkEo0fuG8PLFKWvkFVj2PQ0trJBC3qopDe2ITwtggE8xF2FxiTZ7zMNPrHm4', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 07:43:35', 0, '2022-10-13 13:19:41', NULL, 1),
(305, 2, NULL, 217, 'David Samuel Soltura', 'David Samuel', 'Soltura', NULL, 'davidsoltura@gmail.com', '2021-12-16 08:16:00', '$2y$10$S6dJWydI9hoQtoanVOkkNeb/k59rvCFRvAq08VkqgmNHN2i28EDfe', NULL, 'client/1/user/305/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(306, 2, NULL, 219, 'Giulio Detti', 'Giulio', 'Detti', NULL, 'webmaremma@gmail.com', '2023-03-15 12:17:35', '$2y$10$Q.vsoMHIyNp2KDkeYSR18eDu.LKJ6bcAAKCDftObQNORQ0ArZal6.', NULL, 'client/1/user/306/avatar.png', 'UHKdEWgtRN7S3aXtZzZPenC3xdE80ibrX9vpZLXZYdLXGyZN439GXmHhfgy3', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-03-15 12:16:52', NULL, 1),
(307, 2, NULL, 241, 'Ridhan Abdullah', 'Ridhan', 'Abdullah', 'MY', 'ridhan.com@gmail.com', '2021-12-16 09:21:22', '$2y$10$wwRIKjw4k3JfX6mkShe7g.8ZecCn5Kj4RRCdXNlNXU6gLfxxj8bcm', NULL, 'client/1/user/307/avatar.jpg', 'LTkPanvnh8g5k7Blca4CzOQLY29HGYtRTlUrekssTPZQ0ywUq5YjuDdiPoQm', 0, NULL, NULL, NULL, NULL, 'Web Creative Resources', NULL, '+60166688365', '2021-12-16 09:20:35', 0, '2022-10-13 13:19:41', NULL, 1),
(308, 2, NULL, 220, 'Lou Flores', 'Lou', 'Flores', 'PH', 'lou.flores@protonmail.com', '2021-12-16 09:41:16', '$2y$10$.aNl4DaUrkv8uuD.t.jRj.1pAsHZfKrje9vfrDftlUIGZqFayOcz2', NULL, 'client/1/user/308/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(309, 2, NULL, 221, 'MK CHAN', 'MK', 'CHAN', 'MY', 'mkchan@mkecm.com', '2021-12-16 10:14:12', '$2y$10$GxjghjEC3MW7nwDqSX45BOPx6/1MvdUGFMFSIccbTitZfGlAbK99e', NULL, 'client/1/user/309/avatar.jpg', 'RekTyOjJKvmaSTzecJyLNTe3nmUYlVndGXgZUuQJdkPTfjN5tYy2VWyKTqIq', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(310, 2, NULL, NULL, 'M Razibul Islam Razon', 'M Razibul Islam Razon', NULL, 'US', 'dr.razon@gmail.com', '2021-12-16 10:41:39', '$2y$10$MQ5BQTuwT5RjllnA4vahl.wDTazlHGpvEg5xnPLKotNLK21ROeqO6', NULL, 'client/1/user/310/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 10:40:43', 0, '2022-10-13 13:19:41', NULL, 1),
(311, 2, NULL, 222, 'Hafiz RAHMAN', 'Hafiz', 'RAHMAN', 'AU', 'shr31pals@gmail.com', '2022-01-22 06:08:58', '$2y$10$gn.DGlwrp1/A2EJOg//XmOMUkCFZfJhw6A0cX7IiJR9Bt9VHrkQgy', NULL, 'client/1/user/311/avatar.jpg', 'JYbTCSeSHvPajzCQVuLbBGISl9QyhG9izz45ND3widw53MtaCWESf0V6vifk', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(312, 2, NULL, NULL, 'Aidah O', 'Aidah', 'O', 'SG', 'dydx.inc@gmail.com', '2021-12-16 11:51:25', '$2y$10$yvP6wN5KqJxkZpLf3c.bMetxWav3zZ3iPGVYpyCLtfAtgg4jaNAhu', NULL, 'client/1/user/312/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 11:50:42', 0, '2022-10-13 13:19:41', NULL, 1),
(313, 2, NULL, NULL, 'arcensoft', 'arcensoft', NULL, 'US', 'fabrice.henry@arcensoft.com', '2021-12-16 12:02:25', '$2y$10$e7z9B129XyXoVTt4FMAxqeu/IDE/kHvkv.RB2msmkpJLB5lX5p6Sq', NULL, 'client/1/user/313/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 12:01:38', 0, '2022-10-13 13:19:41', NULL, 1),
(314, 2, NULL, 223, 'Lori Byrne', 'Lori', 'Byrne', NULL, 'lori@noaviv.com', '2021-12-16 18:47:49', '$2y$10$p0o/MXBbeuNiLrTtucESy.40ROaYQldcSbrU202Ou3QnM9B7SqEJW', NULL, 'client/1/user/314/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(315, 2, NULL, 224, 'AJ Gyomber', 'AJ', 'Gyomber', 'US', 'aj@foursightinteractive.com', '2021-12-16 13:29:48', '$2y$10$x98z6NZR9NJFbONBAzTNMe92QzbEv/fkRb3VYURsrSKASbHWd50q2', NULL, 'client/1/user/315/avatar.png', 'fXgdSq9bE1GWRFkpL4amsqdB6ILiuDzsxIVybcFTZHAPwBraxdBOwCHTCbw8', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(316, 2, NULL, 225, 'Besmir Muka', 'Besmir', 'Muka', 'GB', 'mukabesmir@gmail.com', '2021-12-16 13:54:58', '$2y$10$4yBuLA8a/QYJkorsQWeJ.OmyYpgxB8B4j0X7p11KGSAIquPcSs4c.', NULL, 'client/1/user/316/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(317, 2, NULL, 227, 'Abdul Hakem Zahari', 'Abdul Hakem', 'Zahari', 'MY', 'hakemzahari@gmail.com', '2021-12-16 14:25:14', '$2y$10$yo94tTXzrbg4Lc4lhma5z.hqD96lBHJcO7/HwKNN8gBLlF4W7v1tu', NULL, 'client/1/user/317/avatar.png', 'SpbxrhqtBDcRqG6QUiw5jQCiuHXUSm13cJYFUlq1GNBxa2XMq0On59RpupqZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(318, 2, NULL, 229, 'Md Billal Hossain Sarker', 'Md Billal Hossain', 'Sarker', 'BD', 'authorityaid@gmail.com', '2021-12-16 15:25:31', '$2y$10$4FHtkAQ8FjdMo5rIgjuD2e5LAW20ZREvh/Vv61h.5ucBr8rFbesW6', NULL, 'client/1/user/318/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(319, 2, NULL, NULL, 'Asi', 'Asi', NULL, 'US', 'subshero.8203f6@fcl.me', '2021-12-16 16:11:01', '$2y$10$PxZaDjH6nYgclq1jCqFam..XZKbk4ww5abfMR.VesS2AY7azy4PUy', NULL, 'client/1/user/319/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 16:02:13', 0, '2022-10-13 13:19:41', NULL, 1),
(320, 2, NULL, 230, 'pablo lichtik', 'pablo', 'lichtik', NULL, 'plfmanf@gmail.com', '2021-12-16 17:33:07', '$2y$10$c1DFzPiYOcR0l0f.NlCOq.XIdZrHqnogNkZhrFGVSFP0kIkbnBVjm', NULL, 'client/1/user/320/avatar.jpg', '5tPTvrmODkrVQAvopdJmBh4kRbgXGMV40VJtMfAR2yQ5yCmx5d5P1u8HP2J6', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(321, 2, NULL, 231, 'Viral SaaS', 'Viral', 'SaaS', 'US', 'viralsaas@gmail.com', '2021-12-16 16:40:14', '$2y$10$yzqDbrWgE/A8UC0veC40eeL6HWL2CTep82jlNYZQOwCUhIQAREiY.', NULL, 'client/1/user/321/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 16:38:24', 0, '2022-10-13 13:19:41', NULL, 1),
(322, 2, NULL, 232, 'Jeff Krueger', 'Jeff', 'Krueger', NULL, 'jeff@boostforyour.com', '2021-12-16 17:52:36', '$2y$10$PQVuC0uFw0dVEBQjK9538OW0TCm6hFDFZhGsM0xaxoSl1u7SoVVLC', NULL, 'client/1/user/322/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(323, 2, NULL, 233, 'Mario Rajan', 'Mario', 'Rajan', NULL, 'm@gscmr.com', '2021-12-16 20:45:39', '$2y$10$AHlOG5w/WEsm3RkaA68LNOB2b86/lN3Ej4pycHxhMgSF2SUmomXXG', NULL, 'client/1/user/323/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(324, 2, NULL, 235, 'Dimitris Printezis', 'Dimitris', 'Printezis', NULL, 'naxiand@gmail.com', '2021-12-16 20:52:22', '$2y$10$OcoSlbaQmmCr9ylC92Gwse79xNYBKWei.25peSeRAluzzIK2wAvUq', NULL, 'client/1/user/324/avatar.png', 'Ds17oGQQF7f6dnGoJAsrYR2FMzKVQ1gHhJBEQQ6ki3AOCmJfHmy0b50qwWlI', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(325, 2, NULL, 236, 'Rahul Asok', 'Rahul', 'Asok', NULL, 'redgrowlabs@gmail.com', '2021-12-18 00:38:42', '$2y$10$nuD5HwPMLvPdOpKSnYfzVeeM3qNM7391JR5PsATTsMy3yuXFhd1X6', NULL, 'client/1/user/325/avatar.png', 'A2trsuevXaCVgfK9qdWxsy8TQwgE7VhNLoMYiA1t7CGXUK5XsBGz0qDkM2LR', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(326, 2, NULL, NULL, 'Test Account', 'Test Account', NULL, 'US', 'test@account.com', NULL, '$2y$10$JQMV4POYRlr.UL63GOPXwubpeDdaYcIeXUUJI2PB2fk1Na8GIYTqy', NULL, 'client/1/user/326/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 21:05:06', 0, '2022-10-13 13:19:41', NULL, 0),
(327, 2, NULL, NULL, 'Randall Snyder', 'Randall Snyder', NULL, 'US', 'rsnyder@lyndhurstpartners.com', '2021-12-16 21:06:55', '$2y$10$tVTQ9XstxZNjJjLYqqG1uexsDT1AuuOYhRslY7yjR2gGscwFq8tp2', NULL, 'client/1/user/327/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-16 21:06:47', 0, '2022-10-13 13:19:41', NULL, 1),
(328, 2, NULL, 237, 'Jing Liu', 'Jing', 'Liu', NULL, 'sharkyliu@gmail.com', '2021-12-16 21:21:44', '$2y$10$l9fC0Vh9DgnyJz9r7ScCBuNlChWZqQ.o9qzaa.GBQNScQvtC1caNi', NULL, 'client/1/user/328/avatar.png', 'cmUnDrf726AUyBH1E04k2rS5kddW93TY3zOgXwnclet0LRrk3WYPIINBrEx9', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(329, 2, NULL, 238, 'Abraham Reichlin', 'Abraham', 'Reichlin', NULL, 'traumanw@gmail.com', '2021-12-17 17:13:26', '$2y$10$zww1KvYHalFEAAhJ6m/HkuJd9Pr1U0t0a/VtMYgOJNNb4e/mXxlFy', NULL, 'client/1/user/329/avatar.png', 'J0LPIkLFdsHv90GynSaD58kG0TsLLf5eTUbAX9GzwhZzqza9fHcmztimyvkL', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1);
INSERT INTO `users` (`id`, `role_id`, `team_user_id`, `wp_user_id`, `name`, `first_name`, `last_name`, `country`, `email`, `email_verified_at`, `password`, `description`, `image`, `remember_token`, `marketplace_status`, `marketplace_token`, `paypal_api_username`, `paypal_api_password`, `paypal_api_secret`, `company_name`, `facebook_username`, `phone`, `created_at`, `created_by`, `updated_at`, `reset_at`, `status`) VALUES
(330, 2, NULL, 239, 'Peter Vogopoulos', 'Peter', 'Vogopoulos', NULL, 'peter.vog@gmail.com', NULL, '', NULL, 'client/1/user/330/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(331, 2, NULL, 240, 'Keith Yu', 'Keith', 'Yu', 'PH', 'keithbrianyu@gmail.com', '2021-12-17 01:40:10', '$2y$10$Bt3S96ORCe77DPawqPbXjOqOWjOx4KcOUNMBeHXQe8wJAqyrdyrh6', NULL, 'client/1/user/331/avatar.png', 'nixCUR2sy4ZJP1eAb29Jvs10bi3s23laVexput0LXjkhXexo9Rbc1tLz0A1d', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(332, 2, NULL, NULL, 'Daniel', 'Daniel', NULL, 'US', 'daniel@adslhome.dk', '2021-12-17 02:32:28', '$2y$10$CtW0QvtEbFTFqLNaRXM27u9BjOhTjhsdum3xtF9rqhGs2yLmMBoU2', NULL, 'client/1/user/332/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-17 02:31:26', 0, '2022-10-13 13:19:41', NULL, 1),
(333, 2, NULL, 242, 'Thomas Fellows', 'Thomas', 'Fellows', NULL, 'thethomasjfellows@gmail.com', '2021-12-17 02:38:37', '$2y$10$vj4s2kmw8l6NvGrVWYqXveRyH5JT2COv1CPhXMWTT3fT0ATuFvYk6', NULL, 'client/1/user/333/avatar.png', '780nAwS0w1dw2D3a5URdDe0SYaRmI2sUgHGifUkQuYrqQxRsc9ysdpMngDM8', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(334, 2, NULL, 243, 'Arman Mazaheri', 'Arman', 'Mazaheri', NULL, 'armand.mazaheri@gmail.com', '2021-12-17 03:52:58', '$2y$10$WW/OTutpwZV9HnYLLFyDY.MRwNPT5nRmy05xQAsfIJ9t/ZOY4.yBm', NULL, 'client/1/user/334/avatar.png', 'TUAHUHiO2ySCiLQDsRvPPNE8wMsxcTwGXShRkgsojk35x4uvFQUrCNvTjInJ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(335, 2, NULL, 244, 'Gilbert Arias', 'Gilbert', 'Arias', NULL, 'info@leadsintoinbox.com', NULL, '', NULL, 'client/1/user/335/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(336, 2, NULL, 245, 'Tom Schuller', 'Tom', 'Schuller', 'AT', 'tom@diemarketingnerds.com', '2021-12-17 06:11:55', '$2y$10$S5Q2iNIzasip.R.QN.FFJOAg8MfUzmQM2xAnN/U7pO2gNCzmbXtWm', NULL, 'client/1/user/336/avatar.jpg', 'jvJtBIDnGYDqVSJRA7NdY8CadHVw8CFQKjtYUM2FNrni4uDsYFg4AY3MMO3p', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 08:05:51', NULL, 1),
(337, 2, NULL, 246, 'Leon Firer', 'Leon', 'Firer', NULL, 'leon@streamin.com.au', '2022-12-02 08:41:02', '$2y$10$TWSHWYNlshtoc.tPUjb8begRcz3u/27u4fhzl8v5lmXkTmrLadmQW', NULL, 'client/1/user/337/avatar.png', 'CndLCuOZABHvIHD37zkY6zgQYoKfbBpleZGcZ7iVrEoGFMXtDzAlRmXvnntU', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-02-10 05:31:52', NULL, 1),
(338, 2, NULL, 247, 'Scott Bloom', 'Scott', 'Bloom', NULL, 'sbloom@gmail.com', '2021-12-17 07:33:35', '$2y$10$7t7uUfP4Mve1WpIcoffEBuBAM6UtC/yhQT10BSGR9mplj2uOFLAIS', NULL, 'client/1/user/338/avatar.png', 'w7qC2XastYCK1t6apgEKCeARUk3Bthzo2VkNxKfgSQsGD34CRi9THXqRsI1C', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(339, 2, NULL, 248, 'Richard Tunnah', 'Richard', 'Tunnah', NULL, 'rtunnah@gmail.com', '2021-12-17 08:39:11', '$2y$10$EhlgJWHoTG0R251iXgFCL.7epX8IW2WViLPDkqOr3l8ZzEAE7Yy92', NULL, 'client/1/user/339/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(340, 2, NULL, NULL, 'Bra,', 'Bra,', NULL, 'US', 'veenvdbram@gmail.com', '2021-12-17 11:26:11', '$2y$10$HAY6poEa0OxeF.NkJV8tIOMcpRA9ToFS530GBAJzMkuluRMrnWBE6', NULL, 'client/1/user/340/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-17 11:26:00', 0, '2022-10-13 13:19:41', NULL, 1),
(341, 2, NULL, 249, 'Kyriakos P.', 'Kyriakos', 'P.', 'GR', 'kiriakos@pkapps.xyz', '2021-12-17 11:55:14', '$2y$10$KqRZmON3SmfuJqzYERufB.ESfOELrdKaN5ISSa3CdTMGlUqY303HC', NULL, 'client/1/user/341/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(342, 2, NULL, 250, 'Jared Stein', 'Jared', 'Stein', NULL, 'jared.stein@extremeoutcome.com', '2021-12-17 12:27:15', '$2y$10$VhOQ.f3SuP1jifu0YTuJcuy8I6A5B0Qr4WsqSm7xrrMOsxQ59Febm', NULL, 'client/1/user/342/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(343, 2, NULL, 251, 'Kah Wong', 'Kah', 'Wong', NULL, 'wkkiong@yahoo.com', '2021-12-17 18:55:39', '$2y$10$HvV90K.uBvlV9tBKXfKk3umJAJ0/fZnaKDeZGh4PTjLps7hTCK9UK', NULL, 'client/1/user/343/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(344, 2, NULL, 252, 'Nhu Do', 'Nhu', 'Do', NULL, 'admin@educe.solutions', '2021-12-17 13:01:37', '$2y$10$.v4rTvhkVGPFWXIG8ZSs4eJKcwicHzujrnGxCoOWpQdIzayEt53py', NULL, 'client/1/user/344/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(345, 2, NULL, 253, 'Amir Latif', 'Amir', 'Latif', 'DK', 'info@nitro9.co', '2021-12-17 14:14:50', '$2y$10$R9B4IjAUIU4ffOJOnvvzju66wN/tSgateG3dHyj6vfJrRoz3eglbC', NULL, 'client/1/user/345/avatar.png', 'e49016qO1Fe2iRkgBDiwuFht47HI7tXi2zVpnnN6ccV9acTZi9GkwDoEUF5L', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(346, 2, NULL, 254, 'Jay Gross', 'Jay', 'Gross', 'US', 'jay@lifenextlevel.com', '2021-12-17 14:09:28', '$2y$10$OF8jhLrxbUU6uJO1rH0thujLnXTuKrl1bSgKPyy3QtFURiFNhSGCu', NULL, 'client/1/user/346/avatar.png', 'T3KgdxUghVy6yskXRNX6EJj7tGQw6NsHDhH5L3NsH1luzdGVs1dlefRBjgke', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(347, 2, NULL, NULL, 'Teddy Marie', 'Teddy', 'Marie', NULL, 'tdmx@tdmx.orf', NULL, '', NULL, 'client/1/user/347/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(348, 2, NULL, 256, 'Edwin Rubio', 'Edwin', 'Rubio', 'US', 'subshero@ltdmail.io', '2021-12-17 18:48:15', '$2y$10$MZGZm5vlcEZ.vOkDGPffa.XI8.RM0dh2aYH7fLEuii3WStXxPEdca', NULL, 'client/1/user/348/avatar.png', 'dvnMLMpkgh65OLIDiwuV0OixnOjDfJXsYBQPhPqlyVNPbuCMXf9olnEQoojH', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-17 18:47:52', 0, '2022-10-13 13:19:41', NULL, 1),
(349, 2, NULL, 257, 'Stefan Glumpler', 'Stefan', 'Glumpler', NULL, 'stefan@shabushabu.eu', '2021-12-17 19:10:27', '$2y$10$N6CCwgDYnVDlXCGpM/2hGuTLdRZJ5WExLRBUMesx0idcncIupIVUy', NULL, 'client/1/user/349/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(350, 2, NULL, 258, 'DanFyn', 'DanFyn', NULL, 'US', 'dantanianfynora@gmail.com', '2021-12-17 19:34:22', '$2y$10$FEKMFhppxbt2nKvMw2g/P.6Iaiv07RzcC9N/1DTvYYo5m8rcvM0K.', NULL, 'client/1/user/350/avatar.png', 'FIQL3O3GC3Rtj7opSOKuvdJy0MzvjIUF5GdDKaK4b8lP69A3H8hcjAsH5HNV', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-17 19:30:31', 0, '2022-10-13 13:19:41', NULL, 1),
(351, 2, NULL, 259, 'United Agency', 'United', 'Agency', 'US', 'apps@unitedagency.com', '2021-12-22 06:11:04', '$2y$10$D1Zs9rVjvQBnmckBd8Hdje9hX3oXhnvDz8pIGOOFugTVGleHBfESq', NULL, 'client/1/user/351/avatar.png', '7twmsO9p482sOTPIVvlfTSw35zsnQN2EMYvbXL8UkJNgjUecm5cnRXwqebC1', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(352, 2, NULL, 260, 'Nik Donovic', 'Nik', 'Donovic', NULL, 'nik.donovic@gmail.com', '2021-12-18 03:57:33', '$2y$10$A66pcKM/Jbq3YKJ9DY.FN.MlGaqVwR40n2r2ddX19Bl71/BNDGliG', NULL, 'client/1/user/352/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(353, 2, NULL, 261, 'Ram K', 'Ram', 'K', 'SK', 'nablbosti55@gmail.com', '2021-12-17 21:23:18', '$2y$10$FTXcTyXEGhjJErjzxJXmaucoIkIXa0nfLK9BH5IQIXgVsrcIW5OrO', NULL, 'client/1/user/353/avatar.png', 'g2DlbVMTRcsWrbHRpj0kHMIIyOkEFXem7l0LbM6TVKMOJsdJt739wnqekGF3', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-31 17:24:12', NULL, 1),
(354, 2, NULL, 263, 'Tony Chamberlain', 'Tony', 'Chamberlain', 'US', 'tchamberlain@gmail.com', '2021-12-17 21:32:53', '$2y$10$iQkaraQLiY2w2DUNGaenvutM/0pVoMmah92EqfxPSkZuFHjUn.8Le', NULL, 'client/1/user/354/avatar.png', 'Rv8mb4urGZgfA1O3OYloy4NmtKa6Fan1TFX1Biu1qnr5BkV4BIHXkXbF2Rjo', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-17 21:32:40', 0, '2022-10-13 13:19:41', NULL, 1),
(355, 2, NULL, 262, 'Paul Stevens', 'Paul', 'Stevens', NULL, 'stevens_ps@yahoo.com', '2021-12-17 21:43:50', '$2y$10$GLVACYX3pEFrkOusM1uQWOgfb5HppMALtLkwmZdWO52AoVJtV/yAa', NULL, 'client/1/user/355/avatar.png', 'rVl7n2LSrM0BFyigaLoqdh1wE36X5S5pYIKPxFUjYMsoF43xcKa2yRIPgysO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(356, 2, NULL, 264, 'Theo Pistorius', 'Theo', 'Pistorius', 'ZA', 'info@oneiroi-invest.com', '2021-12-17 22:10:49', '$2y$10$0ao/2JoSbYViF2irKdOrveaoI4Nc.Ocb29SznFAryC4/ApsuD81ae', NULL, 'client/1/user/356/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(357, 2, NULL, 265, 'Paul Beamer', 'Paul', 'Beamer', 'US', 'paul@leaveit2beamer.com', '2021-12-17 23:00:03', '$2y$10$1O4rTauZUvZuY9DRLyz3meQDLohYodCbEnyhLhtHfA5RzdjMZz1le', NULL, 'client/1/user/357/avatar.jpg', 'PxSFTPqAQ4XDIuDhmk4QfHr0f8AYDw2kpZmllkiimWcLJFAiJqf67C7KQjMT', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-04 14:02:12', NULL, 1),
(358, 2, NULL, 266, 'Michael Krakowiak', 'Michael', 'Krakowiak', NULL, 'mkrakowiak@fastmail.com', '2022-01-05 04:25:58', '$2y$10$VtTexYFoN8Y0a0MF7ljfLu4nMNh0q6pTpXygYmcetcs74K63a0Fvu', NULL, 'client/1/user/358/avatar.png', '1Nyc0QJlgxrtTVHtf4q3Z77x9v7GFr6qJd1Jg4iuxCnCKUDiXLhyBnCW4DK3', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(359, 2, NULL, 267, 'Victor Lam', 'Victor', 'Lam', 'SE', 'victorl@extexc.com', '2021-12-17 23:30:54', '$2y$10$bcJymnTRsT7/AyGj5s0G.e6HYq7sSIFsOxzfZpo5HzECMmk3Uh/je', NULL, 'client/1/user/359/avatar.png', '3d4mrDuvLJO07sYGd9yqz5MOJPa2nbLGfJV09T0hEvrzSRuB8SCBw7NHRDe8', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(360, 2, NULL, 268, 'Orlando Dizon', 'Orlando', 'Dizon', 'US', 'subshero@serf.me', '2021-12-18 00:08:36', '$2y$10$v39fzaNyq7a52400ln/AF.KEv8fjZSy8idunVuI9rS80YWTqXSlxu', NULL, 'client/1/user/360/avatar.jpg', '2MaL4YxfurvmOtFMuF73Qxp5LYs0mnTWGEVylvaC0yuXwIfzRmvxVwV2ZtVg', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(361, 2, NULL, 269, 'Dan Engelsmeier', 'Dan', 'Engelsmeier', NULL, 'dan@fortysevenminutes.com', '2021-12-18 00:09:48', '$2y$10$cOrMBmdi4hkxg0c2zD9MVerLRHjjHR6ZCpkU6mp3spFbairtj7FKq', NULL, 'client/1/user/361/avatar.png', '0DdrzvxTVst0jWvZ4LNl0o42kwt5YokEwa48FSvwxs66jJSBA4UWF1lgMqqv', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(362, 2, NULL, 270, 'Ken Fleisher', 'Ken', 'Fleisher', NULL, 'ken@EasyColorImaging.com', '2021-12-18 00:30:58', '$2y$10$Dsgp.jEy6.OKvDBDtvYDH.JZt.Ei9PR/Z6ZeHJq/9hU9wSr6nDfSa', NULL, 'client/1/user/362/avatar.png', 'qTJhhAINnwMbhebpFvwA6jkVnXx0qwzXxA2j7ZVDUl7yVzV60p9kJ5ayTHzK', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(363, 2, NULL, 271, 'Paul Reiken', 'Paul', 'Reiken', 'AU', 'preiken@gmail.com', '2021-12-18 00:43:26', '$2y$10$CsjXyexmmyqtDFT81wQP1eH3ZtGIONWig7yyHgewp//6MvrlgAZIu', NULL, 'client/1/user/363/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(364, 2, NULL, 272, 'Edwin Yeoh', 'Edwin', 'Yeoh', NULL, 'edyeoh@live.com', '2021-12-18 01:23:54', '$2y$10$llHI9cfoPxOKh5SR3pR0XuSHsod6K6Q/xISSiVaxwSRheKM.cofUO', NULL, 'client/1/user/364/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(365, 2, NULL, 273, 'Jody-Ann Henry', 'Jody-Ann', 'Henry', 'CA', 'jodyann.henry@gmail.com', '2021-12-18 18:40:35', '$2y$10$5rT/pw/JfXCSiYohJ45RjOi.bDxq8uYR4trpLEk1/TMnaNZ7QQG7a', NULL, 'client/1/user/365/avatar.png', 'XQX9IZw8svSlbwA3lHO1EyJrRGEbIktK9Gam4MyX5H1ibOqUJFfDSbGMwRcp', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-03-01 23:43:45', NULL, 1),
(366, 2, NULL, NULL, 'Paul', 'Paul', NULL, 'US', 'stackby.5b80e3@maildepot.net', '2021-12-18 02:56:20', '$2y$10$znbZHrlo0UwNTDvcP65H0eqCB94TOR6O5y/AT6mfSgxPD86ui7QVS', NULL, 'client/1/user/366/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 02:55:55', 0, '2022-10-13 13:19:41', NULL, 1),
(367, 2, NULL, 275, 'Andrew Tran', 'Andrew', 'Tran', 'AU', 'aqqtran@gmail.com', '2021-12-18 04:00:48', '$2y$10$OL056fAKZF6Tb0DZzHBngeBM10L/JBFtXW/xIHW2hM.RnNqXBGeme', NULL, 'client/1/user/367/avatar.png', 'YWepawx1UYr3HzzGhHFTUe8QwAt0RLEzpog8tP2CVS19CuhEbudQk92VD6wU', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(368, 2, NULL, 276, 'Khushboo K', 'Khushboo', 'K', 'IN', 'themophiles@gmail.com', '2021-12-18 04:26:11', '$2y$10$tM.Qc73YNwk8e5pubG0tmezSh/y1ffZ4rbXva3/bRwT.1IVaLDQVS', NULL, 'client/1/user/368/avatar.png', 'TUEVFKeJkW6OyOhf93157ZV9OWGXpnFr0DvDcN3vKDeZywdIUuDvKgf4Lf7E', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(369, 2, NULL, 277, 'Calvin Sue', 'Calvin', 'Sue', NULL, 'calvin.sue@gmail.com', '2021-12-18 05:46:33', '$2y$10$MHKrwBDhIrPgct1VRTmd1e2LUZlC1Whhkl6/gIBvhoK5fd3d8srwG', NULL, 'client/1/user/369/avatar.png', '28qeOeQEjMvN4T68BgtoxBPExv7hxifmHQnYC8IHm42K548muKw50P3z1NLI', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(370, 2, NULL, NULL, 'binaya', 'binaya', NULL, 'US', 'tuladharbr@gmail.com', '2021-12-18 05:47:07', '$2y$10$yBueKyKiqKuduqIMtiYt0ehxYCYTUZROFxfHXafFJOStlTAFlTNH6', NULL, 'client/1/user/370/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 05:46:51', 0, '2022-10-13 13:19:41', NULL, 1),
(371, 2, NULL, 278, 'Prabdeep Mann', 'Prabdeep', 'Mann', 'GB', 'prabdeep_mann@hotmail.com', '2021-12-18 07:06:00', '$2y$10$NmIoORJTFyTk8.Zg7dSWe..cDLFBuH0KPZ1mkG1NKhTU5mCljDdeq', NULL, 'client/1/user/371/avatar.jpg', '4xVuEc2B7ZoG2qDoTyMHDJUEH3GyRywperafmeQIV3BWQbB0QAaKoV40brqA', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-22 05:11:22', NULL, 1),
(372, 2, NULL, 279, 'Jasper Sierink', 'Jasper', 'Sierink', 'NL', 'info@sierink-wp.nl', '2021-12-18 07:26:44', '$2y$10$6UoTtzV/niBEZnCpvFnHj.oUJSzsqssf1p72jK2Ny.Xj164vwFU/2', NULL, 'client/1/user/372/avatar.png', 'fDefMJqmsqDLT2bTNw1r0QFNcgyR9KC1AL9CaQBKFF5zWQJ2Kf3UoVGBmwOZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(373, 2, NULL, 280, 'Hassan Aziz', 'Hassan', 'Aziz', NULL, 'info@bullmade.dk', '2021-12-18 08:16:56', '$2y$10$Jo9CNWrW/93b3GlDXqPbQu3FUDEx9wqDDy.AoPuvA.i7OqyLZbqp2', NULL, 'client/1/user/373/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(374, 2, NULL, 281, 'Amit Kumar', 'Amit', 'Kumar', NULL, 'caamitldh@gmail.com', '2021-12-18 09:04:53', '$2y$10$/JdDpdWhnkb2j7LKpdZrpOVwJrUCrijRzS5gE.wEUs0ScVHi1WTda', NULL, 'client/1/user/374/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(375, 2, NULL, NULL, 'Teddy Marie', 'Teddy', 'Marie', 'FR', 'app@tdmx.org', '2021-12-18 10:01:50', '$2y$10$beSbWiDYoYqItkGLQYCYD.mlKxLWT3xbGeDfF1UTnagHCUUJUzbEe', NULL, 'client/1/user/375/avatar.png', '3QMQ8bJOQmvmOeMdB64OWsU24gDvZiUKvq3LXeEAxq1NvK0XjyqlKqf5YGzX', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(376, 2, NULL, 282, 'Eduardo Alfaro', 'Eduardo', 'Alfaro', 'ES', 'info@clickemprendedores.com', '2021-12-18 10:06:51', '$2y$10$ZD6FAGUd5XBuwD/gYEtYJuTxa.iEIxzR5IUMKEAU3.sx6HRcoPDye', NULL, 'client/1/user/376/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 10:06:31', 0, '2022-10-13 13:19:41', NULL, 1),
(377, 2, NULL, 283, 'Fred Vinson', 'Fred', 'Vinson', NULL, 'fredyyv@msn.com', '2021-12-19 19:51:52', '$2y$10$XgtZdACvts0zPbvMBMoclOEsuckzjH09EUHJKMsAtkXpFVzTng6ue', NULL, 'client/1/user/377/avatar.png', 'T6duQlqi1U4KqXbgnugD7hKqEZkuyjq0LVvnCZRnh4voajUJTFJTWDJqLFxa', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(378, 2, NULL, 295, 'Nabil Tayeb', 'Nabil', 'Tayeb', 'CA', 'nabil@dng.ai', '2021-12-18 11:27:12', '$2y$10$F39Fv5bZJVV.AJUBxdGhu.lBHFK2Lo/9CGWEzTITalh4GT9STQzk.', NULL, 'client/1/user/378/avatar.jpg', 'aFACapT7wgbOMaHXLlcLuJhthcRbO5T9q1Q6JCPlfnkXkPtGGPCsMOBnd8ij', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 11:25:20', 0, '2022-10-13 13:19:41', NULL, 1),
(379, 2, NULL, 285, 'Rick DiChristofaro', 'Rick DiChristofaro', NULL, 'US', 'rickdichris@gmail.com', '2021-12-18 12:13:23', '$2y$10$2lXCPcPGV0Y9awPk9shfneEhyTuZMMLf8j0x9ESyrSadoJBeqEiQO', NULL, 'client/1/user/379/avatar.png', 'v3SYjD2QbEo1NW07eLib3Wef4jExDzchioju7T1SFTWcLO9bnvZQHCkFaeTQ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 12:13:04', 0, '2022-10-13 13:19:41', NULL, 1),
(380, 2, NULL, NULL, 'Junwen Chen', 'Junwen', 'Chen', 'SG', 'junwen@designthylife.com', '2021-12-18 12:40:30', '$2y$10$SIhPr8QnDBSHZJCbYyDUBOp1zTMwOgp6/QztzLL8ZEXArRP6PdNf.', NULL, 'client/1/user/380/avatar.jpg', 'HKNjNS5Wmrs7AqqPGfWBAwUHjBzGRlk8QpaisLZbwlA9qAPwxLCaoyzlRJqs', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(381, 2, NULL, 286, 'Brian Lee', 'Brian', 'Lee', 'SG', 'eleejh@gmail.com', '2021-12-21 05:13:14', '$2y$10$mElX0xT7necRhsGABkSbGuhGvhpOYRgnmrun544dxEse0r1dWW19a', NULL, 'client/1/user/381/avatar.png', '4Xs7lMQ9nvbH6GS9MxQPbxwHNpFzZVlKO8m4uWrzxbGK8jyMsTW1jUuTB8Cc', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 07:02:05', NULL, 1),
(382, 2, NULL, 301, 'Ashish Rai', 'Ashish Rai', NULL, 'US', 'krish1302@gmail.com', '2021-12-18 20:56:09', '$2y$10$EEsWDsV0HzUAKFAIKly4MedRTaq04c2bD8ywPhdRAV8c78NGY.VMy', NULL, 'client/1/user/382/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 12:59:22', 0, '2022-10-13 13:19:41', NULL, 1),
(383, 2, NULL, NULL, 'Trial', 'Trial', NULL, 'US', 'trialmail@monthly.paced.email', '2021-12-18 13:49:58', '$2y$10$E6dCiZmM8CGOCIz01B1LIeQQb4fDj6bE65fwPttG0NX4nMa6WK7mm', NULL, 'client/1/user/383/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 13:49:42', 0, '2022-10-13 13:19:41', NULL, 1),
(384, 2, NULL, 287, 'Pieter Heikoop', 'Pieter', 'Heikoop', NULL, 'heikoopwebdesign@gmail.com', '2021-12-18 13:58:09', '$2y$10$dru43XwuFfkl34m6w1JciupKwgr7Xn8q9cAK8OP0EOu7zjkCWuUlq', NULL, 'client/1/user/384/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(385, 2, NULL, 288, 'Mohamad Faiz Yaman', 'Mohamad Faiz', 'Yaman', 'AU', 'toolup.one@gmx.com', '2021-12-18 14:11:41', '$2y$10$SY0ng9e7IpecuH38NB6d7u0PnGDWY6K8rSQgaZxMLBacVqvdjVsKS', NULL, 'client/1/user/385/avatar.png', 'xmfQlTIfeCQkg9iw6PXzrwv74lJHYRneQH0vuioeS2LR89rNXblFFU6boWuM', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(386, 2, NULL, 289, 'David Caren', 'David', 'Caren', NULL, 'zenrosegarden@gmail.com', '2021-12-18 21:43:24', '$2y$10$xQM5xh.n0sjgduJ4syseO.gekwj0R9baTdhrJVewF.YtuQCWIAEMe', NULL, 'client/1/user/386/avatar.png', 'eKicg67Tu53qJA0JUuk1w0nU9LN9roJrVaf8O3W8HuwGoU6E0jdoUiwu0SJq', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(387, 2, NULL, 290, 'Chiheb Ben Aissa', 'Chiheb', 'Ben Aissa', NULL, 'chiheb@zerda.digital', '2021-12-18 14:49:10', '$2y$10$tQwifb8XRAsUrN8hyx33MeI/nfdhOg.r9qpmbCUld041VXkU2NQ7W', NULL, 'client/1/user/387/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(388, 2, NULL, 291, 'Just Sturgis', 'Just', 'Sturgis', NULL, 'subsheropro@gmail.com', '2021-12-18 15:08:01', '$2y$10$aMLIC3NSWv0JosDdBWTBkOEQ4RbL6MfqbvHD/1NwIMU7NjGE4VShi', NULL, 'client/1/user/388/avatar.png', 'nDNvVKMhxg1Wn3dR6uVU5E9NqLA8Hpal7xINfAVhBXPBTpHwPNQVTfbO7zWB', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(389, 2, NULL, 292, 'Fulvio Di Stefano', 'Fulvio', 'Di Stefano', 'CH', 'webmaster@internetdiffusion.com', '2021-12-18 14:56:13', '$2y$10$SiP2oSzW0bu0tTcvfsfKUOGbU0cNsW/.koeHLkDvkGB4Vc5dwq8fi', NULL, 'client/1/user/389/avatar.png', 'FaubIe40CJnNsP38fdfAnGNYGIdz5EmyHA1O7PbYAlLAUvTmJYT5gbeIV0nt', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(390, 2, NULL, 293, 'Chad McDonald', 'Chad', 'McDonald', NULL, 'interagentgroup@gmail.com', '2021-12-18 15:27:19', '$2y$10$/dwsZHJh7MDk5eJll8qB/uiOwAgbkFYf2Q4xQx5jv2tnvyUisYjdq', NULL, 'client/1/user/390/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(391, 2, NULL, 294, 'Lee Fletcher', 'Lee', 'Fletcher', NULL, 'lee@fletcherdigital.uk', '2021-12-18 16:35:34', '$2y$10$2yxQX1qPDn9bwV2LUzok.enzsXMFmUdI6oj7PwI4sHxS5Tg/Ut3iu', NULL, 'client/1/user/391/avatar.png', 'uz3uIglhxDfekHvs9zI8JnF56VarkrGLKXEnmjfbESmhEFO49dumqMIGxIHm', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(392, 2, NULL, NULL, 'Nikunj', 'Nikunj', NULL, 'US', 'nikunj.tamboli@gmail.com', '2021-12-18 17:06:11', '$2y$10$xLZW6Tr///19vEHZhNdPtu5sQX.8OVAofL7j7T22xzQFoJ9DQF246', NULL, 'client/1/user/392/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 17:05:22', 0, '2022-10-13 13:19:41', NULL, 1),
(393, 2, NULL, 296, 'Emma Shapiro', 'Emma', 'Shapiro', NULL, 'debtfreept@gmail.com', '2021-12-18 18:04:44', '$2y$10$HIXMPZpCNMm.67TccFdVC.MPe/jyV2Ai8ObWe0ekH/UW9NSzA9sbK', NULL, 'client/1/user/393/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(394, 2, NULL, NULL, 'Testo', 'Testo', NULL, 'US', 'tastino@yopmail.com', '2021-12-18 19:00:57', '$2y$10$lbsMG4oYBQLQ9IvTJNQhxeWcxNA3P.HmSUdW0PJoe/i3zChTuDa66', NULL, 'client/1/user/394/avatar.png', 'jQr9JWvAcyeoR5dC54HPMrdLSrbyIQS0g6HGKASw4m66ond5pCjbcBVbzlv0', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 18:57:31', 0, '2022-10-13 13:19:41', NULL, 1),
(395, 2, NULL, 298, 'Alex Choy', 'Alex', 'Choy', NULL, 'alexcinvest@gmail.com', '2021-12-18 19:04:51', '$2y$10$U5fvppI6LBKD.n5h5x3l7et.we/4eW1i6MpPJjRBfCPKR4a6gfo6K', NULL, 'client/1/user/395/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(396, 2, NULL, 299, 'Orcaa Consulting', 'Orcaa', 'Consulting', NULL, 'orcaaco01@gmail.com', '2022-12-02 16:58:00', '$2y$10$jv4I1A9eBtTECBUoH/6/x.AfwoQjAKXQgeZibOshCBko6bypmAE5a', NULL, 'client/1/user/396/avatar.png', 'C3YejOXodOKVJcUhq2ZLYuN7aEBqfN02zqNAwTlXmuN0OaEjAex2sRX0vhIk', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 16:55:44', NULL, 1),
(397, 2, NULL, 300, 'Fat K', 'Fat', 'K', NULL, 'oneflic@gmail.com', '2021-12-18 21:06:01', '$2y$10$nBW7u4vtpxmIKyvYlceq2OoAcY7rR/x.rlNSqn3HFeWqL4tsYMXVa', NULL, 'client/1/user/397/avatar.png', 'X0dJG7C9yGhpLgnWjwUgMF0PyBqGkjF5pijVvMUsuSnnszNiZ4Rf3lWu1Rrk', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(398, 2, NULL, 302, 'Ravindra W', 'Ravindra', 'W', 'IN', 'ravindraw@gmail.com', '2021-12-18 21:13:46', '$2y$10$BpGUSdkJtwseGXdTq6Gy2uxcdF7HAdKinlKEz2i5G95vorY2K2.YC', NULL, 'client/1/user/398/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(399, 2, NULL, NULL, 'Chayanon', 'Chayanon', NULL, 'US', 'shopnetdesign@gmail.com', '2021-12-18 23:33:09', '$2y$10$220UmV1E3n9FE.y3BCexo.rKX5hDJhFruYW.5cLV7No26Fho3AAl.', NULL, 'client/1/user/399/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-18 23:32:50', 0, '2022-10-13 13:19:41', NULL, 1),
(400, 2, NULL, 304, 'Joshua Lim', 'Joshua', 'Lim', 'SG', 'apps@socialhackrs.com', '2021-12-19 03:32:36', '$2y$10$rwD.5Y1BhreNOaX2eYrRwORmVzziHpcfp.CHNViaHot5iyM3tinwq', NULL, 'client/1/user/400/avatar.png', 'vRJIso1VAoKg5slNhM6FaLIl8LFlZTqSsq9tbUyXHvkVmN0AF0SiWYCOcHDZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-19 03:30:42', 0, '2022-10-13 13:19:41', NULL, 1),
(401, 2, NULL, 303, 'Phuong Nguyen', 'Phuong', 'Nguyen', 'VN', 'phuong.nguyen.cala@gmail.com', '2021-12-19 12:00:05', '$2y$10$Kz6h4NCI3fPe8/eMJflULeHEC97obG1721TJl/DzJ8Wyv8Ro9wFpK', NULL, 'client/1/user/401/avatar.png', 'NpCv6vNZsRSvGsSD5ja76peM0jWosrgu88ZcdCiqi1e5XGSrP8QrS7Ifiik1', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(402, 2, NULL, NULL, 'marc', 'marc', NULL, 'US', 'hiremarcell@gmail.com', '2021-12-19 22:29:43', '$2y$10$QsdkdwNi3mS6eSI8nfcFTOkJjoupyQ7aAcuOHJYz5yX9iXaN9RGoq', NULL, 'client/1/user/402/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-19 22:28:31', 0, '2022-10-13 13:19:41', NULL, 1),
(403, 2, NULL, 305, 'Jakob Bader', 'Jakob', 'Bader', 'SG', 'jacob.bader@gmail.com', '2021-12-20 08:34:16', '$2y$10$pfnlUufmf/lIYykobarL/OXV6EoyAUH/RlGQDIJnCQwsvn19mYvFC', NULL, 'client/1/user/403/avatar.jpg', '3zOFznrbvngzqJ3tYZzcD4S2wP6dgl1xYiTjYDCPP8tHvGNv3LfrTVf9lUL5', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 06:47:49', NULL, 1),
(404, 2, NULL, 306, 'Patrick Andre', 'Patrick', 'Andre', NULL, 'shokauf09@gmail.com', '2021-12-20 12:24:46', '$2y$10$csbNUgpA..tjHkN1CIVBqeF.IZYNKO19s9n3fkpz8RAtluY9EzVr.', NULL, 'client/1/user/404/avatar.png', '2DAzN1ZsXKbhSCVGA5AhCGAmoCLfVV7nChOp7DfPKA1vSUgBtm1w1n30vDmZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(405, 2, NULL, 307, 'POH KIM LIM', 'POH KIM', 'LIM', 'MY', 'gilatekano@gmail.com', '2021-12-20 15:06:26', '$2y$10$UJAejuP2lK1OWqOtNOfdH./xQ7ff7is49eeS08HSt0s7wP3TEn9aS', NULL, 'client/1/user/405/avatar.png', 'XdW8PAL4UH6Ipc4DaySL41FxyxzEmrkJfDOszhOjE2Wxm7C5YJVypKyau6As', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-20 15:06:07', 0, '2022-11-07 21:24:48', NULL, 1),
(406, 2, NULL, 308, 'Lisa Atkinson', 'Lisa', 'Atkinson', NULL, 'lisamariem0065@gmail.com', '2021-12-21 20:15:06', '$2y$10$ApDOZylqeFP37ZAB.XF0i.5tkcUSZ0v6okPbGW3UYg8rGW4wQ4gPi', NULL, 'client/1/user/406/avatar.png', 'Vmq3HlqQr1Obh6jYXVEOhIs0punZ7lLSmODIYfPjPKk5WGLJbmkaki5bzbR6', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(407, 2, NULL, 309, 'Joan Mershon', 'Joan', 'Mershon', 'US', 'joan.mershon@gmail.com', '2021-12-24 16:58:19', '$2y$10$M4kX1qDw4qOdvpnmEDFm4eSlHqX/1x1/9hHHqw2OiPL1KLVjLOdsK', NULL, 'client/1/user/407/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(408, 2, NULL, NULL, 'Sergio', 'Sergio', NULL, 'US', 'sergiomeota007@yahoo.com.br', '2021-12-24 17:59:14', '$2y$10$UjK0IVyZMBYILTRUYMBFxeyExqF1AL9FUiJbCzhbJ6iew8Velvk5G', NULL, 'client/1/user/408/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-24 17:58:05', 0, '2022-10-13 13:19:41', NULL, 1),
(409, 2, NULL, NULL, 'johnwalshonline', 'johnwalshonline', NULL, 'US', 'johnwalshonline@gmail.com', '2021-12-24 20:10:33', '$2y$10$6MUyMyyFC1/.vRxFeeNlKeH/GTwU2sVtBygo6VwOJC/cEtJrfjIXu', NULL, 'client/1/user/409/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-24 20:09:21', 0, '2022-10-13 13:19:41', NULL, 1),
(410, 2, NULL, NULL, 'AS', 'AS', NULL, 'US', 'hoiraggkn@alilot.com', '2021-12-25 02:30:19', '$2y$10$vsI/wDwAkf2uVioaWxQHBecWmOdL1UFGA8Ly2vRBrpn0C2H4pNm8O', NULL, 'client/1/user/410/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-25 02:30:12', 0, '2022-10-13 13:19:41', NULL, 1),
(411, 2, NULL, 310, 'Marcus Shen', 'Marcus', 'Shen', NULL, 'marcusshen@outlook.com', '2021-12-25 02:38:08', '$2y$10$dt2kBo939bD2l3zfIUCDAuohT/PkrYoWo8PESU9qdjBt1krMy.M5a', NULL, 'client/1/user/411/avatar.png', 'I1Iu6ZAflq67Pte6BsZ6ss1ySWd8Dx7aXTLJGqk3Q3jJoCMRdT4pEYUX0E9C', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(412, 2, NULL, 311, 'Ivan van der Tuuk', 'Ivan', 'van der Tuuk', 'NL', 'info@ivanit.nl', '2021-12-25 14:02:06', '$2y$10$QX8FQW1mtuj50mvEBkE2m.h9kacOCDMU6sU9aSwk6t9KAHne2r2m2', NULL, 'client/1/user/412/avatar.jpg', 'T37XuuJIIZbEsnfPJsHllHKtuO3iz1jifrSJrsDo9Qz0cWrIYLmc0AklugDZ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(413, 2, NULL, NULL, 'Nayyar', 'Nayyar', NULL, 'US', '1001subscriptions@gmail.com', '2021-12-27 00:52:22', '$2y$10$pKYge4EVeupRPu.3Li0NDuJjreLq4kI5H5GDI2z2da40y5CkSC4Fe', NULL, 'client/1/user/413/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-27 00:51:54', 0, '2022-10-13 13:19:41', NULL, 1),
(414, 2, NULL, NULL, 'pukkuman', 'pukkuman', NULL, 'US', 'pukkuman@hotmail.com', '2021-12-27 07:53:13', '$2y$10$NmWPHpO1T/wiCJQYlN/mf.HgZudpzQbNxkk3K8fZ9AKDNaEv3AEkC', NULL, 'client/1/user/414/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-27 07:52:57', 0, '2022-10-13 13:19:41', NULL, 1),
(415, 2, NULL, 312, 'Leo Koo', 'Leo', 'Koo', NULL, 'leokoo@gmail.com', NULL, '', NULL, 'client/1/user/415/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 0),
(416, 2, NULL, NULL, 'Martin Newman', 'Martin Newman', NULL, 'US', 'londonmn@hotmail.com', '2021-12-30 02:32:57', '$2y$10$T9FM60owuc4xbZTa5CVLDechDW2K9cgCt4Mddf8.FYQ912mSo5z/C', NULL, 'client/1/user/416/avatar.png', '1Nh82rLoRRdLJgNl5shVpepO3ZgtYXtYydE872R1jkYjKe1x8Kuoqq7Dnon1', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-30 02:31:49', 0, '2022-10-13 13:19:41', NULL, 1),
(417, 2, NULL, 8, 'inter stellar', 'inter', 'stellar', NULL, 'wpadmin@interstellarconsulting.com', '2023-07-07 10:12:58', '$2y$10$sx.Jed47B.SHFeXRppkjOe.gPElXGHqfFWDRKs9HePpJ95GnFqnAW', NULL, 'client/1/user/417/avatar.png', 'VcJ6b9S0UzhfMH6RugoHb9eyII190zV38K9vuz9GrZCyiWuA0FeiNC6ZGAhP', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-07-07 10:12:09', NULL, 1),
(418, 2, NULL, 313, 'Bitfirms Solutions', 'Bitfirms', 'Solutions', NULL, 'bitfirms.solutions@gmail.com', '2022-12-02 08:51:32', '$2y$10$SjMyjEkz6sIBkjqQ3tpoy.MZ3dtJhYQki4q3JsdqWLL2/4f64qNtG', NULL, 'client/1/user/418/avatar.png', 'zccR4YTzAXY9Rgqgc3DaMQBgPnEm5gDwUityvMmscj0lPGjJHQD12vK9AWQT', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-12-02 08:50:44', NULL, 1),
(419, 2, NULL, NULL, 'Peter', 'Peter', NULL, 'US', 'peterbachdahl@gmail.com', '2022-01-08 03:19:10', '$2y$10$/D.Wlv/A22UDZhc3GnDaietYFSMkmHadI7cHfZdGs.QJY5SIWhSey', NULL, 'client/1/user/419/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-01-08 03:18:31', 0, '2022-10-13 13:19:41', NULL, 1),
(420, 2, NULL, 314, 'David Kocsis', 'David', 'Kocsis', NULL, 'ceo@gurumuscle.com', '2022-01-08 11:22:14', '$2y$10$.3m.Hqc79.Ri1HxPjTHLneLaf8kP61Nbbhl82MMKKAtfS2hYaGzGC', NULL, 'client/1/user/420/avatar.png', 'UTE0Hk3q4BuFWfSXz3dCDv5czh8IdyIzG5hp2tHCVTaiydL6KyGa9csZXPXn', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(421, 2, NULL, NULL, 'Medha Pfaff', 'Medha Pfaff', NULL, 'US', 'medha@interstellarconsulting.com', '2022-01-27 16:48:35', '$2y$10$ZqQ2JLqaWQDE752DdkzsXundDwXH0mX2.5AkFzNGKPNZ8O1nBGl6.', NULL, 'client/1/user/421/avatar.png', 'ZSqLY5jkWN8ZRtEw9CPv3yEcVCeSp7G2iA4qRejHRiXAdSw6SMPuAixde6rO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-01-27 16:45:48', 0, '2022-10-13 13:19:41', NULL, 1),
(422, 2, NULL, NULL, 'suj', 'suj', NULL, 'US', 'sujeethaskini@gmail.com', '2022-01-30 11:53:25', '$2y$10$rOibdh/fFffIY4OHNsS4AeiWGDt6G5uqoo9H5dp2DMWv17ENh20CC', NULL, 'client/1/user/422/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-01-30 11:52:04', 0, '2022-10-13 13:19:41', NULL, 1),
(423, 2, NULL, 316, 'Nilayan Ghosh', 'Nilayan', 'Ghosh', NULL, 'nilayanghosh007@gmail.com', '2022-01-31 12:09:21', '$2y$10$kq0ssL1su.gJAqtqyOKJU.1EZKrf15PDSBrZbPDE05/Fr/8/0gc9a', NULL, 'client/1/user/423/avatar.jpg', 'lxZeK92s23LOoofUrQ5tixbzzzgaJF6nwCMh9z6EzjijIVpXvxmZyteenea0', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(424, 2, NULL, NULL, 'Redream Creative', 'Redream', 'Creative', 'US', 'redreamcreative@gmail.com', '2022-02-01 18:21:06', '$2y$10$Y7vtbiVCN8VXBexBOeIuouerUjzxo9HpIgrA5IUDN6ChHgmP/LgCq', NULL, 'client/1/user/424/avatar.png', '7dNlWnPJjt21LRL89p70xVzALA2nrv2KT5C9eQN3RnggcMpBqMcLEBPsTVpO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-13 13:19:41', NULL, 1),
(425, 2, NULL, NULL, 'Madhavi Murlidhar', 'Madhavi Murlidhar', NULL, 'US', 'madhavi@interstellarconsulting.com', '2022-02-22 06:04:07', '$2y$10$FEZ4ALtw6NsWa0.pscUBQ.Xpa/1AId.3OAzKbVjGcdPyTQ70kuXDa', NULL, 'client/1/user/425/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-02-22 06:03:46', 0, '2022-10-13 13:19:41', NULL, 1),
(426, 2, NULL, NULL, 'Podseven', 'Podseven', NULL, 'US', 'cwsacnpodseven@gmail.com', '2022-04-07 06:35:02', '$2y$10$72ldSq1N1Dgm.H5sjCv.peAYiKF/1ZptOjMRZsAV6NqJLqMHMqbWy', NULL, 'client/1/user/426/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-07 06:31:35', 0, '2022-10-13 13:19:41', NULL, 1),
(427, 2, NULL, NULL, 'sana', NULL, NULL, NULL, 'sanawolv080@gmail.com', '2022-04-27 06:29:22', '', NULL, 'client/1/user/427/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-27 06:28:35', 0, '2022-10-13 13:19:41', NULL, 1),
(428, 2, NULL, NULL, 'Rivera', 'Rivera', NULL, 'US', 'markriverame@gmail.com', '2022-05-13 11:04:36', '$2y$10$5iNMEtT5D8/e5b/h749Jc.rXPqvhh3rbB9MdPnPloqycsgR3HStMO', NULL, 'client/1/user/428/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-13 11:02:09', 0, '2022-10-13 13:19:41', NULL, 1),
(429, 2, NULL, NULL, 'G', 'G', NULL, 'US', 'gabe@alves.id.au', NULL, '$2y$10$xjDsXNOgeNRu0zFmu4LF3.D8dVETwhDX1Ttm8FXGY4Ht6lOUUvrlG', NULL, 'client/1/user/429/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-16 00:49:15', 0, '2022-10-13 13:19:41', NULL, 0),
(430, 2, NULL, NULL, 'Vix', 'Vix', NULL, 'US', 'v@secured.aleeas.com', '2022-05-25 10:33:37', '$2y$10$Z1CPL16afbm..u9vD3dCe.GrEVGRixXgJ/bOiiozYGOLWbpM25xey', NULL, 'client/1/user/430/avatar.png', 'OPQuX3YF5RWVZ7Z9xt4TGikNDRKnoVIsO3Db15ChQOLigsCAlojPdiWTR2qk', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-25 10:33:04', 0, '2022-10-13 13:19:41', NULL, 1),
(431, 2, NULL, NULL, 'NAMRATHA SHENOY', 'NAMRATHA SHENOY', NULL, 'US', 'namruthashenoy@gmail.com', '2022-06-16 10:08:46', '$2y$10$4UWQoR0Q92sUeAwrYhnDiuFQ4k2ZxFvjyXnGq6u3zwdrIHRXF124.', NULL, 'client/1/user/431/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-06-16 10:06:49', 0, '2022-10-13 13:19:41', NULL, 1),
(432, 2, NULL, NULL, 'Remi Delhaye', 'Remi Delhaye', NULL, 'US', 'remi@xenoapp.com', '2022-06-21 05:47:30', '$2y$10$s5gKWQS5bFDn0/eFoi/Cxurj01pu8.pnCyuLOnDr6vspGFWFqJsWa', NULL, 'client/1/user/432/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-06-21 05:47:10', 0, '2022-10-13 13:19:41', NULL, 1),
(433, 2, NULL, NULL, 't', 't', NULL, 'US', 'telmo.santos.msn@gmail.com', '2022-07-03 15:14:33', '$2y$10$l5sh.0Vw9hqcte9lnzJl9uUV/eriik8CRuh4seZDfgdEONpufcJ5a', NULL, 'client/1/user/433/avatar.png', 'gAhdQx9dz5Sc8aO1yGIxnbMl2QbUHc7zbPEfbKC4cNjcH5RiQOeXCQ0o0NqW', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-03 15:14:05', 0, '2022-10-13 13:19:41', NULL, 1),
(434, 2, NULL, NULL, 'designer', 'designer', NULL, 'US', 'teamadsdev22@gmail.com', '2022-07-07 03:13:18', '$2y$10$3oUpXTaG5XCMFbBZj2n1UeQ.G4iWPDRYnYICaBtWArDATf70pxMAy', NULL, 'client/1/user/434/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-07 03:11:27', 0, '2022-10-13 13:19:41', NULL, 1),
(435, 2, NULL, NULL, 'Ross Haynes', 'Ross Haynes', NULL, 'US', 'kika@mailinator.com', NULL, '$2y$10$ytwQ/nsjtlKC4B44evIRNej6J5zqSDn0SL3RbqwJClkKQBvvtppra', NULL, 'client/1/user/435/avatar.png', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-13 07:50:06', 0, '2022-10-13 13:19:41', NULL, 0),
(436, 2, NULL, NULL, 'Shiban Ashiq', 'Shiban Ashiq', NULL, 'US', 'teamadsdev21@gmail.com', '2022-07-13 07:51:44', '$2y$10$nsLKuCM1QNJeicP9XomEiOebU.OhooRe.xZkPH.LiVp6Oc212CkcW', NULL, 'client/1/user/436/avatar.png', 'FnyxVDJ9RMimQUvlDQ4Ma7viN9b4C4bZ679PJi5iBcfszfeBLQfuU8WXDJem', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-13 07:51:31', 0, '2022-10-13 13:19:41', NULL, 1),
(437, 2, NULL, 330, 'Goshen', 'Goshen', NULL, 'US', 'webmaster@goshenweb.com', '2022-07-21 14:31:23', '$2y$10$oXiwMXOS9BN/LSaxm.40LO0cZWBD7FRIqV9o5p/QUksY95xN5MB8e', NULL, 'client/1/user/437/avatar.png', 'wZcR3PaATUVhJnZbdDH5KHaIULBdBmYErVkKw4jbfxAwQtVCKif9Ta6UquNm', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-21 14:29:51', 0, '2022-11-01 21:48:41', NULL, 1),
(438, 2, NULL, NULL, 'demo', 'demo', NULL, 'US', 'sana.interstellar@gmail.com', '2022-08-05 10:04:46', '$2y$10$rL6dGYMrQguwOqVlUUsaxe6ChehLEDMR/dgjVfTVQYKGAkW0o/oAe', NULL, 'client/1/user/438/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 10:04:26', 0, '2022-08-05 10:04:26', NULL, 1),
(439, 2, NULL, NULL, 'sofi', 'sofi', NULL, 'US', 'xonikaf683@safe-cart.com', '2022-08-23 05:12:40', '$2y$10$GUdSr89BIyNT7IZ4o1r.TuExFp4Y5jk/EXD8VlPTZHaf6dZ0tTWA.', NULL, 'client/1/user/439/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-23 05:12:29', 0, '2022-08-23 05:12:29', NULL, 1),
(440, 2, NULL, NULL, 'vultr', 'vultr', NULL, 'US', 'hodrugugne@vusra.com', '2022-08-23 07:14:11', '$2y$10$7INGOn39W5eRRf2VxMkyv.7okq.43RRbJ7byIhjPrSc8ZE6zcECQS', NULL, 'client/1/user/440/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-23 07:12:37', 0, '2022-08-23 07:12:37', NULL, 1),
(442, 2, NULL, 332, 'Royce Foster', 'Royce', 'Foster', NULL, 'ro.yce.f.o.st.er51.4@gmail.com', '2022-08-23 07:48:46', '$2y$10$.4mOjmkT7k3kF3A0mJEYsewbV.zk6gCej.uZK6AUN51oZXE.shcOu', NULL, 'client/1/user/442/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
(443, 2, NULL, NULL, 'Javier', 'Javier', NULL, 'US', 'mails.de.info@gmail.com', '2022-08-28 12:06:12', '$2y$10$bfpsvCM4Kl5KkOG6LF7fYe0P13McngWt1YODKt/oY5pqYoHTif.te', NULL, 'client/1/user/443/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-28 12:04:50', 0, '2022-08-28 12:04:50', NULL, 1),
(444, 2, NULL, NULL, 'pabbly', 'pabbly', NULL, 'US', 'admin@pabbly.com', '2022-09-10 08:41:31', '$2y$10$d8qUYrqUQ.jt8Osn0Hv0XeuvCsdzYcYVD7Q5J4Y57zUPCC2TsWY6O', NULL, 'client/1/user/444/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-03 03:39:16', 0, '2022-09-03 03:39:16', NULL, 1),
(445, 2, NULL, NULL, 'bulpup', 'bulpup', NULL, 'US', 'bulpup18@yahoo.com', '2022-09-05 05:47:36', '$2y$10$zIAjikRF0morkChkiQV1MeAAIREPSdPS2Xt9jlOb14EiecUWwRKEW', NULL, 'client/1/user/445/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-05 05:45:36', 0, '2022-09-05 05:45:36', NULL, 1),
(446, 2, NULL, NULL, 'XX', 'XX', NULL, 'US', 'golaki1245@laluxy.com', '2022-09-07 04:54:50', '$2y$10$A9iofUshgcQuJ2rJoUZkQeK6zueqToto/a4XF1ZB6wzQOp19ZA4KK', NULL, 'client/1/user/446/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-07 04:54:29', 0, '2022-09-07 04:54:29', NULL, 1),
(447, 2, NULL, NULL, 'Jennifer', 'Jennifer', NULL, 'US', 'jennmeeds@gmail.com', '2022-09-09 01:48:23', '$2y$10$jkBcK3tklzR5ExZg6pUb5Ob/0NuvsIQTVuw6y8duL2bel9vIFzdFa', NULL, 'client/1/user/447/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-09 01:44:51', 0, '2022-09-09 01:44:51', NULL, 1),
(448, 2, NULL, 334, 'Henry Hoe', 'Henry', 'Hoe', NULL, 'hyzhenry@gmail.com', '2022-10-12 12:22:43', '$2y$10$oweGqLWIIe184TtDKeLlP.UL7iBIEAopzzUnezHJ9zLq7EAgY1Jr.', NULL, 'client/1/user/448/avatar.jpg', 'KtPwXiRYfhTTYIUZY44TEHzLW4VV3mF0nHWNdy1noK2xybVIM1pnE1xMma83', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-12 12:21:20', NULL, 1),
(449, 2, NULL, NULL, 'Đồng Phục Hải Triều', 'Đồng Phục Hải Triều', NULL, 'US', 'dongphuchaitrieu@gmail.com', '2022-09-23 10:26:25', '$2y$10$z4VMURz/uJuEBJ6W4VH0CuDnmv/voQ8pWaYtvaIxswmgIYBgAdTRW', NULL, 'client/1/user/449/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-23 10:26:03', 0, '2022-09-23 10:26:03', NULL, 1),
(450, 2, NULL, NULL, 'Dur E Nayab', 'Dur E Nayab', NULL, 'US', 'nayab@interstellarconsulting.com', '2022-09-28 08:23:23', '$2y$10$9ycgiyqStQmVVVLeRZDypegaXWQtukaFtsjiH7YiWx8TWjDY/Kvna', NULL, 'client/1/user/450/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-28 08:22:56', 0, '2022-09-28 08:22:56', NULL, 1),
(451, 2, NULL, 335, 'Florin E', 'Florin', 'E', NULL, 'fl.o.rin.e.3.6.7@gmail.com', NULL, '', NULL, 'client/1/user/451/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(452, 2, 40, NULL, 'Oliwia', NULL, NULL, NULL, 'oliwia@interstellarconsulting.com', '2022-09-29 08:22:07', '$2y$10$.ybIfgzAHw0k7J9xaMSx0ut9km7k55u.lRGc9DxCi/9.P8JYI3g6i', NULL, 'client/1/user/452/avatar.jpg', 'dj9dZuOBqry3In7W4iEiKEyZlEZch5n4Xo41JMEMds9ZPbUfA0ayhsUDbDuF', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-29 08:21:46', 0, '2022-09-29 08:29:38', NULL, 1),
(453, 2, NULL, NULL, 'sana', NULL, NULL, NULL, 'saniyagazala188@gmail.com', '2022-10-18 12:23:26', '', NULL, 'client/1/user/453/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-30 03:10:11', 0, '2022-10-18 12:23:26', NULL, 1),
(454, 2, 33, NULL, 'Elijah Bosco', NULL, NULL, NULL, 'your.email+faker32603@gmail.com', '2022-10-06 03:40:24', '', NULL, 'client/1/user/454/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-06 03:39:46', 0, '2022-10-06 03:40:24', NULL, 1),
(455, 2, NULL, NULL, 'Emilia Cummerata', NULL, NULL, NULL, 'your.email+faker74654@gmail.com', NULL, '', NULL, 'client/1/user/455/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-06 03:42:26', 0, '2022-10-06 03:42:26', NULL, 0),
(456, 2, NULL, NULL, 'Mckenzie Bogisich', 'Mckenzie Bogisich', NULL, 'US', 'your.email+faker38335@gmail.com', '2022-10-07 10:28:57', '$2y$10$V66/6c164sPUeUJwHBTSWuM36Vc7ung8TUk/Nl7E.ICRs.mOIzxca', NULL, 'client/1/user/456/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-07 10:28:36', 0, '2022-10-07 10:28:36', NULL, 1),
(457, 2, NULL, NULL, 'Sebastian Tengdahl', 'Sebastian Tengdahl', NULL, 'US', 'sebbetengdahl@hotmail.com', '2022-10-08 02:20:40', '$2y$10$VGYJTqGKgB.Jr6X4qT.m1.tilW/.vnAaJTHVLUmHHVc365yV8EvGW', NULL, 'client/1/user/457/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-08 02:20:08', 0, '2022-10-08 02:20:08', NULL, 1),
(458, 2, NULL, NULL, 'Simon Le Couteur', 'Simon Le Couteur', NULL, 'US', 'simon@collaborateur.com', '2022-10-13 04:11:44', '$2y$10$JuDkSrQuwFII/LnVHl.2d.Ly4Y9gumRHOmy3oMKppj0PD6jX.91iS', NULL, 'client/1/user/458/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-13 04:10:15', 0, '2022-10-13 04:10:15', NULL, 1),
(459, 2, NULL, NULL, 'arabinda@interstellarconsulting.com', 'arabinda@interstellarconsulting.com', NULL, 'US', 'arabinda@interstellarconsulting.com1', NULL, '$2y$10$IJ1.Nu6.bOZqoiDlpXuMo.Qm/7kbBHH51DtatRnyD2eq73PJSm5Z6', NULL, 'client/1/user/459/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-15 05:23:04', 0, '2022-10-15 05:23:04', NULL, 0),
(460, 2, NULL, NULL, 'ara', 'ara', NULL, 'US', 'arabinda@interstellarconsulting.com2', NULL, '$2y$10$0ovIl0frkLXjSvneI1rtFe6BM4Yuq50DiDZWsphmrZSPQ/otRcHZW', NULL, 'client/1/user/460/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-15 05:30:58', 0, '2022-10-15 05:30:58', NULL, 0),
(461, 2, NULL, NULL, 'Enzo', 'Enzo', NULL, 'US', 'enzo7203@hotmail.it', NULL, '$2y$10$b41ayn/yje4UtmHOXGrGWetUQnCgECTz8vzuvmx0CjCLGatD9bsES', NULL, 'client/1/user/461/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-15 11:24:56', 0, '2022-10-15 11:24:56', NULL, 0),
(462, 2, NULL, NULL, 'Thor iranman', 'Thor', 'iranman', NULL, 'saniyagazala776@gmail.com', '2022-10-18 12:26:31', '', NULL, 'client/1/user/462/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-18 12:26:10', 0, '2022-10-18 12:26:31', NULL, 1),
(463, 2, NULL, NULL, 'ihr', 'ihr', '', 'US', 'imevberry@gmail.com', '2022-10-18 17:10:30', '$2y$10$Jidsy5eHBE.x2FYOwUFLneyBZpFTDfWasAY/6NR0ZhitAgHiYPxIK', NULL, 'client/1/user/463/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-18 17:10:12', 0, '2022-10-18 17:10:12', NULL, 1),
(464, 2, NULL, NULL, 'Amit', 'Amit', '', 'US', 'toamit.wadhwa@gmail.com', '2022-10-18 20:01:53', '$2y$10$d2gk1/yN9M4eDxHRbtZsgei.Fz8r0x3bCf16hign9oJIeBZ.cwwRK', NULL, 'client/1/user/464/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-18 20:00:38', 0, '2022-10-18 20:00:38', NULL, 1),
(465, 2, NULL, NULL, 'Asi', 'Asi', '', 'US', 'subshero.80f55b@fcl.me', '2022-10-19 06:19:40', '$2y$10$Fv0MN4qZhGEL4tjQMAxRfukpBafK8fPIsrJJVTfcdv3DW5oQ/BaGe', NULL, 'client/1/user/465/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-19 06:12:24', 0, '2022-10-19 06:12:24', NULL, 1),
(466, 2, NULL, NULL, 'Ephraim Jenkins', 'Ephraim', 'Jenkins', 'US', 'your.email+faker97854@gmail.com', NULL, '$2y$10$hawssEpwCdM/R/NNU0GYGu3nGV7SDsJ3rJ7VXKwSpet6AgOWFoKAK', NULL, 'client/1/user/466/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-19 08:36:56', 0, '2022-10-19 08:36:56', NULL, 0),
(467, 2, NULL, 374, 'Rodrigo Rojas', 'Rodrigo', 'Rojas', NULL, 'rrojas@timehunter.co', '2022-10-21 20:39:38', '$2y$10$8IxNzEbyd1cIOOdBREFVm.nlcnPyScAgqD7sDqsppo7M5pz4ch0gy', NULL, 'client/1/user/467/avatar.jpg', 'xQO2rQ9e3sM5e5hCbzts2o7nXa8DO9bvxvcwLv13MfRXuoiLAdtZQON9AUda', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-21 20:37:56', NULL, 1),
(468, 2, NULL, NULL, 'HM Shuja', 'HM', 'Shuja', 'BD', 'vjnimo@gmail.com', '2022-10-19 10:41:02', '$2y$10$cH6DL/fUa6tXStm.5XCc8.m5FJozplv/K9ITQ56csUlNjvBlH4YHK', NULL, 'client/1/user/468/avatar.jpg', 'wyYCnS9sfXSGfhCaSNKKRzTi8MOSDPW9AMinI8j0WejqSdDYt2J6z39Ap2UU', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-19 10:40:52', 0, '2022-10-19 10:40:52', NULL, 1),
(469, 2, NULL, NULL, 'Olderico', 'Olderico', '', 'US', 'o.caviglia@strategycapp.com', '2022-10-19 17:21:06', '$2y$10$mz7BfZVpS5VYNW3p2t1cw.7SwbCopzS1WQlcckHmfLxOVQnsyFiW6', NULL, 'client/1/user/469/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-19 17:20:50', 0, '2022-10-19 17:20:50', NULL, 1),
(470, 2, NULL, 375, 'Paulo', 'Paulo', '', 'US', 'paulof@modulus.pt', '2022-10-19 19:52:05', '$2y$10$grdVkebCdRA3C6mBseHErO/ETm/rcFa4ZAaVMhGrfBhJBjrbDPBK6', NULL, 'client/1/user/470/avatar.jpg', 'jDfr8CbqnXGZvMeP26mXIkQkJjui9Kg5ELqK74Tm59UsTWjHXp2YwlIj6puG', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-19 19:51:50', 0, '2022-10-19 19:51:50', NULL, 1),
(471, 2, NULL, 376, 'VENKATESH GURUSWAMY', 'VENKATESH', 'GURUSWAMY', NULL, 'venkateshguruswamy123@gmail.com', '2023-03-26 06:33:58', '$2y$10$jmYOoI6kwl2IzQAUXdaBaudFCqBjRJrmUk483JWZ1Mj.oGdxooJni', NULL, 'client/1/user/471/avatar.jpg', 'RSNWQ7sdDrmmQQiJpq7hFx2MlYCofsPdyKOVxDCIxJyvk75kfqVQF97wsdIW', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-03-26 06:27:57', NULL, 1),
(472, 2, NULL, NULL, 'dd', 'dd', '', 'US', 'bimot24383@24rumen.com', '2022-10-21 04:01:29', '$2y$10$48TaeDnkhHdRLVRKJ3oZMuvIEhNqRBQZGATzMv2Cajw.esAlIflAu', NULL, 'client/1/user/472/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-21 04:00:55', 0, '2022-10-21 04:00:55', NULL, 1),
(473, 2, NULL, NULL, 'Santosh Mane', 'Santosh', 'Mane', 'US', 'consultsantosh07@gmail.com', '2022-10-21 04:36:45', '$2y$10$Ri8oNzzaCIEd34mJCQAsc.tRIiYQCjv4FncAl27wG8FD2Tv9W3F3a', NULL, 'client/1/user/473/avatar.jpg', 'OV032udzle7OtKXFeMrzlwbpRWCptDsFsmie6hH7VFkpe5Rsf7SmRSiiGXAj', 0, NULL, NULL, NULL, NULL, 'Inceptaa', NULL, '9067670019', '2022-10-21 04:36:15', 0, '2022-10-21 04:36:15', NULL, 1),
(474, 2, NULL, 377, 'Michele Bugiolacchio', 'Michele', 'Bugiolacchio', NULL, 'mbs@goteki.net', '2023-02-08 22:04:13', '$2y$10$yZaC5sn4cVn5vX2wDcq/SOccVZpolWZ9Pj7HysHEJQ70nLWm5WHNq', NULL, 'client/1/user/474/avatar.jpg', 'h0Ts98JrTt7GrgIOZcqTm9YO5nf1AuvvLtb7RXmDR4nyYZ9AfcMoNXK7c4iQ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-02-08 22:03:52', NULL, 1),
(475, 2, NULL, NULL, 'Tarunabh Dutta', 'Tarunabh', 'Dutta', 'US', 'tdstudio.tarunabh@gmail.com', NULL, '$2y$10$5M2FuPRIXJvgcmT7Rx91Z.fIVWTEPhoBCRF8Q7ucl9obLe1xQAAja', NULL, 'client/1/user/475/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-21 11:48:51', 0, '2022-10-21 11:48:51', NULL, 0),
(476, 2, NULL, 378, 'Lee Miller', 'Lee', 'Miller', 'GB', 'leedmiller@hotmail.com', '2022-10-21 17:37:43', '$2y$10$qnTpWWh7qzKLi5U1Em1PDO4xwzOIgDsoKtW8YjSFaHHCFo9BUcmqe', NULL, 'client/1/user/476/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
(477, 2, NULL, NULL, 'Badal Roy', 'Badal', 'Roy', 'US', 'badalroy52735@gmail.com', '2022-10-22 00:06:11', '$2y$10$CLtfPJqeMxgjLmYmTES.EeSxzpnH9TSgU9iKUcr41bvKcn0APAiDy', NULL, 'client/1/user/477/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-22 00:05:36', 0, '2022-10-22 00:05:36', NULL, 1),
(478, 2, NULL, 379, 'DAE S', 'DAE', 'S', 'US', 'support@bynight.dev', '2022-10-22 00:15:00', '$2y$10$S5WNKItEY9abZ1Y497lo0O8QbbpHS6N8Ke3Svxpl6YnjmdTcEcn8i', NULL, 'client/1/user/478/avatar.jpg', 'MMMqc0yPlewU5ytiSppMD4twLd8UeogTMucjCoD7ZwjJRE3TaN0coCfi7mIr', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-10-22 00:14:38', NULL, 1),
(479, 2, NULL, 380, 'Vincent De La Rosée', 'Vincent', 'De La Rosée', 'SE', 'vincent@delarosee.com', '2022-10-22 14:10:07', '$2y$10$Dqpsifl7IJ4xzDvdowPnUu6q3DUgPC0Clij1BKUPJQP17uOb/n3MC', NULL, 'client/1/user/479/avatar.jpg', 'RPEVe52eJbQGiMj87gK5LYc5q0rMHi10Vc6fvZMjJ4Gg56fyZ1fdr7isCGuJ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1);
INSERT INTO `users` (`id`, `role_id`, `team_user_id`, `wp_user_id`, `name`, `first_name`, `last_name`, `country`, `email`, `email_verified_at`, `password`, `description`, `image`, `remember_token`, `marketplace_status`, `marketplace_token`, `paypal_api_username`, `paypal_api_password`, `paypal_api_secret`, `company_name`, `facebook_username`, `phone`, `created_at`, `created_by`, `updated_at`, `reset_at`, `status`) VALUES
(480, 2, NULL, NULL, 'Deluge Zangen', 'Deluge', 'Zangen', 'BE', 'docu301@outlook.com', '2022-10-22 15:00:23', '$2y$10$DsnYVKBFSEo1d4L9aKKV0uRMr0N4vIGmKXsz.H5DV94L1le.Lhlb2', NULL, 'client/1/user/480/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-22 15:00:13', 0, '2022-10-22 15:00:13', NULL, 1),
(481, 2, NULL, NULL, 'Max', 'Max', '', 'US', 'solaris_@ukr.net', '2022-10-24 06:49:16', '$2y$10$BX1k7BKc/aA/cwEM9DsK2uCE2O73aaciqO2OWgsddMbkKyk0RjinK', NULL, 'client/1/user/481/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-24 06:47:56', 0, '2022-10-24 06:47:56', NULL, 1),
(482, 2, NULL, NULL, 'Lorem', 'Lorem', '', 'US', 'ltd@lorem.com', NULL, '$2y$10$NAkdJaAOKmzUbTx94oUrF.hJKHJbeG3bZrsAWZVz1GyBxtQm/g73a', NULL, 'client/1/user/482/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-24 17:53:32', 0, '2022-10-24 17:53:32', NULL, 0),
(483, 2, NULL, NULL, 'Hamza', 'Hamza', '', 'US', 'diglapagency@gmail.com', '2022-10-25 08:41:02', '$2y$10$E93ISBWqFHPfqP9IAuzqiOhPpLjLVYZodh0sLVh0g7AJ3rpfDMcJm', NULL, 'client/1/user/483/avatar.jpg', 'BNR7ehT1SUsT6knaWtTA9WuCnv0VbtDuaDdz7CbdR6gb2wOCzL5HyftZBmOO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-25 08:37:13', 0, '2022-10-25 08:37:13', NULL, 1),
(484, 2, NULL, NULL, 'Bikram Ghosh', 'Bikram', 'Ghosh', 'US', 'bikramghosh359@gmail.com', '2022-10-28 07:44:12', '$2y$10$rYIG9NQ.rQI0LFoDJ67JEukWoCeuBP5.ItqroBnObvXtnu4L4qpuW', NULL, 'client/1/user/484/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-28 07:42:51', 0, '2022-10-28 07:42:51', NULL, 1),
(485, 2, NULL, NULL, 'Raj', 'Raj', '', 'US', 'charms.05anybody@icloud.com', '2022-10-29 06:05:08', '$2y$10$/FfCcSiINuAVaaOAu1iyL.jWd6cZOwdItMgwTFcqM5NqdoQ6e6ARK', NULL, 'client/1/user/485/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-29 05:57:53', 0, '2022-10-29 05:57:53', NULL, 1),
(486, 2, NULL, NULL, 'Marek Jakubčík', 'Marek', 'Jakubčík', 'US', 'forusak@gmail.com', '2022-10-29 11:36:38', '$2y$10$KuZ.oatpgmUligjuiQaXvu2amd4apqikzTToBEyehTGhEwvb40wLS', NULL, 'client/1/user/486/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-29 11:36:12', 0, '2022-10-29 11:36:12', NULL, 1),
(487, 2, NULL, 382, 'Joe Lipburger', 'Joe', 'Lipburger', NULL, 'sunny.summer@gmx.at', '2022-10-30 15:18:09', '$2y$10$xeAj/Eq2OwTfW2GyO157NOinAOrD1fztWGLkCUnq6SHZLleSvx0ZK', NULL, 'client/1/user/487/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
(488, 2, NULL, 383, 'John Pham', 'John', 'Pham', NULL, 'workstation@gmail.com', '2022-10-31 06:18:18', '$2y$10$laxE1CJdvApA9g3a/q..a.eaN9pfWG9T6rcusyKrgysNaBkoUL.YC', NULL, 'client/1/user/488/avatar.jpg', '5m9iF0DaCWGtfJRtSTDGdeP85LQ3bBS8DOAxAJCIgSLwP1do3BqNbb9ckb57', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
(489, 2, NULL, NULL, 'Raghu', 'Raghu', '', 'US', 'raghutesttt@gmail.com', '2022-10-31 10:40:17', '$2y$10$ELI6503N5brw0dYd9Qq5PuJU8Ns.FToGq7RT1pnnyvg61N3q2iewq', NULL, 'client/1/user/489/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-31 10:40:03', 0, '2022-10-31 10:40:03', NULL, 1),
(490, 2, NULL, NULL, 'Pranab BBB', 'Pranab', 'BBB', 'US', 'pgohain@yahoo.com', '2022-11-01 05:17:00', '$2y$10$4jmx4JjmJQheqF/AOCBsDeR0tCIska72jrDuvbLLgIlJ4i1ED8F4e', NULL, 'client/1/user/490/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-01 05:07:42', 0, '2022-11-01 05:07:42', NULL, 1),
(491, 2, NULL, NULL, 'Darshan Aswani', 'Darshan', 'Aswani', 'US', 'darshanaswani63@gmail.com', '2022-11-04 03:22:33', '$2y$10$4x10/T6Ee40TOVSCA8dFXO.aEpXMiY92XLLTDvyJjzvy/eL/tNFsi', NULL, 'client/1/user/491/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-04 03:21:59', 0, '2022-11-04 03:21:59', NULL, 1),
(492, 2, NULL, NULL, 'Yaroslav Moroz', 'Yaroslav', 'Moroz', 'UA', 'yaroslav@interstellarconsulting.com', '2022-11-11 08:34:55', '$2y$10$H//XLeS5DeUVqYNSzvGrtudyOZTxZQU0tq2CogqlB6KhUMWMqku0q', NULL, 'client/1/user/492/avatar.jpg', '0wTGz3jFqMnHGgAE6wNoD14Me0cq6Rym1Lz4Ref48tFb9uiRSU0NZ9O9Mm0B', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-11 08:34:29', 0, '2023-04-24 05:11:24', NULL, 1),
(493, 2, NULL, NULL, 'Andrew', 'Andrew', '', 'US', 'eltylxeno@gmail.com', '2022-11-11 20:13:53', '$2y$10$UGgpcmbaVvKo3ypirl.fweRo.ICWb3OpkP1dVLrJHki5owtJFGnH2', NULL, 'client/1/user/493/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-11 20:13:33', 0, '2022-11-11 20:13:33', NULL, 1),
(494, 2, NULL, 387, 'Patrick Morgan', 'Patrick', 'Morgan', NULL, 'patrick@appsumo.com', '2022-11-15 13:17:40', '$2y$10$0PMbLJO7ni1PjwFBzy77J.Zu.SqmLRGDMG8m2x8QSyI6AwnhPyIae', NULL, 'client/1/user/494/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
(495, 2, NULL, NULL, 'Mike P', 'Mike', 'P', 'US', 'mike.piacentini@larksuite.com', '2022-11-18 20:34:01', '$2y$10$kEzPN.y.z/4lY.xulQrP9emL0W7Dj6TdDTsM6ASYY0JSej2neS84e', NULL, 'client/1/user/495/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-18 20:33:23', 0, '2022-11-18 20:33:23', NULL, 1),
(496, 2, NULL, NULL, 'Leo', 'Leo', '', 'US', 'leo@plutio.com', '2022-11-25 10:36:17', '$2y$10$C0LXqqd3ZALF3LGBVNB90eiFToiqFe5fTNapInFgIB9j19hlupuS.', NULL, 'client/1/user/496/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-25 10:36:08', 0, '2022-11-25 10:36:08', NULL, 1),
(497, 2, NULL, NULL, 'Thien', 'Thien', '', 'US', 'thienvlkt@yahoo.com.vn', '2022-11-26 14:09:38', '$2y$10$4qFmgB7NRXzIP8KERnzjVOqXKUAspiG1R7eyNFCGu38JakaxS2MRO', NULL, 'client/1/user/497/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-26 14:09:12', 0, '2022-11-26 14:09:12', NULL, 1),
(498, 2, NULL, NULL, 'Kofi Tettefio', 'Kofi', 'Tettefio', 'AU', 'coffeelincoln@gmail.com', '2022-11-30 10:47:09', '$2y$10$TdbrdA9MoUHhtBoCyuJWseeN6iQpDD.s6S0Ur3xmMxOZBK9H7VZZ2', NULL, 'client/1/user/498/avatar.jpg', 'ltVPjc3DzU7fW3sTaefbfbd1v1bmgrNSxs0vaxYhmDfM0zLE8E7kCoyraOwI', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-30 10:46:38', 0, '2022-11-30 10:46:38', NULL, 1),
(499, 2, NULL, NULL, 'roma', 'roma', '', 'US', 'romacaserta@gmail.com', '2022-12-01 16:41:39', '$2y$10$H6gzlIlc8Hs4jR1ziW1hZukAKOduGnwtbOus3DQUQO1aCHs43iPu6', NULL, 'client/1/user/499/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-01 16:41:22', 0, '2022-12-01 16:41:22', NULL, 1),
(500, 2, NULL, NULL, 'Vojislav Ilic', 'Vojislav', 'Ilic', 'RS', 'vojislav.ilich@gmail.com', '2022-12-02 04:24:52', '$2y$10$fsXDNzZzmksY1k/SCZkOh.3ggOOv9kLovucqkBvjo6eC/waZFGpNy', NULL, 'client/1/user/500/avatar.jpg', 'cF2HOQsf1Jji4btnWYMgVLyBdbfhg0n42X6bC9ZAvh1WoiT4F9i2UIyu8ogm', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-02 04:24:29', 0, '2022-12-13 22:34:29', NULL, 1),
(501, 2, NULL, 94, 'Jacques Vreugdenhil', 'Jacques', 'Vreugdenhil', 'FR', 'jacques.vreugdenhil@gmail.com', '2022-12-02 08:10:50', '$2y$10$OxTB/yjFM01fxp.npBEXNOJINvR/gZpl5NwM3zp2RSVUwCJ6nIL7i', NULL, 'client/1/user/501/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
(502, 2, NULL, NULL, 'Simranjeet Singh', 'Simranjeet', 'Singh', 'US', 'info.swagdigital@gmail.com', '2022-12-06 09:37:06', '$2y$10$Lklzsxuz9ZaqOueH907x4OGMib8DApmDKRB6VGk4bMxF381vQHo.6', NULL, 'client/1/user/502/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-06 09:36:51', 0, '2022-12-06 09:36:51', NULL, 1),
(503, 2, NULL, NULL, 'Oliwia', 'Oliwia', NULL, 'US', 'oliwka19891@gmail.com', '2022-12-06 10:01:06', '$2y$10$lk4XlbjyNK4iQ4DNH5XJJubFx.GSgRIHE3HzuSfethWvyLHy1Bycy', NULL, 'client/1/user/503/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-06 09:48:29', 0, '2022-12-06 09:48:29', NULL, 1),
(504, 2, NULL, NULL, 'MD AMDADUL ISLAM', 'MD', 'AMDADUL', 'US', 'arun@contact.passivern.com', '2022-12-06 18:27:01', '$2y$10$kXvnLPiyMi35nJmZ0N/9Y.UoCIAA0Zu3T7YKt4JlXaLhfmdTbJ.J6', NULL, 'client/1/user/504/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-06 18:26:17', 0, '2022-12-06 18:26:17', NULL, 1),
(505, 2, NULL, NULL, 'martex', 'martex', '', 'US', 'amartexweb@gmail.com', '2022-12-07 01:48:26', '$2y$10$XbykNJ9fpLaROKANQto3EOpUJqJFF2hNnSq.EDSB83SAzIlC9uyJ.', NULL, 'client/1/user/505/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-07 01:48:14', 0, '2022-12-07 01:48:14', NULL, 1),
(506, 2, NULL, NULL, 'Riazul islam', 'Riazul', 'islam', 'US', 'Skriaz30@gmail.com', '2022-12-07 04:56:01', '$2y$10$3D9yaOXoQqnAej8pAXa0OO5WqXRU8BoCSmPmhxK9/XcPtzsytsguq', NULL, 'client/1/user/506/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-07 04:55:41', 0, '2022-12-07 04:55:41', NULL, 1),
(507, 2, NULL, NULL, 'Mojomx', 'Mojomx', '', 'US', 'mojomx@gmail.com', '2022-12-07 10:22:54', '$2y$10$Pb3Y/yWHHeBf0ThXrmDvg.moCFIcidgZddiczPJu5yCTb3xSyOXEK', NULL, 'client/1/user/507/avatar.jpg', 'thicmn4l1FkSAMpddJoCC3Cqm2NlvbkRghFELwtz0tZNIFgAz42HeH3jzz5e', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-07 10:20:30', 0, '2022-12-07 10:20:30', NULL, 1),
(508, 2, NULL, NULL, 'karek', 'karek', '', 'US', 'kravzz1@ya.ru', '2022-12-08 22:30:43', '$2y$10$k/X1qOvbVF1BPofTP4iZsuNWswmlhr2E7mPrU/jLvLxz4jTP.mukS', NULL, 'client/1/user/508/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-07 16:48:15', 0, '2022-12-07 16:48:15', NULL, 1),
(509, 2, NULL, NULL, 'intergretly', 'intergretly', '', 'US', 'test_intergretly@example.com', NULL, '$2y$10$Km7Uj4ok/LWIE94aHtdv6.niGBP6NL6G6sg8IMLdaWR4/wFa0ccVG', NULL, 'client/1/user/509/avatar.jpg', 'gKCugzA6YujE7VizfDuKhm0z4SdN2ntLqVgJ6lPDdyyySo5E5JLk0yQHlDV7', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-08 10:18:31', 0, '2022-12-08 10:18:31', NULL, 1),
(510, 2, NULL, NULL, 'JC Casagan', 'JC', 'Casagan', 'US', 'ijchael2@gmail.com', '2022-12-08 12:55:51', '$2y$10$2hr0RSrVbVQKee8tq03Pk.oG4ikU8Z5TgE.FSYmEhPyQLWFDBlwBe', NULL, 'client/1/user/510/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-08 12:55:34', 0, '2022-12-08 12:55:34', NULL, 1),
(511, 2, NULL, 389, 'Lawrence Giordano', 'Lawrence', 'Giordano', NULL, 'lawrencewgiordano@gmail.com', '2022-12-09 04:02:33', '$2y$10$Si.E9WoHhUN0fR8HxHjcle9ytTv0eZEuPfTPn/wBGZAKAgQ2yCUPm', NULL, 'client/1/user/511/avatar.jpg', 'KOVawfL0vtpUqE9JRgdvlOOZMctAzPHE6RvzT4wl9HcWcBZBd6hMUykF602I', 1, 'dVUHAfchWJdEpkLJ', 'AfzkocYmGZaxxFD5qAbffcuhTV4Jz3VWVPUigrA_trj1nlLlh_AiPVWq0R8yoE639111hPm_MJumRxXF', 'stunad1011', 'EBA6jpSpfAn1TeF8chJZa7NGuZMorb2dIURKOiKhIfW1qGopCJVMan-KiA8aG8IqhDNlkoz-WgQYmleX', NULL, NULL, NULL, NULL, 0, '2022-12-13 09:51:10', NULL, 1),
(512, 2, NULL, NULL, 'Huzairi', 'Huzairi', '', 'US', 'huzairi@icloud.com', '2022-12-10 06:05:56', '$2y$10$jNsL4vhjcxoPQdrx8MF54eJaNg9AZNybnE2aNGE0E/m8brFK7K66i', NULL, 'client/1/user/512/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-10 06:05:37', 0, '2022-12-10 06:05:37', NULL, 1),
(513, 2, NULL, NULL, 'Ganesh Ram Jayaraman', 'Ganesh Ram', 'Jayaraman', 'TH', 'ganrad@hotmail.com', '2022-12-10 14:52:08', '$2y$10$yURfki3Q4n77wiQt0qfQzehth42BUOa58Vh6vwyDsq6pJ8nCbp/Im', NULL, 'client/1/user/513/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, 'Tridentpack and Engineering Company Limited', NULL, '+66818413491', '2022-12-10 14:26:11', 0, '2022-12-10 14:26:11', NULL, 1),
(514, 2, NULL, 390, 'Daniel Kroft', 'Daniel', 'Kroft', 'GB', 'subshero@consciousmail.com', '2022-12-13 10:16:37', '$2y$10$GoQanvgRibJQeF.g0S6fnOxlQQojZVtgh3OK5VPwr3H3IINsUktqC', NULL, 'client/1/user/514/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
(515, 2, NULL, NULL, 'Nadia Sherman', 'Nadia', 'Sherman', 'US', 'genres-logjams-06@icloud.com', '2022-12-13 16:25:38', '$2y$10$fzPY.1t5wqGUukd4QtyC6udsCNMgnGSIenqb6ehYukPYuMsGvk1yy', NULL, 'client/1/user/515/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-13 16:23:34', 0, '2022-12-13 16:23:34', NULL, 1),
(516, 2, NULL, NULL, 'Nayeem', 'Nayeem', '', 'US', 'kafapey125@fanneat.com', '2022-12-14 13:14:51', '$2y$10$mg5p1m1cL21I4cZvNpkKrOglvHSS.i8ZtrLJ7HeE2KowkAAHGiDFu', NULL, 'client/1/user/516/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-14 13:14:11', 0, '2022-12-14 13:14:11', NULL, 1),
(517, 2, NULL, 391, 'Jeff Mankini', 'Jeff', 'Mankini', 'US', 'jeff@upclickdigital.com', '2022-12-14 13:53:03', '$2y$10$MWOckR107xqwatIAN1H0gO8h7DpZ.g/XuD5VJV7F9zGT76gpU2Atm', NULL, 'client/1/user/517/avatar.jpg', 'nmbuKcT8BSMXZwZjsyOedAhECgkeXwS8GIizlEJ4BaOHnFv5JrplW9VPWu4H', 0, NULL, NULL, NULL, NULL, 'UpClick Digital', 'upclickdigital', '+1 (414) 563-7141', NULL, 0, '2022-12-14 13:52:42', NULL, 1),
(518, 2, NULL, 392, 'Khaled.F. Haik', 'Khaled.F.', 'Haik', NULL, 'khaled.design.creative@gmail.com', '2022-12-14 13:54:16', '$2y$10$4wFTrdIgMl1oLgM7IT00KuG2jBsNSR5deXmCgLtc1b9VGpt8WWLIO', NULL, 'client/1/user/518/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
(519, 2, NULL, NULL, 'bay', 'bay', '', 'US', 'baywatchblues@gmail.com', '2022-12-15 02:51:40', '$2y$10$npibISc7HTyZUs6bVMTwleJthwHrKqhjI2duZuO2nLyI/riMorwQe', NULL, 'client/1/user/519/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-15 02:51:16', 0, '2022-12-15 02:51:16', NULL, 1),
(520, 2, NULL, NULL, 'Subshero App', 'Subshero', 'App', 'US', 'subsheroapp@gmail.com', '2022-12-15 07:38:01', '$2y$10$SdYzEfvtN6KaCNU1pX5C2eN72dctinv8.t17MNDSSXiUzIzvkswyS', NULL, 'client/1/user/520/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-15 07:35:14', 0, '2022-12-15 07:35:14', NULL, 1),
(521, 2, NULL, NULL, 'asd', 'asd', '', 'US', 'wacerih209@bitvoo.com', '2022-12-17 18:34:30', '$2y$10$SGA7L9WyPpkFa5rsSu.Uyenil4jq4TxerU/EJRMJ5IY04xpIx6qiq', NULL, 'client/1/user/521/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-17 18:33:28', 0, '2022-12-17 18:33:28', NULL, 1),
(522, 2, NULL, NULL, 'Danielle Bourgeois', 'Danielle', 'Bourgeois', 'US', 'daniesq@gmail.com', '2022-12-19 15:45:01', '$2y$10$hMhd98qNWP5SqLmPmH8wruoXwHrZ79LLT/jECpa3V2jQF9Cigz7W2', NULL, 'client/1/user/522/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-19 15:01:40', 0, '2022-12-19 15:01:40', NULL, 1),
(523, 2, NULL, NULL, 'John Arthur', 'John', 'Arthur', 'US', 'admin@telnergy.uk', NULL, '$2y$10$6tCTCobBGrE7L5..DWkVCOsKsZXyWsguEB.Iq6IV/LE/qHHvVSYQe', NULL, 'client/1/user/523/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-19 22:28:24', 0, '2022-12-19 22:28:24', NULL, 0),
(524, 2, NULL, NULL, 'John Arthur', 'John', 'Arthur', 'US', 'admin1@perfectcall.uk', '2022-12-19 22:35:38', '$2y$10$mpCgsqMgHEG6m01cRpqmF.37SDRfWjRt6uid31pM/iwnCg1jgnhwK', NULL, 'client/1/user/524/avatar.jpg', 'x3J6OGtkwhzLCOJ7UBm31fBcn1sAfHvQeivvhos5NabWrUqqupIpfyfdlilS', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-19 22:35:01', 0, '2022-12-19 22:35:01', NULL, 1),
(525, 2, NULL, NULL, 'Matt', 'Matt', '', 'US', 'subsherotest@ltdmail.io', '2022-12-20 17:29:12', '$2y$10$v/WcaHNCrVceFGZU1P.Od.495K36FZyGPDOUbF/ZZsgoqdxYWZoc2', NULL, 'client/1/user/525/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-20 17:28:12', 0, '2022-12-20 17:28:12', NULL, 1),
(526, 2, NULL, NULL, 'Test Ac', 'Test', 'Ac', 'US', 'testthatapp2@gmail.com', '2022-12-21 07:09:52', '$2y$10$xE6zOCSov0lfQz7E3yDaQOOX9VyJ1dU1ZNsHHFv13L48d01ZI1C12', NULL, 'client/1/user/526/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 07:09:34', 0, '2022-12-21 07:09:34', NULL, 1),
(527, 2, NULL, NULL, 'Apprenons De Zero', 'Apprenons', 'De Zero', 'GN', 'apprenonsdezero@gmail.com', '2022-12-21 23:07:13', '$2y$10$Zi0.LlJ.GLTivV.XsTT0iOZGKei.xOkwxt07bCtG6WtEDSBpwvqg.', NULL, 'client/1/user/527/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 23:06:29', 0, '2022-12-21 23:06:29', NULL, 1),
(528, 2, NULL, NULL, 'Phuong Nguyen', 'Phuong', 'Nguyen', 'US', 'mr.nguyenduyphuong@gmail.com', '2022-12-22 10:40:41', '$2y$10$vXMft5oazjYDvO8hTcDI/eSeEKSvdCVyAG4M.oavFJPNorZFWWLQy', NULL, 'client/1/user/528/avatar.jpg', '0sdrxADrgU6zABQnjj2pGjuXwnjFlHueCUivUce87ZFODtn9sIx3GExtVLzB', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-22 10:40:27', 0, '2022-12-22 10:40:27', NULL, 1),
(529, 2, NULL, NULL, 'Keval Vashi', 'Keval', 'Vashi', 'US', 'mapcheckai@gmail.com', '2022-12-23 15:10:08', '$2y$10$pHwJU8Ra21AcIZQeaxJzuOHvZ1NwxOJovttg9dortmCdhpVnqqik2', NULL, 'client/1/user/529/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-23 15:09:14', 0, '2022-12-23 15:09:14', NULL, 1),
(530, 2, NULL, NULL, 'Yugant Nagralawala', 'Yugant', 'Nagralawala', 'US', 'nagralawalay@gmail.com', '2022-12-23 15:30:17', '$2y$10$7MzTrNujRctZx6wZ8nebJuR8ivOz4142ar7fbRGoDuZnnw7gAoku6', NULL, 'client/1/user/530/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-23 15:13:12', 0, '2022-12-23 15:13:12', NULL, 1),
(531, 2, NULL, NULL, 'lamar', 'lamar', NULL, 'US', 'Flyboylamar15@gmail.com', NULL, '$2y$10$SdlFDuSmnbuT/3FSezI6MemSyzw3osUUUifx1MvFM9plAGwjFXfTW', NULL, 'client/1/user/531/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-25 05:38:19', 0, '2022-12-25 05:38:19', NULL, 0),
(532, 2, NULL, NULL, 'Kit', 'Kit', '', 'US', 'kitt88vn@gmail.com', '2022-12-25 13:14:47', '$2y$10$uBkOkWj4ObgwIIqIfU4Xq.GtKbcG86xPtk0XhLzeepszx4/5Y95UW', NULL, 'client/1/user/532/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-25 13:14:09', 0, '2022-12-25 13:14:09', NULL, 1),
(533, 2, NULL, NULL, 'Kevin', 'Kevin', '', 'US', 'hellokevinlee@gmail.com', '2022-12-25 21:18:20', '$2y$10$5b3RlM7SU7AnE3qni0nKz.lbX7H7Eevb09uPWkP0KY1bS4I4SSUOm', NULL, 'client/1/user/533/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-25 21:16:42', 0, '2022-12-25 21:16:42', NULL, 1),
(534, 2, NULL, NULL, 'Administrator Z', 'Administrator', 'Z', 'US', 'activechat.app@gmail.com', '2022-12-29 10:52:55', '$2y$10$p7njDpIdudxigQF77eu.1.mKhe0PrCg.H.V1cynmcizVV6Vm1ZqLO', NULL, 'client/1/user/534/avatar.jpg', 'd4GBVGEzjh51y8UBIA3m4eqZ4nxi8SSNEw0TSi8gmFhu5h33qi7Y6hTOZDDY', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-29 10:50:22', 0, '2022-12-29 10:50:22', NULL, 1),
(535, 2, NULL, NULL, 'nano', 'nano', '', 'US', 'nanodog.net@gmail.com', '2022-12-30 05:52:46', '$2y$10$Ul6QBib1H8W8g6/6uuMjJ.HkAh.3K..QGYU6kCq1XzmCNfjvHcXd2', NULL, 'client/1/user/535/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-30 05:52:24', 0, '2022-12-30 05:52:24', NULL, 1),
(536, 2, NULL, NULL, 'Rik Renzenbrink', 'Rik', 'Renzenbrink', 'US', 'Rikrenzenbrink@hotmail.com', '2022-12-30 12:23:22', '$2y$10$RwvoFpmBt4NhdweyYmosk.BJc3sn1hlvTb1irXlFNiaPUYiuMuLfe', NULL, 'client/1/user/536/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-30 12:23:06', 0, '2022-12-30 12:23:06', NULL, 1),
(537, 2, NULL, NULL, 'Brett Harper', 'Brett', 'Harper', 'US', 'derickjohn690@gmail.com', '2022-12-31 06:01:21', '$2y$10$4MD078LENEcJcxSCg.nh6eyhSjx/5qrGkSEuzmj64qJ1IlUOQAyw6', NULL, 'client/1/user/537/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-31 05:59:26', 0, '2022-12-31 05:59:26', NULL, 1),
(538, 2, NULL, NULL, 'Ibrahim', 'Ibrahim', '', 'US', 'iaimam@hotmail.com', '2022-12-31 20:20:37', '$2y$10$4YaB/E5.Nlgs8Jiq7VaO2.OgMyHqyYPm6Xp6pej7zGJ8ufKb8VwrG', NULL, 'client/1/user/538/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-31 20:18:18', 0, '2022-12-31 20:18:18', NULL, 1),
(539, 2, NULL, NULL, 'Dev Hisaria', 'Dev', 'Hisaria', 'US', 'dev.hisaria@gmail.com', '2023-01-02 10:55:08', '$2y$10$x.nvB2L6kBR7P1PJP8.39eHqEez/bGWFgtaMV2yksCyXJH//0Y3YS', NULL, 'client/1/user/539/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-02 10:54:54', 0, '2023-01-02 10:54:54', NULL, 1),
(540, 2, NULL, NULL, 'Danijel Lucic', 'Danijel', 'Lucic', 'US', 'lucic.danijel1@gmail.com', '2023-01-02 14:08:59', '$2y$10$SGCO0FgOPJ76epik4OdHZ.fALXfw7s90x/HrJWjAnMbWZjqLRZnd6', NULL, 'client/1/user/540/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-02 14:07:07', 0, '2023-01-02 14:07:07', NULL, 1),
(541, 2, NULL, NULL, 'Tom Gifford', 'Tom', 'Gifford', 'US', 'tgifford@comcast.net', NULL, '$2y$10$tLtQA9fVTGAeDC9ZK/WeEOAJDWnlVFng0QddRsg2rWyR7SoNQ5zUy', NULL, 'client/1/user/541/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-02 19:05:01', 0, '2023-01-02 19:05:01', NULL, 0),
(542, 2, NULL, NULL, 'Angelou Kates', 'Angelou', 'Kates', 'US', 'angelou@dentistrybusiness.com', '2023-01-04 11:31:37', '$2y$10$G7cYYZ6ctxOo.k3tSYzaZ.4lxU.pPNQ0Skx1D.XNvYJFltf5aJSsa', NULL, 'client/1/user/542/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-04 11:22:23', 0, '2023-01-04 11:22:23', NULL, 1),
(543, 2, NULL, NULL, 'Gregg Taylor', 'Gregg', 'Taylor', 'US', 'gre.gg@gre.gg', '2023-01-06 17:19:12', '$2y$10$v9Mu/p0BOgOpswa6pevBe.wHcaSTanui904BMblZ8CK2VFAWfWqp2', NULL, 'client/1/user/543/avatar.jpg', 'afKY5KY7NZGOFCN3YLS0Sj5n7w7tRj0KXM5LVza4dfohTrLiZd0EMfNXDa55', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 17:18:46', 0, '2023-01-06 17:18:46', NULL, 1),
(544, 2, NULL, NULL, 'Bhavya Singh', 'Bhavya', 'Singh', 'US', 'bhavyasingh50@gmail.com', '2023-01-08 07:55:43', '$2y$10$OlmanuE0HWhcRCTHQcAZf.JPcHP2p0KkXlYvmdLhDteJR1P6inqD.', NULL, 'client/1/user/544/avatar.jpg', 'y9FW9Yj52x6l5jNZszr7OJZJdrxhvg6Q0I7yqMEHvslCsUVicPnraKPmImMC', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-08 07:55:22', 0, '2023-01-08 07:55:22', NULL, 1),
(545, 2, NULL, NULL, 'Yehu Levy', 'Yehu', 'Levy', 'US', 'osimtovmarketing@gmail.com', '2023-01-11 14:27:05', '$2y$10$6eQYrxqAqZF0tC/mLBlWK.mXbrDfDbn/IT/8lKYEndpXttQHwV62O', NULL, 'client/1/user/545/avatar.jpg', 'Ks08bJF4Q8ndD9d2FpVIjH58P0bYlwzpxsT1JVvyl0jWqPpNYHQerMVisF7L', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-11 14:26:02', 0, '2023-01-11 14:26:02', NULL, 1),
(546, 2, NULL, NULL, 'Eden Lutchmedial', 'Eden', 'Lutchmedial', 'US', 'elutchmedial@gmail.com', '2023-01-11 20:12:37', '$2y$10$K2aiikriVs5BXBNqALzfLOKS2vYA86gq7UMKqj91Yaog0mqvpUJpO', NULL, 'client/1/user/546/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-11 19:51:48', 0, '2023-01-11 19:51:48', NULL, 1),
(547, 2, NULL, NULL, 'LAA', 'LAA', NULL, 'US', 'louy_germany@hotmail.com', NULL, '$2y$10$QZVj8rTqBT2uPcMCrQ5gG.TEYWGScLpIKq/gSF7mfk7XDgPEht8pC', NULL, 'client/1/user/547/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-13 08:24:04', 0, '2023-01-13 08:24:04', NULL, 0),
(548, 2, NULL, NULL, 'Yehu Websites', 'Yehu', 'Websites', NULL, 'yehu@osimtov.com', '2023-01-13 13:06:47', '', NULL, 'client/1/user/548/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-13 13:05:43', 0, '2023-01-13 13:06:47', NULL, 1),
(549, 2, 545, NULL, 'Yehu Websites', 'Yehu', 'Websites', NULL, 'sureimmigrationca@gmail.com', '2023-01-13 13:15:17', '$2y$10$9VQvkqRBBzY0YwLXluWF/eNutWcUwipmTJHNXPDtUvFdYsfuk12O2', NULL, 'client/1/user/549/avatar.jpg', 'j6bvKcc19Mcv3E7CmQOXcTRr8cxUrbyGBE6B4oC7OEAP8bP9he9k1O9bFyVg', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-13 13:13:12', 0, '2023-01-13 13:25:32', NULL, 1),
(550, 2, NULL, NULL, 'Joske Vermeulen', 'Joske', 'Vermeulen', 'BE', 'subs@meelwas.be', '2023-01-14 16:45:24', '$2y$10$ZVHn2GVh0JwPMXx0MT34POEWMESskLvY2GoYi7OQC.JD0FCvmiZDa', NULL, 'client/1/user/550/avatar.jpg', 'Fuv7oH7Grpm31I2odl2QibYUCtRIvmm9VOWhwHgWBMBQvVj9LHzWeaKNGgUT', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-14 16:44:13', 0, '2023-01-14 16:44:13', NULL, 1),
(551, 2, NULL, NULL, 'Johan Vervaeck', 'Johan', 'Vervaeck', 'BE', 'mail@vervaeck.be', '2023-01-15 13:37:00', '$2y$10$aftbHGgNjN9ZPChLZGjQPuP2AnUqucWmblQPXz41DFbgV8hROkzSi', NULL, 'client/1/user/551/avatar.jpg', 'VrMcJGTepTZeMsu1GaxRC1K74DSu4vlxs5nYXgdtXGWqyYpcEhwHo5u5xvtL', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-15 13:36:20', 0, '2023-01-15 13:36:20', NULL, 1),
(552, 2, NULL, NULL, 'GoBAS1 !', 'GoBAS1', '!', 'ES', 'gopala.bas1@gmail.com', '2023-01-16 04:26:44', '$2y$10$sQZW.QKTvM/Im2iWPm8KZuV3ynBVKr5pP0d7IVzUeq4IOeU9L57ca', NULL, 'client/1/user/552/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-16 04:20:47', 0, '2023-02-02 23:44:13', NULL, 1),
(553, 2, NULL, NULL, 'Rik Renzenbrink', 'Rik', 'Renzenbrink', NULL, 'rikrenzenbrink@gmail.com', NULL, '', NULL, 'client/1/user/553/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-16 21:55:56', 0, '2023-01-16 21:55:56', NULL, 0),
(554, 2, NULL, NULL, 'ricky', 'ricky', NULL, 'US', 'sb.won@whatssub.co', NULL, '$2y$10$nM7vMbgr.c/OBlms9TxkNOlPZtDc7EL.TVr2XO5siXS0penajh5Wa', NULL, 'client/1/user/554/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-17 08:05:22', 0, '2023-01-17 08:05:22', NULL, 0),
(555, 2, NULL, NULL, 'Balachandiran NK', 'Balachandiran', 'NK', 'IN', 'accounts@inbox.smartaccountants.in', '2023-01-19 05:25:29', '$2y$10$jTpzeQr.P1SGSzcOmOE.xO1rywh8sBdGpQMhuJ.Dgtg8WmfQuvDAe', NULL, 'client/1/user/555/avatar.jpg', '9TqzkXWh5EthogRhVUJnbeiEDJe9k5GQfZmoVcWI4KrbX6udIX9w78ODg5bs', 0, NULL, NULL, NULL, NULL, 'Smart Accountants', NULL, NULL, '2023-01-19 05:24:25', 0, '2023-01-19 05:24:25', NULL, 1),
(556, 2, NULL, NULL, 'munna', 'munna', NULL, 'US', 'mff22388@gmail.com', NULL, '$2y$10$60bT/VWYA4JVT95vWhhXHONF7CDkeXRK.28AE1s1BCvcEsyW3q.CG', NULL, 'client/1/user/556/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-21 18:38:20', 0, '2023-01-21 18:38:20', NULL, 0),
(557, 2, NULL, NULL, 'Isabella Scott', 'Isabella', 'Scott', 'US', 'Izziescott16@gmail.com', '2023-01-22 00:06:26', '$2y$10$7DIfTWgn.E03HZDOCHdovuYDXiJHB8te7aUhCGxyvmFtM.WQ.wImO', NULL, 'client/1/user/557/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-22 00:04:52', 0, '2023-01-22 00:04:52', NULL, 1),
(558, 2, NULL, NULL, 'Eider', 'Eider', '', 'US', 'eiderfernandezb@gmail.com', '2023-01-22 09:45:43', '$2y$10$8F8SrzZkwbIoZywAdF4vc.4ukWxD.GSUfbVcaL/BUrykXp2MrcAO6', NULL, 'client/1/user/558/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-22 09:45:03', 0, '2023-01-22 09:45:03', NULL, 1),
(559, 2, NULL, NULL, 'Daniel', 'Daniel', '', 'US', 'sjidaniel@yahoo.co.uk', '2023-01-24 02:37:48', '$2y$10$swxRnZXS/anfRqepnBh2X.1fBxWSDuPW4yM/7meWN8MdhmZaNYe9C', NULL, 'client/1/user/559/avatar.jpg', 'kJEqaqPATpPqLeRZLr5Q9YJ54KXCcnqwmZYQL3mTfzaYSfi9oU3dUIbVUb7o', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-24 02:36:03', 0, '2023-01-24 02:36:03', NULL, 1),
(560, 2, NULL, NULL, 'Andrei Vasilescu', 'Andrei', 'Vasilescu', 'US', 'adi@visul.eu', '2023-01-24 11:23:08', '$2y$10$gVbmgTMtq.jCZydFjfHIDOySELsfb5iIZzfTY3qkfK69RCTwBwcmu', NULL, 'client/1/user/560/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-24 11:22:39', 0, '2023-01-24 11:22:39', NULL, 1),
(561, 2, NULL, NULL, 'ojwjnefoin', 'ojwjnefoin', '', 'US', 'wepigfnowoieinf@yopmail.com', '2023-01-25 13:35:29', '$2y$10$uNiBDdwMYJbSmybucI4OpegKJswIQpkq7OeGY6/OB7owZCZBKq2Lm', NULL, 'client/1/user/561/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-25 13:35:08', 0, '2023-01-25 13:35:08', NULL, 1),
(562, 2, NULL, NULL, 'Evren', 'Evren', NULL, 'US', 'evrein@gmail.com', NULL, '$2y$10$IYZlnl1YCrReBGI0d5xgreidSCh7oRj.NKwIifMh/C79aKR3Y9FkG', NULL, 'client/1/user/562/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-25 18:53:44', 0, '2023-01-25 18:53:44', NULL, 0),
(563, 2, NULL, NULL, 'Willian eu', 'Willian', 'eu', 'BR', 'gabriel.ws@live.com', NULL, '$2y$10$QKcdQKhhBMeh9bOMsJmiEuWhGk7r.3VbNX376AcKrpkYycE3KQOVe', NULL, 'client/1/user/563/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-25 22:59:44', 0, '2023-01-25 23:00:08', NULL, 0),
(564, 2, NULL, NULL, 'Theskill co.ltd', 'Theskill', 'co.ltd', 'US', 'mathee_d@yahoo.com', '2023-01-27 23:57:47', '$2y$10$dDsLZPGzRUWaR.aoJvZWlOo6Frp3T4iEljGzKg0GpK.jNCMXTwIOW', NULL, 'client/1/user/564/avatar.jpg', 'JNwsz5Aa5fAeGJjKkNp2qFHXH5tAJcuMEyorvcKeYpjItytntlvkBSWe9iwH', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-27 23:55:53', 0, '2023-01-27 23:55:53', NULL, 1),
(565, 2, NULL, NULL, 'Ilir Pruthi', 'Ilir', 'Pruthi', 'US', 'ipruthi@gmail.com', '2023-01-28 21:02:29', '$2y$10$FQge5Gla9xhQA5CkrF39k.qL8ZvkPuaPOrdxtkPH3NOkOO1RGffDa', NULL, 'client/1/user/565/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-28 21:02:13', 0, '2023-01-28 21:02:13', NULL, 1),
(566, 2, NULL, 393, 'Sam Sen', 'Sam', 'Sen', NULL, 'samuelsen443@gmail.com', NULL, '', NULL, 'client/1/user/566/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(567, 2, NULL, 394, 'Lucifer Decs', 'Lucifer', 'Decs', NULL, 'danii5h236@gmail.com', NULL, '', NULL, 'client/1/user/567/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(568, 2, NULL, 395, 'Lucifer Decs', 'Lucifer', 'Decs', NULL, 'danii5hfh23rr6@gmail.com', NULL, '', NULL, 'client/1/user/568/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(569, 2, NULL, 396, 'Lucifer Decs', 'Lucifer', 'Decs', NULL, 'danifh23rr6@gmail.com', NULL, '', NULL, 'client/1/user/569/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(570, 2, NULL, 397, 'Evomn Cierluf', 'Evomn', 'Cierluf', NULL, 'zqjvqlr@telegmail.com', NULL, '', NULL, 'client/1/user/570/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(571, 2, NULL, 398, 'Nmvoe Iufcerl', 'Nmvoe', 'Iufcerl', NULL, 'venom3edd2@gmail.com', NULL, '', NULL, 'client/1/user/571/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(572, 2, NULL, 399, 'Omnev Rcifuel', 'Omnev', 'Rcifuel', NULL, 'venom80a29@gmail.com', NULL, '', NULL, 'client/1/user/572/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(573, 2, NULL, 400, 'Nevom Lurfcie', 'Nevom', 'Lurfcie', NULL, 'venomf449c@gmail.com', NULL, '', NULL, 'client/1/user/573/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(574, 2, NULL, 401, 'Mneov Ruilecf', 'Mneov', 'Ruilecf', NULL, 'venom955aa@gmail.com', NULL, '', NULL, 'client/1/user/574/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(575, 2, NULL, NULL, 'Ronald Harsh', 'Ronald', 'Harsh', 'US', 'ronald.harsh@protonmail.com', '2023-02-01 06:42:43', '$2y$10$RBVYut0pUvT4fggUdTpkDuw4GA2V9euHNFbtRGw4yPxxCVWVbYDLC', NULL, 'client/1/user/575/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-01 06:41:53', 0, '2023-02-01 06:41:53', NULL, 1),
(576, 2, NULL, NULL, 'John kagema', 'John kagema', NULL, 'US', 'Kagemajohn22@gmail.com', NULL, '$2y$10$UWbqLIt1buyb4az53SQ.hOhiQ/KEMJuzuzzXItuMNAe5D1oLNObdy', NULL, 'client/1/user/576/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-01 21:16:48', 0, '2023-02-01 21:16:48', NULL, 0),
(577, 2, NULL, NULL, 'Sara S David', 'Sara', 'S', 'US', 'davjiwint@gmail.com', '2023-02-04 23:01:16', '$2y$10$ETcp7EIpAh11BX75IqMvO.mFn8MCeY/5pA93hdJnGghQWE4Kl/bEe', NULL, 'client/1/user/577/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-04 22:59:41', 0, '2023-02-04 22:59:41', NULL, 1),
(578, 2, NULL, NULL, 'Team Cannon', 'Team', 'Cannon', 'US', 'it@teamcannon.com', '2023-02-05 16:36:14', '$2y$10$xvnS/sP7Yh06e/TfK6ZAXebgyx6jDPWtNyAkNv/4rCZxJ1n9yc0vq', NULL, 'client/1/user/578/avatar.jpg', '2ImOYTNZFDgaWDwvlSmVKVTHtyXe0UkbmWM3ylUxEpRyxb9WCgCslC3Re85c', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-05 16:29:30', 0, '2023-02-05 16:29:30', NULL, 1),
(579, 2, NULL, NULL, 'Claudiu', 'Claudiu', '', 'US', 'klauss_ok@yahoo.com', '2023-02-05 19:00:06', '$2y$10$FeNoAfdotDHhzZiod6VtQuyh81iFRZ.4Bih5X.VRgMi8ZU7wumaRS', NULL, 'client/1/user/579/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-05 18:59:41', 0, '2023-02-05 18:59:41', NULL, 1),
(580, 2, NULL, NULL, 'Karl', 'Karl', '', 'US', 'swiftseo@outlook.com', '2023-02-08 06:55:17', '$2y$10$n2FuATtAE3n6Zd0WB1vXMObLeRbhiTtN/2jOciuNFWQ4Su.peM3tq', NULL, 'client/1/user/580/avatar.jpg', 'CGeRFunpvmTdAw4O47t3T1OWSRyYJ5oUZazDgm8naglsNuskEEVe8aMjg6gv', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-08 06:54:58', 0, '2023-02-25 09:08:49', NULL, 1),
(581, 2, NULL, NULL, 'peter', 'peter', NULL, 'US', 'ptrbrooks33@gmail.com', NULL, '$2y$10$Y.M5VDSPwdBh2Sc5..cVK.mY./ngPJJws/NuYQPkkjMrEEziVUY.q', NULL, 'client/1/user/581/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-09 00:41:19', 0, '2023-02-09 00:41:19', NULL, 0),
(582, 2, NULL, NULL, 'Shawn', 'Shawn', '', 'US', 'shawn@botsavant.com', '2023-02-09 22:23:53', '$2y$10$5k5okF..VUF7pCZwqd4jGORnLTJV9.i9ET0wzFHgrq2DbkV3ySQhS', NULL, 'client/1/user/582/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-09 22:23:35', 0, '2023-02-09 22:23:35', NULL, 1),
(583, 2, NULL, NULL, 'GA', 'GA', '', 'US', 'subshero@getrepr.com', '2023-02-10 01:27:10', '$2y$10$qLtK7SZz.k4DUM1oP6dyN.QpR5v2MMtbk6FR8ETBlmbIMGXBPFsOu', NULL, 'client/1/user/583/avatar.jpg', '5trJt1aXgQEUyCZCsJHGzXPrDVSkUJ0beiMyhIDILy12fKThchye1H5V60KB', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-10 01:26:54', 0, '2023-02-10 01:26:54', NULL, 1),
(584, 2, NULL, NULL, 'Sumeet Garnaik', 'Sumeet', 'Garnaik', 'US', 'services@rapidapps.dev', '2023-02-15 04:18:06', '$2y$10$Y1.Tg1yO9Mh5AcszOHy2j.cNOqs6hWhpEkU0yG.8rV6oVHemjW0bi', NULL, 'client/1/user/584/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, 'Rapid Apps LLC', NULL, NULL, '2023-02-15 04:16:13', 0, '2023-02-15 04:16:13', NULL, 1),
(586, 2, NULL, NULL, 'Tony', 'Tony', '', 'US', 'goteamsubscribe@gmail.com', '2023-02-17 07:32:17', '$2y$10$4TW./ncmmDJsnRgjWStg0Oc7KhH/Wi1U5CBU//m7yGACFy.xqCenm', NULL, 'client/1/user/586/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-17 07:31:59', 0, '2023-02-17 07:31:59', NULL, 1),
(587, 2, NULL, NULL, 'Jen', 'Jen', '', 'US', 'jen@storiesrx.com', '2023-02-18 23:40:31', '$2y$10$UbtP9.C1cNqo8q2g3RDh2ualKRZGu1fUxMrZcWD.MI5XBzOOi2U1O', NULL, 'client/1/user/587/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-18 23:39:42', 0, '2023-02-18 23:39:42', NULL, 1),
(588, 2, NULL, NULL, 'Rohit Kumar', 'Rohit', 'Kumar', 'IN', 'fisarer817@ngopy.com', '2023-02-20 08:50:14', '$2y$10$O3C7XGUV/FtASVcpzBfSz.lNSPLyt2.5xrgD2eEQq0R9VTbDDqZgu', NULL, 'client/1/user/588/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, 'test', NULL, NULL, '2023-02-20 08:49:54', 0, '2023-02-20 08:49:54', NULL, 1),
(589, 2, NULL, NULL, 'Atis Gailis', 'Atis', 'Gailis', 'US', 'atis@hooed.com', '2023-02-21 18:47:20', '$2y$10$Wnx6PwZSR8utuJJZkyAkwOIsI5Aq2amfZB6DFjWF8QaoOyN9/jJ2i', NULL, 'client/1/user/589/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-21 18:45:44', 0, '2023-02-21 18:45:44', NULL, 1),
(590, 2, NULL, NULL, 'Vincent Kaufman', 'Vincent', 'Kaufman', 'ZA', 'vincent.kaufman@qsasoftware.co.za', '2023-02-24 07:36:26', '$2y$10$uaTUJWogaZKn3g99l9W.YOGfYYqUdT16BHCcqoCV5WhMm0oSGyzkq', NULL, 'client/1/user/590/avatar.jpg', '9Mj3g2FUdQHcrCnkpIwbNSswoHjS3x2vVTZLiTFxrDkYiutiMUW0Zjy7he4l', 0, NULL, NULL, NULL, NULL, 'Quasar Software Development (Pty) Ltd', NULL, '+27 84 757 5292', '2023-02-24 07:32:37', 0, '2023-02-24 07:32:37', NULL, 1),
(591, 2, NULL, NULL, 'Sarah Alkhaled', 'Sarah', 'Alkhaled', 'US', 'sk.alkhaled@gmail.com', '2023-02-24 09:39:29', '$2y$10$ftrHzJ5i1lXRKOskNAbQp.PGMuJDOFtVnKf9kEoTvWBxLjylnzIHu', NULL, 'client/1/user/591/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-24 09:37:06', 0, '2023-02-24 09:37:06', NULL, 1),
(592, 2, NULL, NULL, 'Carolane Duci', 'Carolane', 'Duci', 'US', 'karo-lanne@hotmail.com', '2023-02-25 03:42:46', '$2y$10$RLYdNcavRG9f8l313lbv0uDCFPuM4gpzYC3BJbLAXVwVIVz43RfEu', NULL, 'client/1/user/592/avatar.jpg', 'BR51rKJ3lFECe4yhc2iOiEC4vKa4WY9oUL1CPQsgFUgDpAc83gES1uDI8l3z', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-25 03:39:51', 0, '2023-02-25 03:39:51', NULL, 1),
(593, 2, NULL, NULL, 'Name', 'Name', '', 'US', 'godflove8@gmail.com', '2023-02-26 16:37:32', '$2y$10$vN1eeKe1v4xtHOFzqEXu/.cJCptgH.hC2pXbs00GaS5jF1KMx/6eq', NULL, 'client/1/user/593/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-26 16:36:21', 0, '2023-02-26 16:36:21', NULL, 1),
(594, 2, NULL, NULL, 'Thomas Pedersen', 'Thomas', 'Pedersen', 'US', 'contact@flauwer.com', '2023-02-26 18:23:01', '$2y$10$7jJFvt0fuCH95tyWtl7zgeGYY7kuhFVHenWVsDdIvJBjLuB53zDZW', NULL, 'client/1/user/594/avatar.jpg', 'q3IcuG2MFkuEEPCzWoRTbAVFsoQEi6ocAA8kuBjO3uVvOsUKccmXZKtUmPhX', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-26 18:22:09', 0, '2023-02-26 18:22:09', NULL, 1),
(595, 2, NULL, NULL, 'Derek B', 'Derek', 'B', 'CA', 'db@bowesmail.com', '2023-02-27 17:20:43', '$2y$10$uAjFGGEk/tfXk.PlURDrWO47iAm9bySOtuZXv55E7BCPzM/SAmWiS', NULL, 'client/1/user/595/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-27 17:20:09', 0, '2023-02-27 17:20:09', NULL, 1),
(596, 2, NULL, NULL, 'Alastair Hills', 'Alastair', 'Hills', 'US', 'alastair.hills@cityhope.com.au', '2023-02-28 23:34:05', '$2y$10$FjzQuoLTb6AbfrXo3ko2MemGKdeNpSyM.R2GvHmKS1bp02XeiIai6', NULL, 'client/1/user/596/avatar.jpg', 'IhM6D1ojcSDikBWvjLUpapJH7wPx9GUVin3B96lcHiVvzvKfx0tINLKa9xMX', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-28 22:52:58', 0, '2023-02-28 22:52:58', NULL, 1),
(597, 2, NULL, NULL, 'BizPlanet LLC', 'BizPlanet', 'LLC', 'US', 'info@bizplanet.co', '2023-03-01 01:17:21', '$2y$10$FNsCFYC7BE19p0e1hneRKuSLd1rScSDbb/kerWgT7rRTm437jXi/C', NULL, 'client/1/user/597/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-01 01:16:59', 0, '2023-03-01 01:16:59', NULL, 1),
(598, 2, NULL, NULL, 'test', 'test', '', 'US', 'test@example.com', NULL, '$2y$10$Svu7UAekhjnerh39zkJHleKLIKxfWTgtBMv4AfSBIZOfy.fYpmP1q', NULL, 'client/1/user/598/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-03 05:37:37', 0, '2023-03-03 05:37:37', NULL, 0),
(599, 2, NULL, NULL, 'shmel', 'shmel', NULL, 'US', 'shmelvspb@gmail.com', NULL, '$2y$10$Ip2k2q9uSxebKIZpNMHaF.28/yVGSUbBclBUsR3xzTMgZNA6LDOha', NULL, 'client/1/user/599/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-07 23:40:53', 0, '2023-03-07 23:40:53', NULL, 0),
(600, 2, NULL, NULL, 'ron knick', 'ron', 'knick', 'US', 'rkforen@email.de', NULL, '$2y$10$KPpFzTkZuIQ6sB/oFeMSCO9/9U9/WPr3GsXak45feT/BanTyAp2nm', NULL, 'client/1/user/600/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-08 05:24:42', 0, '2023-03-08 05:24:42', NULL, 0),
(601, 2, NULL, NULL, 'Ilia Sergeenko', 'Ilia', 'Sergeenko', 'US', 'ilya.sergeenko@gmail.com', '2023-03-08 11:27:03', '$2y$10$s/JgiZj8Iu5/ssm/.rRu8e8mJIKa1pGBobvR55RlZ2A78pCNOFkv6', NULL, 'client/1/user/601/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-08 11:26:35', 0, '2023-03-08 11:26:35', NULL, 1),
(602, 2, NULL, NULL, 'bcj', 'bcj', NULL, 'US', 'fifej85854@orgia.com', NULL, '$2y$10$rIIF.AJso0ENmnP25YNENOspirskqyr7A.OG2xn9Fvi2nIxkKvdF.', NULL, 'client/1/user/602/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-10 16:26:25', 0, '2023-03-10 16:26:25', NULL, 0),
(603, 2, NULL, NULL, 'Patrick', 'Patrick', '', 'US', 'patrick-tanner@hispeed.ch', '2023-03-14 14:35:11', '$2y$10$jzw7vHlWcpaXcavwtknUbusHixjgYQayVhNfDytiOK.QMQB5Hp2Eu', NULL, 'client/1/user/603/avatar.jpg', 'bWjvOzj4Bv0hCOttYYzTQ0BlLDDaBDPRSWGfk9ku9KOYJiKWljp8OTQUg2Gf', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-14 14:34:31', 0, '2023-03-14 14:34:31', NULL, 1),
(604, 2, NULL, NULL, 'marce', 'marce', '', 'US', 'marcelloviolini92@gmail.com', '2023-03-15 11:13:58', '$2y$10$UT4bqluG4MBt8A9C318l2.FukjfP4GU6yCAYrSv6I3aC5fGBo9Mz2', NULL, 'client/1/user/604/avatar.jpg', 'h0VI1aZ5DvNANumBITa9B7QXpzYcl7EneSzj6pVxdTpNHRYJLO269LPNRn4j', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-15 11:13:34', 0, '2024-05-20 07:35:22', NULL, 1),
(605, 2, NULL, NULL, 'Barbara Carneiro', 'Barbara', 'Carneiro', 'US', 'barbara@wordrevolution.com', '2023-03-19 00:46:15', '$2y$10$awFIlaYbCZ6zxZmni0U2G.I7FgBuxaBHxeG21URBkmer.6Wg3XCAy', NULL, 'client/1/user/605/avatar.jpg', 'mB7nmIpqvvfbr1i3lRdUV9LSxIZdUm4mh2BExcLfIqERAX28OLUhCZv14kBs', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-19 00:45:21', 0, '2023-03-19 00:45:21', NULL, 1),
(606, 2, NULL, NULL, 'Opulent Tools', 'Opulent', 'Tools', 'US', 'reviews1@opulenttools.com', '2023-03-22 11:40:41', '$2y$10$6GPIzZ/6cyWQceAInKtFYuBzEdyxRbmJ5PW6hOxnEs3259kPfFW.W', NULL, 'client/1/user/606/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-22 11:40:16', 0, '2023-03-22 11:40:16', NULL, 1),
(607, 2, NULL, NULL, 'Tobi Olumide', 'Tobi', 'Olumide', 'US', 'jidemac6@gmail.com', '2023-03-22 20:01:17', '$2y$10$/g8pZItbIL9bFivHKBlc2eJocyixTmhixJTZBYOTxJNrlYHJc9iIy', NULL, 'client/1/user/607/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-22 20:00:38', 0, '2023-03-22 20:00:38', NULL, 1),
(608, 2, NULL, NULL, '唯 観月', '唯', '観月', 'TW', 'bestpika@gmail.com', '2023-03-24 07:33:36', '$2y$10$lENJn7UkcqaRloCXG4IjbOFQmfIjiNdnhsOd4XbYUZX034WCQ5JTm', NULL, 'client/1/user/608/avatar.jpg', 'xX8fe5JFKxsuTZ8wrqw0hb0oqzlKeTw2q6lQDSFbgnQ4RnD4OppJniC82H78', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-24 07:33:21', 0, '2023-03-24 07:33:21', NULL, 1),
(609, 2, NULL, NULL, '踢低吸', '踢低吸', '', NULL, 'tdc@sudo.host', '2023-03-25 09:13:03', '$2y$10$mmJkIFYF2K/UBLn/b1.nze7hKkiFtqvpXTEpO6.nYnQYyrm9aGOsm', NULL, 'client/1/user/609/avatar.jpg', 'Qfn8YOs4xW6tZvOT7nB59fzKUHp5SrMp1FQRJQx99Y5aBmvlTiCB4PsIiV4i', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-25 09:03:43', 0, '2023-03-25 09:11:51', NULL, 1),
(610, 2, NULL, NULL, '陳 鈺中', '陳', '鈺中', 'TW', 'peter890701@gmail.com', '2023-03-27 05:54:49', '$2y$10$NFXQqJe8fEkzFOo0t2DOF.cZ56gkKcRrV15FvQ.q9r2bxi/BPT9Tm', NULL, 'client/1/user/610/avatar.jpg', '8z5eXtlmklPJvXgdrzChVByAtoPuG4h9pqsDRpLg1MFxW8sh2kzF0ZAXAeqc', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-25 09:05:00', 0, '2023-03-27 05:53:57', NULL, 1),
(611, 2, NULL, NULL, 'Yuan Li', 'Yuan', 'Li', 'TW', 'ptntp@u8n.pw', '2023-03-25 09:33:17', '$2y$10$N316C/ZNyfIoxSfISS1X.ufnwvjY2tCVQTtLTQTds5TPfseUrp4fO', NULL, 'client/1/user/611/avatar.jpg', 'KcL0QTEiROFndP9Snr7oW7U4eOibG8RS1U7vVQAiuCx9VJochigdoNhFhsfq', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-25 09:21:55', 0, '2023-03-25 09:28:37', NULL, 1),
(612, 2, NULL, NULL, '張慶媛', '張慶媛', '', NULL, 'jamardy@gmail.com', '2023-03-25 09:26:42', '$2y$10$gPmyK0Dewcq8oUOLkH2d3Ob1cH0baDQfUypb2o590Ilzdm.3mQtcW', NULL, 'client/1/user/612/avatar.jpg', 'bpxHqQsO368WPbMRtehAk8xu0HaUel71hAgTjA3QWU0HFEGFVPrmGTkoSL9k', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-25 09:23:34', 0, '2023-03-25 09:25:27', NULL, 1),
(613, 2, NULL, NULL, 'Hiroshi Saito', 'Hiroshi', 'Saito', 'US', 'info@blueejapan.com', '2023-03-25 23:37:06', '$2y$10$PTp1agW2b6o.xdqYFFYYYOQr3bFeNKw4cRbuIBrtVcpM0Zboegi9O', NULL, 'client/1/user/613/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-25 23:36:28', 0, '2023-03-25 23:36:28', NULL, 1),
(614, 2, NULL, NULL, 'Test', 'Test', '', NULL, 'lagote3594@oniecan.com', '2023-03-27 04:00:18', '$2y$10$4YOJttkuz2MBYOz42u2RIO3VOa7e9Ntr/2h6vXCtxXQILKWRw.K1u', NULL, 'client/1/user/614/avatar.jpg', 'RDEL6YWWCaH1lOaeUTdXIAvNL5ihvaxbad11HXXJG65nbPxU5yA2QIW2t8Wf', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-27 03:58:06', 0, '2023-03-27 03:59:23', NULL, 1),
(615, 2, NULL, NULL, 'New user', 'New', 'user', NULL, 'kd5nu4jgxu@paperpapyrus.com', '2023-03-27 04:29:41', '$2y$10$DUl7Qqpi5D3FAGso9KKrwucNfaBS6/tGP2DS2eApx.4al8lSI7ke.', NULL, 'client/1/user/615/avatar.jpg', 'mMkXh9pRM6CbEqmjLt6C9nhsC4dkZLvsHmLLWgXqiLsWzgSwAacJdjtuRR5P', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-27 04:28:31', 0, '2023-03-27 04:29:22', NULL, 1),
(616, 2, 608, NULL, '企鵝', '企鵝', '', NULL, 'subsheee@eudyp.aleeas.com', '2023-03-29 10:10:33', '$2y$10$.e/ek1h2pJy3063qE3JJL.fVwuI.ZmBD44S4G8VsnUMQ13CwLFX1G', NULL, 'client/1/user/616/avatar.jpg', 'gbzhfhWZqdOUfJVAnDvfRMF1tRPuhMrIXdNyVDxJGbDLQoHSHRPoooZHiJxf', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-27 13:25:38', 0, '2023-03-29 10:10:33', NULL, 1),
(617, 2, NULL, NULL, 'Jor gee', 'Jor', 'gee', 'US', 'smartguy@inboxeen.com', '2023-03-30 04:43:30', '$2y$10$TYXgCS5RKeiHIonjZ9Ho5umnaEwO58qhzjK6dJKMQS/kbCgT5xxsS', NULL, 'client/1/user/617/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-30 04:41:24', 0, '2023-03-30 04:41:24', NULL, 1),
(618, 2, NULL, NULL, 'Test email', 'Test', 'email', NULL, 'soyag22954@jthoven.com', NULL, '', NULL, 'client/1/user/618/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-03 03:25:30', 0, '2023-04-03 03:25:30', NULL, 0),
(619, 2, NULL, NULL, 'rh', 'rh', NULL, 'US', 'hemachandrikasweety@gmail.com', NULL, '$2y$10$8X2EpY6Jbcoi.oiX7hN/U.c.ffYn1nhdc.9rrmTwc3VTs.TYpnANm', NULL, 'client/1/user/619/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-04 02:34:32', 0, '2023-04-04 02:34:32', NULL, 0),
(620, 2, NULL, NULL, 'Eric Aguilar', 'Eric', 'Aguilar', 'MX', 'eric@aiwifi.io', '2023-04-06 19:57:31', '$2y$10$fMEoiDXKZ6jgqSrNJhKXuesEfWr6yr6NrfmY0axtYOhLbWYtySJyi', NULL, 'client/1/user/620/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-06 19:57:20', 0, '2023-04-06 19:57:20', NULL, 1),
(621, 2, NULL, NULL, 'Jim Dickinson', 'Jim Dickinson', NULL, 'US', 'dickinsonjim@gmail.com', NULL, '$2y$10$SkGOcGegqFKQcUo2BcOSmevMfwAtmxz/GafOW0Pfp7hkmsjf0oH6i', NULL, 'client/1/user/621/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-08 08:47:10', 0, '2023-04-08 08:47:10', NULL, 0),
(622, 2, NULL, NULL, 'Ivan', 'Ivan', '', 'US', 'covokod380@ippals.com', '2023-04-11 18:21:09', '$2y$10$Es2kCWxrNYHujrxmmHQm9OO99/X93IroGy0aJcLsHizL9GhKHwkyC', NULL, 'client/1/user/622/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-11 18:20:53', 0, '2023-04-11 18:20:53', NULL, 1),
(623, 2, NULL, NULL, 'Susan', 'Susan', '', 'US', 'susankatheryn@gmail.com', '2023-04-11 20:20:10', '$2y$10$6pr0Aze6FNKppCkx5/9jSe6T0fu99I4s3.ObV8C2/twVX/.5Wzbw2', NULL, 'client/1/user/623/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-11 20:18:48', 0, '2023-04-11 20:18:48', NULL, 1),
(624, 2, NULL, NULL, 'Enzo', 'Enzo', '', 'US', '7203enzoris@gmail.com', '2023-04-12 20:16:44', '$2y$10$jDUjdF7nLzgD6v5oGjNpuODfgqM2ih45MHN6crXVMCWxwYgqSvFN6', NULL, 'client/1/user/624/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-12 20:15:49', 0, '2023-04-12 20:15:49', NULL, 1),
(625, 2, NULL, NULL, 'SitePape', 'SitePape', '', 'US', 'sitepape25@gmail.com', '2023-04-14 10:20:38', '$2y$10$7NGXHbENe8aZF8ATGN1aM.SMh65/LXFYsMWz2J07Ht6dHM911klhS', NULL, 'client/1/user/625/avatar.jpg', 'F6Uzd9O7YQiTARZtuqx0T4wPzlyVcCwra7oHuibjJTRIiXXCRpi14g7enssh', 1, 'oeCmdENMs0ttbULl', 'test', 'test', 'test', NULL, NULL, NULL, '2023-04-14 10:20:06', 0, '2023-09-25 04:50:21', NULL, 1),
(626, 2, NULL, NULL, 'Bradley Leese', 'Bradley', 'Leese', 'US', 'bradley@practicalseos.com', '2023-04-14 20:16:09', '$2y$10$3tvsEHxC6.SN3hci.U9vxuLapuN1yHBXmBASIq9jAYzVn2OYT4M0y', NULL, 'client/1/user/626/avatar.jpg', 'GL8nMte7bnVHAr7R2iBpxlv25BLCsLKWr6YXri5QYyfjlbQMce6gqNWbOnB6', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-14 20:15:34', 0, '2023-04-14 20:15:34', NULL, 1),
(627, 2, NULL, NULL, 'Eslam Mahmoud', 'Eslam', 'Mahmoud', 'GB', 'eslam.mahmoud@lips.org.uk', '2023-04-17 00:55:24', '$2y$10$z3R8uBvQ0qJzVSgZZtjq0uWhpTF3VtP44inDvhRKQH.Rb0pty7ZPS', NULL, 'client/1/user/627/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-17 00:54:46', 0, '2023-04-17 00:54:46', NULL, 1),
(628, 2, NULL, NULL, 'Pius Binder', 'Pius', 'Binder', 'US', 'binder.pius@gmail.com', '2023-04-18 07:33:32', '$2y$10$MRHcD0zAN3q5V1EdIAj5uOKje/jmtKXKFbvQqL0fozd0juV5eLy/C', NULL, 'client/1/user/628/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-18 07:32:55', 0, '2023-04-18 07:32:55', NULL, 1),
(629, 2, NULL, NULL, 'Om', 'Om', '', 'US', 'mrgupta089@gmail.com', '2023-04-19 09:02:10', '$2y$10$8wRT6msNRU1f8huc3QPAcO/XPOBH2t9xu8/nRT1tbYYsngV6nFEvO', NULL, 'client/1/user/629/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-19 09:01:05', 0, '2023-04-19 09:01:05', NULL, 1),
(630, 2, NULL, NULL, 'GIntas', 'GIntas', '', 'US', 'gintas.zenevskis@gmail.com', '2023-04-19 15:18:07', '$2y$10$uzlVFO0htx6e3oUlXstGkeKZR283cdjJUX7YUO0.gyy8PbIzweOFG', NULL, 'client/1/user/630/avatar.jpg', 'bGFb6li8nbK92XN7VAddmKX55tWmIDe7ocjXG32kYWfYEFEsCW6zbdxbGofJ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-19 15:17:27', 0, '2023-04-19 15:17:27', NULL, 1),
(631, 2, NULL, NULL, 'Namratha Shenoy', 'Namratha', 'Shenoy', 'US', 'namratha@interstellarconsulting.com', '2023-04-20 09:12:55', '$2y$10$qmNEwObk6qAtHk2sqXf8B.SzvX/DHJMb5kSALchVYhJmLVPkMOvze', NULL, 'client/1/user/631/avatar.jpg', 'O3R6illMmEusqPWWSDhWxM9he78REn6obmGTk7ZJFmnJJL09kQz0mg2YaLG9', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-20 09:11:42', 0, '2023-04-20 09:11:42', NULL, 1),
(632, 2, NULL, NULL, 'Taimoor Hassan', 'Taimoor', 'Hassan', 'US', 'imperialtrendshop@gmail.com', NULL, '$2y$10$C5cOiQLZdG7aLd67y2NgLOmo7YipB5OXGaI1XfLye9LseQtyhwNPq', NULL, 'client/1/user/632/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-21 13:12:11', 0, '2023-04-21 13:12:11', NULL, 0),
(633, 2, NULL, NULL, 'Anand', 'Anand', '', 'US', 'anandrmy@gmail.com', '2023-04-22 00:05:58', '$2y$10$pIcOiaGacX2CAjTCmyxnEuYE7ObYHGGyvaHOsd9GrDFBwDXqdFhpe', NULL, 'client/1/user/633/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-22 00:05:30', 0, '2023-04-22 00:05:30', NULL, 1);
INSERT INTO `users` (`id`, `role_id`, `team_user_id`, `wp_user_id`, `name`, `first_name`, `last_name`, `country`, `email`, `email_verified_at`, `password`, `description`, `image`, `remember_token`, `marketplace_status`, `marketplace_token`, `paypal_api_username`, `paypal_api_password`, `paypal_api_secret`, `company_name`, `facebook_username`, `phone`, `created_at`, `created_by`, `updated_at`, `reset_at`, `status`) VALUES
(634, 2, NULL, NULL, 'Ronald Stone', 'Ronald', 'Stone', 'US', 'ronald@googlinks.com', '2023-04-26 06:02:29', '$2y$10$aTBHTbGPxFIW0ftJq0ZkTudYtNMXAkQ2ZumXngtgUVQs6vvLCcswy', NULL, 'client/1/user/634/avatar.jpg', '4rNrrmXQE0lHa2iNJrVOKkgjnrV9foODt3KFRX3aJVWpNvMhV5IoLxStwAKp', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-25 15:09:17', 0, '2023-04-25 15:09:17', NULL, 1),
(635, 2, NULL, NULL, 'Abhijit', 'Abhijit', '', 'US', 'singh1a@hotmail.com', '2023-04-26 23:48:24', '$2y$10$sUokR205ePstfnl.Xucfpuiv672Eds6hd/Uz.0JeEq3u/LdZ0lvAS', NULL, 'client/1/user/635/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-26 23:47:45', 0, '2023-04-26 23:47:45', NULL, 1),
(636, 2, NULL, NULL, 'Daniel Padilla', 'Daniel Padilla', NULL, 'US', 'dannypk1977@gmail.com', NULL, '$2y$10$bcigB6lcnbgRESFbmC2wP.lWtvTXvCgVeIowRpmpN8VCz0mOUObaK', NULL, 'client/1/user/636/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-27 01:28:08', 0, '2023-04-27 01:28:08', NULL, 0),
(637, 2, NULL, NULL, 'Disability Support', 'Disability', 'Support', 'AU', 'Social_Empowerment_Group@outlook.com', '2023-04-29 11:29:45', '$2y$10$8jR5I3ym7QaXnwEQixd6F.v1FcVkQOVNw41lEebs2/C6MvOof/0U6', NULL, 'client/1/user/637/avatar.jpg', 'aaNIgsBzLXuN2z893ZdyQTjTBECo6POf1fqIH0P7rBqDp4z8O5LWNQrVuNvi', 0, NULL, NULL, NULL, NULL, 'Social Empowerment Group', NULL, NULL, '2023-04-29 10:58:55', 0, '2023-04-29 10:58:55', NULL, 1),
(638, 2, NULL, NULL, 'Mariah', 'Mariah', NULL, 'US', 'mariahtharp482@gmail.com', NULL, '$2y$10$h9Rw1yFpKNnG7LuiCSCxj.nUDKIszrdhhmJ6d8Nb8tjt9Slrwvgq6', NULL, 'client/1/user/638/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-29 21:26:20', 0, '2023-04-29 21:26:20', NULL, 0),
(639, 2, NULL, NULL, 'alex max', 'alex', 'max', 'US', 'goutham.doungel123@gmail.com', '2023-05-02 03:03:30', '$2y$10$mC1dIjx3GyW1C5lp.2EMAu39Zu0Scalaexj3FJKT1vGpojiyxgSFS', NULL, 'client/1/user/639/avatar.jpg', '4dewyMVA0UuMaILd8NYi5fyfaqFqnKlmPZuAjE6JzXJbtJzIJiRRxEWiHQ6U', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-02 03:01:59', 0, '2023-05-02 03:01:59', NULL, 1),
(640, 2, NULL, NULL, 'seager', 'seager', '', 'US', 'seager.yoni@minofangle.org', '2023-05-04 11:24:47', '$2y$10$9ZYaoSdqUC0VApd2KPfZVeBWbyLJH9w1cejHnsUIE7B/iLjK/L8Wu', NULL, 'client/1/user/640/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-04 11:22:51', 0, '2023-05-04 11:22:51', NULL, 1),
(641, 2, NULL, NULL, 'carol', 'carol', NULL, 'US', 'camitchell123@gmail.com', NULL, '$2y$10$Xx7gFtWzfiZqHHMntDEMXukylL4Yk8tCQopdVorEzquCvKUmEwfxC', NULL, 'client/1/user/641/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-07 10:42:48', 0, '2023-05-07 10:42:48', NULL, 0),
(642, 2, NULL, NULL, 'louis cocks', 'louis', 'cocks', 'US', 'caribbean-social@hotmail.com', '2023-05-08 14:33:36', '$2y$10$zAKxX.7ioGv/.wD94zdaP.SVboqV16Th39Y.awTf3Scmz7PQmzxmG', NULL, 'client/1/user/642/avatar.jpg', 'mg3TUqGrBQdCrXKdzp9PMs4xle3HzNFJS6JA3tFs3p9KUHuIsT22L1SmOdg7', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-08 14:32:17', 0, '2023-05-08 14:32:17', NULL, 1),
(643, 2, NULL, NULL, 'Lutz Augspurger', 'Lutz', 'Augspurger', 'US', 'info@flippingrocks.de', '2023-05-09 07:07:50', '$2y$10$ikSuIO2KdUuVjUUPauYn7.wu1g5tP4/aZVOddAFAon.UalnkV7aXO', NULL, 'client/1/user/643/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-09 07:07:13', 0, '2023-05-09 07:07:13', NULL, 1),
(644, 2, NULL, NULL, 'Naga kiran', 'Naga', 'kiran', 'US', 'nagakiran.ranna@gmail.com', '2023-05-09 09:22:14', '$2y$10$7WG6nkhNQfFg.lnPLFcl3u.iA8x1w3w1rwZkCbh8w3NSCRmCwZXVq', NULL, 'client/1/user/644/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-09 09:21:08', 0, '2023-05-09 09:21:08', NULL, 1),
(645, 2, NULL, NULL, 'frances', 'frances', NULL, 'US', 'thomasfrances73@gmail.com', NULL, '$2y$10$e5cfM/3y0TvAA85s5t588ux9uOB8izDewGmyfouhkEUt1/58v0076', NULL, 'client/1/user/645/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-09 13:01:35', 0, '2023-05-09 13:01:35', NULL, 0),
(646, 2, NULL, 402, 'jana rahman', 'jana', 'rahman', NULL, 'chatgpt8k@gmail.com', NULL, '', NULL, 'client/1/user/646/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(647, 2, NULL, NULL, 'David José de Andrade', 'David', 'José', 'US', 'casimirodandrade@gmail.com', '2023-05-11 07:40:13', '$2y$10$kE50nj7XKjanfuCmgIF9t.cpepxj./gGuttmChP1FJvGTuzRlk3M6', NULL, 'client/1/user/647/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-11 07:38:19', 0, '2023-05-11 07:38:19', NULL, 1),
(648, 2, NULL, NULL, 'Nathan Boyer', 'Nathan', 'Boyer', 'US', 'setsuccess@aol.com', '2023-05-11 18:11:49', '$2y$10$LtoAGyMM1GAvHAZXdJUXie/xZfvl1vxEkwP0PB7yjxwSIYdo6ye8a', NULL, 'client/1/user/648/avatar.jpg', 'cxtrchdiM7UCF4P5Gjx0dMjjA5Na8bVDmERX3JgKjbpoJC6LPujElQfWwzzs', 0, NULL, NULL, NULL, NULL, 'Research Publishing, LLC', NULL, '3156638911', '2023-05-11 18:10:48', 0, '2023-06-09 19:27:20', NULL, 1),
(649, 2, NULL, NULL, 'Hamdi', 'Hamdi', '', 'US', 'hamdiceylan@gmail.com', '2023-05-12 21:51:41', '$2y$10$GPW875h15n52S6AxVbbRb.gRaKhLq9ifD5MnIuxK/g5Jn4OVfr2o6', NULL, 'client/1/user/649/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-12 21:51:09', 0, '2023-05-12 21:51:09', NULL, 1),
(650, 2, NULL, NULL, 'Jeffrey B Irby', 'Jeffrey', 'B', 'US', 'jbi.lawirby@gmail.com', '2023-05-13 01:54:32', '$2y$10$ZKjun4RjTjQ0ny8pUjCd7.8EjUsj0LEdX0fWEAWwv43HQ.b.bbJdi', NULL, 'client/1/user/650/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-13 01:54:02', 0, '2023-05-13 01:54:02', NULL, 1),
(651, 2, NULL, NULL, 'Peter', 'Peter', '', 'US', 'peter-bachmann@hotmail.com', '2023-05-14 00:08:00', '$2y$10$Bg4lS2AflnlUAT3IgE8g8OeJjKg0eeoJZ6kSM.AEwxPTRK837IZLW', NULL, 'client/1/user/651/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-14 00:05:41', 0, '2023-05-14 00:05:41', NULL, 1),
(652, 2, NULL, NULL, 'Lijin', 'Lijin', '', 'US', 'thomaslijin84@gmail.com', '2023-05-15 02:50:57', '$2y$10$g6LfTluoEw5tWGiQ1JSvFuQ3Hi5JFfSsFby9v.DS5i8zATQLu7mkm', NULL, 'client/1/user/652/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-15 02:50:15', 0, '2023-05-15 02:50:15', NULL, 1),
(653, 2, NULL, NULL, 'kerex', 'kerex', '', 'US', 'myxpitstop@yahoo.com', '2023-05-15 23:35:38', '$2y$10$wJO6Pw4flESEnqWwtP7G3uQA1ScqKvo8pqRbnD/ZS47cm7Qq/9c2m', NULL, 'client/1/user/653/avatar.jpg', 'evxGcbicL6zo5ae4nEc4G0x83LlET4shczzb9BVrKDMnB8LXuTHIdHcPbYt2', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-15 23:34:50', 0, '2023-05-15 23:34:50', NULL, 1),
(654, 2, NULL, NULL, 'kae', 'kae', '', 'US', 'sgnewlaunchconnect@gmail.com', '2023-05-17 08:56:06', '$2y$10$dkt3Fj2hjxeFE4lEiqi8.uQ9OqlOden9Cz/nJpasSetLOKnfVnjFa', NULL, 'client/1/user/654/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-17 08:55:27', 0, '2023-05-17 08:55:27', NULL, 1),
(655, 2, NULL, NULL, 'Melissa Alexander', 'Melissa', 'Alexander', 'US', 'malex@gmainterventions.com', '2023-05-17 15:14:13', '$2y$10$rzDF2/F/VVa6k99AFe.4Z.fhZQ6251atKTLsVMtWVRyXHbYi6mVyW', NULL, 'client/1/user/655/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-17 15:13:37', 0, '2023-05-17 15:13:37', NULL, 1),
(656, 2, NULL, NULL, 'Michael', 'Michael', '', 'US', 'appstore1976@gmail.com', '2023-05-17 16:19:51', '$2y$10$zgG7tXclR8.tzhuosrEsxOvIeEwFdajmjU9JMcCdv2whT66BVaRrm', NULL, 'client/1/user/656/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-17 16:19:08', 0, '2023-05-17 16:19:08', NULL, 1),
(657, 2, NULL, NULL, 'Tomasz', 'Tomasz', '', 'US', 't.miecz@gmail.com', '2023-05-17 22:47:36', '$2y$10$JQPDxKDINEKAZJHijRz.5eA3Wl8gj4utwFlJ.g/TvP8CYebqbUrmq', NULL, 'client/1/user/657/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-17 22:42:15', 0, '2023-05-17 22:42:15', NULL, 1),
(658, 2, NULL, NULL, 'Lode Denecker', 'Lode', 'Denecker', 'BE', 'lode@aqwatech.com', '2023-05-18 11:37:07', '$2y$10$KwlwGCeiOdrld9.uNWnUP.KMZyRQoOyoYZp/8bjse.hGVCEVxzX1m', NULL, 'client/1/user/658/avatar.jpg', 'p4lA0koZi3DTC3g7ajXl1vIbJfsR7jPatls2YNqGu9TXxNXKR3sztyYhv9MF', 0, NULL, NULL, NULL, NULL, 'AQwaTech BV', NULL, '+32468155801', '2023-05-18 11:36:22', 0, '2023-05-18 11:36:22', NULL, 1),
(659, 2, NULL, NULL, 'Janos Galik', 'Janos', 'Galik', 'US', 'hello@galikjanos.hu', '2023-05-18 11:54:17', '$2y$10$R8am14tuL8bihv9/VqMPnejhTg/qR68VZSCHb9zeI1dt7gYToTJhi', NULL, 'client/1/user/659/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-18 11:52:34', 0, '2023-05-18 11:52:34', NULL, 1),
(660, 2, NULL, NULL, 'Jennifer Parr', 'Jennifer', 'Parr', 'US', 'jparr@diyvinci.com', '2023-05-18 22:59:25', '$2y$10$AlIEWAmNf8ktg/bKMvtf8eydwZ2ER30P60PdKa5G2XQshiQkuaV6i', NULL, 'client/1/user/660/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, 'DIYvinci', NULL, '309-370-7187', '2023-05-18 22:58:58', 0, '2023-05-18 22:58:58', NULL, 1),
(661, 2, NULL, NULL, 'xx robot xx', 'xx', 'robot xx', 'IN', 'saas.subhero@blugang.me', '2023-05-24 12:58:00', '$2y$10$tu.KPJaz4Grf4QWoy/dOI.aFLuncAhRusLEhU/wpx/DFr25KG/F5G', NULL, 'client/1/user/661/avatar.jpg', 'cmowzhV5wrwidBxDMjMHYohrrZT2UudAerEplOxcSQyJWvSmfkmUHZuZ4tbV', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-24 12:57:17', 0, '2023-05-24 12:57:17', NULL, 1),
(662, 2, NULL, 404, 'Monika Kumari', 'Monika', 'Kumari', NULL, 'monika@interstellarconsulting.com', '2023-05-26 09:28:26', '$2y$10$ylkU8yFo/NqPrRuSgcfI2O07Tdxc9UhoMXC31ftwk/gBiclQMORlm', NULL, 'client/1/user/662/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1),
(663, 2, NULL, 405, 'Rakshitha V', 'Rakshitha', 'V', NULL, 'rakshitha@interstellarconsulting.com', '2023-06-01 08:24:39', '$2y$10$yo4Rn9yqqW5ADSUkD32GV.7BZxkVszKndmHgaF7X.zs.1l75ZDQpa', NULL, 'client/1/user/663/avatar.jpg', 'hVn2QKozFTEO2gcl1G95jTkJXlKM6Xpci4JL1grlkYOo89tl52MfRLMXShwQ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-06-01 08:24:09', NULL, 1),
(664, 2, NULL, NULL, 'Morfeu', 'Morfeu', '', 'US', 'morf3u@outlook.com', '2023-05-27 20:53:20', '$2y$10$waS9ZuedfyZEWWSixyTNq.cdRAc6TWotFXdDOdyBksosRCAMibbry', NULL, 'client/1/user/664/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-05-27 20:52:40', 0, '2023-05-27 20:52:40', NULL, 1),
(665, 2, NULL, NULL, 'Raj', 'Raj', '', 'US', 'rajkv24@gmail.com', '2023-06-21 02:04:23', '$2y$10$mFs/vdENedLXH5rJ1f.1GuHBSLA7EzO0iwWDcxAYQOiW6T6jeRgwq', NULL, 'client/1/user/665/avatar.jpg', 'hAIAPOPda437tJGUAKkL7nC4HRTAo0z8m4YaxbiEgJeL94rsRl8R3bAgqA2p', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-06-21 02:02:37', 0, '2023-06-21 02:02:37', NULL, 1),
(666, 2, NULL, NULL, 'Paul Dumbravanu', 'Paul', 'Dumbravanu', 'US', 'paul@pauldumbravanu.ro', '2023-06-28 18:06:04', '$2y$10$awRYRCjgQLzpdVu739sF/ODzMtUcCrewk7R/fpOAGnTYNS0nq0xPO', NULL, 'client/1/user/666/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-06-28 18:05:32', 0, '2023-06-28 18:05:32', NULL, 1),
(667, 2, NULL, NULL, 'Azrul Naqeeb Zulkafli', 'Azrul', 'Naqeeb', 'US', 'azrulnaqeebcorporate@gmail.com', '2023-07-02 13:44:38', '$2y$10$.24b.FxYqDmwXcFlMibCgehZnayK2tELT9kKzSJgCrZ7L3rFRnZgy', NULL, 'client/1/user/667/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-02 13:43:43', 0, '2023-07-02 13:43:43', NULL, 1),
(668, 2, NULL, NULL, 'Darshil Dholakia', 'Darshil', 'Dholakia', 'US', 'darshild09@gmail.com', '2023-07-12 06:56:34', '$2y$10$CAO8irR3uZuRZVQbnFHrs.qJ6ueahAzOUYVEntYM/rnXw4ANBa.cG', NULL, 'client/1/user/668/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-12 06:56:12', 0, '2023-07-12 06:56:12', NULL, 1),
(669, 2, NULL, NULL, 'Village Talkies', 'Village', 'Talkies', 'US', 'villagetalkiesdm@gmail.com', NULL, '$2y$10$T8gKsmG0DMkCjsbysvAdMu8Jfqtdu4svebIXkD/GkuEcOn4p5.Ucq', NULL, 'client/1/user/669/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-18 11:35:03', 0, '2023-07-18 11:35:03', NULL, 0),
(670, 2, NULL, NULL, 'Cade Benoit', 'Cade', 'Benoit', 'US', 'cadebenoit123@gmail.com', '2023-07-20 19:00:42', '$2y$10$Fox0h1YPqBw574uyallmm.8WIMcEK3qiMtvaK7Obhn0MaJhGqjgfu', NULL, 'client/1/user/670/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-20 19:00:15', 0, '2023-07-20 19:00:15', NULL, 1),
(671, 2, NULL, NULL, 'Brandy Torrez', 'Brandy', 'Torrez', 'US', 'brandyaffiliate@gmail.com', '2023-07-25 05:23:39', '$2y$10$vucnT3mxfHG/bCx1pwwThuLz1TdWwQ8zKDryDvu25CshonDAB.qcG', NULL, 'client/1/user/671/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-25 05:23:25', 0, '2023-07-25 05:23:25', NULL, 1),
(672, 2, NULL, NULL, 'Corey Smith', 'Corey', 'Smith', 'US', 'hirecorey1@gmail.com', '2023-07-27 21:52:05', '$2y$10$FtoJwbOeW1d3HfVQc7vjEOMhaVK3pz1yRHLwJcZ/y6lxwajf86tOS', NULL, 'client/1/user/672/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-27 21:51:42', 0, '2023-07-27 21:51:42', NULL, 1),
(673, 2, NULL, NULL, 'Mina Ihab', 'Mina', 'Ihab', 'US', 'minasupers88@gmail.com', '2023-07-31 18:20:57', '$2y$10$HYTWZ27Po/MvSReUiPiKieML6fP.eYJKrESY6KGeBSPPJrjPzjTjS', NULL, 'client/1/user/673/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-31 18:20:27', 0, '2023-07-31 18:20:27', NULL, 1),
(674, 2, NULL, NULL, 'Tim', 'Tim', '', 'US', 'dmitry.shuvaev@gmail.com', '2023-08-03 08:11:53', '$2y$10$KvMN5aUFz2yR0kTx6x/G6eTFKFaxu4f./HaSVsa9JxqgCW5RhrKJ6', NULL, 'client/1/user/674/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-08-03 08:11:26', 0, '2023-08-03 08:11:26', NULL, 1),
(675, 2, NULL, NULL, 'Pranay Verma', 'Pranay', 'Verma', 'US', 'pranaydiya@gmail.com', '2023-08-09 09:50:05', '$2y$10$wBeSkYykKh63pv.bLA2wTenWO73xb0ywvPSPYwmkEV4QCtbf8x2J2', NULL, 'client/1/user/675/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-08-09 09:49:41', 0, '2023-08-09 09:49:41', NULL, 1),
(676, 2, NULL, NULL, 'Ketan Anand', 'Ketan', 'Anand', 'US', 'ketan.aby@gmail.com', '2023-08-14 12:47:26', '$2y$10$CviUO46qMRIfTGNn3mRLRumseKJeui41.E8RidhkX4t0GX3REd9SS', NULL, 'client/1/user/676/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-08-14 12:47:11', 0, '2023-08-14 12:47:11', NULL, 1),
(677, 2, NULL, NULL, 'parislowrys@gmail.com', 'parislowrys@gmail.com', '', 'US', 'parislowrys@gmail.com', '2023-08-26 06:47:49', '$2y$10$7PNAJByIuTdQvZmR7LMvUu/Khqi1/yC53.262zrIIyOsCBntlHhke', NULL, 'client/1/user/677/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-08-26 06:45:42', 0, '2023-08-26 06:45:42', NULL, 1),
(678, 2, NULL, NULL, 'Market', 'Market', '', 'US', 'marketsmith2024@gmail.com', '2023-08-31 15:48:40', '$2y$10$BfHXYrTvGY67LchwWxOgMuRrtSv./AE5.cyxiFT39FVVmOPkHxWVK', NULL, 'client/1/user/678/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-08-31 15:48:03', 0, '2023-08-31 15:48:03', NULL, 1),
(679, 2, NULL, NULL, 'Abhishek Raj', 'Abhishek', 'Raj', 'IN', 'abhishekrajwin40@gmail.com', NULL, '$2y$10$NU1DRyA2IYD4z9pFEgwUDezxuBaFqAGNNxgz9h28sB.d2X2teBx5e', NULL, 'client/1/user/679/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-09-04 12:15:23', 0, '2023-09-04 13:24:08', NULL, 0),
(681, 2, NULL, NULL, 'Tyson Finnerty', 'Tyson', 'Finnerty', 'US', 'tyman2008@icloud.com', '2023-09-19 18:11:04', '$2y$10$SFA/Bk7R.CpL20kQvovbr.Ud3oDIR6i.DoDjZZhe4YUDiZC3HjEl2', NULL, 'client/1/user/681/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-09-19 18:10:41', 0, '2023-09-19 18:10:41', NULL, 1),
(682, 2, NULL, NULL, 'apps', 'apps', '', 'US', 'appsaccounts@cyberark.com', '2023-09-21 05:15:20', '$2y$10$jZooPO7ME/Od44BOtN2nnuKUKblUQDdvuna6Hab1QwImr2C6bwDJm', NULL, 'client/1/user/682/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-09-21 05:14:41', 0, '2023-09-21 05:14:41', NULL, 1),
(683, 2, NULL, 407, 'Menh Menhera', 'Menh', 'Menhera', NULL, 'menheragodz@gmail.com', NULL, '', NULL, 'client/1/user/683/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(684, 2, NULL, NULL, 'Adam', 'Adam', '', 'US', 'adamr1979@gmail.com', NULL, '$2y$10$rnbvSeoJXRPQbxJWwDinnOwSvid176.4natcd4hE/T.TfI109.rRq', NULL, 'client/1/user/684/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-09-25 04:12:21', 0, '2023-09-25 04:12:21', NULL, 0),
(685, 2, NULL, NULL, 'Chirag K S', 'Chirag', 'K', 'US', 'chirusrinivasan@gmail.com', '2023-09-30 17:05:43', '$2y$10$b7JLVtpsECeDOXFESPIcie/yCIxYA0MRbCHvw.Q4dCLA3udmvpsXG', NULL, 'client/1/user/685/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-09-30 17:05:21', 0, '2023-09-30 17:05:21', NULL, 1),
(686, 2, NULL, NULL, 'Renata Krug', 'Renata', 'Krug', 'US', 'renatakrug23@gmail.com', '2023-10-04 19:46:03', '$2y$10$xfnStWpSDwX1IJhGKEl3X.VdIXuWYyEILUDfTHRV2tkDj8qqbM4sG', NULL, 'client/1/user/686/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '9786024070', '2023-10-04 19:45:34', 0, '2023-10-04 19:45:34', NULL, 1),
(687, 2, NULL, NULL, 'Frank Pacheco', 'Frank', 'Pacheco', 'US', 'frank.pacheco@gmail.com', '2023-10-06 18:08:49', '$2y$10$Rax1fD/mUjNkmOUj7GU7E.9ilVBt4flA4sxSntfTYeyAXDBUMaeqS', NULL, 'client/1/user/687/avatar.jpg', 'D2I22IGdxwVDoq4a29IAwrBAHvc1logLbg0B05GL6QoeSFOLbzBLUzeyIJsT', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-10-06 18:02:37', 0, '2025-03-21 12:16:23', NULL, 1),
(688, 2, NULL, NULL, 'Kaif', 'Kaif', '', 'US', 'kaifs0351@gmail.com', '2023-10-14 08:25:31', '$2y$10$jYZDzymlMP5JRecX4N9Y/uu2b6YozbJxwHptPSUfC4sCH038aE3U.', NULL, 'client/1/user/688/avatar.jpg', 'YYjQ0at1q4AK8BHVKlVOTsQZRZT3X1Fys0wEM432fOoDadaDRhD74JeQHN6o', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-10-14 08:23:39', 0, '2023-10-14 08:23:39', NULL, 1),
(690, 2, NULL, 408, 'Care D', 'Care', 'D', 'CA', 'landcplus5@gmail.com', '2023-10-27 16:01:23', '$2y$10$.kpn0MTT27ishpT1vR9gZeids0XPcZupDJscXU52KMLg3FEOxqg8K', NULL, 'client/1/user/690/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-10-27 16:00:49', 0, '2023-10-27 16:00:49', NULL, 1),
(691, 2, NULL, NULL, 'juan pablo', 'juan', 'pablo', 'US', 'juanpablo@gmx.ch', '2023-10-27 16:44:56', '$2y$10$ntn.A.0T94lrdZkluIjlTuTRqGC2uMpuxi1CpqrWuTGQRw5q9C8te', NULL, 'client/1/user/691/avatar.jpg', 'bCu6JweKd9pbcpk7ocsTQ7kx04tugJTaKqW5wsxm7y3IoiMJw4DXB6Nt2lkg', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-10-27 16:41:37', 0, '2023-10-27 16:41:37', NULL, 1),
(692, 2, NULL, NULL, 'Banico Inc', 'Banico', 'Inc', 'US', 'Banico.Live@gmail.com', '2023-11-03 18:07:48', '$2y$10$EJ1pPRSvTlXenbvZG1k/N.WGHl/rZnpygGQ97cC3AzupJEhBIQuHS', NULL, 'client/1/user/692/avatar.jpg', 'dfpR5ocJ9uR9jvZVRa5Z66a40x3AppXs6M32j1kowGWDRJ7O7kU58810BJwd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-03 18:07:00', 0, '2024-08-19 00:18:34', NULL, 1),
(693, 2, NULL, NULL, 'James Schaeffer', 'James', 'Schaeffer', 'US', 'james_schaeffer@yahoo.com', '2023-11-08 14:20:52', '$2y$10$SafDONwqaZ3Jo0vQ9SDkDu1yg5rJoA7IaiIjn1Gf7fOIjwmOmuEwW', NULL, 'client/1/user/693/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-08 14:19:42', 0, '2023-11-08 14:19:42', NULL, 1),
(695, 2, NULL, NULL, 'David Oswald', 'David', 'Oswald', 'US', 'david@davidoswald.net', '2023-11-29 14:09:58', '$2y$10$XR6DrAISc5f4cW439v.5WuJ1/DLeyMg2DGTAaF81vKWNFLc2VUsVy', NULL, 'client/1/user/695/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-29 14:09:18', 0, '2023-11-29 14:09:18', NULL, 1),
(696, 2, NULL, NULL, 'Ayal Moses', 'Ayal', 'Moses', 'US', 'ayal.moses@gmail.com', '2023-12-01 11:18:41', '$2y$10$AGDfczlRuYjetc2BzkchZOPHoCayHps/wYRwOUkRX0UnBzCcQ5S/u', NULL, 'client/1/user/696/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-01 11:18:12', 0, '2023-12-01 11:18:12', NULL, 1),
(697, 2, NULL, 409, 'Pierre de FIGUEIREDO', 'Pierre', 'de FIGUEIREDO', 'FR', 'pierredefi@gmail.com', '2023-12-01 21:37:06', '$2y$10$36n9JYv1qwL6RFH1QONsf.bf.K01dHj5/ocuTVh75.Z7lRigPZijq', NULL, 'client/1/user/697/avatar.jpg', 'uMqsHY9fhOiQRYO48QBNZJ16iLFGE7fGNMMRnoMXgjyjgNGSPVUcUEDV5ORQ', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-01 21:36:02', 0, '2023-12-29 22:25:12', NULL, 1),
(698, 2, NULL, NULL, 'Monica Ferguson', 'Monica', 'Ferguson', 'US', 'Monicaferguson818@gmail.com', NULL, '$2y$10$84ttOtfMaFIUHmW4WgifFeOSdbQfrXx4uNdkGHvVCzDBoAu771OLe', NULL, 'client/1/user/698/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-03 17:09:26', 0, '2023-12-03 17:09:26', NULL, 0),
(699, 2, NULL, NULL, 'Nihal kamble', 'Nihal', 'kamble', 'US', 'nihalinusa@gmail.com', '2023-12-07 04:18:29', '$2y$10$jBRpkKGuApOn79Ld3bwaGOOJn9//yjFhPDf5LbWffvMI.6n5pXpai', NULL, 'client/1/user/699/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-07 04:18:04', 0, '2023-12-07 04:18:04', NULL, 1),
(700, 2, NULL, NULL, 'BS', 'BS', '', 'US', 'tobwos@gmail.com', '2023-12-08 10:06:54', '$2y$10$S3aEkXI641X3zPRfmCC4j.uK3PHL2MnOFJ3iw2A1j2mfer8KlrU4q', NULL, 'client/1/user/700/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-08 10:04:39', 0, '2023-12-08 10:04:39', NULL, 1),
(701, 2, NULL, NULL, 'Dylan  Laplaunt', 'Dylan', '', 'US', 'dylanlaplaunt01@gmail.com', '2023-12-09 16:13:42', '$2y$10$.0cHlbyTfiqHP.gQmtp9fuFmvOdHm6.cEcg9ABHJbEplcogrO7hLK', NULL, 'client/1/user/701/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-09 16:12:41', 0, '2023-12-09 16:12:41', NULL, 1),
(702, 2, NULL, NULL, 'kaedin', 'kaedin', '', 'US', 'kaedin2604@gmail.com', NULL, '$2y$10$p2q4cScbUrXDpdzU9dO2xuN2CnYGxFr3EE7hB.jwJ2L7ql6i191Aq', NULL, 'client/1/user/702/avatar.jpg', '7gAYvu1cF54QDr5yKgmE5IBuv0x5kjAhhkYz89LDCeZ99upBefErgccm8JXP', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-13 20:48:04', 0, '2023-12-13 20:48:04', NULL, 0),
(703, 2, NULL, NULL, 'Saurabh Kumar', 'Saurabh', 'Kumar', 'US', 'Souravkumar0sk@gmail.com', '2023-12-15 10:47:06', '$2y$10$wYH5PDgliG2DqNNaOUVCfep/PclGU0wd3TKzhGHofRt9cVK8J3/m.', NULL, 'client/1/user/703/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-15 10:46:10', 0, '2023-12-15 10:46:10', NULL, 1),
(704, 2, NULL, NULL, 'Ilya', 'Ilya', '', 'US', 'theqiwistylezz@gmail.com', '2023-12-23 00:57:31', '$2y$10$Ff0RQnJ2UUGtE.gbxLJQVeWO2IR571YyrbeJQzHanCJwbS3m1KOhK', NULL, 'client/1/user/704/avatar.jpg', '6GDsbcHW5YSWUG4Y8TOtJrRjuzMqjgPSOft36hfvCG5bZVILiasVmJUbGRyU', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-23 00:57:14', 0, '2023-12-23 00:57:14', NULL, 1),
(705, 2, NULL, NULL, 'Alex', 'Alex', '', 'US', 'alekseatsburgers@protonmail.com', '2023-12-23 05:29:51', '$2y$10$FZzOWY.amHaVE7DhM9JZmeneNIK4U1gZvyetfzBoJCwWcvbiD7ITW', NULL, 'client/1/user/705/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-23 05:28:48', 0, '2023-12-23 05:28:48', NULL, 1),
(706, 2, NULL, NULL, 'Anshul Sebastian', 'Anshul', 'Sebastian', 'US', 'anshulsebastian.blitzjobs@gmail.com', '2023-12-28 20:52:42', '$2y$10$ppXCjfoGxiss5EYxn0AoH.Z6KfRwQDJwTQb0pE/ktezUCdprQwAr6', NULL, 'client/1/user/706/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-28 20:51:25', 0, '2023-12-28 20:51:25', NULL, 1),
(707, 2, NULL, NULL, 'Gregory', 'Gregory', NULL, 'US', 'gregsherman5@gmail.com', NULL, '$2y$10$Q12QbK3IAq2y8cwCKF3AcOCDQmyQFf/RLsRxHtfDhY39738o7QbAe', NULL, 'client/1/user/707/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-12-30 21:10:03', 0, '2023-12-30 21:10:03', NULL, 0),
(708, 2, NULL, NULL, 'Andrin', 'Andrin', '', 'US', 'subshero-test@loeschnig.ch', '2024-01-02 12:18:53', '$2y$10$3u5rgRwGgp36rjU/g17pTeTsUbnfz/G3URlxe4NCsUOzELkXyxiyu', NULL, 'client/1/user/708/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-02 12:17:30', 0, '2024-01-02 12:17:30', NULL, 1),
(709, 2, NULL, NULL, 'Pratiksha Ekbote', 'Pratiksha', 'Ekbote', 'US', 'ekbotepratiksha@gmail.com', '2024-01-04 14:16:46', '$2y$10$onT6egiFXBRMNblCoVl8j.RjyrvJa9JlrQAt4CfGiUNGsWwQclZyK', NULL, 'client/1/user/709/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-04 14:16:01', 0, '2024-01-04 14:16:01', NULL, 1),
(711, 2, NULL, NULL, 'Ian Wilson', 'Ian', 'Wilson', 'GB', 'iew100.subshero@manyme.com', NULL, '$2y$10$DPXCaHYxBXhioUcQ9BIHdOpyrSqdNeA66URkBo2GjUuuRbAkwaZXi', NULL, 'client/1/user/711/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-08 13:38:38', 0, '2024-01-08 13:39:59', NULL, 0),
(712, 2, NULL, NULL, 'Brandon Popp', 'Brandon Popp', NULL, 'US', 'blp2297@gmail.com', NULL, '$2y$10$R7wKvzK4MXFT1STDMC2Of.RajfEy3C3er9MLp5HvqT7VgjMhpr/Pe', NULL, 'client/1/user/712/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-11 20:20:57', 0, '2024-01-11 20:20:57', NULL, 0),
(713, 2, NULL, NULL, 'Jakub', 'Jakub', '', 'US', 'j.jozefczyk90@gmail.com', '2024-01-12 14:37:29', '$2y$10$Mk1Cp13MvhEoozPr4X2hT.7c.982WMTBwj5ZNBi6kCtpcV1dtU6Li', NULL, 'client/1/user/713/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-12 14:36:18', 0, '2024-01-12 14:36:18', NULL, 1),
(714, 2, NULL, NULL, 'anjali ravindran', 'anjali', 'ravindran', 'US', 'anjalibravindran@gmail.com', '2024-01-13 12:30:02', '$2y$10$8h5MIUqFrr8Ijjl5tkOfTegiiXTUbFyVhNamFeEqZX/P5gjtaKxCm', NULL, 'client/1/user/714/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-13 12:29:42', 0, '2024-01-13 12:29:42', NULL, 1),
(716, 2, NULL, NULL, 'Deepak', 'Deepak', '', 'US', 'deepakmaratha48@gmail.com', '2024-01-15 06:54:49', '$2y$10$VpARRmGI/mNfgKvGp2OaH.Z3Kj0Ze8Vw70aaHMuBbskIVsvrZh6Sy', NULL, 'client/1/user/716/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-15 06:53:50', 0, '2024-01-15 06:53:50', NULL, 1),
(717, 2, NULL, NULL, 'Bernard PC', 'Bernard', 'PC', 'PT', 'bernardo2988@gmail.com', '2024-01-16 19:19:08', '$2y$10$1HEAzqLw2drWPZrXSZ4xPuzg4kyNMn3uVOKqM4oRgaUadM7iCrEDa', NULL, 'client/1/user/717/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-16 19:18:48', 0, '2024-01-16 19:18:48', NULL, 1),
(718, 2, NULL, NULL, 'Xenonek_', 'Xenonek_', '', 'US', 'xenonek13@gmail.com', '2024-01-18 12:26:55', '$2y$10$2z5zYuBaWE845vwhcy2NC.HVY9jEHKOumgqYmi.D24O0niui1wSR6', NULL, 'client/1/user/718/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-18 12:26:35', 0, '2024-01-18 12:26:35', NULL, 1),
(719, 2, NULL, NULL, 'Marc Hembach', 'Marc', 'Hembach', 'US', 'marchembach1@gmx.de', '2024-01-19 06:39:34', '$2y$10$DFv17NiJ5dKyONql93gDMuOza6A2moeZokXhFsw/6UkQnqq5J4FHq', NULL, 'client/1/user/719/avatar.jpg', 'mnL9XKAvyyOjApdUBU0V7K6BKSfkiLC7YQCHs50N8BIYMfK3EVNkvnLXV5Vw', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-19 06:29:49', 0, '2024-04-11 05:35:47', NULL, 1),
(720, 2, NULL, NULL, 'Walter', 'Walter', '', 'US', 'rachel.7c372caa@nicoric.com', '2024-01-19 17:38:27', '$2y$10$vJXcNHbGVSa7yiM/H1ccL.tTKBS2vC0Iub5mhHYkgetTiNFgnTN9u', NULL, 'client/1/user/720/avatar.jpg', 'GZDKXWNsk7adYhcxFyggb6PufMr9uuuKYTfqVEJzMwYGzVwK7kJ7FAncNgWw', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-19 17:36:44', 0, '2024-01-19 17:36:44', NULL, 1),
(721, 2, NULL, NULL, 'Arsalan', 'Arsalan', '', 'US', 'arsalanj@gmail.com', '2024-01-22 08:29:39', '$2y$10$XUk1beq7.Fq8//phdxvM4.5yTrn3eTZIVAHGi8.sx/UuvmNiXh7JC', NULL, 'client/1/user/721/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-22 08:25:54', 0, '2024-01-22 08:25:54', NULL, 1),
(722, 2, NULL, NULL, 'Daniel Seelhöfer', 'Daniel', 'Seelhöfer', 'US', 'daniel.seelhoefer@protonmail.com', '2024-01-24 14:08:37', '$2y$10$nkSrUkR9/UZbPsn3SI1k.eV6J4W4Q4sb./zVq3Ok7xLjU3N6ffena', NULL, 'client/1/user/722/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-24 14:07:17', 0, '2024-01-24 14:07:17', NULL, 1),
(723, 2, NULL, NULL, 'Graham', 'Graham', NULL, 'US', 'grahamalxnder@gmail.com', NULL, '$2y$10$a6xsB75LHComWE/7GLwYxOYJEOPziRSlZ8ZrFodhlbIoFlcTb4QsO', NULL, 'client/1/user/723/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-28 17:00:34', 0, '2024-01-28 17:00:34', NULL, 0),
(725, 2, NULL, NULL, 'Tashi Norden Lepcha Lepcha', 'Tashi Norden Lepcha', 'Lepcha', 'IN', 'tashitnorden@gmail.com', NULL, '$2y$10$D4kXAiGF6gh/b6Ss2ZRU1OxiUZl3m.ePNFLrsG9S/iw6z6kP/aW/6', NULL, 'client/1/user/725/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-12 03:57:51', 0, '2024-02-12 04:02:03', NULL, 0),
(726, 2, NULL, NULL, 'Savitu Singh', 'Savitu', 'Singh', 'US', 'faceyogabysavitusingh@gmail.com', '2024-02-13 11:31:12', '$2y$10$NIrBMXWxlFCpiPImvxz1pey7pN6U4QvJ5SAemeK8Z/A62AV8USN0W', NULL, 'client/1/user/726/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-13 11:30:18', 0, '2024-02-13 11:30:18', NULL, 1),
(729, 2, NULL, NULL, 'Hasan H', 'Hasan', 'H', 'US', 'hasan.hosari@gmail.com', '2024-02-16 00:47:21', '$2y$10$f.zjUiQX2JBND0a4pltJTeF6HsEyLIdfd5kjO8qfZd/UcS0OT5Xby', NULL, 'client/1/user/729/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-16 00:46:57', 0, '2024-02-16 00:46:57', NULL, 1),
(730, 2, NULL, NULL, 'Makis P.', 'Makis', 'P.', 'GR', 'makpap500@gmail.com', '2024-02-23 10:01:32', '$2y$10$B3BtKqFZjL4ICW4Yjyk3Ju4S6zo.lR..Eyns6ikCweyq5joa4OLkC', NULL, 'client/1/user/730/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-23 10:01:08', 0, '2024-02-23 10:01:08', NULL, 1),
(731, 2, NULL, 411, 'Castle Arcade', 'Castle Arcade', NULL, 'US', 'castlearcade@gmail.com', '2024-03-08 15:42:33', '$2y$10$IN.RixHIQDjYz4USvj5/Se1NHmALxc6Ti1oPkoVKcy30CFpTER.LC', NULL, 'client/1/user/731/avatar.jpg', 'JHjGJ2lwUXnX3cGnpobkMpekrlACRKJnaj8TLzpXkOEvG7WaZIAtokhyGeIV', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-08 15:41:35', 0, '2024-03-08 15:41:35', NULL, 1),
(732, 2, NULL, NULL, 'Carlo Gino', 'Carlo', 'Gino', 'US', 'carloginocatapang@gmail.com', '2024-03-13 20:03:43', '$2y$10$xc9263DVBNM.qNvUAEdq2.SuGGlQfX09h5jmupyXOH79mNGz7r8/2', NULL, 'client/1/user/732/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-13 20:03:18', 0, '2024-03-13 20:03:18', NULL, 1),
(733, 2, NULL, NULL, 'Hellio', 'Hellio', '', 'US', 'chrissyboi005@gmail.com', '2024-03-14 03:55:26', '$2y$10$HyN66C02dVPwQT9lNgaDieihgDPWTOZBJCZ7Zdo3cGeaSGgM3UnXm', NULL, 'client/1/user/733/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-14 03:54:34', 0, '2024-03-14 03:54:34', NULL, 1),
(734, 2, NULL, 412, 'Saurabh Damor', 'Saurabh', 'Damor', 'IN', 'saurabhdamor17@gmail.com', '2024-03-26 11:54:19', '$2y$10$lBZjVi5aq.hqNR9jd.GUcuPrdf9TxIJzU8DWoTFLPc9AWWFn57bnO', NULL, 'client/1/user/734/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '8200235450', NULL, 0, NULL, NULL, 1),
(735, 2, NULL, NULL, 'Adrian', 'Adrian', '', 'US', 'abzr6o@gmail.com', '2024-03-29 08:14:33', '$2y$10$ZqsX2lHRKXAk2mEOJa204u4JCX2Zc85Z34b1yKTGp00PbkJOBEI1m', NULL, 'client/1/user/735/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-29 08:14:05', 0, '2024-03-29 08:14:05', NULL, 1),
(736, 2, NULL, NULL, 'Peter', 'Peter', '', 'US', 'meluspe@gmail.com', '2024-04-03 11:51:42', '$2y$10$v.Hin.cSMzGVYDn47ONBm.q69XHKk5eubNLfz1UStdfC3i4OPGtJi', NULL, 'client/1/user/736/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-03 11:51:22', 0, '2024-04-03 11:51:22', NULL, 1),
(737, 2, NULL, NULL, 'Mohamed', 'Mohamed', '', 'US', 'mohamedyahiaoui@live.fr', '2024-04-04 13:28:51', '$2y$10$l13MP8n8Ev6YmgwQxVxoDOPQgOCercTdyq74CUV6/.17hZomqY7Hq', NULL, 'client/1/user/737/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-04 13:27:54', 0, '2024-04-04 13:27:54', NULL, 1),
(738, 2, NULL, NULL, 'icerage', 'icerage', '', 'US', 'silasarmont@gmail.com', '2024-04-07 23:44:31', '$2y$10$WaaVp6SUbIGrfup9f0.ODeOUdQOV7Xgo9.8UIN.gLW2Scl79p7.KO', NULL, 'client/1/user/738/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-07 23:44:03', 0, '2024-04-07 23:44:03', NULL, 1),
(739, 2, NULL, NULL, 'Abdullah .', 'Abdullah', '.', 'SA', 'hicardstore@gmail.com', '2024-04-14 00:43:29', '$2y$10$LkOlI.B3Rbr4/B.VNF6.MefPKKuvmu.bWC12Tf6gbg57ULrEvsneW', NULL, 'client/1/user/739/avatar.jpg', 'gPzfJTrwruzm57iCMwQcAS6GzytpvnAJLX3jxwMrewLIhrNVlo5GNwwSSqnq', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-14 00:42:37', 0, '2024-04-14 00:42:37', NULL, 1),
(740, 2, NULL, NULL, 'Dimitris', 'Dimitris', '', 'US', 'dimioakim@gmail.com', '2024-04-26 03:02:54', '$2y$10$9drbm/7Mgq8I/sp5QYzVkuGs88LVF2a987pZtRY9Mo4NdXaolK29O', NULL, 'client/1/user/740/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-26 03:02:31', 0, '2024-04-26 03:02:31', NULL, 1),
(741, 2, NULL, NULL, 'Mountains Therapy', 'Mountains', 'Therapy', 'US', 'contact@mountainstherapy.com', '2024-05-10 21:45:30', '$2y$10$di0xmRuCSv0BDWeDM.M0feMABQnnzH0vghGallh2.RdrYlWmMxyHq', NULL, 'client/1/user/741/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, 'Mountains Therapy', 'mountainstherapy', '2015883776', '2024-05-10 21:45:04', 0, '2024-05-10 21:45:04', NULL, 1),
(743, 2, NULL, NULL, 'Gayathri Smitha', 'Gayathri', 'Smitha', 'US', 'gsmitha30@gmail.com', '2024-05-27 08:26:47', '$2y$10$.z1bDkpH8pgwE54nrRyaO.Oy4qhDQwN56CONt5Z6NC9.ON8T1dnyW', NULL, 'client/1/user/743/avatar.jpg', 'a2kM5J9GTcYUdPJyVdkxPKtGIPR2NsjYRyoVHq6GP4KXx6vWH31pgbaz8nja', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-27 08:26:18', 0, '2024-05-27 08:26:18', NULL, 1),
(744, 2, NULL, NULL, 'Karen DeRoche', 'Karen', 'DeRoche', 'US', 'dkderoche@gmail.com', '2024-06-04 15:48:54', '$2y$10$TL9Uuh2pqV.2WdEwVzE59.1BlAPOQtUBOMHDPk1sk/7wsse7NS9de', NULL, 'client/1/user/744/avatar.jpg', 'FgBwzbwKxLxvQeAUryY4GhLmzKkNF0hfwKQoubGgIOpzWd1xPvl9ycJ16531', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-04 15:45:59', 0, '2024-06-04 15:48:28', NULL, 1),
(745, 2, NULL, NULL, 'sarah schoen', 'sarah', 'schoen', 'US', 'sarahschoen21@gmail.com', '2024-06-07 03:44:40', '$2y$10$9d6q5Rw1bx7kUYfQgJAFnOjYWxytFjm3.iVHKfQOLBD93Duymy9Tu', NULL, 'client/1/user/745/avatar.jpg', 'uCFXJ03d2T8uTwYDgs0Xf30LY7suqvEL0raqg5OyuOeVjKIRbDbmIUzPYZzg', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-07 03:42:04', 0, '2024-06-07 03:43:50', NULL, 1),
(746, 2, NULL, NULL, 'Nora Jensen', 'Nora', 'Jensen', 'US', 'twistedwizard1228@yahoo.com', '2024-06-07 04:57:21', '$2y$10$F2BRUYxWn502LEWNQYbr0.iw7rkq.zuaSEhYdBXpbrP5ryPVOKQHu', NULL, 'client/1/user/746/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-07 04:56:32', 0, '2024-06-07 04:56:32', NULL, 1),
(747, 2, NULL, NULL, 'Luke Staszewski', 'Luke', 'Staszewski', 'US', 'luke@transformers.agency', '2024-06-16 20:56:56', '$2y$10$8HDHLfSKLjmaN9NAhiiUl.kmc0xl4lNXoiBQb6IAklTyIAFmDK0Di', NULL, 'client/1/user/747/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-16 20:56:33', 0, '2024-06-16 20:56:33', NULL, 1),
(748, 2, NULL, NULL, 'Fahim Abrar', 'Fahim', 'Abrar', 'US', 'fahim@optimalcreatives.com', '2024-06-25 03:25:09', '$2y$10$oaq6eUCyqtBxjVPpcI15/.YE9.Md5lOlpqUdxk542UF39aDn.Gyre', NULL, 'client/1/user/748/avatar.jpg', 'pRdxxFgq4UdlTBLpoOZfOHur7Eh0DHucMvTLhA7YdThP41baTQ9TJprLCFTf', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-25 03:24:38', 0, '2024-06-25 03:24:38', NULL, 1),
(749, 2, NULL, NULL, 'Soelgm', 'Soelgm', '', 'US', 'gomselars@gmail.com', NULL, '$2y$10$RCRrWLpSf9kPU3FpJH437uVO8v8drryYyhufgIoP0DuHz4yhYGTtu', NULL, 'client/1/user/749/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-26 14:25:26', 0, '2024-06-26 14:25:26', NULL, 0),
(750, 2, NULL, NULL, 'Brandon Coburn', 'Brandon', 'Coburn', 'US', 'brandon@bazingastudios.com', '2024-06-27 16:22:07', '$2y$10$Z/hhoyQnRP360bPzT6GhI.XV3.SYAsZRB4pro8umDGszxkAdbu1K6', NULL, 'client/1/user/750/avatar.jpg', 'PM9BBZZK3nOyiY2A5EvuVeaycCMWthhRxLAnpID6Y6HMRiRYjikRjc1Riet2', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-27 16:20:52', 0, '2024-06-27 16:20:52', NULL, 1),
(751, 2, NULL, NULL, 'Brennan Schloendorn', 'Brennan', 'Schloendorn', 'US', 'bschloendorn13@jcu.edu', '2024-07-11 09:51:31', '$2y$10$uxc//8H1Y6plRkyZW2kI7u5ZnL5lgnJNYgGecp.zOO2HL.A2fz4Nu', NULL, 'client/1/user/751/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-11 09:50:59', 0, '2024-07-11 09:50:59', NULL, 1),
(752, 2, NULL, NULL, 'RS', 'RS', '', 'US', 'rshah_sap@yahoo.co.in', '2024-07-12 11:54:03', '$2y$10$TvmqEXGMPAaQ/WdelzSy2uUJgvIAqvKuR5MK/Wpdr3M28Se76fsba', NULL, 'client/1/user/752/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-12 11:53:25', 0, '2024-07-12 11:53:25', NULL, 1),
(754, 2, NULL, NULL, 'Chase Bobier', 'Chase', 'Bobier', 'US', 'crtbgaming@gmail.com', '2024-07-22 14:53:14', '$2y$10$REInk2MyJw5kFzlhipVurO8bBOL8IPDtWohOifRKynyjfr9mGYvTO', NULL, 'client/1/user/754/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-22 14:52:25', 0, '2024-07-22 14:52:25', NULL, 1),
(755, 2, NULL, NULL, 'Daniel', 'Daniel', '', 'US', 'oluwatoyinaji@gmail.com', '2024-07-23 17:58:09', '$2y$10$Y9LYd3GsOaKDKi8TcjzRCuZ8WS/mN8E3LeGez0L.HuqPMf.WP.OpG', NULL, 'client/1/user/755/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-23 17:57:47', 0, '2024-07-23 17:57:47', NULL, 1),
(756, 2, NULL, NULL, 'Kolade Amire', 'Kolade', 'Amire', 'US', 'stephamire@gmail.com', '2024-07-31 11:41:40', '$2y$10$hhjALHOaraRstPHL3w5EWexApFJ6MB71hmDbmeZKLBzfkbmBGrNN2', NULL, 'client/1/user/756/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-31 11:40:39', 0, '2024-07-31 11:40:39', NULL, 1),
(757, 2, NULL, NULL, 'Ali Mehdi Mukadam', 'Ali', 'Mehdi', 'US', 'alimehdi.m@gmail.com', '2024-08-01 22:21:51', '$2y$10$huO1byGm4BIYUWN.8P.AEe7BtlXmASfLIfb6b75mrsCxiQqB9j9am', NULL, 'client/1/user/757/avatar.jpg', 'T1kWKmznGCmu5NtDlhz2TDsdi4Vtur0BAoeUV2nJPOUrBvsxHDT3bR3OLHhI', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-01 22:21:08', 0, '2024-08-01 22:21:08', NULL, 1),
(758, 2, NULL, NULL, 'mustapha erraja', 'mustapha', 'erraja', 'US', 'rajamustaphafs@gmail.com', '2024-08-03 19:32:06', '$2y$10$/0ofCEL7ymc4A5WwzxPNLe2caFoMS5wgTRNWf1vd8P16bbVMzKXGm', NULL, 'client/1/user/758/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-03 19:30:04', 0, '2024-08-03 19:30:04', NULL, 1),
(759, 2, NULL, NULL, 'Jonalyn Ramos', 'Jonalyn', 'Ramos', 'US', 'admin@aureatechnologysolutions.com', '2024-08-04 13:54:09', '$2y$10$Mum/aGr6OQeOlnGeT4tpU.YtRurvxR0IhjQWBqe8N//muKAE6I2ie', NULL, 'client/1/user/759/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-04 13:53:46', 0, '2024-08-04 13:53:46', NULL, 1),
(760, 2, NULL, NULL, 'amar brahim selt', 'amar', 'brahim', 'US', 'Selto95@gmail.com', '2024-08-08 21:30:09', '$2y$10$q2DeSlyHiniV.MJC9TQ4tevvBLJ2gIu.joIBTWk5ho8vYhSTRsdBG', NULL, 'client/1/user/760/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-08 21:29:43', 0, '2024-08-08 21:29:43', NULL, 1),
(761, 2, NULL, NULL, 'Ian McIntosh', 'Ian', 'McIntosh', 'US', 'ianmc1981@gmail.com', '2024-08-19 06:20:40', '$2y$10$ztaq8BO0tl/.Zw3xmuy2x.dBZsDo1shs871UPjc6WMciFxzaFtaO.', NULL, 'client/1/user/761/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-19 06:20:05', 0, '2024-08-19 06:20:05', NULL, 1),
(762, 2, NULL, NULL, 'appeals', 'appeals', '', 'US', 'appealscws@gmail.com', '2024-08-20 02:44:21', '$2y$10$YbnCiVeGJ16hcDwyouZvu.jcOQlWrcsSfwu0ANCekqsxuo3pqLEQu', NULL, 'client/1/user/762/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-20 02:43:54', 0, '2024-08-20 02:43:54', NULL, 1),
(763, 2, NULL, NULL, 'Dusty', 'Dusty', '', 'US', 'dusty@talentondemand.net', '2024-08-30 00:12:08', '$2y$10$HYNwhSoEpCghRxOQZUQyzetGmTLxmiLy0Thi0hy7WPXBrXLFvByFG', NULL, 'client/1/user/763/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-30 00:11:43', 0, '2024-08-30 00:11:43', NULL, 1),
(764, 2, NULL, NULL, 'Aakash Mandava', 'Aakash', 'Mandava', 'US', 'aakashm2003@gmail.com', '2024-09-02 06:23:22', '$2y$10$ESVJg6BeQQeKEnaxtrvqhuuFVBGUTQq1WaQUMFxXDkiSFayWkGlYS', NULL, 'client/1/user/764/avatar.jpg', 'hKI3Ox7Yja7TeeNLdpv5uIkNVXIU8Yxvl1GJEUs9uhtDE10rBnEXtYWnNU7w', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-02 06:22:39', 0, '2024-09-02 06:22:39', NULL, 1),
(766, 2, NULL, NULL, 'M. Arif Budiman', 'M.', 'Arif', 'US', 'mediacerebri@gmail.com', '2024-09-03 19:16:17', '$2y$10$hg0Us51D50AwcH4/LFfQFeAeFIM6kASBO0O8c8uLSTYjKIT3dyIZK', NULL, 'client/1/user/766/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-03 19:15:56', 0, '2024-09-03 19:15:56', NULL, 1),
(767, 2, NULL, NULL, 'Nimrod Mike', 'Nimrod', 'Mike', 'US', 'nimrod.mike@icloud.com', '2024-09-04 05:52:21', '$2y$10$wrplBh4F/GA0Iczw0rWbF.dHaFWxsmwjpzJY3CXvDc4/Rxs.sQF.S', NULL, 'client/1/user/767/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-04 05:51:19', 0, '2024-09-04 05:51:19', NULL, 1),
(768, 2, NULL, NULL, 'Xiaofeng Pan', 'Xiaofeng', 'Pan', 'US', 'xiaofengpan550@gmail.com', '2024-09-05 21:47:03', '$2y$10$70X2WfPkxsDv9Cas0gsnv.mmT1JBuUv1EImViRczJ6he2.irveL5W', NULL, 'client/1/user/768/avatar.jpg', 'L5Fsm4CnlVjPnga1koT26MX7WPaH3S6rS8WMKYsnSqxNXif0LDHGiPtWB3as', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-05 21:45:47', 0, '2024-09-05 21:45:47', NULL, 1),
(769, 2, NULL, NULL, 'Jayden', 'Jayden', '', 'US', 'jaydenw91@gmail.com', '2024-09-08 11:51:25', '$2y$10$ZZAl66I3qilh5nCS7pHMRu.ofZ31apEA1AcKKbXT1CyK7wrlJ/Fmy', NULL, 'client/1/user/769/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-08 11:51:07', 0, '2024-09-08 11:51:07', NULL, 1),
(771, 2, NULL, NULL, 'Kasper Skov Jensen', 'Kasper', 'Skov', 'US', 'kasperwood@gmail.com', '2024-09-25 19:42:03', '$2y$10$9AcCLjORhbtiL3JKUipp9OJ6Ay1fCYlk83L7hzpRfNKbBiz7Mu5DS', NULL, 'client/1/user/771/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-25 19:41:30', 0, '2024-09-25 19:41:30', NULL, 1),
(773, 2, NULL, NULL, 'Atif Shahid', 'Atif', 'Shahid', 'US', 'atifshahid.fastian@gmail.com', '2024-10-11 18:54:43', '$2y$10$gExlajxSKIVoBa1ufMGoXuluJTOEU0mG0c2dhHZD24n589zn0p/Nu', NULL, 'client/1/user/773/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-11 18:54:00', 0, '2024-10-11 18:54:00', NULL, 1),
(774, 2, NULL, NULL, 'Raheel Sturridge', 'Raheel', 'Sturridge', 'US', 'raheel.sturridge@gmail.com', '2024-11-02 23:58:27', '$2y$10$VsswbLNKa1Om0nU8lX48e..3LYHMLd6zviVx3Z0Pw1tL1HMOcqgtG', NULL, 'client/1/user/774/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-02 23:58:09', 0, '2024-11-02 23:58:09', NULL, 1),
(775, 2, NULL, NULL, 'Niall Quigley', 'Niall', 'Quigley', 'US', 'niallquigley92@gmail.com', '2024-11-03 04:32:16', '$2y$10$6muRPZaKNyxnZeysAV8aSOCIr/vc7I6Soc4hkIBM4pBAPlNxHBRdu', NULL, 'client/1/user/775/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-03 04:31:51', 0, '2024-11-03 04:31:51', NULL, 1),
(776, 2, NULL, NULL, 'Sangharsh Gautam', 'Sangharsh', 'Gautam', 'US', 'sangharsh@gmail.com', '2024-11-11 19:11:07', '$2y$10$fIwHdLSx8H5SX.rfnze5rOBgMifTx3hMOEO3bn8fDleaTfHEDNUEa', NULL, 'client/1/user/776/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-11 19:10:18', 0, '2024-11-11 19:10:18', NULL, 1),
(777, 2, NULL, NULL, 'Eden', 'Eden', '', 'US', 'eden@moseri.com', '2024-11-21 01:47:02', '$2y$10$Z6/rDTPhuVfXoAi8BcUtEuH2gjoHjsKAYn8Ej7kIHciikCtbivdlW', NULL, 'client/1/user/777/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-21 01:46:39', 0, '2024-11-21 01:46:39', NULL, 1),
(778, 2, NULL, NULL, 'arul', 'arul', '', 'US', 'j75328320@gmail.com', '2024-11-24 07:46:26', '$2y$10$.f2m1g89OxELZY2wqXSghOd60yrJQbcnNiTjXueAXwn.t7B5f/60u', NULL, 'client/1/user/778/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-24 07:45:52', 0, '2024-11-24 07:45:52', NULL, 1),
(779, 2, NULL, NULL, 'Fede', 'Fede', '', 'US', 'federico.moisello@axialent.com', '2024-11-27 18:46:55', '$2y$10$kvZiGec6IYCKhDa4XeCsFuRO9HpMR8nuwfCqWEiB2/R7Gim68U6Va', NULL, 'client/1/user/779/avatar.jpg', '4NQYnXk5AaV9S5G0EosaXW3xjpsDz5KlYiYGJhaNhGv5iidWlWxFrv5tohwg', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-27 18:45:34', 0, '2024-11-27 18:45:34', NULL, 1),
(780, 2, NULL, NULL, 'Santiago', 'Santiago', '', 'US', 'salonsoweb@gmail.com', '2024-12-06 12:35:53', '$2y$10$sev2x1a9GPCylUrMWhiPruY.F5JEIrFKLqh26SaBJjdvvJiI7VscO', NULL, 'client/1/user/780/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-06 12:35:10', 0, '2024-12-06 12:35:10', NULL, 1),
(781, 2, NULL, NULL, 'Jose', 'Jose', '', 'US', 'finny.jose@yahoo.com', '2025-01-06 11:05:32', '$2y$10$UFe63m9MezBeOu19.Ttu.eyYGH/VMC1YO.gxzcLpxpCRk1Skto8Hq', NULL, 'client/1/user/781/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-06 11:05:11', 0, '2025-01-06 11:05:11', NULL, 1),
(782, 2, NULL, NULL, 'Ngh Chy Hor', 'Ngh', 'Chy', 'US', 'danielhor00@gmail.com', NULL, '$2y$10$9PerGyps6FHaoymWBjEH9eUJxAqvViChmaYa4ORHj/s8ntx.YnKj.', NULL, 'client/1/user/782/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-06 16:42:01', 0, '2025-01-06 16:42:01', NULL, 0),
(783, 2, NULL, NULL, 'Will G', 'Will', 'G', 'US', 'greenski@yahoo.com', '2025-01-26 08:28:33', '$2y$10$uI59dYFp2/SUcv1ZuDJqyeOsiUANm9iKawpboHKd91KLiiPeZPYoy', NULL, 'client/1/user/783/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-26 08:28:07', 0, '2025-01-26 08:28:07', NULL, 1),
(784, 2, NULL, NULL, 'Benja', 'Benja', '', 'US', 'mbikenin@mail.ru', '2025-02-25 14:19:13', '$2y$10$dAmIKOD2cKRgOTYIfz6aKO4Lb.XFW4SERC9B1KF0sK5ORYVGHprfG', NULL, 'client/1/user/784/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-25 13:54:11', 0, '2025-02-25 13:54:11', NULL, 1),
(785, 2, NULL, NULL, 'kristineqty', 'kristineqty', '', 'US', 'matthiasspowers1449@tempr.email', NULL, '$2y$10$26yhUiS0QdiXtLlztwMfMOBj0GAdKsNWcEVcpT2RtdcU4pbv8TSui', NULL, 'client/1/user/785/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-27 21:51:53', 0, '2025-02-27 21:51:53', NULL, 0),
(786, 2, NULL, NULL, 'glebanya', 'glebanya', '', 'US', 'klfayu4gk1@gmail.com', '2025-02-28 07:36:23', '$2y$10$kxguhDGxVYVOop4qWMKwQ.xANbqhIiHume2pUbcizFa.WwLGVan1u', NULL, 'client/1/user/786/avatar.jpg', '3bkm8Y0t3Yz1RZrcSxT5PFLDpO7YgH6K5nEYAclhZ407iWgmAZbXRYZLtape', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-28 07:34:50', 0, '2025-02-28 07:34:50', NULL, 1),
(787, 2, NULL, NULL, 'John Kek', 'John', 'Kek', 'US', 'p76k03@monthly.paced.email', '2025-03-01 05:18:56', '$2y$10$1RNBFHRAYFjl2jq3JDSmc.p18KHvHcc/GikM5zrLZB3Z4u1l4af/K', NULL, 'client/1/user/787/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-01 05:18:04', 0, '2025-03-01 05:18:04', NULL, 1),
(788, 2, NULL, NULL, 'Millie Kaur', 'Millie', 'Kaur', 'US', 'kaurh18@uw.edu', NULL, '$2y$10$XatKvW5JEaAYTq8IRXaD7ebhxBdqrT4115Nlw/Ebdd9Pmcdn7CxTG', NULL, 'client/1/user/788/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-11 20:09:13', 0, '2025-03-11 20:09:13', NULL, 0),
(789, 2, NULL, NULL, 'Demi', 'Demi', '', 'US', 'binge.acronym125@passinbox.com', '2025-03-15 00:33:13', '$2y$10$YRVAHCBG1iBlsjnkDnPUy.yrtk1e8f3d/Z7Im1wSpbzyW2Q1IuIhC', NULL, 'client/1/user/789/avatar.jpg', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 00:32:20', 0, '2025-03-15 00:32:20', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_alert`
--

CREATE TABLE `users_alert` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0 COMMENT '1=Default',
  `time_period` int(11) DEFAULT NULL COMMENT 'Number of days',
  `time_cycle` tinyint(1) DEFAULT NULL COMMENT '1=Days Before',
  `time` time DEFAULT NULL COMMENT 'Time to send the alert',
  `alert_condition` tinyint(1) DEFAULT NULL COMMENT '1=All, 2=Before Due Date, 3=Before Refund Date',
  `alert_contact` int(11) DEFAULT NULL COMMENT 'users_contacts -> id',
  `alert_type` tinyint(1) DEFAULT 1 COMMENT '1=All, 2=Email, 3=Browser',
  `alert_types` set('email','browser','extension','mobile','webhook') NOT NULL,
  `alert_name` varchar(30) DEFAULT NULL COMMENT 'Name of the alert',
  `timezone` varchar(50) DEFAULT NULL COMMENT 'For future use',
  `alert_subs_type` tinyint(1) DEFAULT NULL COMMENT '1=Subscription, 2=Trial, 3=Lifetime',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_alert_preferences`
--

CREATE TABLE `users_alert_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time_period` tinyint(1) DEFAULT NULL,
  `time_cycle` tinyint(1) DEFAULT NULL COMMENT '1=Days Before, 2=Days After, 3=Day Before Refund date',
  `time` time DEFAULT NULL,
  `monthly_report` tinyint(1) DEFAULT NULL,
  `monthly_report_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_billing`
--

CREATE TABLE `users_billing` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_calendar`
--

CREATE TABLE `users_calendar` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_contacts`
--

CREATE TABLE `users_contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0 COMMENT '0=Inactive, 1=Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_payment_methods`
--

CREATE TABLE `users_payment_methods` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_plans`
--

CREATE TABLE `users_plans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `description` text DEFAULT NULL COMMENT 'Plan text for reports',
  `total_subs` smallint(6) NOT NULL DEFAULT 0,
  `total_folders` smallint(6) NOT NULL DEFAULT 0,
  `total_tags` smallint(6) NOT NULL DEFAULT 0,
  `total_contacts` smallint(6) NOT NULL DEFAULT 0,
  `total_pmethods` smallint(6) NOT NULL DEFAULT 0 COMMENT 'payment methods',
  `total_alert_profiles` smallint(6) NOT NULL DEFAULT 0 COMMENT 'Alert profiles',
  `total_webhooks` smallint(6) NOT NULL DEFAULT 0 COMMENT 'Webhooks',
  `total_teams` smallint(6) NOT NULL DEFAULT 0 COMMENT 'Team accounts',
  `total_storage` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Storage in bytes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_profile`
--

CREATE TABLE `users_profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timezone` varchar(50) DEFAULT NULL COMMENT 'Timezone GMT',
  `currency` varchar(10) DEFAULT NULL COMMENT 'Currency code',
  `language` varchar(20) DEFAULT NULL,
  `billing_frequency` tinyint(1) DEFAULT NULL,
  `billing_cycle` tinyint(1) DEFAULT NULL COMMENT '1=Daily, 2=Weekly, 3=Monthly, 4=Yearly',
  `billing_type` tinyint(1) DEFAULT 1 COMMENT '1=Calculate by days, 2=Calculate by date',
  `payment_mode` varchar(20) DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL COMMENT 'users_payment_methods -> id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_report`
--

CREATE TABLE `users_report` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_teams`
--

CREATE TABLE `users_teams` (
  `id` int(11) NOT NULL,
  `team_user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `pro_user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `pro_user_email` varchar(50) DEFAULT NULL COMMENT 'users -> email',
  `status` tinyint(1) DEFAULT NULL COMMENT '1=Sent, 2=Accepted',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'users -> id',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_tour_status`
--

CREATE TABLE `users_tour_status` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 0 COMMENT '0=Incomplete, 1=Finished',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

CREATE TABLE `versions` (
  `id` int(5) NOT NULL,
  `versions_name` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_version` varchar(10) DEFAULT NULL,
  `last_updated` date DEFAULT NULL,
  `is_update_available` tinyint(1) DEFAULT 0 COMMENT '0=False, 1=True',
  `last_checked` date DEFAULT NULL,
  `catalog_version` varchar(10) DEFAULT NULL COMMENT 'Last catalog version executed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webhooks`
--

CREATE TABLE `webhooks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `type` tinyint(1) DEFAULT NULL COMMENT '1=Incoming, 2=Outgoing',
  `name` varchar(50) DEFAULT NULL,
  `endpoint_url` tinytext DEFAULT NULL COMMENT 'Webhook URL',
  `events` text DEFAULT NULL COMMENT 'Comma-separated values',
  `token` varchar(40) DEFAULT NULL COMMENT 'Token for incoming request',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Inactive, 1=Active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webhook_logs`
--

CREATE TABLE `webhook_logs` (
  `id` int(11) NOT NULL,
  `webhook_id` int(11) DEFAULT NULL COMMENT 'webhooks -> id',
  `user_id` int(11) DEFAULT NULL COMMENT 'users -> id',
  `type` tinyint(1) DEFAULT NULL COMMENT '1=Incoming, 2=Outgoing',
  `event` tinytext DEFAULT NULL COMMENT 'webhooks -> events',
  `request` mediumtext DEFAULT NULL COMMENT 'Request data',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_users`
--

CREATE TABLE `wp_users` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(255) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT 0,
  `display_name` varchar(250) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alert_type`
--
ALTER TABLE `alert_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_flags`
--
ALTER TABLE `cron_flags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency_conversion`
--
ALTER TABLE `currency_conversion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_notifications`
--
ALTER TABLE `email_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_types`
--
ALTER TABLE `email_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_table_row_id` (`table_row_id`),
  ADD KEY `idx_event_cron` (`event_cron`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_event_type_status` (`event_type_status`),
  ADD KEY `idx_event_status` (`event_status`),
  ADD KEY `idx_event_datetime` (`event_datetime`);

--
-- Indexes for table `event_browser`
--
ALTER TABLE `event_browser`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_table_row_id` (`table_row_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_event_cron` (`event_cron`),
  ADD KEY `idx_event_status` (`event_status`),
  ADD KEY `idx_event_type_status` (`event_type_status`),
  ADD KEY `idx_event_datetime` (`event_datetime`),
  ADD KEY `idx_event_type_schedule` (`event_type_schedule`);

--
-- Indexes for table `event_browser_notify`
--
ALTER TABLE `event_browser_notify`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `event_chrome`
--
ALTER TABLE `event_chrome`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_table_row_id` (`table_row_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_event_cron` (`event_cron`),
  ADD KEY `idx_event_status` (`event_status`),
  ADD KEY `idx_event_type_status` (`event_type_status`),
  ADD KEY `idx_event_datetime` (`event_datetime`),
  ADD KEY `idx_event_type_schedule` (`event_type_schedule`);

--
-- Indexes for table `event_chrome_extn`
--
ALTER TABLE `event_chrome_extn`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_read` (`read`);

--
-- Indexes for table `event_emails`
--
ALTER TABLE `event_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_table_row_id` (`table_row_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_event_cron` (`event_cron`),
  ADD KEY `idx_event_status` (`event_status`),
  ADD KEY `idx_event_type_status` (`event_type_status`),
  ADD KEY `idx_event_datetime` (`event_datetime`),
  ADD KEY `idx_event_type_schedule` (`event_type_schedule`);

--
-- Indexes for table `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `event_webhook`
--
ALTER TABLE `event_webhook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extension_settings`
--
ALTER TABLE `extension_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `folder`
--
ALTER TABLE `folder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_coupons`
--
ALTER TABLE `plan_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_platforms`
--
ALTER TABLE `product_platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `push_notification_registers`
--
ALTER TABLE `push_notification_registers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings_lang`
--
ALTER TABLE `settings_lang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings_preferences`
--
ALTER TABLE `settings_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions_attachments`
--
ALTER TABLE `subscriptions_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions_history`
--
ALTER TABLE `subscriptions_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions_tags`
--
ALTER TABLE `subscriptions_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_cart`
--
ALTER TABLE `subscription_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tmp_migrate_paths`
--
ALTER TABLE `tmp_migrate_paths`
  ADD PRIMARY KEY (`id`),
  ADD KEY `old_file_exists` (`old_file_exists`),
  ADD KEY `new_file_exists` (`new_file_exists`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users_alert`
--
ALTER TABLE `users_alert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_alert_preferences`
--
ALTER TABLE `users_alert_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_billing`
--
ALTER TABLE `users_billing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_calendar`
--
ALTER TABLE `users_calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_contacts`
--
ALTER TABLE `users_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_payment_methods`
--
ALTER TABLE `users_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_plans`
--
ALTER TABLE `users_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_profile`
--
ALTER TABLE `users_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_report`
--
ALTER TABLE `users_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_teams`
--
ALTER TABLE `users_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_tour_status`
--
ALTER TABLE `users_tour_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `versions`
--
ALTER TABLE `versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webhooks`
--
ALTER TABLE `webhooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webhook_logs`
--
ALTER TABLE `webhook_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_users`
--
ALTER TABLE `wp_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alert_type`
--
ALTER TABLE `alert_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cron_flags`
--
ALTER TABLE `cron_flags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency_conversion`
--
ALTER TABLE `currency_conversion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_notifications`
--
ALTER TABLE `email_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `email_types`
--
ALTER TABLE `email_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_browser`
--
ALTER TABLE `event_browser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_browser_notify`
--
ALTER TABLE `event_browser_notify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_chrome`
--
ALTER TABLE `event_chrome`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_chrome_extn`
--
ALTER TABLE `event_chrome_extn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_emails`
--
ALTER TABLE `event_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_webhook`
--
ALTER TABLE `event_webhook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extension_settings`
--
ALTER TABLE `extension_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `folder`
--
ALTER TABLE `folder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `plan_coupons`
--
ALTER TABLE `plan_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_platforms`
--
ALTER TABLE `product_platforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `push_notification_registers`
--
ALTER TABLE `push_notification_registers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings_lang`
--
ALTER TABLE `settings_lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings_preferences`
--
ALTER TABLE `settings_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions_attachments`
--
ALTER TABLE `subscriptions_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions_history`
--
ALTER TABLE `subscriptions_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions_tags`
--
ALTER TABLE `subscriptions_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_cart`
--
ALTER TABLE `subscription_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmp_migrate_paths`
--
ALTER TABLE `tmp_migrate_paths`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=790;

--
-- AUTO_INCREMENT for table `users_alert`
--
ALTER TABLE `users_alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_alert_preferences`
--
ALTER TABLE `users_alert_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_billing`
--
ALTER TABLE `users_billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_calendar`
--
ALTER TABLE `users_calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_contacts`
--
ALTER TABLE `users_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_payment_methods`
--
ALTER TABLE `users_payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_plans`
--
ALTER TABLE `users_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_profile`
--
ALTER TABLE `users_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_report`
--
ALTER TABLE `users_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_teams`
--
ALTER TABLE `users_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_tour_status`
--
ALTER TABLE `users_tour_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `versions`
--
ALTER TABLE `versions`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webhooks`
--
ALTER TABLE `webhooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webhook_logs`
--
ALTER TABLE `webhook_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_users`
--
ALTER TABLE `wp_users`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
