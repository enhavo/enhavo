## User defined modles

```yaml
enhavo_newsletter:
    newsletter:
        test_receiver:
            token: __TRACKING_TOKEN__
            parameters:
                firstname: Harry
                lastname: Hirsch
                token: __ID_TOKEN__

    subscription:
        local:
            model: Enhavo\Bundle\NewsletterBundle\Model\CustomSubscriber
            form:
                class: Enhavo\Bundle\NewsletterBundle\Form\Type\CustomLocalSubscriberType
                template: 'EnhavoNewsletterBundle:theme/resource/subscriber:custom-subscribe.html.twig'
                options:
                    groups:
                        - default
                        - local
            strategy:
                type: notify
                from: '%env(string:MAILER_FROM)%'
                admin_email: '%env(string:MAILER_TO)%'
                sender_name: '%env(string:MAILER_NAME)%'
                check_exists: true
            storage:
                type: local
                groups:
                    - default
        cleverreach:
            model: Enhavo\Bundle\NewsletterBundle\Model\CustomSubscriber
            form:
                class: Enhavo\Bundle\NewsletterBundle\Form\Type\CustomSubscriberType
                template: 'EnhavoNewsletterBundle:theme/resource/subscriber:custom-subscribe.html.twig'
                options:
                    groups:
                        - 1408629
            strategy:
                type: notify
                from: '%env(string:MAILER_FROM)%'
                admin_email: '%env(string:MAILER_TO)%'
                sender_name: '%env(string:MAILER_NAME)%'
                check_exists: true
            storage:
                type: cleverreach
                client_secret: '%env(string:CLEVERREACH_CLIENT_SECRET)%'
                client_id: '%env(string:CLEVERREACH_CLIENT_ID)%'
                global_attributes:
                    firstname: firstName
                    lastname: lastName
                groups:
                    - 1408629
        mailchimp:
            model: Enhavo\Bundle\NewsletterBundle\Model\CustomSubscriber
            form:
                class: Enhavo\Bundle\NewsletterBundle\Form\Type\CustomSubscriberType
                template: 'EnhavoNewsletterBundle:theme/resource/subscriber:custom-subscribe.html.twig'
            strategy:
                type: notify
                from: '%env(string:MAILER_FROM)%'
                admin_email: '%env(string:MAILER_TO)%'
                sender_name: '%env(string:MAILER_NAME)%'
            storage:
                type: mailchimp
                url: '%env(string:MAILCHIMP_URL)%'
                api_key: '%env(string:MAILCHIMP_API_KEY)%'
                body_parameters:
                    merge_fields:
                        FNAME: firstName
                        LNAME: lastName
                groups:
                    - c78b0465aa

    resources:
        local_subscriber:
            classes:
                factory: Enhavo\Bundle\NewsletterBundle\Factory\CustomLocalSubscriberFactory
                model: Enhavo\Bundle\NewsletterBundle\Entity\CustomLocalSubscriber
                form: Enhavo\Bundle\NewsletterBundle\Form\Type\CustomLocalSubscriberType

services:
    enhavo_newsletter.factory.local_subscriber:
        class: Enhavo\Bundle\NewsletterBundle\Factory\CustomLocalSubscriberFactory
        arguments:
            - '%enhavo_newsletter.model.local_subscriber.class%'
            - '@enhavo_newsletter.repository.group'


enhavo_doctrine_extension:
    metadata:
        Enhavo\Bundle\NewsletterBundle\Entity\CustomLocalSubscriber:
            extends: Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber
            discrName: 'app'
```

```php
<?php

namespace App\Form\Type;

use Enhavo\Bundle\NewsletterBundle\Validator\Constraints\SubscriberExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomSubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class);
        $builder->add('lastName', TextType::class);
        $builder->add('groups', CleverReachGroupType::class, [
            'groups' => $options['groups'],
            'subscription' => $options['subscription'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'constraints' => [
                new SubscriberExists()
            ],
        ]);
    }

    public function getParent()
    {
        return SubscriberType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_custom_subscriber';
    }
}
```

```php
<?php

namespace App\Form\Type;

use Enhavo\Bundle\NewsletterBundle\Entity\CustomLocalSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomLocalSubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName');
        $builder->add('lastName');
        $builder->add('groups', LocalGroupType::class, [
            'groups' => $options['groups'],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CustomLocalSubscriber::class,
            'translation_domain' => 'EnhavoNewsletterBundle',
        ));
    }

    public function getParent()
    {
        return SubscriberType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_local_subscriber';
    }
}
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping">
    <entity name="App\Entity\CustomLocalSubscriber"
            repository-class="Enhavo\Bundle\NewsletterBundle\Repository\LocalSubscriberRepository">

        <field name="lastName" nullable="true" />
        <field name="firstName" nullable="true" />

    </entity>
</doctrine-mapping>
```

```php
<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CustomSubscriber implements SubscriberInterface, GroupAwareInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $subscription;

    /**
     * @var string|null
     */
    private $confirmationToken;

    /** @var string|null */
    private $firstName;

    /** @var string|null */
    private $lastName;

    /**
     * @var GroupInterface[]
     */
    private $groups = [];

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string|null
     */
    public function getSubscription(): ?string
    {
        return $this->subscription;
    }

    /**
     * @param string|null $subscription
     */
    public function setSubscription(?string $subscription): void
    {
        $this->subscription = $subscription;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string|null $token
     */
    public function setConfirmationToken(?string $token): void
    {
        $this->confirmationToken = $token;
    }

    public function __toString()
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return GroupInterface[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param GroupInterface $group
     */
    public function addGroup(GroupInterface $group)
    {
        $this->groups[] = $group;
    }

    /**
     * @param GroupInterface $group
     */
    public function removeGroup(GroupInterface $group)
    {
        if (false !== $key = array_search($group, $this->groups, true)) {
            array_splice($this->groups, $key, 1);
        }
    }
}
```

```php
<?php

namespace App\Entity;

class CustomLocalSubscriber extends LocalSubscriber
{
    /** @var string|null */
    private $firstName;
    /** @var string|null */
    private $lastName;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
```

```php
<?php

namespace App\Factory;

use App\CustomLocalSubscriber;
use App\CustomSubscriber;
use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;

class CustomLocalSubscriberFactory extends LocalSubscriberFactory
{

    public function createFrom(SubscriberInterface $subscriber): LocalSubscriberInterface
    {
        /** @var CustomSubscriber $subscriber */
        /** @var CustomLocalSubscriber $local */
        $local = $this->createNew();
        $local->setCreatedAt(new \DateTime());
        $local->setEmail($subscriber->getEmail());
        $local->setSubscription($subscriber->getSubscription());
        $local->setFirstName($subscriber->getFirstName());
        $local->setLastName($subscriber->getLastName());

        return $local;
    }

}
```