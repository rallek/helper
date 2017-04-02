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
		
		case '0.7.2':
			$this->setVar('descriptionLengthImage', '1000');
			$this->setVar('descriptionLengthInfoList', '250');
			$this->setVar('descriptionLengthImageList', '250');
    
    
        // update successful
        return true;
    }
	
	
	
}
