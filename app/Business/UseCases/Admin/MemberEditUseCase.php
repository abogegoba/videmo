<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Interactors\Admin\MemberEdit\MemberEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\MemberEdit\MemberEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberEdit\MemberEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\MemberEdit\MemberEditUpdateInputPort;
use App\Business\Interfaces\Interactors\Admin\MemberEdit\MemberEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberEdit\MemberEditUpdateOutputPort;
use App\Business\Services\MemberAdminServiceTrait;
use App\Domain\Entities\Member;
use App\Domain\Entities\SelfIntroduction;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class MemberEditUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class MemberEditUseCase implements MemberEditInitializeInteractor, MemberEditUpdateInteractor
{
    use MemberAdminServiceTrait;

    /**
     * 初期化する
     *
     * @param MemberEditInitializeInputPort $inputPort
     * @param MemberEditInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(MemberEditInitializeInputPort $inputPort, MemberEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 選択肢をアウトプットする
        $this->outputChoiceLists($outputPort);

        // 指定された会員を取得する
        $member = $this->getMember($inputPort->memberId);

        // 会員ID
        $outputPort->memberId = $member->getId();
        // 氏名(姓)
        $outputPort->lastName = $member->getLastName();
        // 氏名(名)
        $outputPort->firstName = $member->getFirstName();
        // 氏名かな(せい)
        $outputPort->lastNameKana = $member->getLastNameKana();
        // 氏名かな(めい)
        $outputPort->firstNameKana = $member->getFirstNameKana();
        $outputPort->englishName = $member->getEnglishName();
        // 生年月日
        $outputPort->birthday = $member->getBirthday()->format("Y/m/d");
        // 郵便番号
        $outputPort->country = $member->getCountry();
        $outputPort->zipCode = $member->getZipCode();
        // 都道府県
        $outputPort->prefecture = $member->getPrefecture()->getId();
        // 市区町村
        $outputPort->city = $member->getCity();
        // 番地・建物名・部屋番号など
        $outputPort->blockNumber = $member->getBlockNumber();
        // 電話番号
        $outputPort->phoneNumber = $member->getPhoneNumber();
        // 学校
        $school = $member->getOldSchool();
        if (!empty($school)) {
            // 学校種別
            $outputPort->schoolType = $school->getSchoolType();
            // 学校名
            $outputPort->schoolName = $school->getName();
            // 学部・学科名
            $outputPort->departmentName = $school->getDepartmentName();
            // 学部系統
            $outputPort->facultyType = $school->getFacultyType();
            // 卒業年月
            $outputPort->graduationPeriodYear = $school->getGraduationPeriod()->format("Y");
            $outputPort->graduationPeriodMonth = $school->getGraduationPeriod()->format("n");
        }
        // 証明写真
        $idPhoto = $member->getIdentificationImage();
        if (isset($idPhoto)) {
            $outputPort->idPhoto = [
                "name" => $idPhoto->getFileName(),
                "url" => $idPhoto->getFilePathForFrontShow(),
                "path" => $idPhoto->getFilePath()
            ];
        } else {
            $outputPort->idPhoto = [];
        }
        // プライベート写真
        $privatePhoto = $member->getPrivateImage();
        if (isset($privatePhoto)) {
            $outputPort->privatePhoto = [
                "name" => $privatePhoto->getFileName(),
                "url" => $privatePhoto->getFilePathForFrontShow(),
                "path" => $privatePhoto->getFilePath()
            ];
        } else {
            $outputPort->privatePhoto = [];
        }
        // ハッシュタグ
        $hashTag = $member->getHashTag();
        if (!empty($hashTag)) {
            // ハッシュタグ名
            $outputPort->hashTag = $hashTag->getName();
            // ハッシュタグカラー
            $outputPort->hashTagColor = $hashTag->getColor();
        }
        // ユーザーアカウント
        $userAccount = $member->getUserAccount();
        // メールアドレス
        $outputPort->mailAddress = $userAccount->getMailAddress();
        // パスワード
        $outputPort->password = $userAccount->getPassword();
        // PR動画
        $prVideos = [];
        foreach ($member->getPrVideos() as $prVideo) {
            $prVideos[] = [
                "name" => $prVideo->getFileName(),
                "url" => $prVideo->getFilePathForFrontShow(),
                "path" => $prVideo->getFilePath(),
                "title" => $prVideo->getTitle(),
                "description" => $prVideo->getDescription(),
                "type" => $prVideo->getFileType()
            ];
        }
        $outputPort->prVideos = $prVideos;
        // 自己紹介文
        $outputPort->introduction = $member->getIntroduction();
        // 体育会系所属経験
        $outputPort->affiliationExperience = $member->getAffiliationExperience();
        // インスタフォロワー数
        $outputPort->instagramFollowerNumber = $member->getInstagramFollowerNumber();
        // 自己紹介文
        $selfIntroductions = [];
        $selfIntroduction10Title = null;
        for ($i = 1; $i <= 10; $i++) {
            $selfIntroduction = $this->getSelfIntroductionByDisplayNumberAndMemberId($i, $member->getId());
            if ($i === 10 && ($selfIntroduction !== null)) {
                $selfIntroduction10Title = $selfIntroduction->getTitle();
                $selfIntroductions[$i]['title'] = $selfIntroduction10Title;
            } else {
                $selfIntroductions[$i]['title'] = SelfIntroduction::SELF_TITLE_LIST[$i];
            }
            $selfIntroductions[$i]['content'] = ($selfIntroduction !== null) ? $selfIntroduction->getContent() : '';
        }
        $outputPort->selfIntroductions = $selfIntroductions;
        $outputPort->selfIntroduction10Title = $selfIntroduction10Title;
        //　志望業種
        $aspirationBusinessTypes = $member->getAspirationBusinessTypes();
        if (!empty($aspirationBusinessTypes)) {
            $outputPort->industry1 = (!empty($aspirationBusinessTypes[0]) ? $aspirationBusinessTypes[0]->getId() : null);
            $outputPort->industry2 = (!empty($aspirationBusinessTypes[1]) ? $aspirationBusinessTypes[1]->getId() : null);
            $outputPort->industry3 = (!empty($aspirationBusinessTypes[2]) ? $aspirationBusinessTypes[2]->getId() : null);
        }
        //　志望職種
        $aspirationJobTypes = $member->getAspirationJobTypes();
        if (!empty($aspirationJobTypes)) {
            $outputPort->jobType1 = (!empty($aspirationJobTypes[0]) ? $aspirationJobTypes[0]->getId() : null);
            $outputPort->jobType2 = (!empty($aspirationJobTypes[1]) ? $aspirationJobTypes[1]->getId() : null);
            $outputPort->jobType3 = (!empty($aspirationJobTypes[2]) ? $aspirationJobTypes[2]->getId() : null);
        }
        //　志望勤務地
        $aspirationPrefectures = $member->getAspirationPrefectures();
        if (!empty($aspirationPrefectures)) {
            $outputPort->location1 = (!empty($aspirationPrefectures[0]) ? $aspirationPrefectures[0]->getId() : null);
            $outputPort->location2 = (!empty($aspirationPrefectures[1]) ? $aspirationPrefectures[1]->getId() : null);
            $outputPort->location3 = (!empty($aspirationPrefectures[2]) ? $aspirationPrefectures[2]->getId() : null);
        }
        // インターン希望
        $outputPort->internNeeded = $member->getInternNeeded();
        $outputPort->recruitInfoNeeded = $member->getRecruitInfoNeeded();
        // 語学・資格
        $languageAndCertification = $member->getLanguageAndCertification();
        if (!empty($languageAndCertification)) {
            // TOEIC
            $outputPort->toeicScore = $languageAndCertification->getToeicScore();
            // TOEFL
            $outputPort->toeflScore = $languageAndCertification->getToeflScore();
            // 保有資格・検定など
            $outputPort->certifications = $languageAndCertification->getCertifications();
        }
        // 経歴
        $outputPort->careers = $member->getCareers();
        // 管理メモ
        $outputPort->managementMemo = $member->getManagementMemo();
        // ステータス
        $outputPort->status = $member->getStatus();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 変更する
     *
     * @param MemberEditUpdateInputPort $inputPort
     * @param MemberEditUpdateOutputPort $outputPort
     * @throws FatalBusinessException
     * @throws \ReLab\Commons\Exceptions\BusinessException
     */
    public function update(MemberEditUpdateInputPort $inputPort, MemberEditUpdateOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 指定された会員を取得する
        $member = $this->getMember($inputPort->memberId);
        $this->saveOrUpdate($member, $inputPort);
        $outputPort->memberId = $member->getId();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 変更対象の会員を取得する
     *
     * @param int $memberId
     * @return Member
     * @throws FatalBusinessException
     */
    private function getMember(int $memberId): Member
    {
        try {
            return $this->memberRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $memberId
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("select_target_not_found");
        }
    }

    /**
     * 自己紹介を表示順とメンバーIDから取得
     *
     * @param int $displayNumber
     * @param int $memberId
     * @return SelfIntroduction|null
     */
    private function getSelfIntroductionByDisplayNumberAndMemberId(int $displayNumber, int $memberId): ?SelfIntroduction
    {
        try {
            $selfIntroduction = $this->selfIntroductionRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class, [
                    "displayNumber" => $displayNumber,
                    "member" => $memberId,
                ])
            );
        } catch (ObjectNotFoundException $e) {
            $selfIntroduction = null;
        }
        return $selfIntroduction;
    }
}
