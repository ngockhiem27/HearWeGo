<?php

namespace HearWeGo\HearWeGoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rating
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="HearWeGo\HearWeGoBundle\Entity\Repository\RatingRepository")
 */
class Rating
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="stars", type="integer")
     * @Assert\NotBlank(message="This field must be filled")
     * 
     * @Assert\GreaterThanOrEqual(value=0,message="Rating must be between 0 and 5 stars")
     * @Assert\LessThanOrEqual(value=5,message="Rating must be between 0 and 5 stars")
     */
    private $stars;

    /**
     * @ORM\ManyToOne(targetEntity="HearWeGo\HearWeGoBundle\Entity\User", inversedBy="rates")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id",onDelete="CASCADE")
     * @Assert\NotBlank(message="This field must be filled")
     * 
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="HearWeGo\HearWeGoBundle\Entity\Audio", inversedBy="rates")
     * @ORM\JoinColumn(name="audio_id",referencedColumnName="id",onDelete="CASCADE")
     * @Assert\NotBlank(message="This field must be filled")
     * 
     */
    private $audio;

    function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set stars
     *
     * @param integer $stars
     * @return Rating
     */
    public function setStars($stars)
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * Get stars
     *
     * @return integer 
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * Set user
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\User $user
     * @return Rating
     */
    public function setUser(\HearWeGo\HearWeGoBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \HearWeGo\HearWeGoBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set audio
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Audio $audio
     * @return Rating
     */
    public function setAudio(\HearWeGo\HearWeGoBundle\Entity\Audio $audio = null)
    {
        $this->audio = $audio;

        return $this;
    }

    /**
     * Get audio
     *
     * @return \HearWeGo\HearWeGoBundle\Entity\Audio 
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Rating
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
