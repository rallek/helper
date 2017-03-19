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

namespace RK\HelperModule\Entity\Repository\Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Zikula\Component\FilterUtil\FilterUtil;
use Zikula\Component\FilterUtil\Config as FilterConfig;
use Zikula\Component\FilterUtil\PluginManager as FilterPluginManager;
use Psr\Log\LoggerInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\UsersModule\Api\CurrentUserApi;
use RK\HelperModule\Entity\CarouselEntity;
use RK\HelperModule\Helper\ImageHelper;

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the base repository class for carousel entities.
 */
abstract class AbstractCarouselRepository extends EntityRepository
{
    
    /**
     * @var string The default sorting field/expression
     */
    protected $defaultSortingField = 'carouselName';

    /**
     * @var Request The request object given by the calling controller
     */
    protected $request;
    

    /**
     * Retrieves an array with all fields which can be used for sorting instances.
     *
     * @return array Sorting fields array
     */
    public function getAllowedSortingFields()
    {
        return [
            'carouselName',
            'remarks',
            'slidingTime',
            'controls',
            'carouselGroup',
            'carouselLocale',
            'createdBy',
            'createdDate',
            'updatedBy',
            'updatedDate',
        ];
    }

    /**
     * Returns the default sorting field.
     *
     * @return string
     */
    public function getDefaultSortingField()
    {
        return $this->defaultSortingField;
    }
    
    /**
     * Sets the default sorting field.
     *
     * @param string $defaultSortingField
     *
     * @return void
     */
    public function setDefaultSortingField($defaultSortingField)
    {
        $this->defaultSortingField = $defaultSortingField;
    }
    
    /**
     * Returns the request.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * Sets the request.
     *
     * @param Request $request
     *
     * @return void
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }
    

    /**
     * Returns name of the field used as title / name for entities of this repository.
     *
     * @return string Name of field to be used as title
     */
    public function getTitleFieldName()
    {
        $fieldName = 'carouselName';
    
        return $fieldName;
    }
    
    /**
     * Returns name of the field used for describing entities of this repository.
     *
     * @return string Name of field to be used as description
     */
    public function getDescriptionFieldName()
    {
        $fieldName = 'remarks';
    
        return $fieldName;
    }
    
    /**
     * Returns name of first upload field which is capable for handling images.
     *
     * @return string Name of field to be used for preview images
     */
    public function getPreviewFieldName()
    {
        $fieldName = '';
    
        return $fieldName;
    }
    
    /**
     * Returns name of the date(time) field to be used for representing the start
     * of this object. Used for providing meta data to the tag module.
     *
     * @return string Name of field to be used as date
     */
    public function getStartDateFieldName()
    {
        $fieldName = 'createdDate';
    
        return $fieldName;
    }

    /**
     * Returns an array of additional template variables which are specific to the object type treated by this repository.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    public function getAdditionalTemplateParameters(ImageHelper $imageHelper, $context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'actionHandler', 'block', 'contentType'])) {
            $context = 'controllerAction';
        }
    
        $templateParameters = [];
    
        if ($context == 'controllerAction') {
            if (!isset($args['action'])) {
                $args['action'] = $this->getRequest()->query->getAlpha('func', 'index');
            }
            if (in_array($args['action'], ['index', 'view'])) {
                $templateParameters = $this->getViewQuickNavParameters($context, $args);
            }
    
            // initialise Imagine runtime options
            if (in_array($args['action'], ['display', 'view'])) {
                // use separate preset for images in related items
                $templateParameters['relationThumbRuntimeOptions'] = $imageHelper->getCustomRuntimeOptions('', '', 'RKHelperModule_relateditem', $context, $args);
            }
        }
    
        // in the concrete child class you could do something like
        // $parameters = parent::getAdditionalTemplateParameters($imageHelper, $context, $args);
        // $parameters['myvar'] = 'myvalue';
        // return $parameters;
    
        return $templateParameters;
    }
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParameters($context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'actionHandler', 'block', 'contentType'])) {
            $context = 'controllerAction';
        }
    
        $parameters = [];
        $parameters['workflowState'] = $this->getRequest()->query->get('workflowState', '');
        $parameters['carouselLocale'] = $this->getRequest()->query->get('carouselLocale', '');
        $parameters['q'] = $this->getRequest()->query->get('q', '');
        
        $parameters['controls'] = $this->getRequest()->query->get('controls', '');
    
        // in the concrete child class you could do something like
        // $parameters = parent::getViewQuickNavParameters($context, $args);
        // $parameters['myvar'] = 'myvalue';
        // return $parameters;
    
        return $parameters;
    }

    /**
     * Helper method for truncating the table.
     * Used during installation when inserting default data.
     *
     * @param LoggerInterface $logger Logger service instance
     *
     * @return void
     */
    public function truncateTable(LoggerInterface $logger)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete('RK\HelperModule\Entity\CarouselEntity', 'tbl');
        $query = $qb->getQuery();
    
        $query->execute();
    
        $logArgs = ['app' => 'RKHelperModule', 'entity' => 'carousel'];
        $logger->debug('{app}: Truncated the {entity} entity table.', $logArgs);
    }
    /**
     * Updates the creator of all objects created by a certain user.
     *
     * @param integer             $userId         The userid of the creator to be replaced
     * @param integer             $newUserId      The new userid of the creator as replacement
     * @param TranslatorInterface $translator     Translator service instance
     * @param LoggerInterface     $logger         Logger service instance
     * @param CurrentUserApi      $currentUserApi CurrentUserApi service instance
     *
     * @return void
     *
     * @throws InvalidArgumentException Thrown if invalid parameters are received
     */
    public function updateCreator($userId, $newUserId, TranslatorInterface $translator, LoggerInterface $logger, CurrentUserApi $currentUserApi)
    {
        // check id parameter
        if ($userId == 0 || !is_numeric($userId)
         || $newUserId == 0 || !is_numeric($newUserId)) {
            throw new InvalidArgumentException($translator->__('Invalid user identifier received.'));
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update('RK\HelperModule\Entity\CarouselEntity', 'tbl')
           ->set('tbl.createdBy', $newUserId)
           ->where('tbl.createdBy = :creator')
           ->setParameter('creator', $userId);
        $query = $qb->getQuery();
        $query->execute();
    
        $logArgs = ['app' => 'RKHelperModule', 'user' => $currentUserApi->get('uname'), 'entities' => 'carousells', 'userid' => $userId];
        $logger->debug('{app}: User {user} updated {entities} created by user id {userid}.', $logArgs);
    }
    
    /**
     * Updates the last editor of all objects updated by a certain user.
     *
     * @param integer             $userId         The userid of the last editor to be replaced
     * @param integer             $newUserId      The new userid of the last editor as replacement
     * @param TranslatorInterface $translator     Translator service instance
     * @param LoggerInterface     $logger         Logger service instance
     * @param CurrentUserApi      $currentUserApi CurrentUserApi service instance
     *
     * @return void
     *
     * @throws InvalidArgumentException Thrown if invalid parameters are received
     */
    public function updateLastEditor($userId, $newUserId, TranslatorInterface $translator, LoggerInterface $logger, CurrentUserApi $currentUserApi)
    {
        // check id parameter
        if ($userId == 0 || !is_numeric($userId)
         || $newUserId == 0 || !is_numeric($newUserId)) {
            throw new InvalidArgumentException($translator->__('Invalid user identifier received.'));
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update('RK\HelperModule\Entity\CarouselEntity', 'tbl')
           ->set('tbl.updatedBy', $newUserId)
           ->where('tbl.updatedBy = :editor')
           ->setParameter('editor', $userId);
        $query = $qb->getQuery();
        $query->execute();
    
        $logArgs = ['app' => 'RKHelperModule', 'user' => $currentUserApi->get('uname'), 'entities' => 'carousells', 'userid' => $userId];
        $logger->debug('{app}: User {user} updated {entities} edited by user id {userid}.', $logArgs);
    }
    
    /**
     * Deletes all objects created by a certain user.
     *
     * @param integer             $userId         The userid of the creator to be removed
     * @param TranslatorInterface $translator     Translator service instance
     * @param LoggerInterface     $logger         Logger service instance
     * @param CurrentUserApi      $currentUserApi CurrentUserApi service instance
     *
     * @return void
     *
     * @throws InvalidArgumentException Thrown if invalid parameters are received
     */
    public function deleteByCreator($userId, TranslatorInterface $translator, LoggerInterface $logger, CurrentUserApi $currentUserApi)
    {
        // check id parameter
        if ($userId == 0 || !is_numeric($userId)) {
            throw new InvalidArgumentException($translator->__('Invalid user identifier received.'));
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete('RK\HelperModule\Entity\CarouselEntity', 'tbl')
           ->where('tbl.createdBy = :creator')
           ->setParameter('creator', $userId);
        $query = $qb->getQuery();
    
        $query->execute();
    
        $logArgs = ['app' => 'RKHelperModule', 'user' => $currentUserApi->get('uname'), 'entities' => 'carousells', 'userid' => $userId];
        $logger->debug('{app}: User {user} deleted {entities} created by user id {userid}.', $logArgs);
    }
    
    /**
     * Deletes all objects updated by a certain user.
     *
     * @param integer             $userId         The userid of the last editor to be removed
     * @param TranslatorInterface $translator     Translator service instance
     * @param LoggerInterface     $logger         Logger service instance
     * @param CurrentUserApi      $currentUserApi CurrentUserApi service instance
     *
     * @return void
     *
     * @throws InvalidArgumentException Thrown if invalid parameters are received
     */
    public function deleteByLastEditor($userId, TranslatorInterface $translator, LoggerInterface $logger, CurrentUserApi $currentUserApi)
    {
        // check id parameter
        if ($userId == 0 || !is_numeric($userId)) {
            throw new InvalidArgumentException($translator->__('Invalid user identifier received.'));
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete('RK\HelperModule\Entity\CarouselEntity', 'tbl')
           ->where('tbl.updatedBy = :editor')
           ->setParameter('editor', $userId);
        $query = $qb->getQuery();
    
        $query->execute();
    
        $logArgs = ['app' => 'RKHelperModule', 'user' => $currentUserApi->get('uname'), 'entities' => 'carousells', 'userid' => $userId];
        $logger->debug('{app}: User {user} deleted {entities} edited by user id {userid}.', $logArgs);
    }

    /**
     * Adds an array of id filters to given query instance.
     *
     * @param mixed        $idList The array of ids to use to retrieve the object
     * @param QueryBuilder $qb     Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addIdListFilter($idList, QueryBuilder $qb)
    {
        $orX = $qb->expr()->orX();
    
        foreach ($idList as $id) {
            // check id parameter
            if ($id == 0) {
                throw new InvalidArgumentException('Invalid identifier received.');
            }
    
            if (is_array($id)) {
                $andX = $qb->expr()->andX();
                foreach ($id as $fieldName => $fieldValue) {
                    $andX->add($qb->expr()->eq('tbl.' . $fieldName, $fieldValue));
                }
                $orX->add($andX);
            } else {
                $orX->add($qb->expr()->eq('tbl.id', $id));
            }
        }
    
        $qb->andWhere($orX);
    
        return $qb;
    }
    
    /**
     * Selects an object from the database.
     *
     * @param mixed   $id       The id (or array of ids) to use to retrieve the object (optional) (default=0)
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true)
     * @param boolean $slimMode If activated only some basic fields are selected without using any joins (optional) (default=false)
     *
     * @return array|carouselEntity retrieved data array or carouselEntity instance
     *
     * @throws InvalidArgumentException Thrown if invalid parameters are received
     */
    public function selectById($id = 0, $useJoins = true, $slimMode = false)
    {
        $results = $this->selectByIdList(is_array($id) ? $id : [$id], $useJoins, $slimMode);
    
        return count($results) > 0 ? $results[0] : null;
    }
    
    /**
     * Selects a list of objects with an array of ids
     *
     * @param mixed   $idList   The array of ids to use to retrieve the objects (optional) (default=0)
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true)
     * @param boolean $slimMode If activated only some basic fields are selected without using any joins (optional) (default=false)
     *
     * @return ArrayCollection collection containing retrieved carouselEntity instances
     *
     * @throws InvalidArgumentException Thrown if invalid parameters are received
     */
    public function selectByIdList($idList = [0], $useJoins = true, $slimMode = false)
    {
        $qb = $this->genericBaseQuery('', '', $useJoins, $slimMode);
        $qb = $this->addIdListFilter($idList, $qb);
    
        $query = $this->getQueryFromBuilder($qb);
    
        $results = $query->getResult();
    
        return count($results) > 0 ? $results : null;
    }

    /**
     * Adds where clauses excluding desired identifiers from selection.
     *
     * @param QueryBuilder $qb        Query builder to be enhanced
     * @param integer      $excludeId The id to be excluded from selection
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addExclusion(QueryBuilder $qb, $excludeId)
    {
        if ($excludeId > 0) {
            $qb->andWhere('tbl.id != :excludeId')
               ->setParameter('excludeId', $excludeId);
        }
    
        return $qb;
    }

    /**
     * Returns query builder for selecting a list of objects with a given where clause.
     *
     * @param string  $where    The where clause to use when retrieving the collection (optional) (default='')
     * @param string  $orderBy  The order-by clause to use when retrieving the collection (optional) (default='')
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true)
     * @param boolean $slimMode If activated only some basic fields are selected without using any joins (optional) (default=false)
     *
     * @return QueryBuilder query builder for the given arguments
     */
    public function getListQueryBuilder($where = '', $orderBy = '', $useJoins = true, $slimMode = false)
    {
        $qb = $this->genericBaseQuery($where, $orderBy, $useJoins, $slimMode);
        if (!$useJoins || !$slimMode) {
            $qb = $this->addCommonViewFilters($qb);
        }
    
        return $qb;
    }
    
    /**
     * Selects a list of objects with a given where clause.
     *
     * @param string  $where    The where clause to use when retrieving the collection (optional) (default='')
     * @param string  $orderBy  The order-by clause to use when retrieving the collection (optional) (default='')
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true)
     * @param boolean $slimMode If activated only some basic fields are selected without using any joins (optional) (default=false)
     *
     * @return ArrayCollection collection containing retrieved carouselEntity instances
     */
    public function selectWhere($where = '', $orderBy = '', $useJoins = true, $slimMode = false)
    {
        $qb = $this->getListQueryBuilder($where, $orderBy, $useJoins, $slimMode);
    
        $query = $this->getQueryFromBuilder($qb);
    
        return $this->retrieveCollectionResult($query, $orderBy, false);
    }

    /**
     * Returns query builder instance for retrieving a list of objects with a given where clause and pagination parameters.
     *
     * @param QueryBuilder $qb             Query builder to be enhanced
     * @param integer      $currentPage    Where to start selection
     * @param integer      $resultsPerPage Amount of items to select
     *
     * @return Query Created query instance
     */
    public function getSelectWherePaginatedQuery(QueryBuilder $qb, $currentPage = 1, $resultsPerPage = 25)
    {
        $qb = $this->addCommonViewFilters($qb);
    
        $query = $this->getQueryFromBuilder($qb);
        $offset = ($currentPage-1) * $resultsPerPage;
    
        $query->setFirstResult($offset)
              ->setMaxResults($resultsPerPage);
    
        return $query;
    }
    
    /**
     * Selects a list of objects with a given where clause and pagination parameters.
     *
     * @param string  $where          The where clause to use when retrieving the collection (optional) (default='')
     * @param string  $orderBy        The order-by clause to use when retrieving the collection (optional) (default='')
     * @param integer $currentPage    Where to start selection
     * @param integer $resultsPerPage Amount of items to select
     * @param boolean $useJoins       Whether to include joining related objects (optional) (default=true)
     * @param boolean $slimMode       If activated only some basic fields are selected without using any joins (optional) (default=false)
     *
     * @return array with retrieved collection and amount of total records affected by this query
     */
    public function selectWherePaginated($where = '', $orderBy = '', $currentPage = 1, $resultsPerPage = 25, $useJoins = true, $slimMode = false)
    {
        $qb = $this->genericBaseQuery($where, $orderBy, $useJoins, $slimMode);
    
        $page = $currentPage;
        
        $query = $this->getSelectWherePaginatedQuery($qb, $page, $resultsPerPage);
    
        return $this->retrieveCollectionResult($query, $orderBy, true);
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addCommonViewFilters(QueryBuilder $qb)
    {
        if (null === $this->getRequest()) {
            // if no request is set we return (#433)
            return $qb;
        }
    
        $routeName = $this->getRequest()->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParameters('', []);
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter($qb, $v);
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
    
        $qb = $this->applyDefaultFilters($qb, $parameters);
    
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
    protected function applyDefaultFilters(QueryBuilder $qb, $parameters = [])
    {
    
        return $qb;
    }

    /**
     * Selects entities by a given search fragment.
     *
     * @param string  $fragment       The fragment to search for
     * @param array   $exclude        List with identifiers to be excluded from search
     * @param string  $orderBy        The order-by clause to use when retrieving the collection (optional) (default='')
     * @param integer $currentPage    Where to start selection
     * @param integer $resultsPerPage Amount of items to select
     * @param boolean $useJoins       Whether to include joining related objects (optional) (default=true)
     *
     * @return array with retrieved collection and amount of total records affected by this query
     */
    public function selectSearch($fragment = '', $exclude = [], $orderBy = '', $currentPage = 1, $resultsPerPage = 25, $useJoins = true)
    {
        $qb = $this->genericBaseQuery('', $orderBy, $useJoins);
        if (count($exclude) > 0) {
            $qb = $this->addExclusion($qb, $exclude);
        }
    
        $qb = $this->addSearchFilter($qb, $fragment);
    
        $query = $this->getSelectWherePaginatedQuery($qb, $currentPage, $resultsPerPage);
    
        return $this->retrieveCollectionResult($query, $orderBy, true);
    }
    
    /**
     * Adds where clause for search query.
     *
     * @param QueryBuilder $qb       Query builder to be enhanced
     * @param string       $fragment The fragment to search for
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addSearchFilter(QueryBuilder $qb, $fragment = '')
    {
        if ($fragment == '') {
            return $qb;
        }
    
        $filters = [];
        $parameters = [];
    
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
    
        $qb->andWhere('(' . implode(' OR ', $filters) . ')')
           ->setParameters($parameters);
    
        return $qb;
    }

    /**
     * Performs a given database selection and post-processed the results.
     *
     * @param Query   $query       The Query instance to be executed
     * @param string  $orderBy     The order-by clause to use when retrieving the collection (optional) (default='')
     * @param boolean $isPaginated Whether the given query uses a paginator or not (optional) (default=false)
     *
     * @return array with retrieved collection and (for paginated queries) the amount of total records affected
     */
    public function retrieveCollectionResult(Query $query, $orderBy = '', $isPaginated = false)
    {
        $count = 0;
        if (!$isPaginated) {
            $result = $query->getResult();
        } else {
            $paginator = new Paginator($query, true);
    
            $count = count($paginator);
            $result = $paginator;
        }
    
        if (!$isPaginated) {
            return $result;
        }
    
        return [$result, $count];
    }

    /**
     * Returns query builder instance for a count query.
     *
     * @param string  $where    The where clause to use when retrieving the object count (optional) (default='')
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true)
     *
     * @return QueryBuilder Created query builder instance
     * @TODO fix usage of joins; please remove the first line and test
     */
    protected function getCountQuery($where = '', $useJoins = true)
    {
        $useJoins = false;
    
        $selection = 'COUNT(tbl.id) AS numCarousells';
        if (true === $useJoins) {
            $selection .= $this->addJoinsToSelection();
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($selection)
           ->from('RK\HelperModule\Entity\CarouselEntity', 'tbl');
    
        if (true === $useJoins) {
            $this->addJoinsToFrom($qb);
        }
    
        $this->genericBaseQueryAddWhere($qb, $where);
    
        return $qb;
    }
    
    /**
     * Selects entity count with a given where clause.
     *
     * @param string  $where      The where clause to use when retrieving the object count (optional) (default='')
     * @param boolean $useJoins   Whether to include joining related objects (optional) (default=true)
     * @param array   $parameters List of determined filter options
     *
     * @return integer amount of affected records
     */
    public function selectCount($where = '', $useJoins = true, $parameters = [])
    {
        $qb = $this->getCountQuery($where, $useJoins);
    
        $qb = $this->applyDefaultFilters($qb, $parameters);
    
        $query = $qb->getQuery();
    
        return $query->getSingleScalarResult();
    }


    /**
     * Checks for unique values.
     *
     * @param string $fieldName  The name of the property to be checked
     * @param string $fieldValue The value of the property to be checked
     * @param int    $excludeId  Id of carousells to exclude (optional)
     *
     * @return boolean result of this check, true if the given carousel does not already exist
     */
    public function detectUniqueState($fieldName, $fieldValue, $excludeId = 0)
    {
        $qb = $this->getCountQuery('', false);
        $qb->andWhere('tbl.' . $fieldName . ' = :' . $fieldName)
           ->setParameter($fieldName, $fieldValue);
    
        $qb = $this->addExclusion($qb, $excludeId);
    
        $query = $qb->getQuery();
    
        $count = $query->getSingleScalarResult();
    
        return ($count == 0);
    }

    /**
     * Builds a generic Doctrine query supporting WHERE and ORDER BY.
     *
     * @param string  $where    The where clause to use when retrieving the collection (optional) (default='')
     * @param string  $orderBy  The order-by clause to use when retrieving the collection (optional) (default='')
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true)
     * @param boolean $slimMode If activated only some basic fields are selected without using any joins (optional) (default=false)
     *
     * @return QueryBuilder query builder instance to be further processed
     */
    public function genericBaseQuery($where = '', $orderBy = '', $useJoins = true, $slimMode = false)
    {
        // normally we select the whole table
        $selection = 'tbl';
    
        if (true === $slimMode) {
            // but for the slim version we select only the basic fields, and no joins
    
            $selection = 'tbl.id';
            
            
            $selection .= ', tbl.carouselName';
            $useJoins = false;
        }
    
        if (true === $useJoins) {
            $selection .= $this->addJoinsToSelection();
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($selection)
           ->from('RK\HelperModule\Entity\CarouselEntity', 'tbl');
    
        if (true === $useJoins) {
            $this->addJoinsToFrom($qb);
        }
    
        $this->genericBaseQueryAddWhere($qb, $where);
        $this->genericBaseQueryAddOrderBy($qb, $orderBy);
    
        return $qb;
    }

    /**
     * Adds WHERE clause to given query builder.
     *
     * @param QueryBuilder $qb    Given query builder instance
     * @param string       $where The where clause to use when retrieving the collection (optional) (default='')
     *
     * @return QueryBuilder query builder instance to be further processed
     */
    protected function genericBaseQueryAddWhere(QueryBuilder $qb, $where = '')
    {
        if (!empty($where)) {
            // Use FilterUtil to support generic filtering.
            //$qb->where($where);
    
            // Create filter configuration.
            $filterConfig = new FilterConfig($qb);
    
            // Define plugins to be used during filtering.
            $filterPluginManager = new FilterPluginManager(
                $filterConfig,
    
                // Array of plugins to load.
                // If no plugin with default = true given the compare plugin is loaded and used for unconfigured fields.
                // Multiple objects of the same plugin with different configurations are possible.
                [
                ],
    
                // Allowed operators per field.
                // Array in the form "field name => operator array".
                // If a field is not set in this array all operators are allowed.
                []
            );
    
            // Request object to obtain the filter string (only needed if the filter is set via GET or it reads values from GET).
            // We do this not per default (for now) to prevent problems with explicite filters set by blocks or content types.
            // TODO readd automatic request processing (basically replacing applyDefaultFilters() and addCommonViewFilters()).
            $request = null;
    
            // Name of filter variable(s) (filterX).
            $filterKey = 'filter';
    
            // initialise FilterUtil and assign both query builder and configuration
            $filterUtil = new FilterUtil($filterPluginManager, $request, $filterKey);
    
            // set our given filter
            $filterUtil->setFilter($where);
    
            // you could add explicit filters at this point, something like
            // $filterUtil->addFilter('foo:eq:something,bar:gt:100');
            // read more at https://github.com/zikula/core/tree/1.4/src/docs/Core-2.0/FilterUtil
    
            // now enrich the query builder
            $filterUtil->enrichQuery();
        }
    
        if (null === $this->getRequest()) {
            // if no request is set we return (#783)
            return $qb;
        }
    
        
        $showOnlyOwnEntries = $this->getRequest()->query->getInt('own', 0);
        if ($showOnlyOwnEntries == 1) {
            
            $userId = $this->getRequest()->getSession()->get('uid');
            $qb->andWhere('tbl.createdBy = :creator')
               ->setParameter('creator', $userId);
        }
    
        return $qb;
    }

    /**
     * Adds ORDER BY clause to given query builder.
     *
     * @param QueryBuilder $qb      Given query builder instance
     * @param string       $orderBy The order-by clause to use when retrieving the collection (optional) (default='')
     *
     * @return QueryBuilder query builder instance to be further processed
     */
    protected function genericBaseQueryAddOrderBy(QueryBuilder $qb, $orderBy = '')
    {
        if ($orderBy == 'RAND()') {
            // random selection
            $qb->addSelect('MOD(tbl.id, ' . mt_rand(2, 15) . ') AS HIDDEN randomIdentifiers')
               ->add('orderBy', 'randomIdentifiers');
            $orderBy = '';
        } elseif (empty($orderBy)) {
            $orderBy = $this->defaultSortingField;
        }
    
        // add order by clause
        if (!empty($orderBy)) {
            if (false === strpos($orderBy, '.')) {
                $orderBy = 'tbl.' . $orderBy;
            }
            if (false !== strpos($orderBy, 'tbl.createdBy')) {
                $qb->addSelect('tblCreator')
                   ->leftJoin('tbl.createdBy', 'tblCreator');
                $orderBy = str_replace('tbl.createdBy', 'tblCreator.uname', $orderBy);
            }
            if (false !== strpos($orderBy, 'tbl.updatedBy')) {
                $qb->addSelect('tblUpdater')
                   ->leftJoin('tbl.updatedBy', 'tblUpdater');
                $orderBy = str_replace('tbl.updatedBy', 'tblUpdater.uname', $orderBy);
            }
            $qb->add('orderBy', $orderBy);
        }
    
        return $qb;
    }

    /**
     * Retrieves Doctrine query from query builder, applying FilterUtil and other common actions.
     *
     * @param QueryBuilder $qb Query builder instance
     *
     * @return Query query instance to be further processed
     */
    public function getQueryFromBuilder(QueryBuilder $qb)
    {
        $query = $qb->getQuery();
    
        return $query;
    }

    /**
     * Helper method to add join selections.
     *
     * @return String Enhancement for select clause
     */
    protected function addJoinsToSelection()
    {
        $selection = ', tblCarouselItems';
    
        return $selection;
    }
    
    /**
     * Helper method to add joins to from clause.
     *
     * @param QueryBuilder $qb Query builder instance used to create the query
     *
     * @return QueryBuilder The query builder enriched by additional joins
     */
    protected function addJoinsToFrom(QueryBuilder $qb)
    {
        $qb->leftJoin('tbl.carouselItems', 'tblCarouselItems');
    
        return $qb;
    }
}
