<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Interactors\Admin\JobApplicationPreview\JobApplicationPreviewInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationPreview\JobApplicationPreviewInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationPreview\JobApplicationPreviewInitializeOutputPort;
use App\Domain\Entities\JobApplication;
use App\Domain\Entities\Tag;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\Exception;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class JobApplicationPreviewUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class JobApplicationPreviewUseCase implements JobApplicationPreviewInitializeInteractor
{
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var JobApplicationRepository
     */
    private $jobApplicationRepository;

    /**
     * JobApplicationPreviewUseCase constructor.
     *
     * @param CompanyRepository $companyRepository
     * @param JobApplicationRepository $jobApplicationRepository
     */
    public function __construct(
        CompanyRepository $companyRepository,
        JobApplicationRepository $jobApplicationRepository
    ) {
        $this->companyRepository = $companyRepository;
        $this->jobApplicationRepository = $jobApplicationRepository;
    }

    /**
     * プレビューを表示する
     *
     * @param JobApplicationPreviewInitializeInputPort $inputPort
     * @param JobApplicationPreviewInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function preview(JobApplicationPreviewInitializeInputPort $inputPort, JobApplicationPreviewInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        try {
            $company = $this->companyRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        'id' => $inputPort->companyId
                    ])
            );
        } catch (Exception $e) {
            throw new FatalBusinessException('preview_target_not_found');
        }

        // 企業詳細取得
        $introductorySentence = $company->getIntroductorySentence();
        $outputPort->introductorySentence = $introductorySentence;

        // 企業ロゴ取得
        $companyLogoImage = $company->getCompanyLogoImage();
        $companyLogoFilePath = $companyLogoImage->getFilePathForFrontShow();
        $outputPort->companyLogoFilePath = $companyLogoFilePath;

        // 募集タグ取得
        $recruitTagList = [];
        $recruitmentTags = $company->getRecruitmentTags();
        foreach ($recruitmentTags as $recruitmentTag) {
            $recruitmentTagName = $recruitmentTag->getName();
            $recruitTagList[] = $recruitmentTagName;
        }
        $outputPort->recruitTagList = $recruitTagList;

        // ハッシュタグ取得
        $hashTag = $company->getHashTag();
        $hashTagName = $hashTag->getName();
        $hashTagColor = Tag::TAG_COLLAR_CLASS_LIST[$hashTag->getColor()];
        $outputPort->hashTagName = $hashTagName;
        $outputPort->hashTagColor = $hashTagColor;

        // 企業PR動画を取得
        $companyPrVideo = $company->getCompanyPrVideo();
        if (isset($companyPrVideo)) {
            $companyPrVideoFilePath = $companyPrVideo->getFilePathForFrontShow();
            $outputPort->companyPrVideoFilePath = $companyPrVideoFilePath;
        } else {
            // PR動画がない場合に企業画像を取得
            $companyImages = $company->getCompanyImages();
            $companyImageFilePathList = [];
            foreach ($companyImages as $companyImag) {
                $companyImageFilePath = $companyImag->getFilePathForFrontShow();
                $companyImageFilePathList[] = $companyImageFilePath;
            }
            $outputPort->companyImageFilePathList = $companyImageFilePathList;
        }

        $shortLengthVideos = [];
        $shortLengthVideoThumbnails = [];
        // 短尺5秒動画取得
        $ShortLengthVideoFiveSeconds = $company->getShortLengthVideoFiveSeconds();
        if (isset($ShortLengthVideoFiveSeconds)) {
            $shortLengthVideoFiveSecondsFilePath = $ShortLengthVideoFiveSeconds->getFilePathForFrontShow();
            $shortLengthVideos['five'] = $shortLengthVideoFiveSecondsFilePath;

            // 短尺5秒動画タイトル取得
            $ShortLengthVideoFiveSecondsTitle = $ShortLengthVideoFiveSeconds->getTitle();
            if (isset($ShortLengthVideoFiveSecondsTitle)) {
                $shortLength5sVideoTitle = $ShortLengthVideoFiveSecondsTitle;
            } else {
                $shortLength5sVideoTitle = null;
            }
            $outputPort->shortLength5sVideoTitle = $shortLength5sVideoTitle;

            // 短尺5秒動画サムネイル取得
            $ShortLengthVideoThumbnailFiveSeconds = $company->getShortLengthVideoThumbnailFiveSeconds();
            if (isset($ShortLengthVideoThumbnailFiveSeconds)) {
                $shortLengthVideoThumbnailFiveSecondsFilePath = $ShortLengthVideoThumbnailFiveSeconds->getFilePathForFrontShow();
                $shortLength5sVideoThumbnail = $shortLengthVideoThumbnailFiveSecondsFilePath;
            } else {
                $shortLength5sVideoThumbnail = null;
            }
            $outputPort->shortLength5sVideoThumbnail = $shortLength5sVideoThumbnail;
        }

        // 短尺10秒動画取得
        $ShortLengthVideoTenSeconds = $company->getShortLengthVideoTenSeconds();
        if (isset($ShortLengthVideoTenSeconds)) {
            $shortLengthVideoTenSecondsFilePath = $ShortLengthVideoTenSeconds->getFilePathForFrontShow();
            $shortLengthVideos['ten'] = $shortLengthVideoTenSecondsFilePath;

            // 短尺10秒動画タイトル取得
            $ShortLengthVideoTenSecondsTitle = $ShortLengthVideoTenSeconds->getTitle();
            if (isset($ShortLengthVideoTenSecondsTitle)) {
                $shortLength10sVideoTitle = $ShortLengthVideoTenSecondsTitle;
            } else {
                $shortLength10sVideoTitle = null;
            }
            $outputPort->shortLength10sVideoTitle = $shortLength10sVideoTitle;

            // 短尺10秒動画サムネイル取得
            $ShortLengthVideoThumbnailTenSeconds = $company->getShortLengthVideoThumbnailTenSeconds();
            if (isset($ShortLengthVideoThumbnailTenSeconds)) {
                $shortLengthVideoThumbnailTenSecondsFilePath = $ShortLengthVideoThumbnailTenSeconds->getFilePathForFrontShow();
                $shortLength10sVideoThumbnail = $shortLengthVideoThumbnailTenSecondsFilePath;
            } else {
                $shortLength10sVideoThumbnail = null;
            }
            $outputPort->shortLength10sVideoThumbnail = $shortLength10sVideoThumbnail;
        }

        // 短尺15秒動画取得
        $ShortLengthVideoFifteenSeconds = $company->getShortLengthVideoFifteenSeconds();
        if (isset($ShortLengthVideoFifteenSeconds)) {
            $shortLengthVideoFifteenSecondsFilePath = $ShortLengthVideoFifteenSeconds->getFilePathForFrontShow();
            $shortLengthVideos['fifteen'] = $shortLengthVideoFifteenSecondsFilePath;

            // 短尺15秒動画タイトル取得
            $ShortLengthVideoFifteenSecondsTitle = $ShortLengthVideoFifteenSeconds->getTitle();
            if (isset($ShortLengthVideoFifteenSecondsTitle)) {
                $shortLength15sVideoTitle = $ShortLengthVideoFifteenSecondsTitle;
            } else {
                $shortLength15sVideoTitle = null;
            }
            $outputPort->shortLength15sVideoTitle = $shortLength15sVideoTitle;

            // 短尺15秒動画サムネイル取得
            $ShortLengthVideoThumbnailFifteenSeconds = $company->getShortLengthVideoThumbnailFifteenSeconds();
            if (isset($ShortLengthVideoThumbnailFifteenSeconds)) {
                $shortLengthVideoThumbnailFifteenSecondsFilePath = $ShortLengthVideoThumbnailFifteenSeconds->getFilePathForFrontShow();
                $shortLength15sVideoThumbnail = $shortLengthVideoThumbnailFifteenSecondsFilePath;
            } else {
                $shortLength15sVideoThumbnail = null;
            }
            $outputPort->shortLength15sVideoThumbnail = $shortLength15sVideoThumbnail;
        }

        $outputPort->shortLengthVideos = $shortLengthVideos;

        // 求人詳細取得
        $criteriaFactory = CriteriaFactory::getInstance();
        $jobApplications = $this->jobApplicationRepository->findByCriteria(
            $criteriaFactory->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "company.id" => $company->getId()
                ]
            )
        );
        $displayJobApplications = [];
        foreach ($jobApplications as $jobApplication) {
            if ($jobApplication->getStatus() === JobApplication::STATUS_DISPLAY) {
                $displayJobApplications[] = $jobApplication;
            }
        }
        $outputPort->jobApplications = null;
        if (!empty($displayJobApplications)){
            $outputPort->jobApplications = $displayJobApplications;
        }
        $outputPort->employmentTypeList = JobApplication::EMPLOYMENT_TYPE_LIST;

        // 当社の紹介取得
        $companyIntroductions = $company->getFeatures();
        $companyIntroductionList = [];
        $companyIntroductionListsList = [];
        foreach ($companyIntroductions as $companyIntroduction) {
            if (isset($companyIntroduction)) {
                $title = $companyIntroduction->getTitle();
                $description = $companyIntroduction->getDescription();
                $companyIntroductionFilePath = $companyIntroduction->getFilePathForFrontShow();
                $companyIntroductionList["title"] = $title;
                $companyIntroductionList["description"] = $description;
                $companyIntroductionList["filePath"] = $companyIntroductionFilePath;
                $companyIntroductionListsList[] = $companyIntroductionList;
            }
            $outputPort->companyIntroductionListsList = $companyIntroductionListsList;
        }

        // 企業情報取得
        $outputPort->name = $company->getName();
        $outputPort->descriptionOfBusiness = $company->getDescriptionOfBusiness();
        $outputPort->establishmentDate = $company->getEstablishmentDate();
        $outputPort->capital = $company->getCapital();
        $outputPort->representativePerson = $company->getRepresentativePerson();
        $outputPort->exectiveOfficers = $company->getExectiveOfficers();
        $outputPort->establishment = $company->getEstablishment();
        $outputPort->affiliatedCompany = $company->getAffiliatedCompany();
        $outputPort->qualification = $company->getQualification();
        $outputPort->homePageUrl = $company->getHomePageUrl();
        $outputPort->recruitmentUrl = $company->getRecruitmentUrl();
        $outputPort->mainClient = $company->getMainClient();

        // ログ出力
        Log::infoOut();
    }
}
