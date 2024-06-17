<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LaporanBaru extends Mailable
{
    use Queueable, SerializesModels;

    public $laporan;

    /**
     * Create a new message instance.
     */
    public function __construct($laporan)
    {
        $this->laporan = $laporan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Laporan Baru Diterima')
                    ->markdown('emails.laporanbaru');
    }
}
