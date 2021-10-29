<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Interactors\Admin\MemberDelete\MemberDeleteInputPort;
use App\Business\Interfaces\Interactors\Admin\MemberDelete\MemberDeleteInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberDelete\MemberDeleteOutputPort;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\AlreadyInuseException;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class MemberDeleteUseCase
 *
 * @property  MemberRepository
 * @package App\Business\UseCases\Admin
 */
class MemberDeleteUseCase implements MemberDeleteInteractor
{
    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * MemberDeleteUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     */
    public function __construct(
        MemberRepository $memberRepository
    ) {
        $this->memberRepository = $memberRepository;
    }

    /**
     * 削除する
     *
     * @param MemberDeleteInputPort $inputPort
     * @param MemberDeleteOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function destroy(MemberDeleteInputPort $inputPort, MemberDeleteOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 削除対象の存在確認
        $memberId = $inputPort->memberId;
        try {
            $deletedMember = $this->memberRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $memberId
                    ],
                    [
                        "forUpdate" => true
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            // 削除対象が見つからない場合に例外
            throw new FatalBusinessException("delete_target_not_found");
        }

        // 会員に紐づく志望業種を解除
        $aspirationBusinessTypes = $deletedMember->getAspirationBusinessTypes();
        if (count($aspirationBusinessTypes) > 0) {
            foreach ($aspirationBusinessTypes as $aspirationBusinessType) {
                $aspirationBusinessTypes->removeElement($aspirationBusinessType);
            }
        }

        // 会員に紐づく志望職種を解除
        $aspirationJobTypes = $deletedMember->getAspirationJobTypes();
        if (count($aspirationJobTypes) > 0) {
            foreach ($aspirationJobTypes as $aspirationJobType) {
                $aspirationJobTypes->removeElement($aspirationJobType);
            }
        }

        // 会員に紐づく志望勤務地を解除
        $aspirationPrefectures = $deletedMember->getAspirationPrefectures();
        if (count($aspirationPrefectures) > 0) {
            foreach ($aspirationPrefectures as $aspirationPrefecture) {
                $aspirationPrefectures->removeElement($aspirationPrefecture);
            }
        }

        $this->memberRepository->saveOrUpdate($deletedMember, true);

        // 会員を削除
        $this->memberRepository->delete($deletedMember);

        //ログ出力
        Log::infoOut();
    }
}