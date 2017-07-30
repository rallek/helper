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

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\UsersModule\Constant as UsersConstant;
use RK\HelperModule\Entity\LinkerEntity;
use RK\HelperModule\Entity\CarouselItemEntity;
use RK\HelperModule\Entity\CarouselEntity;
use RK\HelperModule\Entity\ImageEntity;
use RK\HelperModule\Entity\InfoEntity;

/**
 * Entity collection filter helper base class.
 */
abstract class AbstractCollectionFilterHelper
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var CurrentUserApiInterface
     */
    protected $currentUserApi;

    /**
     * @var bool Fallback value to determine whether only own entries should be selected or not
     */
    protected $showOnlyOwnEntries = false;

    /**
     * @var bool Whether to apply a locale-based filter or not
     */
    protected $filterDataByLocale = false;

    /**
     * CollectionFilterHelper constructor.
     *
     * @param RequestStack $requestStack RequestStack service instance
     * @param CurrentUserApiInterface $currentUserApi        CurrentUserApi service instance
     * @param bool           $showOnlyOwnEntries  Fallback value to determine whether only own entries should be selected or not
     * @param bool           $filterDataByLocale  Whether to apply a locale-based filter or not
     */
    public function __construct(
        RequestStack $requestStack,
        CurrentUserApiInterface $currentUserApi,
        $showOnlyOwnEntries,
        $filterDataByLocale
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->currentUserApi = $currentUserApi;
        $this->showOnlyOwnEntries = $showOnlyOwnEntries;
        $this->filterDataByLocale = $filterDataByLocale;
    }

    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $objectType Name of treated entity type
     * @param string $context    Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args       Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    public function getViewQuickNavParameters($objectType = '', $context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'actionHandler', 'block', 'contentType'])) {
            $context = 'controllerAction';
        }
    
        if ($objectType == 'linker') {
            return $this->getViewQuickNavParametersForLinker($context, $args);
        }
        if ($objectType == 'carouselItem') {
            return $this->getViewQuickNavParametersForCarouselItem($context, $args);
        }
        if ($objectType == 'carousel') {
            return $this->getViewQuickNavParametersForCarousel($context, $args);
        }
        if ($objectType == 'image') {
            return $this->getViewQuickNavParametersForImage($context, $args);
        }
        if ($objectType == 'info') {
            return $this->getViewQuickNavParametersForInfo($context, $args);
        }
    
        return [];
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param string       $objectType Name of treated entity type
     * @param QueryBuilder $qb         Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addCommonViewFilters($objectType, QueryBuilder $qb)
    {
        if ($objectType == 'linker') {
            return $this->addCommonViewFiltersForLinker($qb);
        }
        if ($objectType == 'carouselItem') {
            return $this->addCommonViewFiltersForCarouselItem($qb);
        }
        if ($objectType == 'carousel') {
            return $this->addCommonViewFiltersForCarousel($qb);
        }
        if ($objectType == 'image') {
            return $this->addCommonViewFiltersForImage($qb);
        }
        if ($objectType == 'info') {
            return $this->addCommonViewFiltersForInfo($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param string       $objectType Name of treated entity type
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function applyDefaultFilters($objectType, QueryBuilder $qb, $parameters = [])
    {
        if ($objectType == 'linker') {
            return $this->applyDefaultFiltersForLinker($qb, $parameters);
        }
        if ($objectType == 'carouselItem') {
            return $this->applyDefaultFiltersForCarouselItem($qb, $parameters);
        }
        if ($objectType == 'carousel') {
            return $this->applyDefaultFiltersForCarousel($qb, $parameters);
        }
        if ($objectType == 'image') {
            return $this->applyDefaultFiltersForImage($qb, $parameters);
        }
        if ($objectType == 'info') {
            return $this->applyDefaultFiltersForInfo($qb, $parameters);
        }
    
        return $qb;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForLinker($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['linkerLocale'] = $this->request->query->get('linkerLocale', '');
        $parameters['q'] = $this->request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForCarouselItem($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['carousel'] = $this->request->query->get('carousel', 0);
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['itemLocale'] = $this->request->query->get('itemLocale', '');
        $parameters['q'] = $this->request->query->get('q', '');
        $parameters['linkExternal'] = $this->request->query->get('linkExternal', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForCarousel($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['carouselLocale'] = $this->request->query->get('carouselLocale', '');
        $parameters['q'] = $this->request->query->get('q', '');
        $parameters['controls'] = $this->request->query->get('controls', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForImage($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['q'] = $this->request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForInfo($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['infoLocale'] = $this->request->query->get('infoLocale', '');
        $parameters['q'] = $this->request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForLinker(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForLinker();
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('linker', $qb, $v);
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForLinker($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForCarouselItem(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForCarouselItem();
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('carouselItem', $qb, $v);
                }
            } elseif (in_array($k, ['linkExternal'])) {
                // boolean filter
                if ($v == 'no') {
                    $qb->andWhere('tbl.' . $k . ' = 0');
                } elseif ($v == 'yes' || $v == '1') {
                    $qb->andWhere('tbl.' . $k . ' = 1');
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForCarouselItem($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForCarousel(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForCarousel();
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('carousel', $qb, $v);
                }
            } elseif (in_array($k, ['controls'])) {
                // boolean filter
                if ($v == 'no') {
                    $qb->andWhere('tbl.' . $k . ' = 0');
                } elseif ($v == 'yes' || $v == '1') {
                    $qb->andWhere('tbl.' . $k . ' = 1');
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForCarousel($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForImage(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForImage();
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('image', $qb, $v);
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForImage($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForInfo(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForInfo();
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('info', $qb, $v);
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForInfo($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForLinker(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'rkhelpermodule_linker_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        if (true === (bool)$this->filterDataByLocale) {
            $allowedLocales = ['', $this->request->getLocale()];
            if (!in_array('linkerLocale', array_keys($parameters)) || empty($parameters['linkerLocale'])) {
                $qb->andWhere('tbl.linkerLocale IN (:currentLinkerLocale)')
                   ->setParameter('currentLinkerLocale', $allowedLocales);
            }
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForCarouselItem(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'rkhelpermodule_carousel item_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        if (true === (bool)$this->filterDataByLocale) {
            $allowedLocales = ['', $this->request->getLocale()];
            if (!in_array('itemLocale', array_keys($parameters)) || empty($parameters['itemLocale'])) {
                $qb->andWhere('tbl.itemLocale IN (:currentItemLocale)')
                   ->setParameter('currentItemLocale', $allowedLocales);
            }
        }
        
        $startDate = $this->request->query->get('itemStartDate', date('Y-m-d'));
        $qb->andWhere('(tbl.itemStartDate <= :startDate OR tbl.itemStartDate IS NULL)')
           ->setParameter('startDate', $startDate);
        
        $endDate = $this->request->query->get('intemEndDate', date('Y-m-d'));
        $qb->andWhere('tbl.intemEndDate >= :endDate')
           ->setParameter('endDate', $endDate);
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForCarousel(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'rkhelpermodule_carousel_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        if (true === (bool)$this->filterDataByLocale) {
            $allowedLocales = ['', $this->request->getLocale()];
            if (!in_array('carouselLocale', array_keys($parameters)) || empty($parameters['carouselLocale'])) {
                $qb->andWhere('tbl.carouselLocale IN (:currentCarouselLocale)')
                   ->setParameter('currentCarouselLocale', $allowedLocales);
            }
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForImage(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'rkhelpermodule_image_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForInfo(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'rkhelpermodule_info_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        if (true === (bool)$this->filterDataByLocale) {
            $allowedLocales = ['', $this->request->getLocale()];
            if (!in_array('infoLocale', array_keys($parameters)) || empty($parameters['infoLocale'])) {
                $qb->andWhere('tbl.infoLocale IN (:currentInfoLocale)')
                   ->setParameter('currentInfoLocale', $allowedLocales);
            }
        }
    
        return $qb;
    }
    
    /**
     * Adds a where clause for search query.
     *
     * @param string       $objectType Name of treated entity type
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param string       $fragment   The fragment to search for
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addSearchFilter($objectType, QueryBuilder $qb, $fragment = '')
    {
        if ($fragment == '') {
            return $qb;
        }
    
        $filters = [];
        $parameters = [];
    
        if ($objectType == 'linker') {
            $filters[] = 'tbl.linkerImage = :searchLinkerImage';
            $parameters['searchLinkerImage'] = $fragment;
            $filters[] = 'tbl.linkerHeadline LIKE :searchLinkerHeadline';
            $parameters['searchLinkerHeadline'] = '%' . $fragment . '%';
            $filters[] = 'tbl.linkerText LIKE :searchLinkerText';
            $parameters['searchLinkerText'] = '%' . $fragment . '%';
            $filters[] = 'tbl.theLink LIKE :searchTheLink';
            $parameters['searchTheLink'] = '%' . $fragment . '%';
            $filters[] = 'tbl.boostrapSetting LIKE :searchBoostrapSetting';
            $parameters['searchBoostrapSetting'] = '%' . $fragment . '%';
            $filters[] = 'tbl.linkerLocale LIKE :searchLinkerLocale';
            $parameters['searchLinkerLocale'] = '%' . $fragment . '%';
            $filters[] = 'tbl.sorting = :searchSorting';
            $parameters['searchSorting'] = $fragment;
            $filters[] = 'tbl.linkerGroup LIKE :searchLinkerGroup';
            $parameters['searchLinkerGroup'] = '%' . $fragment . '%';
        }
        if ($objectType == 'carouselItem') {
            $filters[] = 'tbl.itemName LIKE :searchItemName';
            $parameters['searchItemName'] = '%' . $fragment . '%';
            $filters[] = 'tbl.title LIKE :searchTitle';
            $parameters['searchTitle'] = '%' . $fragment . '%';
            $filters[] = 'tbl.subtitle LIKE :searchSubtitle';
            $parameters['searchSubtitle'] = '%' . $fragment . '%';
            $filters[] = 'tbl.link LIKE :searchLink';
            $parameters['searchLink'] = '%' . $fragment . '%';
            $filters[] = 'tbl.itemImage = :searchItemImage';
            $parameters['searchItemImage'] = $fragment;
            $filters[] = 'tbl.titleColor LIKE :searchTitleColor';
            $parameters['searchTitleColor'] = '%' . $fragment . '%';
            $filters[] = 'tbl.itemStartDate = :searchItemStartDate';
            $parameters['searchItemStartDate'] = $fragment;
            $filters[] = 'tbl.intemEndDate = :searchIntemEndDate';
            $parameters['searchIntemEndDate'] = $fragment;
            $filters[] = 'tbl.singleItemIdentifier LIKE :searchSingleItemIdentifier';
            $parameters['searchSingleItemIdentifier'] = '%' . $fragment . '%';
            $filters[] = 'tbl.itemLocale LIKE :searchItemLocale';
            $parameters['searchItemLocale'] = '%' . $fragment . '%';
        }
        if ($objectType == 'carousel') {
            $filters[] = 'tbl.carouselName LIKE :searchCarouselName';
            $parameters['searchCarouselName'] = '%' . $fragment . '%';
            $filters[] = 'tbl.remarks LIKE :searchRemarks';
            $parameters['searchRemarks'] = '%' . $fragment . '%';
            $filters[] = 'tbl.slidingTime = :searchSlidingTime';
            $parameters['searchSlidingTime'] = $fragment;
            $filters[] = 'tbl.carouselGroup LIKE :searchCarouselGroup';
            $parameters['searchCarouselGroup'] = '%' . $fragment . '%';
            $filters[] = 'tbl.carouselLocale LIKE :searchCarouselLocale';
            $parameters['searchCarouselLocale'] = '%' . $fragment . '%';
        }
        if ($objectType == 'image') {
            $filters[] = 'tbl.imageTitle LIKE :searchImageTitle';
            $parameters['searchImageTitle'] = '%' . $fragment . '%';
            $filters[] = 'tbl.myImage = :searchMyImage';
            $parameters['searchMyImage'] = $fragment;
            $filters[] = 'tbl.myDescription LIKE :searchMyDescription';
            $parameters['searchMyDescription'] = '%' . $fragment . '%';
            $filters[] = 'tbl.copyright LIKE :searchCopyright';
            $parameters['searchCopyright'] = '%' . $fragment . '%';
        }
        if ($objectType == 'info') {
            $filters[] = 'tbl.infoTitle LIKE :searchInfoTitle';
            $parameters['searchInfoTitle'] = '%' . $fragment . '%';
            $filters[] = 'tbl.titleImage = :searchTitleImage';
            $parameters['searchTitleImage'] = $fragment;
            $filters[] = 'tbl.copyright LIKE :searchCopyright';
            $parameters['searchCopyright'] = '%' . $fragment . '%';
            $filters[] = 'tbl.infoDescription LIKE :searchInfoDescription';
            $parameters['searchInfoDescription'] = '%' . $fragment . '%';
            $filters[] = 'tbl.infoLocale LIKE :searchInfoLocale';
            $parameters['searchInfoLocale'] = '%' . $fragment . '%';
        }
    
        $qb->andWhere('(' . implode(' OR ', $filters) . ')');
    
        foreach ($parameters as $parameterName => $parameterValue) {
            $qb->setParameter($parameterName, $parameterValue);
        }
    
        return $qb;
    }
    
    /**
     * Adds a filter for the createdBy field.
     *
     * @param QueryBuilder $qb     Query builder to be enhanced
     * @param integer      $userId The user identifier used for filtering
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addCreatorFilter(QueryBuilder $qb, $userId = null)
    {
        if (null === $userId) {
            $userId = $this->currentUserApi->isLoggedIn() ? $this->currentUserApi->get('uid') : UsersConstant::USER_ID_ANONYMOUS;
        }
    
        if (is_array($userId)) {
            $qb->andWhere('tbl.createdBy IN (:userIds)')
               ->setParameter('userIds', $userId);
        } else {
            $qb->andWhere('tbl.createdBy = :userId')
               ->setParameter('userId', $userId);
        }
    
        return $qb;
    }
}