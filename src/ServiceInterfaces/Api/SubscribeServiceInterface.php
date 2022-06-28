<?php

namespace App\ServiceInterfaces\Api;

interface SubscribeServiceInterface
{
    public function storeSubscribedEmail($data);
}