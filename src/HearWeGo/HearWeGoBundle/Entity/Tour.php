<?php

namespace HearWeGo\HearWeGoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tour
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HearWeGo\HearWeGoBundle\Entity\Repository\TourRepository")
 */
class Tour
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="This field must be filled")
     * 
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime")
     * @Assert\DateTime()
     */
    private $startdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="datetime")
     * @Assert\DateTime()
     */
    private $enddate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10)
     * @Assert\Length(max=10,maxMessage="Cannot be longer than 10 characters")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="discount", type="integer")
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="text")
     * @Assert\NotBlank(message="This field must be filled")
     * 
     */
    private $info;

    /**
     * @ORM\ManyToOne(targetEntity="HearWeGo\HearWeGoBundle\Entity\Company", inversedBy="tours")
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity="HearWeGo\HearWeGoBundle\Entity\Destination", mappedBy="tours")
     * @Assert\NotBlank(message="This field must be filled")
     * 
     */
    private $destinations;

    function __construct()
    {
        $this->destinations = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Tour
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     * @return Tour
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime 
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     * @return Tour
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * Get enddate
     *
     * @return \DateTime 
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Tour
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return Tour
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer 
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set info
     *
     * @param string $info
     * @return Tour
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set company
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Company $company
     * @return Tour
     */
    public function setCompany(\HearWeGo\HearWeGoBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \HearWeGo\HearWeGoBundle\Entity\Company 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add destinations
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Destination $destinations
     * @return Tour
     */
    public function addDestination(\HearWeGo\HearWeGoBundle\Entity\Destination $destinations)
    {
        $this->destinations[] = $destinations;

        return $this;
    }

    /**
     * Remove destinations
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Destination $destinations
     */
    public function removeDestination(\HearWeGo\HearWeGoBundle\Entity\Destination $destinations)
    {
        $this->destinations->removeElement($destinations);
    }

    /**
     * Get destinations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDestinations()
    {
        return $this->destinations;
    }
}