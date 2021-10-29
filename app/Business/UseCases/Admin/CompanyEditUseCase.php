<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyUploadedFileRepository;
use App\Business\Interfaces\Gateways\Repositories\TagRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Admin\CompanyEdit\CompanyEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\CompanyEdit\CompanyEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyEdit\CompanyEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\CompanyEdit\CompanyEditUpdateInputPort;
use App\Business\Interfaces\Interactors\Admin\CompanyEdit\CompanyEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyEdit\CompanyEditUpdateOutputPort;
use App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit\CompanyBasicInformationEditStoreInputPort;
use App\Business\Services\ListCreateTrait;
use App\Domain\Entities\Company;
use App\Domain\Entities\CompanyAccount;
use App\Domain\Entities\Tag;
use App\Domain\Entities\CompanyUploadedFile;
use App\Domain\Entities\UserAccount;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Utilities\File;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class CompanyEditUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class CompanyEditUseCase implements CompanyEditInitializeInteractor, CompanyEditUpdateInteractor
{
    use ListCreateTrait;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * @var BusinessTypeRepository
     */
    private $businessTypeRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var companyAccountRepository
     */
    private $companyAccountRepository;

    /**
     * @var CompanyUploadedFileRepository
     */
    private $companyUploadedFileRepository;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var JobApplicationRepository
     */
    private $jobApplicationRepository;

    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * CompanyRecruitingCreateUseCase constructor.
     *
     * @param PrefectureRepository $prefectureRepository
     * @param BusinessTypeRepository $businessTypeRepository
     * @param CompanyRepository $companyRepository
     * @param CompanyAccountRepository $companyAccountRepository
     * @param CompanyUploadedFileRepository $companyUploadedFileRepository
     * @param TagRepository $tagRepository
     * @param JobApplicationRepository $jobApplicationRepository
     * @param UserAccountRepository $userAccountRepository
     */
    public function __construct(
        PrefectureRepository $prefectureRepository,
        BusinessTypeRepository $businessTypeRepository,
        CompanyRepository $companyRepository,
        CompanyAccountRepository $companyAccountRepository,
        CompanyUploadedFileRepository $companyUploadedFileRepository,
        TagRepository $tagRepository,
        JobApplicationRepository $jobApplicationRepository,
        UserAccountRepository $userAccountRepository
    ) {
        $this->prefectureRepository = $prefectureRepository;
        $this->businessTypeRepository = $businessTypeRepository;
        $this->companyRepository = $companyRepository;
        $this->companyAccountRepository = $companyAccountRepository;
        $this->companyUploadedFileRepository = $companyUploadedFileRepository;
        $this->tagRepository = $tagRepository;
        $this->jobApplicationRepository = $jobApplicationRepository;
        $this->userAccountRepository = $userAccountRepository;
    }

    /**
     * 初期化する
     *
     * @param CompanyEditInitializeInputPort $inputPort
     * @param CompanyEditInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(CompanyEditInitializeInputPort $inputPort, CompanyEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 都道府県リスト
        $outputPort->prefectureList = $this->createPrefectureList();

        // 業種リスト
        $outputPort->businessTypeList = $this->createBusinessTypeList();

        // 企業アカウントIDから企業を取得
        try {
            $company = $this->companyRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $inputPort->companyId
                    ]
                )
            );
        }catch (ObjectNotFoundException $e){
            throw new FatalBusinessException("data_not_found");
        }

        // 会社ID
        $outputPort->companyId = $company->getId();
        // 会社名
        $outputPort->name = $company->getName();
        // 会社名（かな）
        $outputPort->nameKana = $company->getNameKana();
        // 郵便番号
        $outputPort->zip = $company->getZipCode();
        $prefecture = $company->getPrefecture();
        if (isset($prefecture)) {
            // 都道府県
            $outputPort->prefectures = $prefecture->getId();
        }
        // 市区町村
        $outputPort->city = $company->getCity();
        // 建物名・階数など
        $outputPort->room = $company->getBlockNumber();
        // 業種
        $industryCondition = [];
        foreach ($company->getBusinessTypes() as $buisinessType) {
            $industryCondition[] = $buisinessType->getId();
        }
        $outputPort->industryCondition = $industryCondition;
        // 事業内容
        $outputPort->descriptionOfBusiness = $company->getDescriptionOfBusiness();
        // 設立
        $outputPort->establishmentDate = $company->getEstablishmentDate();
        // 資本金
        $outputPort->capital = $company->getCapital();
        // 従業員
        $outputPort->payrollNumber = $company->getPayrollNumber();
        // 売上高
        $outputPort->sales = $company->getSales();
        // 代表者
        $outputPort->representativePerson = $company->getRepresentativePerson();
        // 役員構成
        $outputPort->exectiveOfficers = $company->getExectiveOfficers();
        // 事業所
        $outputPort->establishment = $company->getEstablishment();
        // 関連会社
        $outputPort->affiliatedCompany = $company->getAffiliatedCompany();
        // 登録・資格
        $outputPort->qualification = $company->getQualification();
        // ホームページURL
        $outputPort->homePageUrl = $company->getHomePageUrl();
        // 採用ホームページ
        $outputPort->recruitmentUrl = $company->getRecruitmentUrl();
        // 主要取引先
        $outputPort->mainClient = $company->getMainClient();
        // 企業ロゴ
        $outputPort->uploadedLogo = $this->createFileData($company->getCompanyLogoImage());
        // 担当者名
        $outputPort->picName = $company->getPicName();
        // 連絡先電話番号
        $outputPort->picPhoneNumber = $company->getPicPhoneNumber();
        // 緊急連絡先電話番号
        $outputPort->picEmergencyPhoneNumber = $company->getPicEmergencyPhoneNumber();
        // 連絡先メールアドレス
        $outputPort->picMailAddress = $company->getPicMailAddress();
        // 企業画像
        $companyImages = [];
        foreach ($company->getCompanyImages() as $companyImage) {
            $companyImages[] = $this->createFileData($companyImage);
        }
        $outputPort->companyImages = $companyImages;
        // 企業紹介文
        $outputPort->introductorySentence = $company->getIntroductorySentence();
        // PR動画
        $outputPort->prVideo = $this->createFileData($company->getCompanyPrVideo());
        // 5秒動画
        $outputPort->video5s = $this->createFileData($company->getShortLengthVideoFiveSeconds());
        // 5秒動画サムネイル画像
        $outputPort->video5sThumb = $this->createFileData($company->getShortLengthVideoThumbnailFiveSeconds());
        // 10秒動画
        $outputPort->video10s = $this->createFileData($company->getShortLengthVideoTenSeconds());
        // 10秒動画サムネイル画像
        $outputPort->video10sThumb = $this->createFileData($company->getShortLengthVideoThumbnailTenSeconds());
        // 15秒動画
        $outputPort->video15s = $this->createFileData($company->getShortLengthVideoFifteenSeconds());
        // 15秒動画サムネイル画像
        $outputPort->video15sThumb = $this->createFileData($company->getShortLengthVideoThumbnailFifteenSeconds());
        // 当社の特徴
        $features = [];
        foreach ($company->getFeatures() as $companyIntroduction) {
            $features[] = $this->createFileData($companyIntroduction);
        }
        $outputPort->features = $features;
        $hashTag = $company->getHashtag();
        if (isset($hashTag)) {
            // ハッシュタグ
            $outputPort->hashtag = $hashTag->getName();
            // ハッシュタグカラー
            $outputPort->hashTagColor = $hashTag->getColor();
        }
        $recruitmentTags = $company->getRecruitmentTags();
        if (isset($recruitmentTags)) {
            foreach ($recruitmentTags as $recruitmentTag) {
                $tagName = $recruitmentTag->getName();
                switch ($tagName) {
                    case Tag::RECRUIT_TAG_LIST[Tag::THIS_YEAR]:
                        // 募集対象年（今年）
                        $outputPort->recruitmentTargetYear = true;
                        break;
                    case Tag::RECRUIT_TAG_LIST[Tag::NEXT_YEAR]:
                        // 募集対象年（来年）
                        $outputPort->recruitmentTargetThisYear = true;
                        break;
                    case Tag::RECRUIT_TAG_LIST[Tag::INTERN]:
                        // インターン
                        $outputPort->recruitmentTargetIntern = true;
                        break;
                }
            }
        }
        // アカウント一覧
        $outputPort->accountList = $this->createAccountList($company);

        // 管理メモ
        $outputPort->managementMemo = $company->getManagementMemo();

        // ステータス
        $outputPort->status = $company->getStatus();

        // 求人枠数
        $outputPort->jobApplicationAvailableNumber = $company->getJobApplicationAvailableNumber();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 変更する
     *
     * @param CompanyEditUpdateInputPort $inputPort
     * @param CompanyEditUpdateOutputPort $outputPort
     * @throws BusinessException
     */
    public function update(CompanyEditUpdateInputPort $inputPort, CompanyEditUpdateOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 企業アカウントIDから企業を取得
        $company = $this->companyRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->companyId
                ]
            )
        );

        /** @var CompanyUploadedFile[] $newFiles */
        $newFiles = [];
        /** @var CompanyUploadedFile[] $deleteFiles */
        $deleteFiles = [];

        // 企業エンティティに入力値を設定
        $prefectureRepository = $this->prefectureRepository;
        $businessTypeRepository = $this->businessTypeRepository;
        if(!$inputPort->companyImageNames) {
            $inputPort->companyImageNames = [];
        }
        Data::mappingToObject($inputPort, $company, [
            // 郵便番号
            "zip" => function ($value, $inputPort, $toObject) {
                /** @var Company $toObject */
                $toObject->setZipCode($value);
            },
            // 都道府県
            "prefectures" => function ($value, $inputPort, $toObject) use ($prefectureRepository) {
                try {
                    $prefecture = $prefectureRepository->findOneByCriteria(
                        CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $value]));
                    /** @var Company $toObject */
                    $toObject->setPrefecture($prefecture);
                } catch (ObjectNotFoundException $e) {
                    throw new FatalBusinessException("select_target_not_found");
                }
            },
            // 建物名・階数など
            "room" => function ($value, $inputPort, $toObject) {
                /** @var Company $toObject */
                $toObject->setBlockNumber($value);
            },
            // 業種
            "industryCondition" => function ($value, $inputPort, $toObject) use ($businessTypeRepository) {
                try {
                    $businessTypes = $businessTypeRepository->findByCriteria(
                        CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $value]));
                    /** @var Company $toObject */
                    $toObject->setBusinessTypes($businessTypes);
                } catch (ObjectNotFoundException $e) {
                    throw new FatalBusinessException("select_target_not_found");
                }
            },
            // 企業ロゴ
            "uploadedLogoName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->uploadedLogoPath;
                $newFile = false;
                $existFile = $toObject->getCompanyLogoImage();
                if (isset($existFile)) {
                    if (is_null($value)) {
                        $deleteFiles[] = $existFile;
                    } else {
                        if ($realPath != $existFile->getRealFilePath()) {
                            $deleteFiles[] = $existFile;
                            $newFile = true;
                        }
                    }
                } else {
                    if (isset($value)) {
                        $newFile = true;
                    }
                }

                if ($newFile && file_exists($realPath)) {
                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = new CompanyUploadedFile();
                    $uploadedFile->setCompany($toObject);
                    $uploadedFile->setFileName($value);
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                    $uploadedFile->setPhysicalFileName($physicalFileName);
                    $toObject->setCompanyLogoImage($uploadedFile);
                    $newFiles[$realPath] = $uploadedFile;
                }
            },
            // 企業画像
            "companyImageNames" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $existFiles = [];
                $companyImages = $toObject->getCompanyImages();
                foreach ($companyImages as $companyImage) {
                    $existFiles[$companyImage->getRealFilePath()] = $companyImage;
                }

                $names = $inputPort->companyImageNames;
                $paths = $inputPort->companyImagePaths;
                $checked = $inputPort->displayImage;
                $newCompanyImages = [];
                foreach ($names as $index => $name) {
                    if (isset($paths[$index])) {
                        $path = STORAGE_PUBLIC_DIR_PATH . DS . $paths[$index];
                        if (isset($existFiles[$path])) {
                            $existFiles[$path]->setSortNumber($index + 1);
                            $existFiles[$path]->setViewSelected($checked === strval($index));
                            unset($existFiles[$path]);
                        } else {
                            if (file_exists($path)) {
                                $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $path);
                                $uploadedFile = new CompanyUploadedFile();
                                $uploadedFile->setCompany($toObject);
                                $uploadedFile->setFileName($name);
                                $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                                $uploadedFile->setPhysicalFileName($physicalFileName);
                                $uploadedFile->setSortNumber($index + 1);
                                $uploadedFile->setViewSelected($checked === strval($index));
                                $newCompanyImages[] = $uploadedFile;
                                $newFiles[$path] = $uploadedFile;
                            }
                        }
                    }
                }
                $toObject->setCompanyImages($newCompanyImages);
                foreach ($existFiles as $existFile) {
                    $deleteFiles[] = $existFile;
                }
            },
            // PR動画
            "prVideoName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->prVideoPath;

                $newFile = false;
                $existFile = $toObject->getCompanyPrVideo();
                if (isset($existFile)) {
                    if (is_null($value)) {
                        $deleteFiles[] = $existFile;
                    } else {
                        if ($realPath != $existFile->getRealFilePath()) {
                            $deleteFiles[] = $existFile;
                            $newFile = true;
                        }
                    }
                } else {
                    if (isset($value)) {
                        $newFile = true;
                    }
                }

                if ($newFile && file_exists($realPath)) {
                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = new CompanyUploadedFile();
                    $uploadedFile->setCompany($toObject);
                    $uploadedFile->setFileName($value);
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                    $uploadedFile->setPhysicalFileName($physicalFileName);
                    $toObject->setCompanyPrVideo($uploadedFile);
                    $newFiles[$realPath] = $uploadedFile;
                }
            },
            // 5秒動画
            "video5sName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video5sPath;
                $video5sTitle = $inputPort->video5sTitle;

                $newFile = false;
                $existFile = $toObject->getShortLengthVideoFiveSeconds();
                if (isset($existFile)) {
                    $existTitle = $toObject->getShortLengthVideoFiveSeconds()->getTitle();

                    if (is_null($value)) {
                        $deleteFiles[] = $existFile;
                    } else {
                        if ($realPath != $existFile->getRealFilePath()) {
                            $deleteFiles[] = $existFile;
                            $newFile = true;
                        } else {
                            if ($realPath === $existFile->getRealFilePath() && $video5sTitle !== $existTitle) {
                                $uploadedFile = $this->companyUploadedFileRepository->findOneByCriteria(
                                    CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                                        [
                                            "company.id" => $inputPort->companyId,
                                            "contentType" => CompanyUploadedFile::FILE_TYPE_PR_SHORT_LENGTH_MOVIE_FIVE_SECONDS,
                                            "fileType" => CompanyUploadedFile::MOVIE_CONTENT,
                                        ]
                                    )
                                );
                                $uploadedFile->setTitle($video5sTitle);
                                $this->companyUploadedFileRepository->saveOrUpdate($uploadedFile, true);
                            }
                        }
                    }
                } else {
                    if (isset($value)) {
                        $newFile = true;
                    }
                }

                if ($newFile && file_exists($realPath)) {
                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = new CompanyUploadedFile();
                    $uploadedFile->setCompany($toObject);
                    $uploadedFile->setFileName($value);
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                    $uploadedFile->setPhysicalFileName($physicalFileName);
                    if (isset($video5sTitle)) {
                        $uploadedFile->setTitle($video5sTitle);
                    }
                    $toObject->setShortLengthVideoFiveSeconds($uploadedFile);
                    $newFiles[$realPath] = $uploadedFile;
                }
            },
            // 5秒動画サムネイル動画
            "video5sThumbName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video5sThumbPath;

                $newFile = false;
                $existFile = $toObject->getShortLengthVideoThumbnailFiveSeconds();
                if (isset($existFile)) {
                    if (is_null($value)) {
                        $deleteFiles[] = $existFile;
                    } else {
                        if ($realPath != $existFile->getRealFilePath()) {
                            $deleteFiles[] = $existFile;
                            $newFile = true;
                        }
                    }
                } else {
                    if (isset($value)) {
                        $newFile = true;
                    }
                }

                if ($newFile && file_exists($realPath)) {
                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = new CompanyUploadedFile();
                    $uploadedFile->setCompany($toObject);
                    $uploadedFile->setFileName($value);
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                    $uploadedFile->setPhysicalFileName($physicalFileName);
                    $toObject->setShortLengthVideoThumbnailFiveSeconds($uploadedFile);
                    $newFiles[$realPath] = $uploadedFile;
                }
            },
            // 10秒動画
            "video10sName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video10sPath;
                $video10sTitle = $inputPort->video10sTitle;

                $newFile = false;
                $existFile = $toObject->getShortLengthVideoTenSeconds();
                if (isset($existFile)) {
                    $existTitle = $toObject->getShortLengthVideoTenSeconds()->getTitle();

                    if (is_null($value)) {
                        $deleteFiles[] = $existFile;
                    } else {
                        if ($realPath != $existFile->getRealFilePath()) {
                            $deleteFiles[] = $existFile;
                            $newFile = true;
                        } else {
                            if ($realPath === $existFile->getRealFilePath() && $video10sTitle !== $existTitle) {
                                $uploadedFile = $this->companyUploadedFileRepository->findOneByCriteria(
                                    CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                                        [
                                            "company.id" => $inputPort->companyId,
                                            "contentType" => CompanyUploadedFile::FILE_TYPE_PR_SHORT_LENGTH_MOVIE_TEN_SECONDS,
                                            "fileType" => CompanyUploadedFile::MOVIE_CONTENT,
                                        ]
                                    )
                                );
                                $uploadedFile->setTitle($video10sTitle);
                                $this->companyUploadedFileRepository->saveOrUpdate($uploadedFile, true);
                            }
                        }
                    }
                } else {
                    if (isset($value)) {
                        $newFile = true;
                    }
                }

                if ($newFile && file_exists($realPath)) {
                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = new CompanyUploadedFile();
                    $uploadedFile->setCompany($toObject);
                    $uploadedFile->setFileName($value);
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                    $uploadedFile->setPhysicalFileName($physicalFileName);
                    if (isset($video10sTitle)) {
                        $uploadedFile->setTitle($video10sTitle);
                    }
                    $toObject->setShortLengthVideoTenSeconds($uploadedFile);
                    $newFiles[$realPath] = $uploadedFile;
                }
            },
            // 10秒動画サムネイル動画
            "video10sThumbName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video10sThumbPath;

                $newFile = false;
                $existFile = $toObject->getShortLengthVideoThumbnailTenSeconds();
                if (isset($existFile)) {
                    if (is_null($value)) {
                        $deleteFiles[] = $existFile;
                    } else {
                        if ($realPath != $existFile->getRealFilePath()) {
                            $deleteFiles[] = $existFile;
                            $newFile = true;
                        }
                    }
                } else {
                    if (isset($value)) {
                        $newFile = true;
                    }
                }

                if ($newFile && file_exists($realPath)) {
                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = new CompanyUploadedFile();
                    $uploadedFile->setCompany($toObject);
                    $uploadedFile->setFileName($value);
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                    $uploadedFile->setPhysicalFileName($physicalFileName);
                    $toObject->setShortLengthVideoThumbnailTenSeconds($uploadedFile);
                    $newFiles[$realPath] = $uploadedFile;
                }
            },
            // 15秒動画
            "video15sName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video15sPath;
                $video15sTitle = $inputPort->video15sTitle;

                $newFile = false;
                $existFile = $toObject->getShortLengthVideoFifteenSeconds();
                if (isset($existFile)) {
                    $existTitle = $toObject->getShortLengthVideoFifteenSeconds()->getTitle();

                    if (is_null($value)) {
                        $deleteFiles[] = $existFile;
                    } else {
                        if ($realPath != $existFile->getRealFilePath()) {
                            $deleteFiles[] = $existFile;
                            $newFile = true;
                        } else {
                            if ($realPath === $existFile->getRealFilePath() && $video15sTitle !== $existTitle) {
                                $uploadedFile = $this->companyUploadedFileRepository->findOneByCriteria(
                                    CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                                        [
                                            "company.id" => $inputPort->companyId,
                                            "contentType" => CompanyUploadedFile::FILE_TYPE_PR_SHORT_LENGTH_MOVIE_FIFTEEN_SECONDS,
                                            "fileType" => CompanyUploadedFile::MOVIE_CONTENT,
                                        ]
                                    )
                                );
                                $uploadedFile->setTitle($video15sTitle);
                                $this->companyUploadedFileRepository->saveOrUpdate($uploadedFile, true);
                            }
                        }
                    }
                } else {
                    if (isset($value)) {
                        $newFile = true;
                    }
                }

                if ($newFile && file_exists($realPath)) {
                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = new CompanyUploadedFile();
                    $uploadedFile->setCompany($toObject);
                    $uploadedFile->setFileName($value);
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                    $uploadedFile->setPhysicalFileName($physicalFileName);
                    if (isset($video15sTitle)) {
                        $uploadedFile->setTitle($video15sTitle);
                    }
                    $toObject->setShortLengthVideoFifteenSeconds($uploadedFile);
                    $newFiles[$realPath] = $uploadedFile;
                }
            },
            // 15秒動画サムネイル動画
            "video15sThumbName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video15sThumbPath;

                $newFile = false;
                $existFile = $toObject->getShortLengthVideoThumbnailFifteenSeconds();
                if (isset($existFile)) {
                    if (is_null($value)) {
                        $deleteFiles[] = $existFile;
                    } else {
                        if ($realPath != $existFile->getRealFilePath()) {
                            $deleteFiles[] = $existFile;
                            $newFile = true;
                        }
                    }
                } else {
                    if (isset($value)) {
                        $newFile = true;
                    }
                }

                if ($newFile && file_exists($realPath)) {
                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = new CompanyUploadedFile();
                    $uploadedFile->setCompany($toObject);
                    $uploadedFile->setFileName($value);
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                    $uploadedFile->setPhysicalFileName($physicalFileName);
                    $toObject->setShortLengthVideoThumbnailFifteenSeconds($uploadedFile);
                    $newFiles[$realPath] = $uploadedFile;
                }
            },
            // 当社の特徴
            "featureNames" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $existFiles = [];
                $companyIntroductions = $toObject->getCompanyUploadedFiles();
                foreach ($companyIntroductions as $companyIntroduction) {
                    $contentType = $companyIntroduction->getContentType();
                    if ($contentType === CompanyUploadedFile::FILE_TYPE_INTRODUCTION) {
                        $existFiles[$companyIntroduction->getRealFilePath()] = $companyIntroduction;
                    }
                }

                $names = $inputPort->featureNames;
                $paths = $inputPort->featurePaths;
                $titles = $inputPort->featureTitles;
                $descriptions = $inputPort->featureDescriptions;
                $newCompanyIntroductions = [];
                foreach ($names as $index => $name) {
                    if (isset($paths[$index])) {
                        $path = STORAGE_PUBLIC_DIR_PATH . DS . $paths[$index];
                        if (isset($existFiles[$path])) {
                            $existFiles[$path]->setSortNumber($index + 1);
                            if (isset($titles) && isset($titles[$index])) {
                                $existFiles[$path]->setTitle($titles[$index]);
                            }
                            if (isset($descriptions) && isset($descriptions[$index])) {
                                $existFiles[$path]->setDescription($descriptions[$index]);
                            }
                            unset($existFiles[$path]);
                        } else {
                            if (file_exists($path)) {
                                $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $path);
                                $uploadedFile = new CompanyUploadedFile();
                                $uploadedFile->setCompany($toObject);
                                $uploadedFile->setFileName($name);
                                $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                                $uploadedFile->setPhysicalFileName($physicalFileName);
                                $uploadedFile->setSortNumber($index + 1);
                                if (isset($titles) && isset($titles[$index])) {
                                    $uploadedFile->setTitle($titles[$index]);
                                }
                                if (isset($descriptions) && isset($descriptions[$index])) {
                                    $uploadedFile->setDescription($descriptions[$index]);
                                }
                                $newCompanyIntroductions[] = $uploadedFile;
                                $newFiles[$path] = $uploadedFile;
                            }
                        }
                    }
                }
                $toObject->setFeatures($newCompanyIntroductions);
                foreach ($existFiles as $existFile) {
                    $deleteFiles[] = $existFile;
                }
            },
            // ハッシュタグ
            "hashtag" => function ($value, $inputPort, $toObject) use ($businessTypeRepository) {
                /** @var Company $toObject */
                $hashTag = $toObject->getHashTag();
                if (is_null($hashTag)) {
                    $hashTag = new Tag();
                    $hashTag->setFixingFlag(Tag::HASH_TAG);
                }
                $hashTag->setName($value);
                $hashTag->setColor($inputPort->hashTagColor);
                $toObject->setHashTag($hashTag);
            },
        ]);

        // 当社の特徴の入力がない場合は既存のデータを削除
        if (is_null($inputPort->featureNames)) {
            $companyIntroductions = $company->getFeatures();
            foreach ($companyIntroductions as $companyIntroduction) {
                $deleteFiles[] = $companyIntroduction;
            }
        }

        // 募集タグ
        $recruitmentTags = [];
        $existTags = [];
        $existRecruitmentTags = $company->getRecruitmentTags();
        foreach ($existRecruitmentTags as $existRecruitmentTag) {
            $existTags[$existRecruitmentTag->getName()] = $existRecruitmentTag;
        }
        $recruitmentTargetYear = $inputPort->recruitmentTargetYear;
        if ($recruitmentTargetYear === '1') {
            if (isset($existTags[Tag::RECRUIT_TAG_LIST[Tag::THIS_YEAR]])) {
                $recruitmentTags[] = $existTags[Tag::RECRUIT_TAG_LIST[Tag::THIS_YEAR]];
                unset($existTags[Tag::RECRUIT_TAG_LIST[Tag::THIS_YEAR]]);
            } else {
                $recruitmentTags[] = $this->createRecruitmentTags(Tag::THIS_YEAR, $company);
            }
        }
        $recruitmentTargetThisYear = $inputPort->recruitmentTargetThisYear;
        if ($recruitmentTargetThisYear === '1') {
            if (isset($existTags[Tag::RECRUIT_TAG_LIST[Tag::NEXT_YEAR]])) {
                $recruitmentTags[] = $existTags[Tag::RECRUIT_TAG_LIST[Tag::NEXT_YEAR]];
                unset($existTags[Tag::RECRUIT_TAG_LIST[Tag::NEXT_YEAR]]);
            } else {
                $recruitmentTags[] = $this->createRecruitmentTags(Tag::NEXT_YEAR, $company);
            }
        }
        $recruitmentTargetIntern = $inputPort->recruitmentTargetIntern;
        if ($recruitmentTargetIntern === '1') {
            if (isset($existTags[Tag::RECRUIT_TAG_LIST[Tag::INTERN]])) {
                $recruitmentTags[] = $existTags[Tag::RECRUIT_TAG_LIST[Tag::INTERN]];
                unset($existTags[Tag::RECRUIT_TAG_LIST[Tag::INTERN]]);
            } else {
                $recruitmentTags[] = $this->createRecruitmentTags(Tag::INTERN, $company);
            }
        }
        $company->setRecruitmentTags($recruitmentTags);

        // 企業保存
        $this->companyRepository->saveOrUpdate($company, true);

        // タグ削除
        $this->tagRepository->delete($existTags);

        // アップロードファイル削除
        $this->companyUploadedFileRepository->delete($deleteFiles);

        foreach ($newFiles as $path => $file) {
            File::createDir($file->getRealFileDir());
            File::rename($path, $file->getRealFilePath());
        }

        foreach ($deleteFiles as $file) {
            File::remove($file->getRealFilePath());
        }

        // アカウント情報
        $companyAccounts = $company->getCompanyAccounts();
        foreach ($companyAccounts as $companyAccount) {
            $userAccount = $companyAccount->getUserAccount();
            $beforeMailAddress = $userAccount->getMailAddress();
            Data::mappingToObject($inputPort,$userAccount);
            Data::mappingToObject($inputPort,$companyAccount);
            $companyAccount->setRepresentativeSetting(10);
            $userAccount->setCompanyAccount($companyAccount);
            $companyAccount->setUserAccount($userAccount);
            $companyAccount->setCompany($company);
        }

        // メールアドレス重複チェック
        $this->checkDuplicateMailAddress($beforeMailAddress, $inputPort->mailAddress, $company->getId());

        $this->companyAccountRepository->saveOrUpdate($companyAccount, true);

        // 企業ID
        $outputPort->companyId = $company->getId();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 募集タグ作成
     *
     * @param int $type
     * @param Company $company
     * @return Tag
     */
    private function createRecruitmentTags(int $type, Company $company): Tag
    {
        $recruitmentTag = new Tag();
        $recruitmentTag->setCompany($company);
        $recruitmentTag->setFixingFlag(Tag::RECRUIT_TAG);
        $recruitmentTag->setName(Tag::RECRUIT_TAG_LIST[$type]);
        return $recruitmentTag;
    }

    /**
     * アップロードファイルデータ作成
     *
     * @param CompanyUploadedFile|null $uploadedFile
     * @return array
     */
    private function createFileData(?CompanyUploadedFile $uploadedFile): array
    {
        $result = [];
        if (isset($uploadedFile)) {
            $result = [
                "name" => $uploadedFile->getFileName(),
                "url" => $uploadedFile->getFilePathForClientShow(),
                "path" => $uploadedFile->getFilePath(),
                "checked" => $uploadedFile->getViewSelected(),
                "title" => $uploadedFile->getTitle(),
                "description" => $uploadedFile->getDescription(),
                "type" => $uploadedFile->getFileType(),
            ];
        }
        return $result;
    }

    /**
     * アカウントリスト作成
     *
     * @param Company $company
     * @return array
     */
    private function createAccountList(Company $company): array
    {
        $result = [];
        $companyAccounts = $company->getCompanyAccounts();
        foreach ($companyAccounts as $companyAccount) {
            $userAccount = $companyAccount->getUserAccount();

            $lastLoginDatetime = $userAccount->getLastLoginDateTime();
            if (!empty($lastLoginDatetime)) {
                $lastLoginDatetime = date("Y/m/d H:i", strtotime($lastLoginDatetime));
                $lastLoginDatetime = $lastLoginDatetime . ' 最終ログイン';
            } else {
                $lastLoginDatetime = '未ログイン';
            }

            $result[] = [
                'firstName' => $companyAccount->getFirstName(),
                'lastName' => $companyAccount->getLastName(),
                'firstNameKana' => $companyAccount->getFirstNameKana(),
                'lastNameKana' => $companyAccount->getLastNameKana(),
                'mailaddress' => $userAccount->getMailAddress(),
                'password' => $userAccount->getPassword(),
                'lastLoginDatetime' => $lastLoginDatetime
            ];
        }
        return $result;
    }

    /**
     * メールアドレスの重複チェックを行う
     *
     * @param string $beforeMailAddress
     * @param string $mailAddress
     * @param int $userAccountId
     * @throws BusinessException
     */
    private function checkDuplicateMailAddress(string $beforeMailAddress, string $mailAddress, int $userAccountId)
    {
        $userAccountSameMailAddress = $this->userAccountRepository->findByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "mailAddress" => $mailAddress
                ]
            )
        );
        if (count($userAccountSameMailAddress) > 0) {
            foreach ($userAccountSameMailAddress as $userAccount) {
                if (($userAccount->getCompanyAccount() !== null && $userAccount->getId() !== $userAccountId && $userAccount->getCompanyAccount()->getDeletedAt() === null) && $mailAddress !== $beforeMailAddress)
                {
                    // 削除済み企業アカウントとの重複ではない場合、エラーを出力
                    throw new BusinessException('duplication.mail_address');
                }
            }
        }
    }
}
