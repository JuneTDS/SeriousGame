<x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="$url">
Verify Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
