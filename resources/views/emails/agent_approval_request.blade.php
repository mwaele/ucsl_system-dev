<x-mail::message>
# Agent Delivery Approval Request

A delivery request (ID: **{{ $requestId }}**) has been initiated by the following agent and requires front office approval:

**Agent Details**
- **Name:** {{ $agentName }}
- **ID Number:** {{ $agentIdNumber }}
- **Phone:** {{ $agentPhone }}

<x-mail::button :url="route('agent.approve', $requestId)">
    Approve Agent Pickup
</x-mail::button>

Thank you,<br>
{{ config('app.name') }}
</x-mail::message>


