<?php
/**
 * Admin.php
 *
 * @since 30/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Admin;

interface Admin
{
    const GRANTED_ACTION_EDIT = 'EDIT';
    const GRANTED_ACTION_CREATE = 'CREATE';
    const GRANTED_ACTION_INDEX = 'INDEX';
    const GRANTED_ACTION_DELETE = 'DELETE';

    public function getRouteCollection();
    public function createView();
    public function init();
    public function isActionGranted($action);
    public function getMenu();
}