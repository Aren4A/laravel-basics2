<x-mail::message>
<div>
    Tabel: {{ $timetableEvents }} {{ $startDate }} {{ $endDate }}
</div>
 
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>