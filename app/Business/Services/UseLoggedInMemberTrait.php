<?php

namespace App\Business\Services;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Trait UseLoggedInMemberTrait
 *
 * ログインメンバー取得トレイト（UseCase専用）
 *
 * @package App\Business\Services
 */
trait UseLoggedInMemberTrait
{
    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * MemberHistoryListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     */
    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * ログイン済みの会員を取得する
     *
     * @param UseLoggedInMemberInputPort $inputPort
     * @return \App\Domain\Entities\Member
     */
    protected function getLoggedInMember(UseLoggedInMemberInputPort $inputPort)
    {
        $loggedInMember = $this->memberRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->loggedInMemberId,
                ]
            )
        );
        return $loggedInMember;
    }
}