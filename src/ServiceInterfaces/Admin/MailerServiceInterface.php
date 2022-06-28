<?php

namespace App\ServiceInterfaces\Admin;


interface MailerServiceInterface
{
  public function registerConfirmation($email, $id);

  public function thankForSubscription($subscriberInfo);

  public function sendNotificationEmail($subscriberEmail, $blog);
}
