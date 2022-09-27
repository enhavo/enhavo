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
    private array $config;
    private PropertyAccessor $propertyAccessor;
    private ConfigKeyProviderInterface $configKeyProvider;

    public function __construct(array $config, ConfigKeyProviderInterface $configKeyProvider)
    {
        $this->config = $config;
        $this->propertyAccessor = new PropertyAccessor();
        $this->configKeyProvider = $configKeyProvider;
    }

    private function getConfig($key, $section)
    {
        if (!is_string($key)) {
            $key = $this->configKeyProvider->getConfigKey();
        }

        if ($key === null) {
            throw ConfigurationException::configKeyNotFound();
        }

        if (!isset($this->config[$key])) {
            throw ConfigurationException::configurationNotFound($key);
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

    public function getRegistrationRegisterConfiguration(?string $key = null): RegistrationRegisterConfiguration
    {
        $configuration = new RegistrationRegisterConfiguration;
        $config = $this->getConfig($key, 'registration_register');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getRegistrationCheckConfiguration(?string $key = null): RegistrationCheckConfiguration
    {
        $configuration = new RegistrationCheckConfiguration;
        $config = $this->getConfig($key, 'registration_check');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getRegistrationConfirmConfiguration(?string $key = null): RegistrationConfirmConfiguration
    {
        $configuration = new RegistrationConfirmConfiguration;
        $config = $this->getConfig($key, 'registration_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getRegistrationFinishConfiguration(?string $key = null): RegistrationFinishConfiguration
    {
        $configuration = new RegistrationFinishConfiguration;
        $config = $this->getConfig($key, 'registration_finish');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getProfileConfiguration(?string $key = null): ProfileConfiguration
    {
        $configuration = new ProfileConfiguration;
        $config = $this->getConfig($key, 'profile');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getResetPasswordRequestConfiguration(?string $key = null): ResetPasswordRequestConfiguration
    {
        $configuration = new ResetPasswordRequestConfiguration;
        $config = $this->getConfig($key, 'reset_password_request');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getResetPasswordCheckConfiguration(?string $key = null): ResetPasswordCheckConfiguration
    {
        $configuration = new ResetPasswordCheckConfiguration;
        $config = $this->getConfig($key, 'reset_password_check');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getResetPasswordConfirmConfiguration(?string $key = null): ResetPasswordConfirmConfiguration
    {
        $configuration = new ResetPasswordConfirmConfiguration;
        $config = $this->getConfig($key, 'reset_password_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getResetPasswordFinishConfiguration(?string $key = null): ResetPasswordFinishConfiguration
    {
        $configuration = new ResetPasswordFinishConfiguration;
        $config = $this->getConfig($key, 'reset_password_finish');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangeEmailRequestConfiguration(?string $key = null): ChangeEmailRequestConfiguration
    {
        $configuration = new ChangeEmailRequestConfiguration;
        $config = $this->getConfig($key, 'change_email_request');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangeEmailCheckConfiguration(?string $key = null): ChangeEmailCheckConfiguration
    {
        $configuration = new ChangeEmailCheckConfiguration;
        $config = $this->getConfig($key, 'change_email_check');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangeEmailConfirmConfiguration(?string $key = null): ChangeEmailConfirmConfiguration
    {
        $configuration = new ChangeEmailConfirmConfiguration;
        $config = $this->getConfig($key, 'change_email_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangeEmailFinishConfiguration(?string $key = null): ChangeEmailFinishConfiguration
    {
        $configuration = new ChangeEmailFinishConfiguration;
        $config = $this->getConfig($key, 'change_email_finish');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getLoginConfiguration(?string $key = null): LoginConfiguration
    {
        $configuration = new LoginConfiguration;
        $config = $this->getConfig($key, 'login');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getChangePasswordConfiguration(?string $key = null): ChangePasswordConfiguration
    {
        $configuration = new ChangePasswordConfiguration;
        $config = $this->getConfig($key, 'change_password');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getDeleteConfirmConfiguration(?string $key = null): DeleteConfirmConfiguration
    {
        $configuration = new DeleteConfirmConfiguration;
        $config = $this->getConfig($key, 'delete_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getDeleteFinishConfiguration(?string $key = null): DeleteFinishConfiguration
    {
        $configuration = new DeleteFinishConfiguration;
        $config = $this->getConfig($key, 'delete_finish');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getVerificationRequestConfiguration(?string $key = null): VerificationRequestConfiguration
    {
        $configuration = new VerificationRequestConfiguration;
        $config = $this->getConfig($key, 'verification_request');
        $this->autoApply($configuration, $config);
        return $configuration;
    }

    public function getVerificationConfirmConfiguration(?string $key = null): VerificationConfirmConfiguration
    {
        $configuration = new VerificationConfirmConfiguration;
        $config = $this->getConfig($key, 'verification_confirm');
        $this->autoApply($configuration, $config);
        return $configuration;
    }
}
