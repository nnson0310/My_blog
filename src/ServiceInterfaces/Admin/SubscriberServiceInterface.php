<?php

namespace App\ServiceInterfaces\Admin;

interface SubscriberServiceInterface
{
   public function getAllSubscriberByQueryBuilder();
   public function countTotalSubscribers();
   public function countTodaySubscribers();
   public function getSubscriberById($id);
   public function updateSubscribedStatus($id);
}
