<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\TagRepository;
use App\Business\Interfaces\Interactors\Front\CompanyDetail\CompanyDetailInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\CompanyDetail\CompanyDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\CompanyDetail\CompanyDetailInitializeOutputPort;
use App\Domain\Entities\Company;
use App\Domain\Entities\CompanyAccount;
use App\Domain\Entities\JobApplication;
use App\Domain\Entities\Tag;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Doctrine\Criteria\GeneralDoctrineCriteria;

/**
 * Class CompanyDetailUseCase
 *
 * @package App\Business\UseCases\Front
 */
class CompanyDetailUseCase implements CompanyDetailInitializeInteractor
{
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * CompanyDetailUseCase constructor.
     *
     * @param CompanyRepository $companyRepository
     * @param TagRepository $tagRepository
     */
    public function __construct(
        CompanyRepository $companyRepository,
        TagRepository $tagRepository
    ) {
        $this->companyRepository = $companyRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * 初期表示
     *
     * @param CompanyDetailInitializeInputPort $inputPort
     * @param CompanyDetailInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(CompanyDetailInitializeInputPort $inputPort, CompanyDetailInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // criteriaから参照対象の企業を取得
        try {
            $companyId = $inputPort->companyId;
            $company = $this->companyRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralDoctrineCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $companyId,
                        "status" => Company::STATUS_VISIBLE
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            // 削除対象が見つからない場合に例外
            throw new FatalBusinessException("data_not_found");
        }

        // 企業の代表アカウントのユーザーID取得
        $companyAccounts = $company->getCompanyAccounts();
        foreach ($companyAccounts as $companyAccount) {
            $representativeSetting = $companyAccount->getRepresentativeSetting();
            if ($representativeSetting === CompanyAccount::REPRESENTATIVE) {
                $companyUserAccountId = $companyAccount->getUserAccount()->getId();
            }
        }

        // 企業詳細取得
        $introductorySentence = $company->getIntroductorySentence();
        $outputPort->introductorySentence = $introductorySentence;

        // 企業ロゴ取得
        $companyLogoImage = $company->getCompanyLogoImage();
        if(!is_null($companyLogoImage)){
            $companyLogoFilePath = $companyLogoImage->getFilePathForFrontShow();
        }else{
            $companyLogoFilePath = asset('/img/common/no_image_logo.png');
        }

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
        $jobApplications = $company->getJobApplications();
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

        $outputPort->messageDetailUrl = route("front.message.detail", ["userAccountId" => $companyUserAccountId]);
        $outputPort->interviewRequestUrl = route("front.message.detail-request", ["userAccountId" => $companyUserAccountId]);

        //ログ出力
        Log::infoOut();
    }
}
