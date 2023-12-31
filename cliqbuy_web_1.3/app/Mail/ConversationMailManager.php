<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConversationMailManager extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $array;

    public function __construct($array)
    {
        $this->array = $array;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.conversation')
                    ->from($this->array['from'], get_setting('mail_from_name'))
                    ->subject($this->array['subject'])
                    ->with([
                        'content' => $this->array['content'],
                        'link' => $this->array['link'],
                        'product_name' => $this->array['product_name'],
                        'product_link' => $this->array['product_link'],
                        'sender' => $this->array['sender'],
                        'details' => $this->array['details']
                    ]);
    }
}
