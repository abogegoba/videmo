<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Front\ProfileAddressEdit\ProfileAddressEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileAddressEdit\ProfileAddressEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileAddressEdit\ProfileAddressEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfileAddressEdit\ProfileAddressEditStoreInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileAddressEdit\ProfileAddressEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileAddressEdit\ProfileAddressEditStoreOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\Member;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class ProfileAddressEditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfileAddressEditUseCase implements ProfileAddressEditInitializeInteractor, ProfileAddressEditStoreInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * MemberHistoryListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param PrefectureRepository $prefectureRepository
     */
    public function __construct(MemberRepository $memberRepository,PrefectureRepository $prefectureRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->prefectureRepository = $prefectureRepository;
    }

    /**
     * 初期表示
     *
     * @param ProfileAddressEditInitializeInputPort $inputPort
     * @param ProfileAddressEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileAddressEditInitializeInputPort $inputPort, ProfileAddressEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $outputPort->zipCode = $member->getZipCode();
        $outputPort->prefecture = $member->getPrefecture()->getId();
        $outputPort->city = $member->getCity();
        $outputPort->blockNumber = $member->getBlockNumber();
        $outputPort->phoneNumber = $member->getPhoneNumber();
        $outputPort->country = $member->getCountry();
        if($member->getCountry() > 1){
            $outputPort->overseasList = Member::CITIZENSHIP_OVERSEAS_LIST;
        }
        // 都道府県リスト
        $prefectures = $this->prefectureRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $prefectureNameList = array_column($prefectures, "name");
        $prefectureIdList = array_column($prefectures, "id");
        $outputPort->prefectureList = array_combine($prefectureIdList, $prefectureNameList);

        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録変更する
     *
     * @param ProfileAddressEditStoreInputPort $inputPort
     * @param ProfileAddressEditStoreOutputPort $outputPort
     */
    public function store(ProfileAddressEditStoreInputPort $inputPort, ProfileAddressEditStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $member = $this->getLoggedInMember($inputPort);

        // 変更する
        $prefectureRepository = $this->prefectureRepository;
        Data::mappingToObject($inputPort, $member, [
            // 都道府県
            "prefecture" => function ($value, $inputPort, $toObject) use ($prefectureRepository) {
                try {
                    $prefecture = $prefectureRepository->findOneByCriteria(
                        CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $value]));
                    /** @var Member $toObject */
                    $toObject->setPrefecture($prefecture);
                } catch (ObjectNotFoundException $e) {
                    throw new FatalBusinessException("select_target_not_found");
                }
            }
        ]);
        $this->memberRepository->saveOrUpdate($member, true);

        // 操作ログ
        Log::infoOperationUpdateLog("", ["member" => (array)$member], '');

        //ログ出力
        Log::infoOut();
    }
}
