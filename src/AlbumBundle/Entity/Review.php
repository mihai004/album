<?php


namespace AlbumBundle\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * Review
 *
 * @ORM\Table(name="`review`")
 * @ORM\Entity(repositoryClass="AlbumBundle\Repository\ReviewRepository")
 */
class Review
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="review", type="string", type="text", nullable=false)
     */
    private $review;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Range(min=0, max=5)
     *
     * @ORM\Column(name="rating", type="string")
     */
    private $rating = 0;

    /**
     *
     * @var DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var User
     *
     *
     * @ORM\ManyToOne(targetEntity="AlbumBundle\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(name="reviewer", referencedColumnName="id", nullable=false)
     */
    private $reviewer;

    /**
     * @var Album
     *
     * This is the owning side of the relationship.
     *
     * @ORM\ManyToOne(targetEntity="AlbumBundle\Entity\Album", inversedBy="reviews")
     * @ORM\JoinColumn(name="album", referencedColumnName="id", nullable=false)
     */
    private $album;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     * @return string
     */
    public function setTitle($title)
    {
        if(empty($title)) {
            return $this->title = "Unspecified title";
        }
        return $this->title = $title;
    }

    /**
     * @return string
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param string
     */
    public function setReview($review)
    {
        $this->review = $review;
    }

    /**
     * Get rating.
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return DateTime|string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param DateTime $timestamp
     *
     * @return Review
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return User
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }

    /**
     * @param User $reviewer
     */
    public function setReviewer($reviewer)
    {
        $this->reviewer = $reviewer;
    }

    /**
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     *
     * @param Album $album
     */
    public function setAlbum(Album $album)
    {
        $this->album = $album;
    }
}
