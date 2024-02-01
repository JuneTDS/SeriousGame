<x-mail::message>
# Thank you for registering with us. 

Please verify your email by clicking on the below button.

<x-mail::button :url="$url">
Verify Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
