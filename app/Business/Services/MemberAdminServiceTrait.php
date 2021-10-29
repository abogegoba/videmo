<?php

namespace App\Business\Services;

use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Criteria\JobTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\CareerRepository;
use App\Business\Interfaces\Gateways\Repositories\CertificationRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\LanguageAndCertificationRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Gateways\Repositories\SelfIntroductionRepository;
use App\Business\Interfaces\Gateways\Repositories\UploadedFileRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateStoreInputPort;
use App\Domain\Entities\BusinessType;
use App\Domain\Entities\Career;
use App\Domain\Entities\Certification;
use App\Domain\Entities\JobType;
use App\Domain\Entities\LanguageAndCertification;
use App\Domain\Entities\Member;
use App\Domain\Entities\Prefecture;
use App\Domain\Entities\School;
use App\Domain\Entities\SelfIntroduction;
use App\Domain\Entities\Tag;
use App\Domain\Entities\UploadedFile;
use App\Domain\Entities\UserAccount;
use App\Utilities\Log;
use Carbon\Carbon;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Utilities\File;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;

/**
 * Trait MemberAdminServiceTrait
 *
 * @package App\Business\Services
 */
trait MemberAdminServiceTrait
{
    use ListCreateTrait;

    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var UploadedFileRepository
     */
    private $uploadedFileRepository;

    /**
     * @var SelfIntroductionRepository
     */
    private $selfIntroductionRepository;

    /**
     * @var CertificationRepository
     */
    private $certificationRepository;

    /**
     * @var LanguageAndCertificationRepository
     */
    private $languageAndCertificationRepository;

    /**
     * @var CareerRepository
     */
    private $careerRepository;

    /**
     * MemberCreateUseCase constructor.
     *
     * @param JobTypeRepository $jobTypeRepository
     * @param PrefectureRepository $prefectureRepository
     * @param BusinessTypeRepository $businessTypeRepository
     * @param UserAccountRepository $userAccountRepository
     * @param MemberRepository $memberRepository
     * @param UploadedFileRepository $uploadedFileRepository
     * @param SelfIntroductionRepository $selfIntroductionRepository
     * @param CertificationRepository $certificationRepository
     * @param LanguageAndCertificationRepository $languageAndCertificationRepository
     * @param CareerRepository $careerRepository
     */
    public function __construct(
        JobTypeRepository $jobTypeRepository,
        PrefectureRepository $prefectureRepository,
        BusinessTypeRepository $businessTypeRepository,
        UserAccountRepository $userAccountRepository,
        MemberRepository $memberRepository,
        UploadedFileRepository $uploadedFileRepository,
        SelfIntroductionRepository $selfIntroductionRepository,
        CertificationRepository $certificationRepository,
        LanguageAndCertificationRepository $languageAndCertificationRepository,
        CareerRepository $careerRepository
    )
    {
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->businessTypeRepository = $businessTypeRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->memberRepository = $memberRepository;
        $this->uploadedFileRepository = $uploadedFileRepository;
        $this->selfIntroductionRepository = $selfIntroductionRepository;
        $this->certificationRepository = $certificationRepository;
        $this->languageAndCertificationRepository = $languageAndCertificationRepository;
        $this->careerRepository = $careerRepository;
    }

    /**
     * 選択肢をアプトプットする
     *
     * @param MemberCreateInitializeOutputPort $outputPort
     */
    private function outputChoiceLists(MemberCreateInitializeOutputPort $outputPort)
    {
        // ログ出力
        Log::infoIn();
        $outputPort->overseasList = Member::CITIZENSHIP_OVERSEAS_LIST;
        // 都道府県リスト
        $outputPort->prefectureList = $this->createPrefectureList();
        // 学校種別リスト
        $outputPort->schoolTypeList = School::SCHOOL_TYPE_LIST;
        // 学部系統リスト
        $outputPort->facultyTypeList = School::FACULTY_TYPE_LIST;
        $outputPort->overseasFacultyTypeList = School::OVERSEAS_FACULTY_TYPE_LIST;
        // 年リスト
        $outputPort->yearList = School::getGraduationTwelveYearListYearAgo();
        // 月リスト
        $outputPort->monthList = School::getAllMonthList();
        // ハッシュタグカラークラスリスト
        $outputPort->hashTagColorClassList = Tag::TAG_COLLAR_CLASS_LIST;
        // 体育会系所属経験リスト
        $outputPort->affiliationExperienceLabelList = Member::AFFILIATION_EXPERIENCE_LABEL_LIST;
        // インスタフォロワー人数リスト
        $outputPort->instagramFollowerNumberLabelList = Member::INSTAGRAM_FOLLOWER_NUMBER_LABEL_LIST;
        // 業種リスト
        $outputPort->businessTypeList = $this->createBusinessTypeList();
        // 職種リスト
        $outputPort->jobTypeList = $this->createJobTypeList();
        // ステータスリスト
        $outputPort->statusList = Member::STATUS_LIST;

        // ログ出力
        Log::infoOut();
    }

    /**
     * 会員を登録更新する
     *
     * @param Member $member
     * @param MemberCreateStoreInputPort $inputPort
     * @throws BusinessException
     * @throws FatalBusinessException
     */
    private function saveOrUpdate(Member $member, MemberCreateStoreInputPort $inputPort)
    {
        // ログ出力
        Log::infoIn();

        // メールアドレスの重複チェックを行う
        $this->checkDuplicateMailAddress($inputPort->mailAddress, $this->userAccountRepository, $member->getId());

        // 登録可能な卒業年月かを確認する
        $this->checkCanStoreGraduationPeriod($inputPort->graduationPeriodYear, $inputPort->graduationPeriodMonth);

        /** @var UploadedFile[] $newPhotos */
        $newPhotos = [];
        /** @var UploadedFile[] $deletePhotos */
        $deletePhotos = [];
        /** @var UploadedFile[] $newPrVideos */
        $newPrVideos = [];
        /** @var UploadedFile[] $deletePrVideos */
        $deletePrVideos = [];
        /** @var SelfIntroduction[] $deleteSelfIntroductions */
        $deleteSelfIntroductions = [];

        $prefectureRepository = $this->prefectureRepository;
        $selfIntroductionRepository = $this->selfIntroductionRepository;
        Data::mappingToObject($inputPort, $member, [
            // 生年月日
            "birthday" => function ($value, $inputPort, $toObject) {
                /**
                 * @var string $value
                 * @var MemberCreateStoreInputPort $inputPort
                 * @var Member $toObject
                 */
                $toObject->setBirthday(Carbon::make($value));
            },
            // 都道府県
            "prefecture" => function ($value, $inputPort, $toObject) use ($prefectureRepository) {
                /**
                 * @var int $value
                 * @var MemberCreateStoreInputPort $inputPort
                 * @var Member $toObject
                 */
                try {
                    $prefecture = $prefectureRepository->findOneByCriteria(
                        CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class,
                            [
                                "id" => $value
                            ]
                        )
                    );
                    /** @var Member $toObject */
                    $toObject->setPrefecture($prefecture);
                } catch (ObjectNotFoundException $e) {
                    throw new FatalBusinessException("select_target_not_found");
                }
            },
            // 学校
            "schoolType" => function ($value, $fromObject, $toObject) {
                /**
                 * @var int $value
                 * @var MemberCreateStoreInputPort $fromObject
                 * @var Member $toObject
                 */
                $school = $toObject->getOldSchool();
                if (empty($school)) {
                    $school = new School();
                    $school->setMember($toObject);
                }
                $school->setSchoolType($value);
                $school->setname($fromObject->schoolName);
                $school->setDepartmentName($fromObject->departmentName);
                $school->setFacultyType($fromObject->facultyType);
                $school->setGraduationPeriod(new Carbon($fromObject->graduationPeriodYear . sprintf('%02d', $fromObject->graduationPeriodMonth) . '01'));
                $toObject->setOldSchool($school);
            },
            // 証明写真
            "idPhotoName" => function ($value, $fromObject, $toObject) use (&$newPhotos, &$deletePhotos) {
                /**
                 * @var string $value
                 * @var MemberCreateStoreInputPort $fromObject
                 * @var Member $toObject
                 */
                $idPhotoName = $fromObject->idPhotoName;
                $idPhotoPath = $fromObject->idPhotoPath;
                if (!empty($idPhotoName) && !empty($idPhotoPath)) {
                    $realIdPhotoPath = STORAGE_PUBLIC_DIR_PATH . DS . $fromObject->idPhotoPath;

                    $newPhoto = false;
                    $existIdentificationImage = $toObject->getIdentificationImage();
                    if (isset($existIdentificationImage) && $realIdPhotoPath != $existIdentificationImage->getRealFilePath()) {
                        $deletePhotos[] = $existIdentificationImage;
                        $newPhoto = true;
                    } else if (!isset($existIdentificationImage)) {
                        $newPhoto = true;
                    }

                    if ($newPhoto && file_exists($realIdPhotoPath)) {
                        $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realIdPhotoPath);
                        $uploadedFile = new UploadedFile();
                        $uploadedFile->setMember($toObject);
                        $uploadedFile->setFileName($value);
                        $uploadedFile->setFilePath("member/" . $toObject->getId() . "/" . $physicalFileName);
                        $uploadedFile->setPhysicalFileName($physicalFileName);
                        $toObject->setIdentificationImage($uploadedFile);
                        $newPhotos[$realIdPhotoPath] = $uploadedFile;
                    }
                }
            },
            // プライベート写真
            "privatePhotoName" => function ($value, $fromObject, $toObject) use (&$newPhotos, &$deletePhotos) {
                /**
                 * @var string $value
                 * @var MemberCreateStoreInputPort $fromObject
                 * @var Member $toObject
                 */
                $privatePhotoName = $fromObject->privatePhotoName;
                $privatePhotoPath = $fromObject->privatePhotoPath;
                if (!empty($privatePhotoName) && !empty($privatePhotoPath)) {
                    $realPrivatePhotoPath = STORAGE_PUBLIC_DIR_PATH . DS . $fromObject->privatePhotoPath;

                    $newPhoto = false;
                    $existPrivateImage = $toObject->getPrivateImage();
                    if (isset($existPrivateImage) && $realPrivatePhotoPath != $existPrivateImage->getRealFilePath()) {
                        $deletePhotos[] = $existPrivateImage;
                        $newPhoto = true;
                    } else if (!isset($existPrivateImage)) {
                        $newPhoto = true;
                    }

                    if ($newPhoto && file_exists($realPrivatePhotoPath)) {
                        $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPrivatePhotoPath);
                        $uploadedFile = new UploadedFile();
                        $uploadedFile->setMember($toObject);
                        $uploadedFile->setFileName($value);
                        $uploadedFile->setFilePath("member/" . $toObject->getId() . "/" . $physicalFileName);
                        $uploadedFile->setPhysicalFileName($physicalFileName);
                        $toObject->setPrivateImage($uploadedFile);
                        $newPhotos[$realPrivatePhotoPath] = $uploadedFile;
                    }
                }
            },
            // ハッシュタグ
            "hashTag" => function ($value, $fromObject, $toObject) {
                /**
                 * @var int $value
                 * @var MemberCreateStoreInputPort $fromObject
                 * @var Member $toObject
                 */
                $hashTag = $toObject->getHashTag();
                if (empty($hashTag)) {
                    $hashTag = new Tag();
                    $hashTag->setMember($toObject);
                    $hashTag->setFixingFlag(Tag::HASH_TAG);
                }
                $hashTag->setName($value);
                $hashTag->setColor($fromObject->hashTagColor);
                $toObject->setHashTag($hashTag);
            },
            // ユーザーアカウント
            "mailAddress" => function ($value, $fromObject, $toObject) {
                /**
                 * @var int $value
                 * @var MemberCreateStoreInputPort $fromObject
                 * @var Member $toObject
                 */
                $userAccount = $toObject->getUserAccount();
                if (empty($userAccount)) {
                    $userAccount = new UserAccount();
                    $userAccount->setMember($toObject);
                }
                $userAccount->setMailAddress($value);
                $userAccount->setPassword($fromObject->password);
                $toObject->setUserAccount($userAccount);
            },
            // PR動画
            "prVideoNames" => function ($prVideoNames, $fromObject, $toObject) use (&$newPrVideos, &$deletePrVideos) {
                /**
                 * @var int $value
                 * @var MemberCreateStoreInputPort $fromObject
                 * @var Member $toObject
                 */
                /** @var UploadedFile[] $existPrVideos */
                $existPrVideos = [];
                $prVideos = $toObject->getPrVideos();
                foreach ($prVideos as $prVideo) {
                    $existPrVideos[$prVideo->getRealFilePath()] = $prVideo;
                }

                $prVideoPaths = $fromObject->prVideoPaths;
                $prVideoTitles = $fromObject->prVideoTitles;
                $prVideoDescriptions = $fromObject->prVideoDescriptions;
                foreach ($prVideoNames as $index => $prVideoName) {
                    if (isset($prVideoPaths[$index])) {
                        $prVideoPath = STORAGE_PUBLIC_DIR_PATH . DS . $prVideoPaths[$index];
                        if (isset($existPrVideos[$prVideoPath])) {
                            // 既存のPR動画変更の場合は内容を変更する
                            $existPrVideos[$prVideoPath]->setSortNumber($index + 1);
                            if (isset($prVideoTitles[$index])) {
                                $existPrVideos[$prVideoPath]->setTitle($prVideoTitles[$index]);
                            }
                            if (isset($prVideoDescriptions[$index])) {
                                $existPrVideos[$prVideoPath]->setDescription($prVideoDescriptions[$index]);
                            }
                            unset($existPrVideos[$prVideoPath]);
                        } else if (file_exists($prVideoPath)) {
                            // 新規PR動画の場合は新規にUploadedFileを作成して内容を格納する
                            $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $prVideoPath);
                            $uploadedFile = new UploadedFile();
                            $uploadedFile->setMember($toObject);
                            $uploadedFile->setFileName($prVideoName);
                            $uploadedFile->setFilePath("member/" . $toObject->getId() . "/" . $physicalFileName);
                            $uploadedFile->setPhysicalFileName($physicalFileName);
                            $uploadedFile->setSortNumber($index + 1);
                            if (isset($prVideoTitles[$index])) {
                                $uploadedFile->setTitle($prVideoTitles[$index]);
                            }
                            if (isset($prVideoDescriptions[$index])) {
                                $uploadedFile->setDescription($prVideoDescriptions[$index]);
                            }
                            $newPrVideos[$prVideoPath] = $uploadedFile;
                        }
                    }
                }
                $toObject->setPrVideos($newPrVideos);
                $deletePrVideos = $existPrVideos;
            },
            // 自己紹介
            "selfIntroductions" => function ($value, $fromObject, $toObject) use ($selfIntroductionRepository) {
                /**
                 * @var string[] $value
                 * @var MemberCreateStoreInputPort $fromObject
                 * @var Member $toObject
                 */
                $memberId = $toObject->getId();
                $titleList = SelfIntroduction::SELF_TITLE_LIST;
                $titleList[SelfIntroduction::SELF_DISPLAY_NUMBER_10] = $fromObject->selfIntroduction10Title;
                $selfIntroductions = [];
                foreach ($value as $displayNumber => $inputtedSelfIntroduction) {
                    $selfIntroduction = null;
                    if (!empty($memberId)) {
                        try {
                            $selfIntroduction = $selfIntroductionRepository->findOneByCriteria(
                                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class, [
                                    "displayNumber" => $displayNumber,
                                    "member" => $memberId,
                                ])
                            );
                        } catch (ObjectNotFoundException $e) {
                            $selfIntroduction = null;
                        }
                    }
                    if (!empty($inputtedSelfIntroduction)) {
                        if ($selfIntroduction === null) {
                            $selfIntroduction = new SelfIntroduction();
                            $selfIntroduction->setMember($toObject);
                            $selfIntroduction->setDisplayNumber($displayNumber);
                        }
                        $selfIntroduction->setTitle($titleList[$displayNumber]);
                        $selfIntroduction->setContent($inputtedSelfIntroduction);
                        $selfIntroductions[] = $selfIntroduction;
                    } elseif ($selfIntroduction !== null) {
                        // データに存在するが、入力値としてない場合は物理削除
                        $deleteSelfIntroductions[] = $selfIntroduction;
                    }
                }
                $toObject->setAspirationSelfIntroductions($selfIntroductions);
            }
        ]);

        // 志望業種
        $industries[] = $inputPort->industry1;
        $industries[] = $inputPort->industry2;
        $industries[] = $inputPort->industry3;
        /** @var BusinessType[] $aspirationBusinessTypes */
        $aspirationBusinessTypes = [];
        foreach ($industries as $industryId) {
            if (!empty($industryId)) {
                try {
                    $businessType = $this->businessTypeRepository->findOneByCriteria(
                        CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class,
                            [
                                "id" => $industryId
                            ]
                        )
                    );
                    $aspirationBusinessTypes[] = $businessType;
                } catch (ObjectNotFoundException $e) {
                    throw new FatalBusinessException("select_target_not_found");
                }
            }
        }
        $member->setAspirationBusinessTypes($aspirationBusinessTypes);

        // 志望職種
        $jobTypes[] = $inputPort->jobType1;
        $jobTypes[] = $inputPort->jobType2;
        $jobTypes[] = $inputPort->jobType3;
        /** @var JobType[] $aspirationJobTypes */
        $aspirationJobTypes = [];
        foreach ($jobTypes as $jobTypeId) {
            if (!empty($jobTypeId)) {
                try {
                    $jobType = $this->jobTypeRepository->findOneByCriteria(
                        CriteriaFactory::getInstance()->create(JobTypeSearchCriteria::class, GeneralExpressionBuilder::class,
                            [
                                "id" => $jobTypeId
                            ]
                        )
                    );
                    $aspirationJobTypes[] = $jobType;
                } catch (ObjectNotFoundException $e) {
                    throw new FatalBusinessException("select_target_not_found");
                }
            }
        }
        $member->setAspirationJobTypes($aspirationJobTypes);

        // 志望勤務地
        $locations[] = $inputPort->location1;
        $locations[] = $inputPort->location2;
        $locations[] = $inputPort->location3;
        /** @var Prefecture[] $aspirationPrefectures */
        $aspirationPrefectures = [];
        foreach ($locations as $locationId) {
            if (!empty($locationId)) {
                try {
                    $prefecture = $this->prefectureRepository->findOneByCriteria(
                        CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class,
                            [
                                "id" => $locationId
                            ]
                        )
                    );
                    $aspirationPrefectures[] = $prefecture;
                } catch (ObjectNotFoundException $e) {
                    throw new FatalBusinessException("select_target_not_found");
                }
            }
        }
        $member->setAspirationPrefectures($aspirationPrefectures);

        // 語学・資格
        $languageAndCertification = $member->getLanguageAndCertification();
        $beforeCertificationDisplayNumberList = [];
        $memberId = $member->getId();
        if ($languageAndCertification === null) {
            // 語学・資格が会員に紐づいていなければ新規作成
            $languageAndCertification = new LanguageAndCertification();
            $languageAndCertification->setMember($member);
        } else {
            // 語学・資格が会員に紐づいている場合は、既存の資格・検定の表示順を配列で取得
            $beforeCertifications = $certification = $this->certificationRepository->findValuesByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class, [
                    "languageAndCertification.member" => $memberId,
                ]), ["displayNumber"]
            );
            $beforeCertificationDisplayNumberList = array_column($beforeCertifications, "displayNumber");
        }

        // TOEICとTOEFLを登録
        $languageAndCertification->setToeflScore($inputPort->toeflScore);
        $languageAndCertification->setToeicScore($inputPort->toeicScore);

        // 保有する資格・検定を作成・取得
        $certifications = [];
        $inputtedCertificationList = $inputPort->certificationList;
        foreach ($inputtedCertificationList as $index => $inputtedCertification) {
            if (!empty($inputtedCertification)) {
                $certification = $this->getCertificationByDisplayNumberAndMemberId($index, $memberId);
                if ($certification === null) {
                    $certification = new Certification();
                    $certification->setDisplayNumber($index);
                    $certification->setLanguageAndCertification($languageAndCertification);
                }
                $certification->setName($inputtedCertification);
                $certifications[] = $certification;
                // 削除するリストから外す
                unset($beforeCertificationDisplayNumberList[array_search($index, $beforeCertificationDisplayNumberList)]);
            }
        }
        $languageAndCertification->setCertifications($certifications);
        $member->setLanguageAndCertification($languageAndCertification);

        // キャリア
        $originalCareers = $member->getCareers();
        $beforeCareerDisplayNumberList = [];
        if ($originalCareers !== null) {
            foreach ($originalCareers as $originalCareer) {
                $beforeCareerDisplayNumberList[] = $originalCareer->getDisplayNumber();
            }
        }
        $careerNames = $inputPort->careerNames;
        $memberId = $member->getId();
        $careers = [];
        if(!is_null($inputPort->careerPeriodYears) && !is_null($inputPort->careerPeriodMonths)){
            foreach ($careerNames as $index => $careerName) {
                $periodYear = $inputPort->careerPeriodYears[$index];
                $periodMonth = $inputPort->careerPeriodMonths[$index];
                // 経歴年月・経歴名が入力されている場合のみ
                if (!empty($careerName) && !empty($periodYear) && !empty($periodMonth)) {
                    $career = $this->getCareerByDisplayNumberAndMemberId($index, $memberId);
                    if ($career === null) {
                        $career = new Career();
                        $career->setDisplayNumber($index);
                    }
                    $career->setPeriod(new Carbon($periodYear . sprintf('%02d', $periodMonth) . '01'));
                    $career->setName($careerName);
                    $career->setMember($member);
                    $careers[] = $career;
                    unset($beforeCareerDisplayNumberList[array_search($index, $beforeCareerDisplayNumberList)]);
                }
            }
        }

        $member->setCareers($careers);

        // 登録・変更リクエストする証明写真が存在しない場合は現在登録されている証明写真を削除する
        $idPhotoPath = $inputPort->idPhotoPath;
        if (empty($idPhotoPath)) {
            $identificationImage = $member->getIdentificationImage();
            if (!empty($identificationImage)) {
                $deletePhotos[] = $identificationImage;
            }
        }

        // 登録・変更リクエストするプライベート写真が存在しない場合は現在登録されているプライベート写真を削除する
        $privatePhotoPath = $inputPort->privatePhotoPath;
        if (empty($privatePhotoPath)) {
            $privateImage = $member->getPrivateImage();
            if (!empty($privateImage)) {
                $deletePhotos[] = $privateImage;
            }
        }

        // 登録・変更リクエストするPR動画が存在しない場合は現在登録されているPR動画を全て削除する
        $prVideoNames = $inputPort->prVideoNames;
        $prVideoPaths = $inputPort->prVideoPaths;
        if (empty($prVideoNames) || empty($prVideoPaths)) {
            $deletePrVideos = $member->getPrVideos();
        }

        // 変更を実行する
        $this->memberRepository->saveOrUpdate($member, true);
        $this->languageAndCertificationRepository->saveOrUpdate($languageAndCertification, true);

        // 不要となった画像ファイルを削除する
        if (count($deletePhotos) > 0) {
            $this->uploadedFileRepository->delete($deletePhotos);
        }

        // 不要となった動画ファイルを削除する
        if (count($deletePhotos) > 0) {
            $this->uploadedFileRepository->delete($deletePhotos);
        }

        // 不要となったPR動画ファイルを削除する
        if (count($deletePrVideos) > 0) {
            $this->uploadedFileRepository->delete($deletePrVideos);
        }

        // 不要となった自己紹介を物理削除する
        if (count($deleteSelfIntroductions) > 0) {
            foreach ($deleteSelfIntroductions as $deleteSelfIntroduction) {
                $this->selfIntroductionRepository->physicalDelete($deleteSelfIntroduction);
                Log::infoOperationDeleteLog("", ["selfIntroduction" => (array)$deleteSelfIntroduction], "");
            }
        }

        // 不要となったキャリアを物理削除する
        if (count($beforeCertificationDisplayNumberList) > 0) {
            foreach ($beforeCertificationDisplayNumberList as $beforeCertificationDisplayNumber) {
                $deletedCertification = $this->getCertificationByDisplayNumberAndMemberId($beforeCertificationDisplayNumber, $memberId);
                if (isset($deletedCertification)) {
                    $this->certificationRepository->physicalDelete($deletedCertification);
                    Log::infoOperationDeleteLog("", ["certification" => (array)$deletedCertification], "");
                }
            }
        }

        // 不要になった経歴年月・経歴名があれば物理削除
        if (count($beforeCareerDisplayNumberList) > 0) {
            foreach ($beforeCareerDisplayNumberList as $beforeCareerDisplayNumber) {
                $deletedCareer = $this->getCareerByDisplayNumberAndMemberId($beforeCareerDisplayNumber, $memberId);
                $this->careerRepository->physicalDelete($deletedCareer);
                Log::infoOperationDeleteLog("", ["career" => (array)$deletedCareer], "");
            }
        }

        // 新規写真をTMPフォルダから会員のフォルダへ移動する
        foreach ($newPhotos as $path => $photo) {
            File::createDir($photo->getRealFileDir());
            File::rename($path, $photo->getRealFilePath());
        }

        // 削除対象の写真を削除する
        foreach ($deletePhotos as $photo) {
            File::remove($photo->getRealFilePath());
        }

        // 新規PR動画をTMPフォルダから会員のフォルダへ移動する
        foreach ($newPrVideos as $prVideoPath => $newPrVideo) {
            File::createDir($newPrVideo->getRealFileDir());
            File::rename($prVideoPath, $newPrVideo->getRealFilePath());
        }

        // 削除対象のPR動画を削除する
        foreach ($deletePrVideos as $deletePrVideo) {
            File::remove($deletePrVideo->getRealFilePath());
        }

        // メール送信
        if ($inputPort->sendMail) {
            if ($member->getStatus() == Member::STATUS_TEMPORARY_MEMBER) {
                // 仮会員の場合、会員登録受付メールを送信する
                $this->sendEntryReceptionMail($member);
            } else if ($member->getStatus() == Member::STATUS_REAL_MEMBER) {
                // 本会員の場合、会員登録受付完了メールを送信する
                $this->sendEntryCompleteMail($member);
            }
        }

        // ログ出力
        Log::infoOut();
    }

    /**
     * 保有資格・検定を表示順とメンバーIDから取得
     *
     * @param int $displayNumber
     * @param int $memberId
     * @return Certification|null
     */
    private function getCertificationByDisplayNumberAndMemberId(int $displayNumber, ?int $memberId): ?Certification
    {
        $certification = null;
        if (!empty($memberId)) {
            try {
                $certification = $this->certificationRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class, [
                        "displayNumber" => $displayNumber,
                        "languageAndCertification.member" => $memberId,
                    ])
                );
            } catch (ObjectNotFoundException $e) {
                $certification = null;
            }
        }
        return $certification;
    }

    /**
     * 経歴を表示順とメンバーIDから取得
     *
     * @param int $displayNumber
     * @param int $memberId
     * @return Career|null
     */
    private function getCareerByDisplayNumberAndMemberId(int $displayNumber, ?int $memberId): ?Career
    {
        $career = null;
        if (!empty($memberId)) {
            try {
                $career = $this->careerRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class, [
                        "displayNumber" => $displayNumber,
                        "member" => $memberId,
                    ])
                );
            } catch (ObjectNotFoundException $e) {
                $career = null;
            }
        }
        return $career;
    }

    /**
     * メールアドレスの重複チェックを行う
     *
     * @param string $mailAddress
     * @param UserAccountRepository $userAccountRepository
     * @param int|null $memberId
     * @throws BusinessException
     */
    private function checkDuplicateMailAddress(string $mailAddress, UserAccountRepository $userAccountRepository, ?int $memberId)
    {
        $userAccountSameMailAddress = $userAccountRepository->findByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "mailAddress" => $mailAddress
                ]
            )
        );
        if (count($userAccountSameMailAddress) > 0) {
            foreach ($userAccountSameMailAddress as $userAccount) {
                if (($userAccount->getMember() !== null &&
                    $userAccount->getMember()->getId() !== $memberId &&
                    $userAccount->getMember()->getStatus() !== Member::STATUS_WITHDRAWN_MEMBER)) {
                    // ユーザーアカウントに紐づく会員のステータスが退会済みでない場合はエラーへ
                    throw new BusinessException('duplication.mail_address');
                }
            }
        }
    }

    /**
     * 登録可能な卒業年月かを確認する
     *
     * @param int|null $graduationPeriodYear
     * @param int|null $graduationPeriodMonth
     * @throws BusinessException
     */
    private function checkCanStoreGraduationPeriod(?int $graduationPeriodYear, ?int $graduationPeriodMonth)
    {
        $graduationTwelveYearListYearAgo = YearMonthTrait::getGraduationTwelveYearListYearAgo();
        $allMonthList = YearMonthTrait::getAllMonthList();
        if (!array_key_exists($graduationPeriodYear, $graduationTwelveYearListYearAgo) || !array_key_exists($graduationPeriodMonth, $allMonthList)) {
            // 現在の年を基準に2年前から10年先以外の場合
            throw new BusinessException('cant_store_graduation_period');
        }
    }

    /**
     * 会員登録受付完了メール送信
     *
     * @param Member $member
     * @throws FatalBusinessException
     */
    private function sendEntryReceptionMail(Member $member)
    {
        // ログ出力
        Log::infoIn();

        // 登録完了URL生成
        $userAccount = $member->getUserAccount();
        $createdAt = $userAccount->getCreatedAt();
        $formattedCreatedAt = $createdAt->format("Y-m-d H:i:s");
        $pass = 'memberAccount';
        $encryptedCreatedAt = $userAccount->encrypt($formattedCreatedAt, $pass);
        $memberId = $member->getId();
        $encryptedId = $userAccount->encrypt($memberId, $pass);
        $urlEncodeCreatedAt = urlencode($encryptedCreatedAt);
        $urlEncodeId = urlencode($encryptedId);
        $completionURL = env('FRONT_APP_URL') . '/entry/complete?param=' . $urlEncodeCreatedAt . '&def=' . $urlEncodeId;
        $dataList["completionURL"] = $completionURL;
        $dataList["member"] = $member;
        $data = Data::wrap($dataList);
        $template = "mail.admin.member.entry_reception_mail";
        $title = "【LinkT】 会員登録を受け付けいたしました。";
        $this->sendMail($userAccount, $template, $title, $data);

        // ログ出力
        Log::infoOut();
    }

    /**
     * 会員登録受付完了メール送信
     *
     * @param Member $member
     * @throws FatalBusinessException
     */
    public function sendEntryCompleteMail(Member $member)
    {
        // ログ出力
        Log::infoIn();

        $userAccount = $member->getUserAccount();
        $dataList["member"] = $member;
        $data = Data::wrap($dataList);
        $template = "mail.admin.member.entry_complete_mail";
        $title = "【LinkT】 会員登録が完了いたしました。";
        $this->sendMail($userAccount, $template, $title, $data);

        // ログ出力
        Log::infoOut();
    }

    /**
     * メール送信
     *
     * @param UserAccount $userAccount
     * @param string $template
     * @param string $title
     * @param Data $data
     * @return bool
     * @throws FatalBusinessException
     */
    public function sendMail(UserAccount $userAccount, string $template, string $title, Data $data)
    {
        // ログ出力
        Log::infoIn();

        $mailAddress = $userAccount->getMailAddress();

        // メールクライアントをgetInstance 引数(viewのテンプレート名、toのアドレス(１個)、件名(任意)、データ)
        $mail = Mail::getInstance($template, $mailAddress, trans($title), $data);
        $result = $mail->send();
        if ($result !== true) {
            throw new FatalBusinessException("not_send_mail");
        }

        // ログ出力
        Log::infoOut();

        return $result;
    }
}
