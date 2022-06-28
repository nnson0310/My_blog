<?php

namespace App\ServiceInterfaces\Api;

interface BlogServiceInterface
{
  public function getLatestBlogs();

  public function getLatestBlogInfo();

  public function getBlogDetails($id);

  public function getAllTagPosts($tagId);

  public function getAllCategoryPosts($catId);

  public function getAllBlogs();

  public function search($keyword);

  public function getFeaturePosts();
}
