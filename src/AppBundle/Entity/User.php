<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Defines the properties of the User entity to represent the application users.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_users")
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class User extends BaseUser
{
    /**
     * The unique identifier
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The users preferred site locale
     *
     * @var string
     *
     * @Assert\Locale()
     * @ORM\Column(name="locale", type="string", length=2, nullable=false)
     */
    protected $locale;

    /**
     * Entity creation time stamp
     *
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * Entity last update time stamp
     *
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * List of Group entities to which a User is assigned
     *
     * @var Group|[]
     *
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="fos_users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var Invitation
     *
     * @Assert\NotNull(message="user.invitation.invalid", groups={"AppRegistration"})
     * @ORM\OneToOne(targetEntity="Invitation", inversedBy="user")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $invitation;

    /**
     * Set up the User entity
     */
    public function __construct()
    {
        parent::__construct();

        $this->locale = 'en';
    }

    /**
     * Returns the unique identifier for the User entity
     *
     * @return integer unique identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the users preferred site locale
     *
     * @param string $locale
     *
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get the users preferred site locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Returns the entities creation time stamp
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Returns the entities last update time stamp
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Returns the users invitation code
     *
     * @return Invitation|null
     */
    public function getInvitation()
    {
        return $this->invitation;
    }

    /**
     * Set the users invittaion code
     *
     * @param Invitation $invitation
     */
    public function setInvitation(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
