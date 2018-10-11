<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 20:52
 */

namespace Enhavo\Bundle\MediaBundle\Provider;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Repository\FileRepository;

class DefaultProvider implements ProviderInterface
{
    /**
     * @var TokenGeneratorInterface;
     */
    private $tokenGenerator;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * DefaultProvider constructor.
     *
     * @param TokenGeneratorInterface $tokenGenerator
     * @param FileRepository $fileRepository
     */
    public function __construct(TokenGeneratorInterface $tokenGenerator, FileRepository $fileRepository)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->fileRepository = $fileRepository;
    }

    public function updateFile(FileInterface $file)
    {
        if($file->getToken() === null) {
            $file->setToken($this->generateToken());
        }
        $this->provideChecksum($file);
    }

    public function updateFormat(FormatInterface $format)
    {
        // do nothing here
    }

    public function supportsClass($object)
    {
        if($object instanceof FileInterface) {
            return true;
        }

        if($object instanceof FormatInterface) {
            return true;
        }

        return true;
    }

    private function generateToken()
    {
        do {
            $token = $this->tokenGenerator->generateToken(10);
            $file = $this->fileRepository->findOneBy([
                'token' => $token
            ]);
        } while($file !== null); //make sure token is unique
        return $token;
    }

    private function provideChecksum(FileInterface $file)
    {
        $content = $file->getContent()->getContent();
        $file->setMd5Checksum(md5($content));
    }
}