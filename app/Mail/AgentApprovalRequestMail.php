<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgentApprovalRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $requestId;
    public $agentName;
    public $agentIdNumber;
    public $agentPhone;

    public function __construct($requestId, $agentName, $agentIdNumber, $agentPhone)
    {
        $this->requestId = $requestId;
        $this->agentName = $agentName;
        $this->agentIdNumber = $agentIdNumber;
        $this->agentPhone = $agentPhone;
    }

    public function build()
    {
        return $this->subject('Agent Approval Request')
            ->view('emails.agent_approval_request')
            ->with([
                'requestId' => $this->requestId,
                'agentName' => $this->agentName,
                'agentIdNumber' => $this->agentIdNumber,
                'agentPhone' => $this->agentPhone,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Agent Approval Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.agent_approval_request',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
