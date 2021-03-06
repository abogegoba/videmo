<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CertificationRepository;
use App\Business\Interfaces\Gateways\Repositories\LanguageAndCertificationRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit\ProfileLanguageEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit\ProfileLanguageEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit\ProfileLanguageEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit\ProfileLanguageEditStoreInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit\ProfileLanguageEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit\ProfileLanguageEditStoreOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\Certification;
use App\Domain\Entities\LanguageAndCertification;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class ProfileLanguageEditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfileLanguageEditUseCase implements ProfileLanguageEditInitializeInteractor, ProfileLanguageEditStoreInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * @var LanguageAndCertificationRepository
     */
    private $languageAndCertificationRepository;

    /**
     * @var CertificationRepository
     */
    private $certificationRepository;

    /**
     * MemberHistoryListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param LanguageAndCertificationRepository $languageAndCertificationRepository
     * @param CertificationRepository $certificationRepository
     */
    public function __construct(MemberRepository $memberRepository, LanguageAndCertificationRepository $languageAndCertificationRepository, CertificationRepository $certificationRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->languageAndCertificationRepository = $languageAndCertificationRepository;
        $this->certificationRepository = $certificationRepository;
    }

    /**
     * ????????????
     *
     * @param ProfileLanguageEditInitializeInputPort $inputPort
     * @param ProfileLanguageEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileLanguageEditInitializeInputPort $inputPort, ProfileLanguageEditInitializeOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $languageAndCertification = $member->getLanguageAndCertification();
        if (isset($languageAndCertification)) {
            $outputPort->toeicScore = $languageAndCertification->getToeicScore();
            $outputPort->toeflScore = $languageAndCertification->getToeflScore();
            $certifications = $languageAndCertification->getCertifications();
            $outputPort->certifications = $certifications;
        }

        //????????????
        Log::infoOut();
    }

    /**
     * ??????????????????
     *
     * @param ProfileLanguageEditStoreInputPort $inputPort
     * @param ProfileLanguageEditStoreOutputPort $outputPort
     */
    public function store(ProfileLanguageEditStoreInputPort $inputPort, ProfileLanguageEditStoreOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $languageAndCertification = $member->getLanguageAndCertification();
        $beforeCertificationDisplayNumberList = [];
        $memberId = $member->getId();
        if ($languageAndCertification === null) {
            // ??????????????????????????????????????????????????????????????????
            $languageAndCertification = new LanguageAndCertification();
            $languageAndCertification->setMember($member);
        } else {
            // ???????????????????????????????????????????????????????????????????????????????????????????????????????????????
            $beforeCertifications = $certification = $this->certificationRepository->findValuesByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,[
                    "languageAndCertification.member" => $memberId,
                ]), ["displayNumber"]
            );
            $beforeCertificationDisplayNumberList = array_column($beforeCertifications, "displayNumber");
        }

        // TOEIC???TOEFL?????????
        $languageAndCertification->setToeflScore($inputPort->toeflScore);
        $languageAndCertification->setToeicScore($inputPort->toeicScore);

        // ?????????????????????????????????????????????
        $inputtedCertificationList = $inputPort->certificationList;
        $certifications = [];

        foreach ($inputtedCertificationList as $key => $inputtedCertification) {
            if (!empty($inputtedCertification)) {
                $certification = $this->getCertificationByDisplayNumberAndMemberId($key, $memberId);
                if ($certification === null) {
                    $certification = new Certification();
                    $certification->setDisplayNumber($key);
                    $certification->setLanguageAndCertification($languageAndCertification);
                }
                $certification->setName($inputtedCertification);
                $certifications[] = $certification;
                // ?????????????????????????????????
                unset($beforeCertificationDisplayNumberList[array_search($key, $beforeCertificationDisplayNumberList)]);
            }
        }

        // ?????????????????????????????????????????????????????????
        if (count($beforeCertificationDisplayNumberList) > 0) {
            foreach ($beforeCertificationDisplayNumberList as $beforeCertificationDisplayNumber) {
                $deletedCertification = $this->getCertificationByDisplayNumberAndMemberId($beforeCertificationDisplayNumber, $memberId);
                if (isset($deletedCertification)) {
                    $this->certificationRepository->physicalDelete($deletedCertification);
                    Log::infoOperationDeleteLog("", ["certification" => (array)$deletedCertification], "");
                }
            }
        }

        $languageAndCertification->setCertifications($certifications);
        $this->languageAndCertificationRepository->saveOrUpdate($languageAndCertification, true);

        $member->setLanguageAndCertification($languageAndCertification);
        $this->memberRepository->saveOrUpdate($member, true);

        //????????????
        Log::infoOut();
    }

    /**
     * ????????????????????????????????????????????????ID????????????
     *
     * @param int $displayNumber
     * @param int $memberId
     * @return Certification|null
     */
    private function getCertificationByDisplayNumberAndMemberId(int $displayNumber, int $memberId): ?Certification
    {
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
        return $certification;
    }
}