<?php
namespace HearWeGo\HearWeGoBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Audio
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="HearWeGo\HearWeGoBundle\Entity\Repository\AudioRepository")
 */
class Audio
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
     * @var string
     *
     * @ORM\Column(name="audiopath", type="string", length=255)
     * @Assert\NotBlank(message="This field must be filled")
     *
     */
    private $audiopath;
    /**
     * @Assert\File( maxSize="600000")
     */
    private $audio;
    /**
     * @ORM\Column(type="datetime")
     */

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    private $createdAt;
    /**
     * @ORM\OneToMany(targetEntity="HearWeGo\HearWeGoBundle\Entity\Rating", mappedBy="audio")
     */
    private $rates;
    /**
     * @ORM\OneToOne(targetEntity="HearWeGo\HearWeGoBundle\Entity\Destination", inversedBy="audio")
     * @ORM\JoinColumn(name="destination_id",referencedColumnName="id",onDelete="CASCADE")
     * @Assert\NotBlank(message="This field must be filled")
     */
    private $destination;
    /**
     * Get id
     *
     * @return integer
     */
    /**
     * @ORM\OneToMany(targetEntity="HearWeGo\HearWeGoBundle\Entity\Orders", mappedBy="audios")
     */
    private $orders;

    /**
     * @ORM\Column(type="integer")
     */
    private $sales;

    public function getId()
    {
        return $this->id;
    }
    /**
     * Set name
     *
     * @param string $name
     * @return Audio
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
     * Constructor
     */
    public function __construct()
    {
        $this->rates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->sales = 0;
    }
    /**
     * Add rates
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Rating $rates
     * @return Audio
     */
    public function addRate(\HearWeGo\HearWeGoBundle\Entity\Rating $rates)
    {
        $this->rates[] = $rates;
        return $this;
    }
    /**
     * Remove rates
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Rating $rates
     */
    public function removeRate(\HearWeGo\HearWeGoBundle\Entity\Rating $rates)
    {
        $this->rates->removeElement($rates);
    }
    /**
     * Get rates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRates()
    {
        return $this->rates;
    }
    /**
     * Set destination
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Destination $destination
     * @return Audio
     */
    public function setDestination(\HearWeGo\HearWeGoBundle\Entity\Destination $destination = null)
    {
        $this->destination = $destination;
        return $this;
    }
    /**
     * Get destination
     *
     * @return \HearWeGo\HearWeGoBundle\Entity\Destination
     */
    public function getDestination()
    {
        return $this->destination;
    }
    /**
     * Add orders
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Order $orders
     * @return Audio
     */
    public function addOrder(\HearWeGo\HearWeGoBundle\Entity\Orders $orders)
    {
        $this->orders[] = $orders;
        return $this;
    }
    /**
     * Remove orders
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Order $orders
     */
    public function removeOrder(\HearWeGo\HearWeGoBundle\Entity\Orders $orders)
    {
        $this->orders->removeElement($orders);
    }
    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
    /**
     * @return string
     */
    public function getAudiopath()
    {
        return $this->audiopath;
    }
    /**
     * @param string $audiopath
     */
    public function setAudiopath($audiopath)
    {
        $this->audiopath = $audiopath;
    }
    /**
     * @return mixed
     */
    public function getAudio()
    {
        return $this->audio;
    }
    /**
     * @param mixed $audio
     */
    public function setAudio($audio)
    {
        $this->audio = $audio;
    }
    public function getAbsolutePath()
    {
        return null === $this->audiopath
            ? null
            : $this->getUploadRootDir().'/'.$this->audiopath;
    }
    public function getWebPath()
    {
        return null === $this->audiopath
            ? null
            : $this->getUploadDir().'/'.$this->audiopath;
    }
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/bundles/hearwegohearwego/uploads/audio';
    }
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getAudio()) {
            return;
        }
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        $this->getAudio()->move(
            $this->getUploadRootDir(),
            $this->getAudio()->getClientOriginalName()
        );
        // set the path property to the filename where you've saved the file
        $this->audiopath = $this->getAudio()->getClientOriginalName();
        // clean up the file property as you won't need it anymore
        $this->audio = null;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Audio
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

    public function increaseSale(){
        $this->sales += 1;
    }

    /**
     * Set sales
     *
     * @param integer $sales
     * @return Audio
     */
    public function setSales($sales)
    {
        $this->sales = $sales;

        return $this;
    }

    /**
     * Get sales
     *
     * @return integer 
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Audio
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }
}
