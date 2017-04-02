<?php
/**
 * Helper.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\HelperModule\Entity\Factory\Base;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;

/**
 * Factory class used to create entities and receive entity repositories.
 *
 * This is the base factory class.
 */
abstract class AbstractHelperFactory
{
    /**
     * @var ObjectManager The object manager to be used for determining the repository
     */
    protected $objectManager;

    /**
     * HelperFactory constructor.
     *
     * @param ObjectManager $objectManager The object manager to be used for determining the repositories
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Returns a repository for a given object type.
     *
     * @param string $objectType Name of desired entity type
     *
     * @return EntityRepository The repository responsible for the given object type
     */
    public function getRepository($objectType)
    {
        $entityClass = 'RK\\HelperModule\\Entity\\' . ucfirst($objectType) . 'Entity';

        return $this->objectManager->getRepository($entityClass);
    }

    /**
     * Creates a new linker instance.
     *
     * @return RK\HelperModule\Entity\linkerEntity The newly created entity instance
     */
    public function createLinker()
    {
        $entityClass = 'RK\\HelperModule\\Entity\\LinkerEntity';

        return new $entityClass();
    }

    /**
     * Creates a new carouselItem instance.
     *
     * @return RK\HelperModule\Entity\carouselItemEntity The newly created entity instance
     */
    public function createCarouselItem()
    {
        $entityClass = 'RK\\HelperModule\\Entity\\CarouselItemEntity';

        return new $entityClass();
    }

    /**
     * Creates a new carousel instance.
     *
     * @return RK\HelperModule\Entity\carouselEntity The newly created entity instance
     */
    public function createCarousel()
    {
        $entityClass = 'RK\\HelperModule\\Entity\\CarouselEntity';

        return new $entityClass();
    }

    /**
     * Creates a new image instance.
     *
     * @return RK\HelperModule\Entity\imageEntity The newly created entity instance
     */
    public function createImage()
    {
        $entityClass = 'RK\\HelperModule\\Entity\\ImageEntity';

        return new $entityClass();
    }

    /**
     * Creates a new info instance.
     *
     * @return RK\HelperModule\Entity\infoEntity The newly created entity instance
     */
    public function createInfo()
    {
        $entityClass = 'RK\\HelperModule\\Entity\\InfoEntity';

        return new $entityClass();
    }

    /**
     * Gets the list of identifier fields for a given object type.
     *
     * @param string $objectType The object type to be treated
     *
     * @return array List of identifier field names
     */
    public function getIdFields($objectType = '')
    {
        if (empty($objectType)) {
            throw new InvalidArgumentException('Invalid object type received.');
        }
        $entityClass = 'RKHelperModule:' . ucfirst($objectType) . 'Entity';
    
        $meta = $this->getObjectManager()->getClassMetadata($entityClass);
    
        if ($this->hasCompositeKeys($objectType)) {
            $idFields = $meta->getIdentifierFieldNames();
        } else {
            $idFields = [$meta->getSingleIdentifierFieldName()];
        }
    
        return $idFields;
    }

    /**
     * Checks whether a certain entity type uses composite keys or not.
     *
     * @param string $objectType The object type to retrieve
     *
     * @return Boolean Whether composite keys are used or not
     */
    public function hasCompositeKeys($objectType)
    {
        return false;
    }

    /**
     * Returns the object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
    
    /**
     * Sets the object manager.
     *
     * @param ObjectManager $objectManager
     *
     * @return void
     */
    public function setObjectManager($objectManager)
    {
        $this->objectManager = $objectManager;
    }
    
}
