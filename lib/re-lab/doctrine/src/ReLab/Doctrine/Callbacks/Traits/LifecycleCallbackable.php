<?php
namespace ReLab\Doctrine\Callbacks\Traits;

use App\Domain\Model\AdminAuthentication;
use App\Domain\Model\MemberAuthentication;
use Doctrine\ORM\Event\LifecycleEventArgs;

/** @Entity @HasLifecycleCallbacks */
trait LifecycleCallbackable
{
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
     * @ORM\prePersist
     */
    public function doPrePersist()
    {
        // 当該アカウントのユーザーIDを作成者IDに追加
        $adminAuthentication = AdminAuthentication::loadSession();
        $memberAuthentication = MemberAuthentication::loadSession();
        if (!empty($adminAuthentication)) {
            $this->createdBy = $adminAuthentication->getUserAccountId();
        }elseif (!empty($memberAuthentication)){
            $this->createdBy = $memberAuthentication->getUserAccountId();
        }
    }

    /**
     * @ORM\prePersist
     * @ORM\preUpdate
     */
    public function doPreUpdate()
    {
        // 当該アカウントのユーザーIDを更新者IDに追加
        $adminAuthentication = AdminAuthentication::loadSession();
        $memberAuthentication = MemberAuthentication::loadSession();
        if (!empty($adminAuthentication)) {
            $this->updatedBy = $adminAuthentication->getUserAccountId();
        }elseif (!empty($memberAuthentication)){
            $this->updatedBy = $memberAuthentication->getUserAccountId();
        }
    }
}