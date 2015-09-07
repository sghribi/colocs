<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\IpTraceable\Traits\IpTraceableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

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
}
