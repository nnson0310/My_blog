<?php

namespace App\ServiceInterfaces\Admin;

interface CategoryServiceInterface
{
  public function createNewCategory($params);

  public function getAllCategory();

  public function getAllCategoryByQueryBuilder();

  public function deleteCategoryById($cat_id);

  public function updateCategory($params);

  public function getAllDeletedCategory();

  public function restoreCategory($params);

}
