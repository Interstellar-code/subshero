<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\Cron;
use Illuminate\Support\Facades\Storage;

class RunCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:run {type} {--user_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run cron command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->argument('type');
        $this->info("Running cron: $type");
        switch ($type) {
            case 'schedule':
                $count = Cron::schedule();
                break;

            case 'mail':
                $count = Cron::send_mail();
                break;

            case 'plan':
                $count = Cron::plan_calculate();
                break;

            case 'report':
                $count = Cron::report();
                break;

            case 'misc':
                $count = Cron::misc();
                break;

            case 'notification':
                $count = Cron::notification();
                break;
            
            case 'fix_subscription_history':
                $count = Cron::fix_subscription_history($this->option('user_id'));
                break;
            case 'messages':
                $count = Cron::send_messages();
                break;
            case 'migrate_notifications':
                $count = Cron::migrate_notifications();
                break;
            default:
                $this->error("Invalid cron type: $type");
                return 1;
        }
        Storage::disk('local')->append("system/cron/$type.log", '[' . (string)lib()->do->timezone_convert([
            'from_timezone' => LARAVEL_TIMEZONE,
            'to_timezone' => APP_TIMEZONE,
            'return_format' => DATE_ATOM,
        ]) . ']: ' . json_encode([
            'status' => true,
            'message' => "Total $count records processed",
        ]));
        $this->info("Total $count records processed");
        return 0;
    }
}
