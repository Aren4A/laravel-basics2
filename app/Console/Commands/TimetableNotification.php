<?php

namespace App\Console\Commands;

use App\Mail\Timetable;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class TimetableNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:timetable-notification';

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
        $startDate = now()->addWeek(1)->startOfWeek()->toISOString();
        $endDate = now()->addWeek(1)->endOfWeek()->toISOString();
        $response = Http::get('https://tahvel.edu.ee/hois_back/timetableevents/timetableByGroup/38', [
            'from' => $startDate,
            'studentGroups' => '5901',
            'thru' => $endDate,
        ]);

        $timetableEvents = collect($response['timetableEvents'])
            ->sortBy(['date', 'timeStart'])
            ->groupBy(function ($event) {
                return Carbon::parse($event['date'])->locale('et_EE')->dayName;
            });
        $timetableEvents->all();
        Mail::to('ametikoolitest@gmail.com')->send(new Timetable($timetableEvents, $startDate, $endDate));

    }
}
