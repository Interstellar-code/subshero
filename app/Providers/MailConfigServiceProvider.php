<?php

namespace App\Providers;

use App\Models\SettingsModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $config = SettingsModel::get(1);

        if (!empty($config)) {
            $mail_config = array(
                'driver'     => 'smtp',
                'host'       => $config->smtp_host,
                'port'       => $config->smtp_port,
                'username'   => $config->smtp_username,
                'password'   => $config->smtp_password,
                'encryption' => $config->smtp_encryption,
                'from'       => [
                    'address' => $config->smtp_sender_email,
                    'name' => $config->smtp_sender_name
                ],
                'sendmail'   => '/usr/sbin/sendmail -bs',
                'pretend'    => false,
            );

            Config::set('mail', $mail_config);
        }
    }
}
