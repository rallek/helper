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

namespace RK\HelperModule\Entity;

use RK\HelperModule\Entity\Base\AbstractCarouselItemEntity as BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for carousel item entities.
 * @ORM\Entity(repositoryClass="RK\HelperModule\Entity\Repository\CarouselItemRepository")
 * @ORM\Table(name="rk_helper_carouselitem",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 * @UniqueEntity(fields="singleItemIdentifier", ignoreNull="false")
 */
class CarouselItemEntity extends BaseEntity
{
    // feel free to add your own methods here
}