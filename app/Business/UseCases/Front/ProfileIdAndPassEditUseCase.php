<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit\ProfileIdAndPassEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit\ProfileIdAndPassEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit\ProfileIdAndPassEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit\ProfileIdAndPassEditStoreInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit\ProfileIdAndPassEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit\ProfileIdAndPassEditStoreOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\Member;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class ProfileIdAndPassEditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfileIdAndPassEditUseCase implements ProfileIdAndPassEditInitializeInteractor, ProfileIdAndPassEditStoreInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * ProfileIdAndPassEditUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param UserAccountRepository $userAccountRepository
     */
    public function __construct(MemberRepository $memberRepository, UserAccountRepository $userAccountRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->userAccountRepository = $userAccountRepository;
    }

    /**
     * 初期表示
     *
     * @param ProfileIdAndPassEditInitializeInputPort $inputPort
     * @param ProfileIdAndPassEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileIdAndPassEditInitializeInputPort $inputPort, ProfileIdAndPassEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $member = $this->getLoggedInMember($inputPort);
        $userAccount = $member->getUserAccount();

        // 初期値セット
        $outputPort->mailAddress = $userAccount->getMailAddress();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録変更する
     *
     * @param ProfileIdAndPassEditStoreInputPort $inputPort
     * @param ProfileIdAndPassEditStoreOutputPort $outputPort
     * @throws BusinessException
     */
    public function store(ProfileIdAndPassEditStoreInputPort $inputPort, ProfileIdAndPassEditStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $userAccount = $member->getUserAccount();
        // メールアドレス重複チェック
        $this->checkDuplicateMailAddress($inputPort->mailAddress, $userAccount->getId());

        Data::mappingToObject($inputPort, $userAccount);
        $this->userAccountRepository->saveOrUpdate($userAccount, true);

        //ログ出力
        Log::infoOut();
    }

    /**
     * メールアドレスの重複チェックを行う
     *
     * @param string $mailAddress
     * @throws BusinessException
     */
    private function checkDuplicateMailAddress(string $mailAddress, int $userAccountId)
    {
        $userAccountSameMailAddress = $this->userAccountRepository->findByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class, ["mailAddress" => $mailAddress])
        );
        if (count($userAccountSameMailAddress) > 0) {
            foreach ($userAccountSameMailAddress as $userAccount) {
                if (($userAccount->getMember() !== null && $userAccount->getId() !== $userAccountId && $userAccount->getMember()->getStatus() !== Member::STATUS_WITHDRAWN_MEMBER)) {
                    // ユーザーアカウントに紐づく会員のステータスが退会済みでない場合はエラーへ
                    throw new BusinessException('duplication.mail_address');
                }
            }
        }
    }
}