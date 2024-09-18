<?php

namespace App\Console\Commands;

use App\Mail\NotificationByDatesMail;
use Illuminate\Console\Command;
use App\Services\DateService;
use App\Services\GfileReaderService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailable;

class CheckDatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-dates-command {url} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = GfileReaderService::getContent($this->input->getArgument('url'));
        $data = GfileReaderService::processCsv($data);
        $data = DateService::searchByDate($data, time(), 'Дата рождения', 'Дата общения', 'd.m.Y');
        if ($data) {
            foreach ($data as $user) {
                $mail = new NotificationByDatesMail($user);
                if ($mail instanceof ShouldQueue && $mail instanceof Mailable) {
                    Mail::to($this->input->getArgument('email'))->queue($mail);
                }
            }
        }
    }
}
