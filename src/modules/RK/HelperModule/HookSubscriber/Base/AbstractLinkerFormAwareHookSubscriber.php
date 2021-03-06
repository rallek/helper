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

namespace RK\HelperModule\HookSubscriber\Base;

use Zikula\Bundle\HookBundle\Category\FormAwareCategory;
use Zikula\Bundle\HookBundle\HookSubscriberInterface;
use Zikula\Common\Translator\TranslatorInterface;

/**
 * Base class for form aware hook subscriber.
 */
abstract class AbstractLinkerFormAwareHookSubscriber implements HookSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * LinkerFormAwareHookSubscriber constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function getOwner()
    {
        return 'RKHelperModule';
    }
    
    /**
     * @inheritDoc
     */
    public function getCategory()
    {
        return FormAwareCategory::NAME;
    }
    
    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->translator->__('Linker form aware subscriber');
    }

    /**
     * @inheritDoc
     */
    public function getEvents()
    {
        return [
            // Display hook for create/edit forms.
            FormAwareCategory::TYPE_EDIT => 'rkhelpermodule.form_aware_hook.linkers.edit',
            // Process the results of the edit form after the main form is processed.
            FormAwareCategory::TYPE_PROCESS_EDIT => 'rkhelpermodule.form_aware_hook.linkers.process_edit',
            // Display hook for delete forms.
            FormAwareCategory::TYPE_DELETE => 'rkhelpermodule.form_aware_hook.linkers.delete',
            // Process the results of the delete form after the main form is processed.
            FormAwareCategory::TYPE_PROCESS_DELETE => 'rkhelpermodule.form_aware_hook.linkers.process_delete'
        ];
    }
}
