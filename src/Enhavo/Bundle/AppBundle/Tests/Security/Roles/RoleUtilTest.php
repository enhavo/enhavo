<?php

namespace Enhavo\Bundles\AppBundle\Tests\Security\Roles;

use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class RoleUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRoleNames()
    {
        $roleUtil = new RoleUtil();

        $resource = new EntityMock();

        $this->assertEquals('ROLE_ENHAVO_APP_ENTITYMOCK_CREATE', $roleUtil->getRoleName($resource, RoleUtil::ACTION_CREATE));
        $this->assertEquals('ROLE_ENHAVO_APP_ENTITYMOCK_UPDATE', $roleUtil->getRoleName($resource, RoleUtil::ACTION_UPDATE));
        $this->assertEquals('ROLE_ENHAVO_APP_ENTITYMOCK_INDEX', $roleUtil->getRoleName($resource, RoleUtil::ACTION_INDEX));
        $this->assertEquals('ROLE_ENHAVO_APP_ENTITYMOCK_DELETE', $roleUtil->getRoleName($resource, RoleUtil::ACTION_DELETE));
    }

    public  function testGetAction(){
        $roleUtil = new RoleUtil();

        $this->assertEquals(RoleUtil::ACTION_UPDATE, $roleUtil->getAction('ROLE_ENHAVO_APP_ENTITYMOCK_UPDATE'));
        $this->assertEquals(RoleUtil::ACTION_DELETE, $roleUtil->getAction('ROLE_ENHAVO_APP_ENTITYMOCK_DELETE'));
        $this->assertEquals(RoleUtil::ACTION_INDEX, $roleUtil->getAction('ROLE_ENHAVO_APP_ENTITYMOCK_INDEX'));
        $this->assertEquals(RoleUtil::ACTION_CREATE, $roleUtil->getAction('ROLE_ENHAVO_APP_ENTITYMOCK_CREATE'));
    }
}