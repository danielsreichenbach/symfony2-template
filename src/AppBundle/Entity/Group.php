<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Defines the properties of the Group entity to group application users.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupRepository")
 * @ORM\Table(name="fos_groups")
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class Group extends BaseGroup
{
    /**
     * The unique identifier
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * Set up the Group entity
     *
     * @param string $name
     * @param array  $roles
     */
    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles = array());
    }

    /**
     * Returns the unique identifier for the Group entity
     *
     * @return integer unique identifier
     */
    public function getId()
    {
        return $this->id;
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
}
