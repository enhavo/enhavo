<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileEndpointType extends AbstractEndpointType
{
    use FileContentTrait;

    public function __construct(
        private readonly ?string $maxAge,
        private readonly ?string $streamingDisabled,
        private readonly ?string $streamingThreshold,
        private readonly EntityRepository $fileRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $file = $this->getFile($options, $request);

        $response = $this->createFileResponse($file, $request);
        $this->handleCaching($response);

        $context->set('file', $file);
        $context->setResponse($response);
    }

    private function getFile($options, Request $request): FileInterface
    {
        $arguments = $this->expressionLanguage->evaluateArray($options['repository_arguments'], [], true);
        $file = call_user_func_array([$this->fileRepository, $options['repository_method']], $arguments);

        if ($file === null) {
            throw $this->createNotFoundException();
        }

        if ($options['permission'] && !$this->authorizationChecker->isGranted($options['permission'], $file)) {
            throw $this->createAccessDeniedException();
        }

        return $file;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'repository_method' => 'findFileBy',
            'repository_arguments' => [
                ['token' => 'expr:request.get("token")'],
            ],
            'permission' => 'ROLE_ENHAVO_MEDIA_FILE_SHOW',
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_file';
    }
}
