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
use RK\HelperModule\Entity\Factory\EntityInitialiser;

/**
 * Factory class used to create entities and receive entity repositories.
 */
abstract class AbstractHelperFactory
{
    /**
     * @var ObjectManager The object manager to be used for determining the repository
     */
    protected $objectManager;

    /**
     * @var EntityInitialiser The entity initialiser for dynamical application of default values
     */
    protected $entityInitialiser;

    /**
     * HelperFactory constructor.
     *
     * @param ObjectManager     $objectManager     The object manager to be used for determining the repositories
     * @param EntityInitialiser $entityInitialiser The entity initialiser for dynamical application of default values
     */
    public function __construct(ObjectManager $objectManager, EntityInitialiser $entityInitialiser)
    {
        $this->objectManager = $objectManager;
        $this->entityInitialiser = $entityInitialiser;
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

        $entity = new $entityClass();

        $this->entityInitialiser->initLinker($entity);

        return $entity;
    }

    /**
     * Creates a new carouselItem instance.
     *
     * @return RK\HelperModule\Entity\carouselItemEntity The newly created entity instance
     */
    public function createCarouselItem()
    {
        $entityClass = 'RK\\HelperModule\\Entity\\CarouselItemEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initCarouselItem($entity);

        return $entity;
    }

    /**
     * Creates a new carousel instance.
     *
     * @return RK\HelperModule\Entity\carouselEntity The newly created entity instance
     */
    public function createCarousel()
    {
        $entityClass = 'RK\\HelperModule\\Entity\\CarouselEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initCarousel($entity);

        return $entity;
    }

    /**
     * Creates a new image instance.
     *
     * @return RK\HelperModule\Entity\imageEntity The newly created entity instance
     */
    public function createImage()
    {
        $entityClass = 'RK\\HelperModule\\Entity\\ImageEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initImage($entity);

        return $entity;
    }

    /**
     * Creates a new info instance.
     *
     * @return RK\HelperModule\Entity\infoEntity The newly created entity instance
     */
    public function createInfo()
    {
        $entityClass = 'RK\\HelperModule\\Entity\\InfoEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initInfo($entity);

        return $entity;
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
    

    /**
     * Returns the entity initialiser.
     *
     * @return EntityInitialiser
     */
    public function getEntityInitialiser()
    {
        return $this->entityInitialiser;
    }
    
    /**
     * Sets the entity initialiser.
     *
     * @param EntityInitialiser $entityInitialiser
     *
     * @return void
     */
    public function setEntityInitialiser($entityInitialiser)
    {
        $this->entityInitialiser = $entityInitialiser;
    }
    
}
