<?php


namespace ReviewBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query as QueryAlias;

/**
 * In order to isolate, reuse and test Review queries, it is a good practice to create a custom repository class for
 * the ReviewBundle entity.
 *
 * Class ReviewRepository
 * @package ReviewBundle\Repository
 */
class ReviewRepository extends EntityRepository
{
    /**
     * Returns all the review for a given albumID.
     *
     * @param $albumID
     *
     * @return QueryAlias
     */
    public function getReviewsByAlbumID($albumID) {

        $qb = $this->createQueryBuilder('review');
        $qb->where('review.album = :albumID')
            ->orderBy('review.timestamp', 'DESC')
            ->setParameter(':albumID', $albumID);

        return $qb->getQuery();
    }
}