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

namespace RK\HelperModule;

use RK\HelperModule\Base\AbstractHelperModuleInstaller;

/**
 * Installer implementation class.
 */
class HelperModuleInstaller extends AbstractHelperModuleInstaller
{
    // feel free to extend the installer here
	
	public function upgrade($oldVersion)
    {
		switch ($oldVersion) {
		case '0.7.3':
        $this->setVar('enableShrinkingForInfoTitleImage', false);
        $this->setVar('shrinkWidthInfoTitleImage', '800');
        $this->setVar('shrinkHeightInfoTitleImage', '600');
        $this->setVar('thumbnailModeInfoTitleImage',  'inset' );
        $this->setVar('thumbnailWidthInfoTitleImageView', '32');
        $this->setVar('thumbnailHeightInfoTitleImageView', '24');
        $this->setVar('thumbnailWidthInfoTitleImageDisplay', '240');
        $this->setVar('thumbnailHeightInfoTitleImageDisplay', '180');
        $this->setVar('thumbnailWidthInfoTitleImageEdit', '240');
        $this->setVar('thumbnailHeightInfoTitleImageEdit', '180');
    
		}
        // update successful
        return true;
    }
	
	
	
}
