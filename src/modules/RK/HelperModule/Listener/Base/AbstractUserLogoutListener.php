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

namespace RK\HelperModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Core\Event\GenericEvent;
use Zikula\UsersModule\AccessEvents;

/**
 * Event handler base class for user logout events.
 */
abstract class AbstractUserLogoutListener implements EventSubscriberInterface
{
    /**
     * Makes our handlers known to the event system.
     */
    public static function getSubscribedEvents()
    {
        return [
            AccessEvents::LOGOUT_SUCCESS => ['succeeded', 5]
        ];
    }
    
    /**
     * Listener for the `module.users.ui.logout.succeeded` event.
     *
     * Occurs right after a successful logout. All handlers are notified.
     * The event's subject contains the user's UserEntity.
     * Args contain array of `['authentication_method' => $authenticationMethod,
     *                         'uid'                   => $uid];`
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     * @param GenericEvent $event The event instance
     */
    public function succeeded(GenericEvent $event)
    {
    }
}
