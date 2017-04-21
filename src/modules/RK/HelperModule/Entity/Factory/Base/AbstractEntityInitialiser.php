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

use RK\HelperModule\Entity\LinkerEntity;
use RK\HelperModule\Entity\CarouselItemEntity;
use RK\HelperModule\Entity\CarouselEntity;
use RK\HelperModule\Entity\ImageEntity;
use RK\HelperModule\Entity\InfoEntity;

/**
 * Entity initialiser class used to dynamically apply default values to newly created entities.
 */
abstract class AbstractEntityInitialiser
{
    /**
     * Initialises a given linker instance.
     *
     * @param LinkerEntity $entity The newly created entity instance
     *
     * @return LinkerEntity The updated entity instance
     */
    public function initLinker(LinkerEntity $entity)
    {
        return $entity;
    }

    /**
     * Initialises a given carouselItem instance.
     *
     * @param CarouselItemEntity $entity The newly created entity instance
     *
     * @return CarouselItemEntity The updated entity instance
     */
    public function initCarouselItem(CarouselItemEntity $entity)
    {
        return $entity;
    }

    /**
     * Initialises a given carousel instance.
     *
     * @param CarouselEntity $entity The newly created entity instance
     *
     * @return CarouselEntity The updated entity instance
     */
    public function initCarousel(CarouselEntity $entity)
    {
        return $entity;
    }

    /**
     * Initialises a given image instance.
     *
     * @param ImageEntity $entity The newly created entity instance
     *
     * @return ImageEntity The updated entity instance
     */
    public function initImage(ImageEntity $entity)
    {
        return $entity;
    }

    /**
     * Initialises a given info instance.
     *
     * @param InfoEntity $entity The newly created entity instance
     *
     * @return InfoEntity The updated entity instance
     */
    public function initInfo(InfoEntity $entity)
    {
        return $entity;
    }

}