<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Mail;

class EmailJobService implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $mailVO;
    
    protected $tipoEnvio;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Pojo\MailVO $mailVO,$tipoEnvio) {
        //
        $this->mailVO = $mailVO;
        $this->tipoEnvio = $tipoEnvio;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        Log::error('Init Handle');
        switch ($this->tipoEnvio) {
            case "docEmision":
                $this->sendEmisor();
                break;
            case "docEmisionRec":
                $this->sendReceptor();
                break;
        }
    }

    private function sendEmisor() {
        Log::error('sendEmisor ' . $this->mailVO->urlTemplate);
        Log::error('sendEmisor Correo To ' . $this->mailVO->to);
        Log::error('sendEmisor Correo from ' . $this->mailVO->from);
        $mailVO = $this->mailVO;
        Mail::send($this->mailVO->urlTemplate, $this->mailVO->parametro, function ($message) use($mailVO) {
            $message->from($mailVO->from);
            $message->to($mailVO->to)
                    ->subject($mailVO->subject);
        });
    }

    private function sendReceptor() {
        Log::error('sendReceptor ' . $this->mailVO->urlTemplate);
        Log::error('sendReceptor Correo To ' . $this->mailVO->to);
        Log::error('sendReceptor Correo from ' . $this->mailVO->from);
        $mailVO = $this->mailVO;
        Mail::send($this->mailVO->urlTemplate, $this->mailVO->parametro, function ($message) use($mailVO) {
            $message->from($mailVO->from);
            $message->to($mailVO->to);
            $message->subject($mailVO->subject);
            $size = sizeOf($mailVO->attachs);
            for ($i = 0; $i < $size; $i++) {
                $message->attach($mailVO->attachs[$i]);
            }
        });
        $this->borrarArchivos();
    }

    private function borrarArchivos() {
        $size = sizeOf($this->mailVO->attachs);
        for ($i = 0; $i < $size; $i++) {
            unlink($this->mailVO->attachs[$i]);
        }
    }

}
