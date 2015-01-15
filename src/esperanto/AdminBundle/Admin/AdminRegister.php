<?php

namespace esperanto\AdminBundle\Admin;


class AdminRegister
{
    protected $admins = array();

    public function registerAdmin(Admin $admin)
    {
        $this->admins[] = $admin;
    }

    public function getAdmins()
    {
        return $this->admins;
    }
}