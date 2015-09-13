<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\IpTraceable\Traits\IpTraceableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;


/**
 * User
 *
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity("username")
 * @Serializer\ExclusionPolicy("all")
 */
class User extends BaseUser implements UserInterface
{
    use TimestampableEntity;
    use IpTraceableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     */
    protected $id;

    /**
     * @var string $firstName
     *
     * @Assert\NotBlank(message="Merci de préciser votre prénom.", groups={"Registration"})
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Serializer\Expose
     */
    protected $firstName;

    /**
     * @var string $lastName
     *
     * @Assert\NotBlank(message="Merci de préciser votre nom.", groups={"Registration"})
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Serializer\Expose
     */
    protected $lastName;

    /**
     * @var string
     *
     * String corresponding to the photo of the user encoded in base64
     *
     * @ORM\Column(name="photo", type="text", nullable=true)
     * @Serializer\Expose
     */
    protected $photo;

    /**
     * @var House
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\House", cascade={"persist"}, inversedBy="members")
     * @ORM\JoinColumn(name="house_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $house;

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return User
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set house
     *
     * @param \AppBundle\Entity\House $house
     * @return User
     */
    public function setHouse(\AppBundle\Entity\House $house = null)
    {
        $this->house = $house;

        return $this;
    }

    /**
     * Get house
     *
     * @return \AppBundle\Entity\House 
     */
    public function getHouse()
    {
        return $this->house;
    }
}
