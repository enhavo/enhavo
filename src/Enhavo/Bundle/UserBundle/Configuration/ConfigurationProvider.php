<?php

namespace Enhavo\Bundle\UserBundle\Configuration;

use Enhavo\Bundle\UserBundle\Configuration\ChangeEmail\ChangeEmailCheckConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ChangeEmail\ChangeEmailConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ChangeEmail\ChangeEmailFinishConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ChangeEmail\ChangeEmailRequestConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ChangePassword\ChangePasswordConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Delete\DeleteConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Delete\DeleteFinishConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Profile\ProfileConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Registration\RegistrationCheckConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Registration\RegistrationConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Registration\RegistrationFinishConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Registration\RegistrationRegisterConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ResetPassword\ResetPasswordCheckConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ResetPassword\ResetPasswordConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ResetPassword\ResetPasswordFinishConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ResetPassword\ResetPasswordRequestConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Verification\VerificationConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Verification\VerificationRequestConfiguration;
use Enhavo\Bundle\UserBundle\Exception\ConfigurationException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ConfigurationProvider
{
    /** @var array */
    private $config;

    /** @var PropertyAccessor */
    private $propertyAccessor;

    /**
     * ConfigurationProvider constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->propertyAccessor = new PropertyAccessor();
    }

    private function getConfig($key, $section)
    {
        if (!isset($this->config[$key][$section])) {
            throw ConfigurationException::configurationNotFound($key, $section);
        }

        return $this->config[$key][$section];
    }

    private function autoApply($configuration, $config)
    {
        foreach ($config as $property => $value) {
            if ($this->propertyAccessor->isWritable($configuration, $property)) {
                $this->propertyAccessor->setValue($configuration, $property, $value);
            }

            if (is_array($value)) {
                foreach ($value as $childProperty => $childValue) {
                    $childPropertyPath = $property.ucfirst($childProperty);
                    if ($this->propertyAccessor->isWritable($configuration, $childPropertyPath)) {
                        $this->propertyAccessor->setValue($configuration, $childPropertyPath, $childValue);
                    }
                }
            }
        }
    }

    public function getRegistrationRegisterConfiguration($key): RegistrationRegisterConfiguration
    {
        $configuration = new RegistrationRegisterConfiguration;
        $config = $this->getConfig($key, 'registration_register');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getRegistrationCheckConfiguration($key): RegistrationCheckConfiguration
    {
        $configuration = new RegistrationCheckConfiguration;
        $config = $this->getConfig($key, 'registration_check');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getRegistrationConfirmConfiguration($key): RegistrationConfirmConfiguration
    {
        $configuration = new RegistrationConfirmConfiguration;
        $config = $this->getConfig($key, 'registration_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getRegistrationFinishConfiguration($key): RegistrationFinishConfiguration
    {
        $configuration = new RegistrationFinishConfiguration;
        $config = $this->getConfig($key, 'registration_finish');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getProfileConfiguration($key): ProfileConfiguration
    {
        $configuration = new ProfileConfiguration;
        $config = $this->getConfig($key, 'profile');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getResetPasswordRequestConfiguration($key): ResetPasswordRequestConfiguration
    {
        $configuration = new ResetPasswordRequestConfiguration;
        $config = $this->getConfig($key, 'reset_password_request');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getResetPasswordCheckConfiguration($key): ResetPasswordCheckConfiguration
    {
        $configuration = new ResetPasswordCheckConfiguration;
        $config = $this->getConfig($key, 'reset_password_check');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getResetPasswordConfirmConfiguration($key): ResetPasswordConfirmConfiguration
    {
        $configuration = new ResetPasswordConfirmConfiguration;
        $config = $this->getConfig($key, 'reset_password_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getResetPasswordFinishConfiguration($key): ResetPasswordFinishConfiguration
    {
        $configuration = new ResetPasswordFinishConfiguration;
        $config = $this->getConfig($key, 'reset_password_finish');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangeEmailRequestConfiguration($key): ChangeEmailRequestConfiguration
    {
        $configuration = new ChangeEmailRequestConfiguration;
        $config = $this->getConfig($key, 'change_email_request');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangeEmailCheckConfiguration($key): ChangeEmailCheckConfiguration
    {
        $configuration = new ChangeEmailCheckConfiguration;
        $config = $this->getConfig($key, 'change_email_check');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangeEmailConfirmConfiguration($key): ChangeEmailConfirmConfiguration
    {
        $configuration = new ChangeEmailConfirmConfiguration;
        $config = $this->getConfig($key, 'change_email_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangeEmailFinishConfiguration($key): ChangeEmailFinishConfiguration
    {
        $configuration = new ChangeEmailFinishConfiguration;
        $config = $this->getConfig($key, 'change_email_finish');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getLoginConfiguration($key): LoginConfiguration
    {
        $configuration = new LoginConfiguration;
        $config = $this->getConfig($key, 'login');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangePasswordConfiguration($key): ChangePasswordConfiguration
    {
        $configuration = new ChangePasswordConfiguration;
        $config = $this->getConfig($key, 'change_password');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getDeleteConfirmConfiguration($key): DeleteConfirmConfiguration
    {
        $configuration = new DeleteConfirmConfiguration;
        $config = $this->getConfig($key, 'delete_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getDeleteFinishConfiguration($key): DeleteFinishConfiguration
    {
        $configuration = new DeleteFinishConfiguration;
        $config = $this->getConfig($key, 'delete_finish');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getVerificationRequestConfiguration($key): VerificationRequestConfiguration
    {
        $configuration = new VerificationRequestConfiguration;
        $config = $this->getConfig($key, 'verification_request');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getVerificationConfirmConfiguration($key): VerificationConfirmConfiguration
    {
        $configuration = new VerificationConfirmConfiguration;
        $config = $this->getConfig($key, 'verification_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }
}
