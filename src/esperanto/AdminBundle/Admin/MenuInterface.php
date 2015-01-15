<?php

namespace esperanto\AdminBundle\Admin;


interface MenuInterface
{
    public function getRouteName();
    public function getIconName();
    public function getName();
} 