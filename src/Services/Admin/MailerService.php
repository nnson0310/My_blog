<?php

namespace App\Services\Admin;

use App\ServiceInterfaces\Admin\MailerServiceInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerService implements MailerServiceInterface
{

  protected $mailer;

  public function __construct(MailerInterface $mailer)
  {
    $this->mailer = $mailer;
  }

  public function registerConfirmation($userEmail, $userId)
  {
    $email = (new TemplatedEmail())
      ->to($userEmail)
      ->subject('Complete Registration Confirmation')
      ->htmlTemplate('/admin/email_template/confirm_registration.html.twig')
      ->context([
        'userEmail' => $userEmail,
        'userId' => $userId
      ]);

    try {
      $this->mailer->send($email);
    } catch (TransportExceptionInterface $e) {
      return new Response(
        'Unable to send email due to SMTP server down or 500 internal server error. Please try again later.',
        Response::HTTP_INTERNAL_SERVER_ERROR,
        ['Content-type' => 'text/plain']
      );
    }
  }

  //send thank for subscription email for all subscribed user
  public function thankForSubscription($subscriberInfo)
  {
    $email = (new TemplatedEmail())
      ->to($subscriberInfo->getEmail())
      ->subject('Thanks for newsletter subscription')
      ->htmlTemplate('/admin/email_template/thank_for_subscription.html.twig')
      ->context([
        'userEmail' => $subscriberInfo->getEmail()
      ]);

    try {
      $this->mailer->send($email);
      return true;
    } catch (TransportExceptionInterface $e) {
      return new Response(
        'Unable to send email due to SMTP server down or 500 internal server error. Please try again later.',
        Response::HTTP_INTERNAL_SERVER_ERROR,
        ['Content-type' => 'text/plain']
      );
    }
  }

  //send notification email for subscribed user whenever a new article is posted
  public function sendNotificationEmail($subscriberEmails, $blog)
  {
    $email = (new TemplatedEmail())
      ->to(...$subscriberEmails) //su dung splat operator de truyen tung phan tu cua mang vao ham to()
      ->subject('A new post is ready ')
      ->htmlTemplate('/admin/email_template/new_post_notification_email.html.twig')
      ->context([
        'blogTitle' => $blog->getTitle()
      ]);

    try {
      $this->mailer->send($email);
      return true;
    } catch (TransportExceptionInterface $e) {
      return new Response(
        'Unable to send email due to SMTP server down or 500 internal server error. Please try again later.',
        Response::HTTP_INTERNAL_SERVER_ERROR,
        ['Content-type' => 'text/plain']
      );
    }
  }
}
