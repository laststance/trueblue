<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity("twitterId")
 */
class User extends OAuthUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bigint
     *
     * @ORM\Column(name="twitter_id", type="bigint", unique=true)
     */
    private $twitterId;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    protected $username;

    /**
     * @var bigint
     *
     * @ORM\Column(name="today_since_id", type="bigint", nullable=true)
     */
    private $todaySinceId;

    /**
     * @var PastTimeline
     *
     * @ORM\OneToMany(targetEntity="PastTimeline", mappedBy="user")
     */
    private $pastTimelines;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="since_id_at", type="datetime", nullable=true)
     */
    private $sinceIdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_initial_tweet_import", type="boolean")
     */
    private $isInitialTweetImport;

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

    public function __construct()
    {
        $this->pastTimelines = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set twitterId.
     *
     * @param int $twitterId
     *
     * @return User
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    /**
     * Get twitterId.
     *
     * @return int
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set todaySinceId.
     *
     * @return User
     */
    public function setTodaySinceId($todaySinceId)
    {
        $this->todaySinceId = $todaySinceId;

        return $this;
    }

    /**
     * Get todaySinceId.
     *
     * @return int
     */
    public function getTodaySinceId()
    {
        return $this->todaySinceId;
    }

    /**
     * Set sinceIdAt.
     *
     * @param \DateTime $sinceIdAt
     *
     * @return User
     */
    public function setSinceIdAt(\DateTime $sinceIdAt)
    {
        $this->sinceIdAt = $sinceIdAt;

        return $this;
    }

    /**
     * Get sinceIdAt.
     *
     * @return \DateTime
     */
    public function getSinceIdAt()
    {
        return $this->sinceIdAt;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isInitialTweetImport
     */
    public function setIsInitialTweetImport(bool $isInitialTweetImport)
    {
        $this->isInitialTweetImport = $isInitialTweetImport;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsInitialTweetImport(): bool
    {
        return $this->isInitialTweetImport;
    }

    /**
     * Set createAt.
     *
     * @param \DateTime $createAt
     *
     * @return User
     */
    public function setCreateAt(\DateTime $createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt.
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set updateAt.
     *
     * @param \DateTime $updateAt
     *
     * @return User
     */
    public function setUpdateAt(\DateTime $updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt.
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Add pastTimelines.
     *
     * @param \AppBundle\Entity\PastTimeline $pastTimelines
     *
     * @return User
     */
    public function addPastTimeline(\AppBundle\Entity\PastTimeline $pastTimelines)
    {
        $this->pastTimelines[] = $pastTimelines;

        return $this;
    }

    /**
     * Remove pastTimelines.
     *
     * @param \AppBundle\Entity\PastTimeline $pastTimelines
     */
    public function removePastTimeline(\AppBundle\Entity\PastTimeline $pastTimelines)
    {
        $this->pastTimelines->removeElement($pastTimelines);
    }

    /**
     * Get pastTimelines.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPastTimelines()
    {
        return $this->pastTimelines;
    }
}
