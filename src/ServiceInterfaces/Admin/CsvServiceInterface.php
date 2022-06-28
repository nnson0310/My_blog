<?php

namespace App\ServiceInterfaces\Admin;

interface CsvServiceInterface
{
  public function createCategoryFromCsv($params);
  public function createTopicFromCsv($params);
  public function createTagsFromCsv($params);
}
