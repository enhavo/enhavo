<?php
/**
 * ConfigurationFactory.php
 *
 * @since 13/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContactBundle\Configuration;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ConfigurationFactory
{
    use ContainerAwareTrait;

    /**
     * Create configuration
     *
     * @param $name
     * @return Configuration
     */
    public function create($name)
    {
        $this->checkName($name);

        $configuration = new Configuration();

        $configuration->setName($name);

        $configuration->setModel($this->getParameter($name, 'model'));
        $configuration->setForm($this->getParameter($name, 'form'));
        $configuration->setFormTemplate($this->getParameter($name, 'template.form'));
        $configuration->setRecipientTemplate($this->getParameter($name, 'template.recipient'));
        $configuration->setConfirmTemplate($this->getParameter($name, 'template.confirm'));
        $configuration->setFrom($this->getParameter($name, 'from'));
        $configuration->setSenderName($this->getParameter($name, 'sender_name'));
        $configuration->setSubject($this->getParameter($name, 'subject'));
        $configuration->setTranslationDomain($this->getParameter($name, 'translation_domain'));
        $configuration->setConfirmMail($this->getParameter($name, 'confirm_mail'));
        $configuration->setFormName($this->resolveFormName($name));

        return $configuration;
    }

    private function getParameter($name, $key)
    {
        $parameterName = sprintf('enhavo_contact.%s.%s', $name, $key);
        return $this->container->getParameter($parameterName);
    }

    private function checkName($name)
    {
        $forms = $this->container->getParameter('enhavo_contact.forms');
        if(!is_array($forms)) {
            throw new \Exception(sprintf(
                'No forms found in configuration under enhavo_contact.forms'
            ));
        }

        $names = array_keys($forms);
        if(!in_array($name, $names)) {
            throw new \Exception(sprintf(
                'Could not find a form for name "%s". Possible values are [%s]. Please check your configuration under enhavo_contact.forms',
                $name,
                implode(',', $names)
            ));
        }
    }

    private function resolveFormName($name)
    {
        $serviceFormName = sprintf('enhavo_contact_%s', $name);

        if($this->container->has($serviceFormName)) {
            return $serviceFormName;
        }

        return $this->getParameter($name, 'form');
    }
}