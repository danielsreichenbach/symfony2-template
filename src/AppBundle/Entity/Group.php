<?php

namespace AppBundle\Entity;

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
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Returns the unique identifier for the Group entity
     *
     * @return integer unique identifier
     */
    public function getId()
    {
        return $this->id;
    }
}
