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

interface ProviderInterface
{
    /**
     * @param array $criteria
     * @return FileInterface
     */
    public function findOneBy(array $criteria);

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return FileInterface[]
     */
    public function findBy(array $criteria = [], array $orderBy = [], $limit = null, $offset = null);

    /**
     * @param $id
     * @return FileInterface
     */
    public function find($id);

    /**
     * @param FileInterface $file
     * @return void
     */
    public function save(FileInterface $file);

    /**
     * @param FileInterface $file
     * @return void
     */
    public function delete(FileInterface $file);

    /**
     * @return FileInterface[]
     */
    public function collectGarbage();

    /**
     * @param FileInterface $file
     * @param $format
     * @return FormatInterface
     */
    public function findFormat(FileInterface $file, $format);

    /**
     * @param FileInterface $file
     * @return FormatInterface[]
     */
    public function findAllFormats(FileInterface $file);

    /**
     * @param FormatInterface $format
     * @return void
     */
    public function saveFormat(FormatInterface $format);

    /**
     * @param FormatInterface $format
     * @return void
     */
    public function deleteFormat(FormatInterface $format);
}