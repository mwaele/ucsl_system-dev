<x-mail::message>
# ATTENTION ADMIN
# Agent Delivery Approval Request

A delivery request (ID: **{{ $requestId }}**) has been initiated for the following agent and requires front office approval:

**Agent Details**
- **Name:** {{ $agentName }}
- **ID Number:** {{ $agentId }}
- **Phone:** {{ $agentPhone }}
- **Reason:** {{ $agentReason }}

Please login to take action:

<x-mail::button :url="$approvalUrl">
    View Request
</x-mail::button>

Thank you,<br>
{{ config('app.name') }}
</x-mail::message>
