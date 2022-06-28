<?php

namespace App\ServiceInterfaces\Api;

interface CommentServiceInterface
{
    public function storeComment($data);

    public function getCommentsByPost($id);
}