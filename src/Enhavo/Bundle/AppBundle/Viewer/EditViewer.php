<?php
/**
 * EditViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;


use Symfony\Component\Routing\Exception\RouteNotFoundException;

class EditViewer extends CreateViewer
{
    /**
     * Criteria to identify resource
     *
     * @var string
     */
    private $identifier = 'id';

    /**
     * Criteria to identify resource
     *
     * @var string
     */
    private $identifierProperty = 'id';

    public function getDefaultConfig()
    {
        $config =  array(
            'buttons' => array(
                'cancel' => array(
                    'type' => 'cancel',
                ),
                'save' => array(
                    'type' => 'save',
                ),
            ),
            'form' => array(
                'template' => 'EnhavoAppBundle:View:tab.html.twig',
                'theme' => '',
                'action' => sprintf('%s_%s_update', $this->getBundlePrefix(), $this->getResourceName()),
                'delete' => sprintf('%s_%s_delete', $this->getBundlePrefix(), $this->getResourceName())
            )
        );

        $securityContext = $this->getContainer()->get('security.context');
        $route = sprintf('%s_%s_delete', $this->getBundlePrefix(), $this->getResourceName());
        if($securityContext->isGranted('ROLE_'.strtoupper($route))) {
            $config['buttons']['delete'] = array(
                'type' => 'delete',
            );
        }

        return $config;
    }

    public function getFormDelete()
    {
        $route = $this->getConfig()->get('form.delete');
        $securityContext = $this->container->get('security.context');
        try {
            if($securityContext->isGranted('ROLE_'.strtoupper($route))) {
                return $this->container->get('router')->generate($route, array(
                    'id' => $this->getResource()->getId()
                ));
            }
        } catch(RouteNotFoundException $exception) {
            return null;
        }
        return null;
    }

    public function getFormAction()
    {
        $route = $this->getConfig()->get('form.action');
        return $this->container->get('router')->generate($route, array(
            $this->getIdentifier() => $this->getProperty($this->getResource(), $this->getIdentifierProperty())
        ));
    }

    public function getParameters()
    {
        $parameters = array(
            'buttons' => $this->getButtons(),
            'form' => $this->getForm(),
            'viewer' => $this,
            'tabs' => $this->getTabs(),
            'data' => $this->getResource(),
            'form_template' => $this->getFormTemplate(),
            'form_action' => $this->getFormAction(),
            'form_delete' => $this->getFormDelete(),
            'translationDomain' => $this->getTranslationDomain()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifierProperty()
    {
        return $this->identifierProperty;
    }

    /**
     * @param string $identifierProperty
     */
    public function setIdentifierProperty($identifierProperty)
    {
        $this->identifierProperty = $identifierProperty;
    }
}