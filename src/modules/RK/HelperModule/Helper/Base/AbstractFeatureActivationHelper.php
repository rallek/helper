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

namespace RK\HelperModule\Helper\Base;

/**
 * Helper base class for dynamic feature enablement methods.
 */
abstract class AbstractFeatureActivationHelper
{
    /**
     * Translation feature
     */
    const TRANSLATIONS = 'translations';
    
    /**
     * This method checks whether a certain feature is enabled for a given entity type or not.
     *
     * @param string $feature     Name of requested feature
     * @param string $objectType  Currently treated entity type
     *
     * @return boolean True if the feature is enabled, false otherwise
     */
    public function isEnabled($feature, $objectType)
    {
        if ($feature == self::TRANSLATIONS) {
            $method = 'hasTranslations';
            if (method_exists($this, $method)) {
                return $this->$method($objectType);
            }
    
            return in_array($objectType, ['info']);
        }
    
        return false;
    }
}
