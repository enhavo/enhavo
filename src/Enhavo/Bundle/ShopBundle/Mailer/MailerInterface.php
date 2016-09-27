<?php
/**
 * MailerInterface.php
 *
 * @since 27/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Mailer;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

interface MailerInterface
{
    public function sendMail(OrderInterface $order);
}