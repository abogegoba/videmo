<?php

namespace ReLab\Domain\Properties;

use Carbon\Carbon;
use ReLab\Doctrine\Callbacks\Traits\LifecycleCallbackable;

/**
 * Trait Editable
 *
 * @package ReLab\Domain\Properties
 */
trait Editable
{
    use Readable;
    use LifecycleCallbackable;

    /**
     * 作成日時
     *
     * @var null|Carbon
     *
     * @ORM\Column(
     *     name="created_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="作成日時"})
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * 作成者ID
     *
     * @var null|int
     *
     * @ORM\Column(
     *     name="created_by",
     *     type="integer",
     *     nullable=true,
     *     options={"comment"="作成者ID"})
     */
    private $createdBy;

    /**
     * 更新日時
     *
     * @var null|Carbon
     *
     * @ORM\Column(
     *     name="updated_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="更新日時"})
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * 更新者ID
     *
     * @var null|int
     *
     * @ORM\Column(
     *     name="updated_by",
     *     type="integer",
     *     nullable=true,
     *     options={"comment"="更新者ID"})
     */
    private $updatedBy;

    /**
     * 削除日時
     *
     * @var null|Carbon
     *
     * @ORM\Column(
     *     name="deleted_at",
     *     type="datetime",
     *     nullable=true,
     *     options={"comment"="削除日時"})
     */
    private $deletedAt;

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAt(): ?Carbon
    {
        return new Carbon($this->createdAt->format("Y-m-d H:i:s"));
    }

    /**
     * @param Carbon|null $createdAt
     */
    public function setCreatedAt(?Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int|null
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     * @param int|null $createdBy
     */
    public function setCreatedBy(?int $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return Carbon|null
     */
    public function getUpdatedAt(): ?Carbon
    {
        return new Carbon($this->updatedAt->format("Y-m-d H:i:s"));
    }

    /**
     * @param Carbon|null $updatedAt
     */
    public function setUpdatedAt(?Carbon $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int|null
     */
    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    /**
     * @param int|null $updatedBy
     */
    public function setUpdatedBy(?int $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return Carbon|null
     */
    public function getDeletedAt(): ?Carbon
    {
        return $this->deletedAt;
    }

    /**
     * @param Carbon|null $deletedAt
     */
    public function setDeletedAt(?Carbon $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * 復活する
     */
    public function restore(): void
    {
        $this->deletedAt = null;
    }

    /**
     * 削除済みか確認する
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deletedAt && Carbon::now() >= $this->deletedAt;
    }
}