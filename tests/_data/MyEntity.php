<?php

/**
 * Class MyEntity
 *
 * @package    _data
 * @subpackage _data\MyEntity
 * @author     Dave Redfern
 */
class MyEntity
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Carbon\Carbon
     */
    protected $createdAt;

    /**
     * Constructor.
     *
     * @param string         $name
     * @param \Carbon\Carbon $createdAt
     */
    public function __construct($name = null, \Carbon\Carbon $createdAt = null)
    {
        $this->name      = $name;
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \Carbon\Carbon
     */
    public function getCreatedAt(): \Carbon\Carbon
    {
        return $this->createdAt;
    }

    /**
     * @param \Carbon\Carbon $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
