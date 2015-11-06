<?php
namespace Enhavo\Bundle\NewsletterBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\NewsletterBundle\Entity\Subscriber;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SubscriberEmailInUseValidator extends ConstraintValidator
{

    protected $em;
    protected $container;
    private $subscriber;

    public function __construct(EntityManager $entityManager,Container $container, $subscriber)
    {
        $this->em = $entityManager;
        $this->container = $container;
        $this->subscriber = $subscriber;
    }

    public function validate($value, Constraint $constraint)
    {
        $em = $this->em;
        $subscriberRepository = $em->getRepository('EnhavoNewsletterBundle:Subscriber');
        $subscriber = $subscriberRepository->findOneBy(array('email' => $value));
        if($subscriber != null) {
            if($subscriber->getActive() == true) {
                $this->context->buildViolation($constraint->messageEmailInUse)
                    ->addViolation();
            } else {
                $email = $subscriber->getEmail();
                $code = $subscriber->getToken();

                $router = $this->container->get('router');
                $link = $router->generate('enhavo_newsletter_subscriber_activation', array('code' => $code), true);
                $text = $this->container->get('templating')->render($this->subscriber['template'], array(
                    "link" => $link
                ));

                $message = \Swift_Message::newInstance()
                    ->setSubject($this->subscriber['subject'])
                    ->setFrom($this->subscriber['send_from'])
                    ->setTo($email)
                    ->setBody($text);

                $this->container->get('mailer')->send($message);
                $this->context->buildViolation($constraint->messageMailAgain)
                    ->addViolation();
            }
        }
    }
}