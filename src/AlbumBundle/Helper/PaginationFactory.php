<?php


namespace AlbumBundle\Helper;

use AlbumBundle\Entity\PaginatedCollection;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Reusable Pagination System
 *
 * Class PaginationFactory
 * @package AlbumBundle\Helper
 */
class PaginationFactory
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * PaginationFactory constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Query $qb
     * @param Request $request
     * @param $maxPerPage
     * @return array
     */
    private static function getItems(Query $qb, Request $request, $maxPerPage)
    {
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        $page = $request->query->get('page', 1);
        $pagerfanta->setMaxPerPage($maxPerPage);
        if ($page == 0) {
            $page = 1;
        }
        $pagerfanta->setCurrentPage($page);

        $results = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $results[] = $result;
        }

        return array($pagerfanta, $page, $results);
    }

    /**
     * Create a collection for any given request.
     *
     * @param Query $qb
     * @param Request $request
     * @param $maxPerPage
     * @param $route
     * @param $slug
     * @param array $routeParameters
     *
     * @return PaginatedCollection
     */
    public function createCollectionBySlug(Query $qb, Request $request, $maxPerPage, $route, $slug,
                                     array $routeParameters = array()) {

        list($pagerfanta, $page, $results) = self::getItems($qb, $request, $maxPerPage);

        $paginatedCollection = new PaginatedCollection($results, $pagerfanta->getNbPages());

        $createLinkUrl = function ($slug, $targetPage) use ($route, $routeParameters) {
            return $this->router->generate($route, array_merge($routeParameters,
                array('slug' => $slug, 'page' => $targetPage)));
        };

        $paginatedCollection->addLink('self', $createLinkUrl($slug, $page));
        $paginatedCollection->addLink('first', $createLinkUrl($slug, 1));
        $paginatedCollection->addLink('last', $createLinkUrl($slug, $pagerfanta->getNbPages()));

        if ($pagerfanta->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($slug, $pagerfanta->getNextPage()));
        }
        if ($pagerfanta->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($slug, $pagerfanta->getPreviousPage()));
        }

        return $paginatedCollection;
    }

    /**
     * Create a collection for any given request.
     *
     * @param Query $qb
     * @param Request $request
     * @param $maxPerPage
     * @param $route
     * @param array $routeParameters
     *
     * @return PaginatedCollection
     */
    public function createCollection(Query $qb, Request $request, $maxPerPage, $route,
                                     array $routeParameters = array()) {

        list($pagerfanta, $page, $results) = self::getItems($qb, $request, $maxPerPage);

        $paginatedCollection = new PaginatedCollection($results, $pagerfanta->getNbPages());

        $createLinkUrl = function ($targetPage) use ($route, $routeParameters) {
            return $this->router->generate($route, array_merge($routeParameters,
                array('page' => $targetPage)));
        };

        $paginatedCollection->addLink('self', $createLinkUrl($page));
        $paginatedCollection->addLink('first', $createLinkUrl(1));
        $paginatedCollection->addLink('last', $createLinkUrl($pagerfanta->getNbPages()));

        if ($pagerfanta->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($pagerfanta->getNextPage()));
        }
        if ($pagerfanta->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($pagerfanta->getPreviousPage()));
        }

        return $paginatedCollection;
    }
}