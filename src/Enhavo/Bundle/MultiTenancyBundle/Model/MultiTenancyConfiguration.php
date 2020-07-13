<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.07.18
 * Time: 23:51
 */

namespace Bundle\MultiTenancyBundle\Model;

class MultiTenancyConfiguration
{
    /**
     * @var string;
     */
    private $name;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string[]
     */
    private $domains;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string;
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $values = [];

    /**
     * @return string[]
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @param string[] $domains
     */
    public function setDomains($domains)
    {
        $this->domains = $domains;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param string $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }
}
