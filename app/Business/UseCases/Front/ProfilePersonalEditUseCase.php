<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit\ProfilePersonalEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit\ProfilePersonalEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit\ProfilePersonalEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit\ProfilePersonalEditStoreInputPort;
use App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit\ProfilePersonalEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit\ProfilePersonalEditStoreOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Utilities\Log;
use ReLab\Commons\Wrappers\Data;

/**
 * Class ProfilePersonalEditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfilePersonalEditUseCase implements ProfilePersonalEditInitializeInteractor, ProfilePersonalEditStoreInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * 初期表示
     *
     * @param ProfilePersonalEditInitializeInputPort $inputPort
     * @param ProfilePersonalEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfilePersonalEditInitializeInputPort $inputPort, ProfilePersonalEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $outputPort->lastName = $member->getLastName();
        $outputPort->firstName = $member->getFirstName();
        $outputPort->lastNameKana = $member->getLastNameKana();
        $outputPort->firstNameKana = $member->getFirstNameKana();
        $outputPort->birthday = $member->getBirthday()->format('Ymd');
        $outputPort->country = $member->getCountry();
        if($member->getCountry() > 1){
            $outputPort->englishName = $member->getEnglishName();
        }
        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録変更する
     *
     * @param ProfilePersonalEditStoreInputPort $inputPort
     * @param ProfilePersonalEditStoreOutputPort $outputPort
     */
    public function store(ProfilePersonalEditStoreInputPort $inputPort, ProfilePersonalEditStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);

        // 変更する
        Data::mappingToObject($inputPort, $member);
        $this->memberRepository->saveOrUpdate($member, true);

        // 操作ログ
        Log::infoOperationUpdateLog("", ["member" => (array)$member], '');

        //ログ出力
        Log::infoOut();
    }
}
