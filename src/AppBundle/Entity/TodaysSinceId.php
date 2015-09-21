<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TodaysSinceId
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TodaysSinceIdRepository")
 */
class TodaysSinceId
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
     * @ORM\Column(name="since_id", type="string", length=255)
     */
    private $sinceId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_at", type="datetime")
     */
    private $updateAt;


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
     * Set sinceId
     *
     * @param string $sinceId
     * @return TodaysSinceId
     */
    public function setSinceId($sinceId)
    {
        $this->sinceId = $sinceId;

        return $this;
    }

    /**
     * Get sinceId
     *
     * @return string 
     */
    public function getSinceId()
    {
        return $this->sinceId;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     * @return TodaysSinceId
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime 
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     * @return TodaysSinceId
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime 
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }
}
