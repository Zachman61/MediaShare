<?php


namespace App;


use Laravel\Passport\Client;

class PassportClient extends Client
{
    public function skipsAuthorization()
    {
        return true;
    }
}
