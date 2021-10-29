<?php

namespace ReLab\Domain\Properties;

/**
 * Trait Readable
 *
 * @package ReLab\Domain\Properties
 */
trait Readable
{
    /**
     * ID
     *
     * @var null|int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     options={"comment" = "ID"})
     */
    private $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}