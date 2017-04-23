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

namespace RK\HelperModule\Controller\Base;

use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Zikula\Component\SortableColumns\Column;
use Zikula\Component\SortableColumns\SortableColumns;
use Zikula\Core\Controller\AbstractController;
use Zikula\Core\RouteUrl;
use RK\HelperModule\Entity\ImageEntity;

/**
 * Image controller base class.
 */
abstract class AbstractImageController extends AbstractController
{
    /**
     * This is the default action handling the index admin area called without defining arguments.
     * @Cache(expires="+7 days", public=true)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminIndexAction(Request $request)
    {
        return $this->indexInternal($request, true);
    }
    
    /**
     * This is the default action handling the index area called without defining arguments.
     * @Cache(expires="+7 days", public=true)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function indexAction(Request $request)
    {
        return $this->indexInternal($request, false);
    }
    
    /**
     * This method includes the common implementation code for adminIndex() and index().
     */
    protected function indexInternal(Request $request, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'image';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_OVERVIEW;
        if (!$this->hasPermission('RKHelperModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        return $this->redirectToRoute('rkhelpermodule_image_' . $templateParameters['routeArea'] . 'view');
    }
    /**
     * This action provides an item list overview in the admin area.
     * @Cache(expires="+2 hours", public=false)
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminViewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return $this->viewInternal($request, $sort, $sortdir, $pos, $num, true);
    }
    
    /**
     * This action provides an item list overview.
     * @Cache(expires="+2 hours", public=false)
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function viewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return $this->viewInternal($request, $sort, $sortdir, $pos, $num, false);
    }
    
    /**
     * This method includes the common implementation code for adminView() and view().
     */
    protected function viewInternal(Request $request, $sort, $sortdir, $pos, $num, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'image';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_READ;
        if (!$this->hasPermission('RKHelperModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        $controllerHelper = $this->get('rk_helper_module.controller_helper');
        $viewHelper = $this->get('rk_helper_module.view_helper');
        
        $request->query->set('pos', $pos);
        
        $sortableColumns = new SortableColumns($this->get('router'), 'rkhelpermodule_image_' . ($isAdmin ? 'admin' : '') . 'view', 'sort', 'sortdir');
        
        $sortableColumns->addColumns([
            new Column('imageTitle'),
            new Column('myImage'),
            new Column('myDescription'),
            new Column('copyright'),
            new Column('createdBy'),
            new Column('createdDate'),
            new Column('updatedBy'),
            new Column('updatedDate'),
        ]);
        $sortableColumns->setOrderBy($sortableColumns->getColumn($sort), strtoupper($sortdir));
        
        $templateParameters = $controllerHelper->processViewActionParameters($objectType, $sortableColumns, $templateParameters, true);
        
        foreach ($templateParameters['items'] as $k => $entity) {
            $entity->initWorkflow();
        }
        
        // fetch and return the appropriate template
        return $viewHelper->processTemplate($objectType, 'view', $templateParameters);
    }
    /**
     * This action provides a item detail view in the admin area.
     * @ParamConverter("image", class="RKHelperModule:ImageEntity", options = {"id" = "id", "repository_method" = "selectById"})
     * @Cache(lastModified="image.getUpdatedDate()", ETag="'Image' ~ image.getid() ~ image.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param ImageEntity $image Treated image instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if image to be displayed isn't found
     */
    public function adminDisplayAction(Request $request, ImageEntity $image)
    {
        return $this->displayInternal($request, $image, true);
    }
    
    /**
     * This action provides a item detail view.
     * @ParamConverter("image", class="RKHelperModule:ImageEntity", options = {"id" = "id", "repository_method" = "selectById"})
     * @Cache(lastModified="image.getUpdatedDate()", ETag="'Image' ~ image.getid() ~ image.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param ImageEntity $image Treated image instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if image to be displayed isn't found
     */
    public function displayAction(Request $request, ImageEntity $image)
    {
        return $this->displayInternal($request, $image, false);
    }
    
    /**
     * This method includes the common implementation code for adminDisplay() and display().
     */
    protected function displayInternal(Request $request, ImageEntity $image, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'image';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_READ;
        if (!$this->hasPermission('RKHelperModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        // create identifier for permission check
        $instanceId = $image->createCompositeIdentifier();
        if (!$this->hasPermission('RKHelperModule:' . ucfirst($objectType) . ':', $instanceId . '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        
        $image->initWorkflow();
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : '',
            $objectType => $image
        ];
        
        $controllerHelper = $this->get('rk_helper_module.controller_helper');
        $templateParameters = $controllerHelper->processDisplayActionParameters($objectType, $templateParameters, true);
        
        // fetch and return the appropriate template
        $response = $this->get('rk_helper_module.view_helper')->processTemplate($objectType, 'display', $templateParameters);
        
        return $response;
    }
    /**
     * This action provides a handling of edit requests in the admin area.
     * @Cache(lastModified="image.getUpdatedDate()", ETag="'Image' ~ image.getid() ~ image.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if image to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminEditAction(Request $request)
    {
        return $this->editInternal($request, true);
    }
    
    /**
     * This action provides a handling of edit requests.
     * @Cache(lastModified="image.getUpdatedDate()", ETag="'Image' ~ image.getid() ~ image.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if image to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function editAction(Request $request)
    {
        return $this->editInternal($request, false);
    }
    
    /**
     * This method includes the common implementation code for adminEdit() and edit().
     */
    protected function editInternal(Request $request, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'image';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_EDIT;
        if (!$this->hasPermission('RKHelperModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        $controllerHelper = $this->get('rk_helper_module.controller_helper');
        $templateParameters = $controllerHelper->processEditActionParameters($objectType, $templateParameters);
        
        // delegate form processing to the form handler
        $formHandler = $this->get('rk_helper_module.form.handler.image');
        $result = $formHandler->processForm($templateParameters);
        if ($result instanceof RedirectResponse) {
            return $result;
        }
        
        $templateParameters = $formHandler->getTemplateParameters();
        
        // fetch and return the appropriate template
        return $this->get('rk_helper_module.view_helper')->processTemplate($objectType, 'edit', $templateParameters);
    }
    /**
     * This action provides a handling of simple delete requests in the admin area.
     * @ParamConverter("image", class="RKHelperModule:ImageEntity", options = {"id" = "id", "repository_method" = "selectById"})
     * @Cache(lastModified="image.getUpdatedDate()", ETag="'Image' ~ image.getid() ~ image.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param ImageEntity $image Treated image instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if image to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminDeleteAction(Request $request, ImageEntity $image)
    {
        return $this->deleteInternal($request, $image, true);
    }
    
    /**
     * This action provides a handling of simple delete requests.
     * @ParamConverter("image", class="RKHelperModule:ImageEntity", options = {"id" = "id", "repository_method" = "selectById"})
     * @Cache(lastModified="image.getUpdatedDate()", ETag="'Image' ~ image.getid() ~ image.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param ImageEntity $image Treated image instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if image to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function deleteAction(Request $request, ImageEntity $image)
    {
        return $this->deleteInternal($request, $image, false);
    }
    
    /**
     * This method includes the common implementation code for adminDelete() and delete().
     */
    protected function deleteInternal(Request $request, ImageEntity $image, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'image';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_DELETE;
        if (!$this->hasPermission('RKHelperModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $logger = $this->get('logger');
        $logArgs = ['app' => 'RKHelperModule', 'user' => $this->get('zikula_users_module.current_user')->get('uname'), 'entity' => 'image', 'id' => $image->createCompositeIdentifier()];
        
        $image->initWorkflow();
        
        // determine available workflow actions
        $workflowHelper = $this->get('rk_helper_module.workflow_helper');
        $actions = $workflowHelper->getActionsForObject($image);
        if (false === $actions || !is_array($actions)) {
            $this->addFlash('error', $this->__('Error! Could not determine workflow actions.'));
            $logger->error('{app}: User {user} tried to delete the {entity} with id {id}, but failed to determine available workflow actions.', $logArgs);
            throw new \RuntimeException($this->__('Error! Could not determine workflow actions.'));
        }
        
        // redirect to the list of images
        $redirectRoute = 'rkhelpermodule_image_' . ($isAdmin ? 'admin' : '') . 'view';
        
        // check whether deletion is allowed
        $deleteActionId = 'delete';
        $deleteAllowed = false;
        foreach ($actions as $actionId => $action) {
            if ($actionId != $deleteActionId) {
                continue;
            }
            $deleteAllowed = true;
            break;
        }
        if (!$deleteAllowed) {
            $this->addFlash('error', $this->__('Error! It is not allowed to delete this image.'));
            $logger->error('{app}: User {user} tried to delete the {entity} with id {id}, but this action was not allowed.', $logArgs);
        
            return $this->redirectToRoute($redirectRoute);
        }
        
        $form = $this->createForm('RK\HelperModule\Form\DeleteEntityType', $image);
        
        if ($form->handleRequest($request)->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $hookHelper = $this->get('rk_helper_module.hook_helper');
                // Let any hooks perform additional validation actions
                $validationHooksPassed = $hookHelper->callValidationHooks($image, 'validate_delete');
                if ($validationHooksPassed) {
                    // execute the workflow action
                    $success = $workflowHelper->executeAction($image, $deleteActionId);
                    if ($success) {
                        $this->addFlash('status', $this->__('Done! Item deleted.'));
                        $logger->notice('{app}: User {user} deleted the {entity} with id {id}.', $logArgs);
                    }
                    
                    // Let any hooks know that we have deleted the image
                    $hookHelper->callProcessHooks($image, 'process_delete', null);
                    
                    return $this->redirectToRoute($redirectRoute);
                }
            } elseif ($form->get('cancel')->isClicked()) {
                $this->addFlash('status', $this->__('Operation cancelled.'));
        
                return $this->redirectToRoute($redirectRoute);
            }
        }
        
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : '',
            'deleteForm' => $form->createView(),
            $objectType => $image
        ];
        
        $controllerHelper = $this->get('rk_helper_module.controller_helper');
        $templateParameters = $controllerHelper->processDeleteActionParameters($objectType, $templateParameters, true);
        
        // fetch and return the appropriate template
        return $this->get('rk_helper_module.view_helper')->processTemplate($objectType, 'delete', $templateParameters);
    }

    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function adminHandleSelectedEntriesAction(Request $request)
    {
        return $this->handleSelectedEntriesActionInternal($request, true);
    }
    
    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function handleSelectedEntriesAction(Request $request)
    {
        return $this->handleSelectedEntriesActionInternal($request, false);
    }
    
    /**
     * This method includes the common implementation code for adminHandleSelectedEntriesAction() and handleSelectedEntriesAction().
     *
     * @param Request $request Current request instance
     * @param Boolean $isAdmin Whether the admin area is used or not
     */
    protected function handleSelectedEntriesActionInternal(Request $request, $isAdmin = false)
    {
        $objectType = 'image';
        
        // Get parameters
        $action = $request->request->get('action', null);
        $items = $request->request->get('items', null);
        
        $action = strtolower($action);
        
        $repository = $this->get('rk_helper_module.entity_factory')->getRepository($objectType);
        $workflowHelper = $this->get('rk_helper_module.workflow_helper');
        $hookHelper = $this->get('rk_helper_module.hook_helper');
        $logger = $this->get('logger');
        $userName = $this->get('zikula_users_module.current_user')->get('uname');
        
        // process each item
        foreach ($items as $itemId) {
            // check if item exists, and get record instance
            $entity = $repository->selectById($itemId, false);
            if (null === $entity) {
                continue;
            }
            $entity->initWorkflow();
        
            // check if $action can be applied to this entity (may depend on it's current workflow state)
            $allowedActions = $workflowHelper->getActionsForObject($entity);
            $actionIds = array_keys($allowedActions);
            if (!in_array($action, $actionIds)) {
                // action not allowed, skip this object
                continue;
            }
        
            // Let any hooks perform additional validation actions
            $hookType = $action == 'delete' ? 'validate_delete' : 'validate_edit';
            $validationHooksPassed = $hookHelper->callValidationHooks($entity, $hookType);
            if (!$validationHooksPassed) {
                continue;
            }
        
            $success = false;
            try {
                // execute the workflow action
                $success = $workflowHelper->executeAction($entity, $action);
            } catch(\Exception $e) {
                $this->addFlash('error', $this->__f('Sorry, but an error occured during the %action% action.', ['%action%' => $action]) . '  ' . $e->getMessage());
                $logger->error('{app}: User {user} tried to execute the {action} workflow action for the {entity} with id {id}, but failed. Error details: {errorMessage}.', ['app' => 'RKHelperModule', 'user' => $userName, 'action' => $action, 'entity' => 'image', 'id' => $itemId, 'errorMessage' => $e->getMessage()]);
            }
        
            if (!$success) {
                continue;
            }
        
            if ($action == 'delete') {
                $this->addFlash('status', $this->__('Done! Item deleted.'));
                $logger->notice('{app}: User {user} deleted the {entity} with id {id}.', ['app' => 'RKHelperModule', 'user' => $userName, 'entity' => 'image', 'id' => $itemId]);
            } else {
                $this->addFlash('status', $this->__('Done! Item updated.'));
                $logger->notice('{app}: User {user} executed the {action} workflow action for the {entity} with id {id}.', ['app' => 'RKHelperModule', 'user' => $userName, 'action' => $action, 'entity' => 'image', 'id' => $itemId]);
            }
        
            // Let any hooks know that we have updated or deleted an item
            $hookType = $action == 'delete' ? 'process_delete' : 'process_edit';
            $url = null;
            if ($action != 'delete') {
                $urlArgs = $entity->createUrlArgs();
                $urlArgs['_locale'] = $request->getLocale();
                $url = new RouteUrl('rkhelpermodule_image_' . /*($isAdmin ? 'admin' : '') . */'display', $urlArgs);
            }
            $hookHelper->callProcessHooks($entity, $hookType, $url);
        }
        
        return $this->redirectToRoute('rkhelpermodule_image_' . ($isAdmin ? 'admin' : '') . 'index');
    }
}
