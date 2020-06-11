<?php

namespace MatheusFS\LaravelCheckout\Mail\Postback;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MatheusFS\LaravelCheckout\PagarMe\Status;

class Supplier extends Mailable {

    use Queueable, SerializesModels;

    const FROM = 'contato@refreshertrends.com.br';
    public $data;
    public $status = Status::class;
    public $name;
    public $delivery_days;

    public function __construct($data) {

        $this->data = $data;
        $this->name = explode(' ', $data['customer']['name'])[0] ?? 'Cliente';

        $delivery_date = new DateTime($data['shipping']['delivery_date']);
        $this->delivery_days = (new DateTime())->diff($delivery_date)->d;
    }

    public function build() {

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        
        return $this->subject("Venda realizada!"); //: " . $this->data['items'][0]['title']
        ->from(Supplier::FROM, 'REFRESHER Marketplace')
        ->markdown('checkout::mail.postback.supplier');
    }
}
