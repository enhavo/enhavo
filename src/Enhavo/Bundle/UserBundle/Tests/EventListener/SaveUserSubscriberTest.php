<?php
/**
 * @author blutze-media
 * @since 2020-11-05
 */

namespace Enhavo\Bundle\UserBundle\Tests\EventListener;


use Enhavo\Bundle\UserBundle\EventListener\SaveUserSubscriber;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\GenericEvent;

class SaveUserSubscriberTest extends TestCase
{
    private function createDependencies(): SaveUserSubscriberTestDependencies
    {
        $dependencies = new SaveUserSubscriberTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance(SaveUserSubscriberTestDependencies $dependencies): SaveUserSubscriber
    {
        return new SaveUserSubscriber(
            $dependencies->userManager
        );
    }

    public function testGetSubscribedEvents()
    {
        $events = SaveUserSubscriber::getSubscribedEvents();

        $this->assertEquals([
            'enhavo_user.user.pre_update' => ['onSave', 1],
            'enhavo_user.user.pre_create' => ['onSave', 1],
        ], $events);
    }

    public function testOnSave()
    {
        $dependencies = $this->createDependencies();

        $user = new UserMock();
        $user->setEmail('user@enhavo.com');
        $event = new GenericEvent($user);

        $subscriber = $this->createInstance($dependencies);
        $dependencies->userManager->expects($this->once())->method('updatePassword');
        $dependencies->userManager->expects($this->once())->method('updateUsername');

        $subscriber->onSave($event);
    }
}

class SaveUserSubscriberTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;
}
