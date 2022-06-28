<?php

namespace App\ServiceInterfaces\Admin;

interface TopicServiceInterface
{
  public function getAllTopic();
  public function getAllDeletedTopic();
  public function createNewTopic($params);
  public function deleteTopic($params);
  public function updateTopic($params);
}
