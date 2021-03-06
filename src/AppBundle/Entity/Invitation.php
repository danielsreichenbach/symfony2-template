<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Defines invitations required to create a user entity.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvitationRepository")
 * @ORM\Table(name="fos_invitations")
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class Invitation
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
     * @var string
     *
     * @ORM\Column(type="string", length=6, unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    protected $email;

    /**
     * When sending invitations, use this value to mark an invitation.
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $sent = false;

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
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User", mappedBy="invitation", cascade={"persist", "merge"})
     */
    protected $user;

    /**
     * Set up the Invitation entity
     */
    public function __construct()
    {
        $this->code = substr(md5(uniqid(rand(), true)), 0, 6);
    }

    /**
     * Returns the unique identifier for the Invitation entity
     *
     * @return integer unique identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the invite code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Returns the email address the invitation is limited to
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns true if an invitation has been sent already.
     *
     * @return boolean
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * @param boolean $sent
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
