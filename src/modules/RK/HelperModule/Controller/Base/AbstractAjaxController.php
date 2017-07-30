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

namespace RK\HelperModule\Controller\Base;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Controller\AbstractController;

/**
 * Ajax controller base class.
 */
abstract class AbstractAjaxController extends AbstractController
{
    
    /**
     * Retrieve item list for finder selections in Forms, Content type plugin and Scribite.
     *
     * @param string $ot      Name of currently used object type
     * @param string $sort    Sorting field
     * @param string $sortdir Sorting direction
     *
     * @return JsonResponse
     */
    public function getItemListFinderAction(Request $request)
    {
        if (!$this->hasPermission('RKHelperModule::Ajax', '::', ACCESS_EDIT)) {
            return true;
        }
        
        $objectType = $request->query->getAlnum('ot', 'linker');
        $controllerHelper = $this->get('rk_helper_module.controller_helper');
        $contextArgs = ['controller' => 'ajax', 'action' => 'getItemListFinder'];
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $contextArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $contextArgs);
        }
        
        $repository = $this->get('rk_helper_module.entity_factory')->getRepository($objectType);
        $entityDisplayHelper = $this->get('rk_helper_module.entity_display_helper');
        $descriptionFieldName = $entityDisplayHelper->getDescriptionFieldName($objectType);
        
        $sort = $request->query->getAlnum('sort', '');
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
        }
        
        $sdir = strtolower($request->query->getAlpha('sortdir', ''));
        if ($sdir != 'asc' && $sdir != 'desc') {
            $sdir = 'asc';
        }
        
        $where = ''; // filters are processed inside the repository class
        $searchTerm = $request->query->get('q', '');
        $sortParam = $sort . ' ' . $sdir;
        
        $entities = [];
        if ($searchTerm != '') {
            list ($entities, $totalAmount) = $repository->selectSearch($searchTerm, [], $sortParam, 1, 50);
        } else {
            $entities = $repository->selectWhere($where, $sortParam);
        }
        
        $slimItems = [];
        $component = 'RKHelperModule:' . ucfirst($objectType) . ':';
        foreach ($entities as $item) {
            $itemId = $item->getKey();
            if (!$this->hasPermission($component, $itemId . '::', ACCESS_READ)) {
                continue;
            }
            $slimItems[] = $this->prepareSlimItem($repository, $objectType, $item, $itemId, $descriptionFieldName);
        }
        
        // return response
        return new JsonResponse($slimItems);
    }
    
    /**
     * Builds and returns a slim data array from a given entity.
     *
     * @param EntityRepository $repository       Repository for the treated object type
     * @param string           $objectType       The currently treated object type
     * @param object           $item             The currently treated entity
     * @param string           $itemId           Data item identifier(s)
     * @param string           $descriptionField Name of item description field
     *
     * @return array The slim data representation
     */
    protected function prepareSlimItem($repository, $objectType, $item, $itemId, $descriptionField)
    {
        $previewParameters = [
            $objectType => $item
        ];
        $contextArgs = ['controller' => $objectType, 'action' => 'display'];
        $previewParameters = $this->get('rk_helper_module.controller_helper')->addTemplateParameters($objectType, $previewParameters, 'controllerAction', $contextArgs);
    
        $previewInfo = base64_encode($this->get('twig')->render('@RKHelperModule/External/' . ucfirst($objectType) . '/info.html.twig', $previewParameters));
    
        $title = $this->get('rk_helper_module.entity_display_helper')->getFormattedTitle($item);
        $description = $descriptionField != '' ? $item[$descriptionField] : '';
    
        return [
            'id'          => $itemId,
            'title'       => str_replace('&amp;', '&', $title),
            'description' => $description,
            'previewInfo' => $previewInfo
        ];
    }
    
    /**
     * Checks whether a field value is a duplicate or not.
     *
     * @param Request $request Current request instance
     *
     * @return JsonResponse
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function checkForDuplicateAction(Request $request)
    {
        if (!$this->hasPermission('RKHelperModule::Ajax', '::', ACCESS_EDIT)) {
            throw new AccessDeniedException();
        }
        
        $objectType = $request->query->getAlnum('ot', 'linker');
        $controllerHelper = $this->get('rk_helper_module.controller_helper');
        $contextArgs = ['controller' => 'ajax', 'action' => 'checkForDuplicate'];
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $contextArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $contextArgs);
        }
        
        $fieldName = $request->query->getAlnum('fn', '');
        $value = $request->query->get('v', '');
        
        if (empty($fieldName) || empty($value)) {
            return new JsonResponse($this->__('Error: invalid input.'), JsonResponse::HTTP_BAD_REQUEST);
        }
        
        // check if the given field is existing and unique
        $uniqueFields = [];
        switch ($objectType) {
            case 'carouselItem':
                    $uniqueFields = ['singleItemIdentifier'];
                    break;
        }
        if (!count($uniqueFields) || !in_array($fieldName, $uniqueFields)) {
            return new JsonResponse($this->__('Error: invalid input.'), JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $exclude = $request->query->getInt('ex', '');
        
        $result = false;
        switch ($objectType) {
        case 'carouselItem':
            $repository = $this->get('rk_helper_module.entity_factory')->getRepository($objectType);
            switch ($fieldName) {
            case 'singleItemIdentifier':
                    $result = $repository->detectUniqueState('singleItemIdentifier', $value, $exclude);
                    break;
            }
            break;
        }
        
        // return response
        return new JsonResponse(['isDuplicate' => $result]);
    }
}
