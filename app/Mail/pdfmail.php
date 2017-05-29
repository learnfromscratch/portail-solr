<?php

namespace App\Mail;
use App\ConcatPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class pdfmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // connect to database

        // foreach user in the database
            //  add to array1 the title
            // add to array2 the path to pdfs
        // concatenate the pdf files and the titles
        //send email to the user
        //next

        // after the boucle finishes delete the content of the table


        return $this->view('emails.pdf')->attachData($this->data, 'newsletter.pdf', [
                        'mime' => 'application/pdf',
                    ]);;
    }
}
