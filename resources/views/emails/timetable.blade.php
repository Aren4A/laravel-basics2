<x-mail::message>
    @component('mail::message')
    # Timetable Schedule
    
    Here is the schedule for the period from {{ $startDate }} to {{ $endDate }}.
    
    @foreach ($timetableEvents as $dayName => $events)
    ## {{ ucfirst($dayName) }}
    @foreach ($events as $event)
    - {{ $event['nameEt'] }} at {{ $event['timeStart'] ?? 'Time' }} {{ $event['teachers'][0]['name']}}
    @endforeach
    @endforeach
    
    Thanks,
    {{ config('app.name') }}
    @endcomponent
</x-mail::message>