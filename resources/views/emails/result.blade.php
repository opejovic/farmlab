@component('mail::message')
# Hello, {{ $vet->name }}

New result for the farmer {{ $labresult->farmer_name }} has just been uploaded.

Check it out.

@component('mail::button', ['url' => "http://127.0.0.1:8000/labresults/{$labresult->id}"])
    Labresult number {{ $labresult->id }}
@endcomponent

@component('mail::panel', ['url' => ''])
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
