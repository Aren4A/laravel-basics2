<?php

use App\Mail\Timetable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mailable', function () {

    // All the code logic from timetableNotification commands handle() method
    $startDate = Carbon::now()->addWeek(1)->startOfWeek()->toISOString();
    $endDate = Carbon::now()->addWeek(1)->endOfWeek()->toISOString();
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

    return new Timetable($timetableEvents, $startDate, $endDate);
});
