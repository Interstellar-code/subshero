<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefaultMail extends Mailable
{
    private $data = [
        'view' => 'mail/load',
        'html' => null,
    ];

    /**
     * The subject of the message.
     *
     * @var string
     */
    public $subject;


    use Queueable, SerializesModels;

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Mail\Events\MessageSending' => [
            'App\Listeners\LogSendingMessage',
        ],
        'Illuminate\Mail\Events\MessageSent' => [
            'App\Listeners\LogSentMessage',
        ],
    ];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        if (isset($data['view'])) {
            $this->data['view'] = $data['view'];
        }

        if (isset($data['html'])) {
            $this->data['html'] = $data['html'];
        }

        if (isset($data['subject'])) {
            $this->subject = $data['subject'];
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->data['view'], [
            'html' => $this->data['html'],
        ]);
    }
}
