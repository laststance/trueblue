<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * PastTimeline.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PastTimelineRepository")
 * @UniqueEntity("date")
 */
class PastTimeline
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
     * @var User
     *
     *@ORM\ManyToOne(targetEntity="User", inversedBy="pastTimelines", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", unique=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="timeline", type="json_array")
     */
    private $timeline;

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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return PastTimeline
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return PastTimeline
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set timelineJson.
     *
     * @param array $timeline
     *
     * @return PastTimeline
     */
    public function setTimeline(array $timeline)
    {
        $this->timeline = $timeline;

        return $this;
    }

    /**
     * Get timelineJson.
     *
     * @return array
     */
    public function getTimeline(): array
    {
        return $this->timeline;
    }

    /**
     * Set createAt.
     *
     * @param \DateTime $createAt
     *
     * @return PastTimeline
     */
    public function setCreateAt($createAt)
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
     * @return PastTimeline
     */
    public function setUpdateAt($updateAt)
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
}
