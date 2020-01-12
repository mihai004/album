<?php


namespace TrackBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query as QueryAlias;

/**
 * Class TrackRepository
 * @package TrackBundle\Repository
 */
class TrackRepository extends EntityRepository
{
    /**
     * Return tracks by ID.
     *
     * @param $albumID
     *
     * @return QueryAlias
     */
    public function getTracksByAlbumID($albumID) {

        $qb = $this->createQueryBuilder('track');
        $qb->where('track.album = :albumID')
            ->setParameter(':albumID', $albumID);

        return $qb->getQuery();
    }
}