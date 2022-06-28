<?php

namespace App\ServiceInterfaces\Admin;

interface BlogServiceInterface
{
  public function getAllBlogsByQueryBuilder();

  public function countTotalBlogs();

  public function countTodayBlogs();

  public function countTotalViews();

  public function countTodayViews();

  public function updateBlog ($id, $data, $lastModifiedBy, $fileName);

  public function createNewBlog($data, $createdBy, $fileName);

  public function setThumbnailByBlogId($id);

  public function getBlogById($id);

  public function deleteBlog($id);

  public function titleToSlug($title);
  
  public function searchBlog($keyword);

  public function getDeletedBlogs();

  public function restoreDeletedBlog($id);
}
