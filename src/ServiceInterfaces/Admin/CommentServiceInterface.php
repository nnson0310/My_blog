<?php

namespace App\ServiceInterfaces\Admin;

interface CommentServiceInterface
{
    public function getAllCommentsByQueryBuilder();
    public function countTotalComments();
    public function countTodayComments();
    public function approvalComment($id);
}