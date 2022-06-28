<?php

namespace App\ServiceInterfaces\Admin;

interface AdminServiceInterface {

    public function createAdminAccount($params);
    public function getAdminByAccount($params);
    public function getAdminByUsername($username);
    public function verifyAccount($id);
    public function updateAdminAccount($username, $params);
}
