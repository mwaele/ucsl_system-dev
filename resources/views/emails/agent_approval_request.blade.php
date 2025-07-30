<x-mail::message>
# Agent Delivery Approval Request

A delivery request (ID: **{{ $requestId }}**) has been initiated for the following agent and requires front office approval:

**Agent Details**
- **Name:** {{ $agentName }}
- **ID Number:** {{ $agentIdNumber }}
- **Phone:** {{ $agentPhone }}

Please login to take action:

<x-mail::button :url="route('sameday.on-account', $requestId)">
    View Request
</x-mail::button>

Thank you,<br>
{{ config('app.name') }}
</x-mail::message>


