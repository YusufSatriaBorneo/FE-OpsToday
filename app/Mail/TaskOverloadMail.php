<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskOverloadMail extends Mailable
{
    use Queueable, SerializesModels;
    public $overloadedEngineers;

    /**
     * Create a new message instance.
     */
    public function __construct($overloadedEngineers)
    {
        //
        $this->overloadedEngineers = $overloadedEngineers;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Alert] Ops Today: Tickets Exceeding 10',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('[Alert] Ops Today: Tickets Exceeding 10')
                    ->view('emails.taskOverload')
                    ->with([
                        'overloadedEngineers' => $this->overloadedEngineers,
                    ]);
    }
}
