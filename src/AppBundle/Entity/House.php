<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\IpTraceable\Traits\IpTraceableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Oh\GoogleMapFormTypeBundle\Validator\Constraints as OhAssert;


/**
 * Article
 *
 * @ORM\Entity
 * @ORM\Table(name="house")
 * @Serializer\ExclusionPolicy("all")
 */
class House
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text", nullable=true)
     * @Serializer\Expose
     */
    protected $summary;

    /**
     * @var ArrayCollection $categories
     *
     * @Assert\NotNull(message="Veuillez sélectionner un type de logement")
     * @ORM\Column(name="type", type="integer", nullable=false)
     * @Serializer\Expose
     */
    protected $type = self::COLOC;

    const COLOC = 0;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", cascade={"persist", "remove"}, mappedBy="house")
     * @Serializer\Expose
     */
    protected $members;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=false)
     * @Serializer\Expose
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal", precision=10, scale=6, nullable=true)
     * @Assert\NotBlank(groups={"position"})
     * @Serializer\Expose
     */
    protected $latitude;
    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", precision=10, scale=6, nullable=true)
     * @Assert\NotBlank(groups={"position"})
     * @Serializer\Expose
     */
    protected $longitude;


    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
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
     * Constructor
     */
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return House
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
     * Set summary
     *
     * @param string $summary
     * @return House
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return House
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return House
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
     * Set latitude
     *
     * @param string $latitude
     * @return House
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return House
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Add members
     *
     * @param \AppBundle\Entity\User $members
     * @return House
     */
    public function addMember(\AppBundle\Entity\User $members)
    {
        $this->members[] = $members;
        $members->setHouse($this);

        return $this;
    }

    /**
     * Remove members
     *
     * @param \AppBundle\Entity\User $members
     */
    public function removeMember(\AppBundle\Entity\User $members)
    {
        $this->members->removeElement($members);
        $members->setHouse(null);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
    }

    public function setLatLng($latlng)
    {
        $this->setLatitude($latlng['lat']);
        $this->setLongitude($latlng['lng']);

        return $this;
    }

    /**
     * @Assert\NotBlank()
     * @OhAssert\LatLng()
     */
    public function getLatLng()
    {
        return array(
            'lat' => $this->getLatitude(),
            'lng' => $this->getLongitude()
        );
    }
}
