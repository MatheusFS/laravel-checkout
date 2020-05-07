<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

use DateTime;

class Shipping {

    protected $name;
    protected $address;
    public $fee;
    protected $delivery_date;
    protected $expedited;


    public function __construct(string $name, Address $address, string $fee, DateTime $delivery_date, bool $expedited = false){

        $this->name = $name;
        $this->address = $address->toArray();
        $this->fee = floatval($fee) * 100;
        $this->delivery_date = $delivery_date->format('Y-m-d');
        $this->expedited = $expedited;
    }

    public function toArray(){return get_object_vars($this);}
}