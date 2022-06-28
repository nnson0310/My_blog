<?php

namespace App\ServiceInterfaces\Admin;

interface TagsServiceInterface
{
    public function getAllTags();
    public function createNewTags($params);
    public function deleteTags($params);
    public function getAllDeletedTags();
    public function restoreTags($params);
}
