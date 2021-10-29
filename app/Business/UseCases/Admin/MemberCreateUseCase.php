<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateStoreInputPort;
use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateStoreOutputPort;
use App\Business\Services\MemberAdminServiceTrait;
use App\Domain\Entities\Member;
use App\Domain\Entities\SelfIntroduction;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\FatalBusinessException;

/**
 * Class MemberCreateUseCase
 *
 * 会員を登録する
 *
 * @package App\Business\UseCases\Admin
 */
class MemberCreateUseCase implements MemberCreateInitializeInteractor, MemberCreateStoreInteractor
{
    use MemberAdminServiceTrait;

    /**
     * 初期化する
     *
     * @param MemberCreateInitializeInputPort $inputPort
     * @param MemberCreateInitializeOutputPort $outputPort
     */
    public function initialize(MemberCreateInitializeInputPort $inputPort, MemberCreateInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // 選択肢をアプトプットする
        $this->outputChoiceLists($outputPort);

        // 自己紹介
        $selfIntroductions = [];
        for ($i = 1; $i <= 10; $i++) {
            $selfIntroductions[$i]['title'] = SelfIntroduction::SELF_TITLE_LIST[$i];
            $selfIntroductions[$i]['content'] = '';
        }
        $outputPort->selfIntroductions = $selfIntroductions;

        // ログ出力
        Log::infoOut();
    }

    /**
     * 登録する
     *
     * @param MemberCreateStoreInputPort $inputPort
     * @param MemberCreateStoreOutputPort $outputPort
     * @throws BusinessException
     * @throws FatalBusinessException
     */
    public function store(MemberCreateStoreInputPort $inputPort, MemberCreateStoreOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        $member = new Member();
        $this->saveOrUpdate($member, $inputPort);
        $outputPort->memberId = $member->getId();

        // ログ出力
        Log::infoOut();
    }
}