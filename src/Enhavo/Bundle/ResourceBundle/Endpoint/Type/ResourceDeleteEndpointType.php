<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\Input\Input;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\Security\CsrfChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ResourceDeleteEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly ResourceManager $resourceManager,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        private readonly CsrfChecker $csrfChecker,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);
        $resource = $input->getResource();

        $this->denyAccessUnlessGranted(new Permission($input->getResourceName(), $options['permission']), $resource);

        if ($resource === null) {
            throw $this->createNotFoundException();
        }

        if ($this->csrfChecker->isEnabled() && !$this->csrfTokenManager->isTokenValid(new CsrfToken('resource_delete', $request->getPayload()->get('token')))) {
            $context->setStatusCode(400);
            $data['success'] = false;
            $data['message'] = 'Invalid token';
            return;
        }

        $this->resourceManager->delete($resource);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'permission' => Permission::DELETE
        ]);

        $resolver->setRequired('input');
    }

    public static function getName(): ?string
    {
        return 'resource_delete';
    }
}
