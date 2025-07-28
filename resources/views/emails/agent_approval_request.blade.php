<x-mail::message>
# Agent Delivery Approval Request

A delivery request (ID: **{{ $requestId }}**) has been initiated by an agent and requires front office approval.

<x-mail::button :url="route('agent.approve', $requestId)">
    Approve Agent Pickup
</x-mail::button>

Thank you,<br>
{{ config('app.name') }}
</x-mail::message>

