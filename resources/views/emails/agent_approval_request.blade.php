<x-mail::message>
# ATTENTION ADMIN
# Agent Delivery Approval Request

A Same Day On-Account delivery request (ID: **{{ $requestId }}**) has been initiated for the following agent and requires front office approval:

**Agent Details**
- **Name:** {{ $agentName }}
- **ID Number:** {{ $agentIdNumber }}
- **Phone:** {{ $agentPhone }}
- **Reason:** {{ $agentReason }}

Please login to take action:

<x-mail::button :url="route('sameday.on-account', $requestId)">
    View Request
</x-mail::button>

Thank you,<br>
{{ config('app.name') }}
</x-mail::message>


