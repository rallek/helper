<?php
/**
 * Helper.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace RK\HelperModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;
use RK\HelperModule\Traits\StandardFieldsTrait;
use RK\HelperModule\Validator\Constraints as HelperAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for linker entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractLinkerEntity extends EntityAccess
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'linker';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=1000000000)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @HelperAssert\ListEntry(entityName="linker", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * Linker image meta data array.
     *
     * @ORM\Column(type="array")
     * @Assert\Type(type="array")
     * @var array $linkerImageMeta
     */
    protected $linkerImageMeta = [];
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @Assert\File(
     *    mimeTypes = {"image/*"}
     * )
     * @Assert\Image(
     *    allowSquare = false,
     *    allowPortrait = false
     * )
     * @var string $linkerImage
     */
    protected $linkerImage = null;
    
    /**
     * Full linker image path as url.
     *
     * @Assert\Type(type="string")
     * @var string $linkerImageUrl
     */
    protected $linkerImageUrl = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $linkerHeadline
     */
    protected $linkerHeadline = '';
    
    /**
     * @ORM\Column(type="text", length=2000)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="2000")
     * @var text $linkerText
     */
    protected $linkerText = '';
    
    /**
     * You must be carefull with the link settings. It is not validated!
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @var string $theLink
     */
    protected $theLink = '';
    
    /**
     * see the definitions at the bootstrap documentation
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $boostrapSetting
     */
    protected $boostrapSetting = 'col-xs-12 col-sm-6 col-md-3';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Regex(pattern="/\s/", match=false, message="This value must not contain space chars.")
     * @Assert\Length(min="0", max="255")
     * @Assert\Locale()
     * @var string $linkerLocale
     */
    protected $linkerLocale = '';
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $sorting
     */
    protected $sorting = 0;
    
    /**
     * a field to be used for block filtering. We may want to filter the same string here. Please do not use spaces an scpecial characters.
     * @ORM\Column(length=255, nullable=true)
     * @Assert\Regex(pattern="/\s/", match=false, message="This value must not contain space chars.")
     * @Assert\Length(min="0", max="255")
     * @var string $linkerGroup
     */
    protected $linkerGroup = '';
    
    
    
    /**
     * LinkerEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        if ($this->_objectType != $_objectType) {
            $this->_objectType = $_objectType;
        }
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = isset($workflowState) ? $workflowState : '';
        }
    }
    
    /**
     * Returns the linker image.
     *
     * @return string
     */
    public function getLinkerImage()
    {
        return $this->linkerImage;
    }
    
    /**
     * Sets the linker image.
     *
     * @param string $linkerImage
     *
     * @return void
     */
    public function setLinkerImage($linkerImage)
    {
        if ($this->linkerImage !== $linkerImage) {
            $this->linkerImage = isset($linkerImage) ? $linkerImage : '';
        }
    }
    
    /**
     * Returns the linker image url.
     *
     * @return string
     */
    public function getLinkerImageUrl()
    {
        return $this->linkerImageUrl;
    }
    
    /**
     * Sets the linker image url.
     *
     * @param string $linkerImageUrl
     *
     * @return void
     */
    public function setLinkerImageUrl($linkerImageUrl)
    {
        if ($this->linkerImageUrl !== $linkerImageUrl) {
            $this->linkerImageUrl = isset($linkerImageUrl) ? $linkerImageUrl : '';
        }
    }
    
    /**
     * Returns the linker image meta.
     *
     * @return array
     */
    public function getLinkerImageMeta()
    {
        return $this->linkerImageMeta;
    }
    
    /**
     * Sets the linker image meta.
     *
     * @param array $linkerImageMeta
     *
     * @return void
     */
    public function setLinkerImageMeta($linkerImageMeta = [])
    {
        if ($this->linkerImageMeta !== $linkerImageMeta) {
            $this->linkerImageMeta = isset($linkerImageMeta) ? $linkerImageMeta : '';
        }
    }
    
    /**
     * Returns the linker headline.
     *
     * @return string
     */
    public function getLinkerHeadline()
    {
        return $this->linkerHeadline;
    }
    
    /**
     * Sets the linker headline.
     *
     * @param string $linkerHeadline
     *
     * @return void
     */
    public function setLinkerHeadline($linkerHeadline)
    {
        if ($this->linkerHeadline !== $linkerHeadline) {
            $this->linkerHeadline = isset($linkerHeadline) ? $linkerHeadline : '';
        }
    }
    
    /**
     * Returns the linker text.
     *
     * @return text
     */
    public function getLinkerText()
    {
        return $this->linkerText;
    }
    
    /**
     * Sets the linker text.
     *
     * @param text $linkerText
     *
     * @return void
     */
    public function setLinkerText($linkerText)
    {
        if ($this->linkerText !== $linkerText) {
            $this->linkerText = isset($linkerText) ? $linkerText : '';
        }
    }
    
    /**
     * Returns the the link.
     *
     * @return string
     */
    public function getTheLink()
    {
        return $this->theLink;
    }
    
    /**
     * Sets the the link.
     *
     * @param string $theLink
     *
     * @return void
     */
    public function setTheLink($theLink)
    {
        if ($this->theLink !== $theLink) {
            $this->theLink = isset($theLink) ? $theLink : '';
        }
    }
    
    /**
     * Returns the boostrap setting.
     *
     * @return string
     */
    public function getBoostrapSetting()
    {
        return $this->boostrapSetting;
    }
    
    /**
     * Sets the boostrap setting.
     *
     * @param string $boostrapSetting
     *
     * @return void
     */
    public function setBoostrapSetting($boostrapSetting)
    {
        if ($this->boostrapSetting !== $boostrapSetting) {
            $this->boostrapSetting = isset($boostrapSetting) ? $boostrapSetting : '';
        }
    }
    
    /**
     * Returns the linker locale.
     *
     * @return string
     */
    public function getLinkerLocale()
    {
        return $this->linkerLocale;
    }
    
    /**
     * Sets the linker locale.
     *
     * @param string $linkerLocale
     *
     * @return void
     */
    public function setLinkerLocale($linkerLocale)
    {
        if ($this->linkerLocale !== $linkerLocale) {
            $this->linkerLocale = isset($linkerLocale) ? $linkerLocale : '';
        }
    }
    
    /**
     * Returns the sorting.
     *
     * @return integer
     */
    public function getSorting()
    {
        return $this->sorting;
    }
    
    /**
     * Sets the sorting.
     *
     * @param integer $sorting
     *
     * @return void
     */
    public function setSorting($sorting)
    {
        if (intval($this->sorting) !== intval($sorting)) {
            $this->sorting = intval($sorting);
        }
    }
    
    /**
     * Returns the linker group.
     *
     * @return string
     */
    public function getLinkerGroup()
    {
        return $this->linkerGroup;
    }
    
    /**
     * Sets the linker group.
     *
     * @param string $linkerGroup
     *
     * @return void
     */
    public function setLinkerGroup($linkerGroup)
    {
        if ($this->linkerGroup !== $linkerGroup) {
            $this->linkerGroup = $linkerGroup;
        }
    }
    
    
    
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array The resulting arguments list
     */
    public function createUrlArgs()
    {
        return [
            'id' => $this->getId()
        ];
    }
    
    /**
     * Returns the primary key.
     *
     * @return integer The identifier
     */
    public function getKey()
    {
        return $this->getId();
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'rkhelpermodule.ui_hooks.linkers';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects The objects are added to this array. Default: []
     * 
     * @return array of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = []) 
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Linker ' . $this->getKey() . ': ' . $this->getLinkerHeadline();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!$this->id) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifier
        $this->setId(0);
    
        // reset workflow
        $this->setWorkflowState('initial');
    
        // reset upload fields
        $this->setLinkerImage(null);
        $this->setLinkerImageMeta([]);
        $this->setLinkerImageUrl('');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    }
}
