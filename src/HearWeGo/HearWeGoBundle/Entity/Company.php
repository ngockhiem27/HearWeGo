<?php

namespace HearWeGo\HearWeGoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Company
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HearWeGo\HearWeGoBundle\Entity\Repository\CompanyRepository")
 * @UniqueEntity("email")
 */
class Company implements UserInterface
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
     * @ORM\Column(name="email", type="string", length=64,unique=true)
     * @Assert\NotBlank(message="This field must be filled")
     * 
     * @Assert\Email(message="Not a valid email")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(message="This field must be filled")
     * 
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=13)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="HearWeGo\HearWeGoBundle\Entity\Tour", mappedBy="company")
     */
    /**
     * @ORM\Column(type="string")
     */
    private $roles;

    private $tours;

    function __construct()
    {
        $this->tours = new ArrayCollection();
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
     * @return Company
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
     * Set email
     *
     * @param string $email
     * @return Company
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Company
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Company
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Company
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add tours
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Tour $tours
     * @return Company
     */
    public function addTour(\HearWeGo\HearWeGoBundle\Entity\Tour $tours)
    {
        $this->tours[] = $tours;

        return $this;
    }

    /**
     * Remove tours
     *
     * @param \HearWeGo\HearWeGoBundle\Entity\Tour $tours
     */
    public function removeTour(\HearWeGo\HearWeGoBundle\Entity\Tour $tours)
    {
        $this->tours->removeElement($tours);
    }

    /**
     * Get tours
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTours()
    {
        return $this->tours;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Company
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt(){
        return null;
    }

    public function getUsername(){
        return $this->email;
    }

    public function eraseCredentials(){

    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return Company
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return array('ROLE_COMPANY');
    }
}