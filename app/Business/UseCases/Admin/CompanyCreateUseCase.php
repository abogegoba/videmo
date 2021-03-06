<?php


namespace App\Business\UseCases\Admin;


use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyUploadedFileRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Gateways\Repositories\TagRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Admin\CompanyCreate\CompanyCreateInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\CompanyCreate\CompanyCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyCreate\CompanyCreateInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\CompanyCreate\CompanyCreateStoreInputPort;
use App\Business\Interfaces\Interactors\Admin\CompanyCreate\CompanyCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyCreate\CompanyCreateStoreOutputPort;
use App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit\CompanyBasicInformationEditStoreInputPort;
use App\Business\Services\ListCreateTrait;
use App\Domain\Entities\Company;
use App\Domain\Entities\CompanyAccount;
use App\Domain\Entities\CompanyUploadedFile;
use App\Domain\Entities\Member;
use App\Domain\Entities\Tag;
use App\Domain\Entities\UserAccount;
use App\Utilities\Log;
use Illuminate\Support\Facades\Storage;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Utilities\File;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

class CompanyCreateUseCase implements CompanyCreateInitializeInteractor, CompanyCreateStoreInteractor
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
     * ???????????????
     *
     * @param CompanyCreateInitializeInputPort $inputPort
     * @param CompanyCreateInitializeOutputPort $outputPort
     */
    public function initialize(CompanyCreateInitializeInputPort $inputPort, CompanyCreateInitializeOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        // ?????????????????????
        $outputPort->prefectureList = $this->createPrefectureList();

        // ???????????????
        $outputPort->businessTypeList = $this->createBusinessTypeList();

        // ????????????
        $outputPort->uploadedLogo = [];
        // ????????????
        $outputPort->companyImages = [];
        // PR??????
        $outputPort->prVideo = [];
        // 5?????????
        $outputPort->video5s = [];
        // 5??????????????????????????????
        $outputPort->video5sThumb = [];
        // 10?????????
        $outputPort->video10s = [];
        // 10??????????????????????????????
        $outputPort->video10sThumb = [];
        // 15?????????
        $outputPort->video15s = [];
        // 15??????????????????????????????
        $outputPort->video15sThumb = [];
        // ???????????????
        $outputPort->features = [];

        //????????????
        Log::infoOut();
    }

    /**
     * @param CompanyCreateStoreInputPort $inputPort
     * @param CompanyCreateStoreOutputPort $outputPort
     * @throws BusinessException
     */
    public function store(CompanyCreateStoreInputPort $inputPort, CompanyCreateStoreOutputPort $outputPort): void
    {
        // ????????????
        Log::infoIn();

        // ??????
        $company = new Company();
        $prefectureRepository = $this->prefectureRepository;
        $businessTypeRepository = $this->businessTypeRepository;
        Data::mappingToObject($inputPort, $company, [
            // ????????????
            "zip" => function ($value, $inputPort, $toObject) {
                /** @var Company $toObject */
                $toObject->setZipCode($value);
            },
            // ????????????
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
            // ????????????????????????
            "room" => function ($value, $inputPort, $toObject) {
                /** @var Company $toObject */
                $toObject->setBlockNumber($value);
            },
            // ??????
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
            // ????????????
            "uploadedLogoName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->uploadedLogoPath;
                $newFile = false;

                if (isset($value)){
                    $newFile = true;
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
                } else {
                    copy(STORAGE_PUBLIC_DIR_PATH . DS . "no_image_logo.png", STORAGE_PUBLIC_TEMP_DIR_PATH . DS . "no_image_logo.png");
                    $realPath = STORAGE_PUBLIC_TEMP_DIR_PATH . DS . "no_image_logo.png";
                    $value = "no_image_logo.png";

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
            // ????????????
            "companyImageNames" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */

                $names = $inputPort->companyImageNames;
                $paths = $inputPort->companyImagePaths;
                $checked = $inputPort->displayImage;
                $newCompanyImages = [];
                    foreach ($names as $index => $name) {
                        if (isset($paths[$index])) {
                            $path = STORAGE_PUBLIC_DIR_PATH . DS . $paths[$index];
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
                        } else {
                            copy(STORAGE_PUBLIC_DIR_PATH . DS . "no_image_photo.png", STORAGE_PUBLIC_TEMP_DIR_PATH . DS . "no_image_photo.png");
                            $path = STORAGE_PUBLIC_TEMP_DIR_PATH . DS . "no_image_photo.png";
                            $name = "no_image_photo.png";

                            $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $path);
                            $uploadedFile = new CompanyUploadedFile();
                            $uploadedFile->setCompany($toObject);
                            $uploadedFile->setFileName($name);
                            $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                            $uploadedFile->setPhysicalFileName($physicalFileName);
                            $uploadedFile->setSortNumber( 1);
                            $uploadedFile->setViewSelected(true);
                            $newCompanyImages[] = $uploadedFile;
                            $newFiles[$path] = $uploadedFile;
                        }
                    }

                $toObject->setCompanyImages($newCompanyImages);
            },
             // ??????????????????
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

        // ????????????
        $recruitmentTags = [];

        $recruitmentTargetYear = $inputPort->recruitmentTargetYear;
        if ($recruitmentTargetYear === '1') {
            $recruitmentTags[] = $this->createRecruitmentTags(Tag::THIS_YEAR, $company);
        }
        $recruitmentTargetThisYear = $inputPort->recruitmentTargetThisYear;
        if ($recruitmentTargetThisYear === '1') {
                $recruitmentTags[] = $this->createRecruitmentTags(Tag::NEXT_YEAR, $company);
        }
        $recruitmentTargetIntern = $inputPort->recruitmentTargetIntern;
        if ($recruitmentTargetIntern === '1') {
                $recruitmentTags[] = $this->createRecruitmentTags(Tag::INTERN, $company);
        }
        $company->setRecruitmentTags($recruitmentTags);

        // ????????????
        $this->companyRepository->saveOrUpdate($company, true);

        $outputPort->companyId = $company->getId();

        // ???????????????????????????????????????
        $this->checkDuplicateMailAddress($inputPort->mailAddress, $outputPort->companyId);

        // ???????????????????????????
        $userAccount = new UserAccount();
        Data::mappingToObject($inputPort,$userAccount);
        
        // ?????????????????????
        $companyAccount = new CompanyAccount();
        Data::mappingToObject($inputPort,$companyAccount);
        $companyAccount->setRepresentativeSetting(10);
        $userAccount->setCompanyAccount($companyAccount);
        $companyAccount->setUserAccount($userAccount);
        $companyAccount->setCompany($company);

        $this->companyAccountRepository->saveOrUpdate($companyAccount, true);

        Data::mappingToObject($inputPort, $company, [
            // ????????????
            "uploadedLogoName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->uploadedLogoPath;

                $newFile = false;

                if (isset($value)){
                    $newFile = true;
                }

                if ($newFile && file_exists($realPath)) {
                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = $toObject->getCompanyLogoImage();
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                } else {
                    $realPath = STORAGE_PUBLIC_TEMP_DIR_PATH . DS . "no_image_logo.png";

                    $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPath);
                    $uploadedFile = $toObject->getCompanyLogoImage();
                    $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                }
            },
            // ????????????
            "companyImageNames" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */

                $names = $inputPort->companyImageNames;
                $paths = $inputPort->companyImagePaths;
                $newCompanyImages = [];
                    foreach ($names as $index => $name) {
                        if (isset($paths[$index])) {
                            $path = STORAGE_PUBLIC_DIR_PATH . DS . $paths[$index];
                            if (file_exists($path)) {
                                $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $path);
                                $uploadedFile = $toObject->getCompanyImages()[$index];
                                $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                            }
                        } else {
                            $path = STORAGE_PUBLIC_TEMP_DIR_PATH . DS . "no_image_photo.png";

                            $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $path);
                            $uploadedFile = $toObject->getCompanyImages()[$index];
                            $uploadedFile->setViewSelected(true);
                            $uploadedFile->setFilePath("company/" . $toObject->getId() . "/" . $physicalFileName);
                        }
                    }

                $toObject->setCompanyImages($newCompanyImages);
            },
            // PR??????
            "prVideoName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->prVideoPath;

                $newFile = false;

                if (isset($value)){
                    $newFile = true;
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
            // 5?????????
            "video5sName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video5sPath;
                $video5sTitle = $inputPort->video5sTitle;

                $newFile = false;

                if (isset($value)){
                    $newFile = true;
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
            // 5??????????????????????????????
            "video5sThumbName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video5sThumbPath;

                $newFile = false;

                if (isset($value)){
                    $newFile = true;
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
            // 10?????????
            "video10sName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video10sPath;
                $video10sTitle = $inputPort->video10sTitle;

                $newFile = false;

                if (isset($value)){
                    $newFile = true;
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
            // 10??????????????????????????????
            "video10sThumbName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video10sThumbPath;

                $newFile = false;

                if (isset($value)){
                    $newFile = true;
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
            // 15?????????
            "video15sName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video15sPath;
                $video15sTitle = $inputPort->video15sTitle;

                $newFile = false;

                if (isset($value)){
                    $newFile = true;
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
            // 15??????????????????????????????
            "video15sThumbName" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */
                $realPath = STORAGE_PUBLIC_DIR_PATH . DS . $inputPort->video15sThumbPath;

                $newFile = false;

                if (isset($value)){
                    $newFile = true;
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
            // ???????????????
            "featureNames" => function ($value, $inputPort, $toObject) use (&$newFiles, &$deleteFiles) {
                /** @var string $value */
                /** @var CompanyBasicInformationEditStoreInputPort $inputPort */
                /** @var Company $toObject */

                $names = $inputPort->featureNames;
                $paths = $inputPort->featurePaths;
                $titles = $inputPort->featureTitles;
                $descriptions = $inputPort->featureDescriptions;
                $newCompanyIntroductions = [];
                foreach ($names as $index => $name) {
                    if (isset($paths[$index])) {
                        $path = STORAGE_PUBLIC_DIR_PATH . DS . $paths[$index];
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
                $toObject->setFeatures($newCompanyIntroductions);
            },
            // ??????????????????
            "hashtag" => function ($value, $inputPort, $toObject) use ($businessTypeRepository) {
                /** @var Company $toObject */
                $hashTag = $toObject->getHashTag();
                if (is_null($hashTag)) {
                    $hashTag = new Tag();
                    $hashTag->setFixingFlag(Tag::HASH_TAG);
                }
                $hashTag->setName($value);
                $hashTag->setColor($inputPort->hashTagColor);
                $hashTag->setCompany($toObject);
                $toObject->setHashTag($hashTag);
            },
        ]);

        // ????????????
        $this->companyRepository->saveOrUpdate($company, true);

        foreach ($newFiles as $path => $file) {
            File::createDir($file->getRealFileDir());
            File::rename($path, $file->getRealFilePath());
        }

        // ????????????
        Log::infoOut();
    }

    /**
     * ??????????????????
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
     * ???????????????????????????????????????????????????
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

                if (($userAccount->getCompanyAccount() !== null && $userAccount->getId() !== $userAccountId && $userAccount->getCompanyAccount()->getDeletedAt() === null))
                {
                    // ????????????????????????????????????????????????????????????????????????????????????
                    throw new BusinessException('duplication.mail_address');
                }
            }
        }
    }

}