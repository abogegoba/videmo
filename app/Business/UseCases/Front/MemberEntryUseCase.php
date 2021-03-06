<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryCompleteInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryCompleteInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryCompleteInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryConfirmInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryConfirmInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryConfirmInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeFiveInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeFiveInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeFiveOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeFourInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeFourInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeFourOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeOneInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeOneInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeOneOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeThreeInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeThreeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeThreeOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeTwoInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeTwoInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeTwoOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryReceptionInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryReceptionInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryReceptionInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryStoreInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryStoreInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryStoreOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToConfirmInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToConfirmInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToConfirmOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageFourInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageFourInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageFourOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageOneInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageOneInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageOneOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageThreeInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageThreeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageThreeOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageTwoInputPort;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageTwoInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageTwoOutputPort;
use App\Business\Services\EncryptTrait;
use App\Business\Services\SendMailTrait;
use App\Business\Services\YearMonthTrait;
use App\Domain\Entities\BusinessType;
use App\Domain\Entities\Member;
use App\Domain\Entities\Prefecture;
use App\Domain\Entities\School;
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

/**
 * Class MemberEntryOneUseCase
 *
 * @package App\Business\UseCases\Front
 */
class MemberEntryUseCase implements MemberEntryInitializeOneInteractor, MemberEntryInitializeTwoInteractor, MemberEntryInitializeThreeInteractor, MemberEntryInitializeFourInteractor, MemberEntryInitializeFiveInteractor, MemberEntryConfirmInitializeInteractor, MemberEntryReceptionInitializeInteractor, MemberEntryCompleteInitializeInteractor, MemberEntryToNextPageOneInteractor, MemberEntryToNextPageTwoInteractor, MemberEntryToNextPageThreeInteractor, MemberEntryToNextPageFourInteractor, MemberEntryToConfirmInteractor, MemberEntryStoreInteractor
{
    use EncryptTrait, YearMonthTrait, SendMailTrait;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * @var BusinessTypeRepository
     */
    private $businessTypeRepository;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * MemberEntryUseCase constructor.
     *
     * @param PrefectureRepository $prefectureRepository
     * @param BusinessTypeRepository $businessTypeRepository
     * @param MemberRepository $memberRepository
     * @param UserAccountRepository $userAccountRepository
     */
    public function __construct(
        PrefectureRepository $prefectureRepository,
        BusinessTypeRepository $businessTypeRepository,
        MemberRepository $memberRepository,
        UserAccountRepository $userAccountRepository
    ) {
        $this->businessTypeRepository = $businessTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->memberRepository = $memberRepository;
        $this->userAccountRepository = $userAccountRepository;
    }

    /**
     * ??????????????????
     *
     * @param MemberEntryInitializeOneInputPort $inputPort
     * @param MemberEntryInitializeOneOutputPort $outputPort
     */
    public function initializeOne(MemberEntryInitializeOneInputPort $inputPort, MemberEntryInitializeOneOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        //????????????
        Log::infoOut();
    }

    /**
     * ?????????????????????
     *
     * @param MemberEntryToNextPageOneInputPort $inputPort
     * @param MemberEntryToNextPageOneOutputPort $outputPort
     */
    public function toNextPageOne(MemberEntryToNextPageOneInputPort $inputPort, MemberEntryToNextPageOneOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        //????????????
        Log::infoOut();
    }

    /**
     * ??????????????????
     *
     * @param MemberEntryInitializeTwoInputPort $inputPort
     * @param MemberEntryInitializeTwoOutputPort $outputPort
     */
    public function initializeTwo(MemberEntryInitializeTwoInputPort $inputPort, MemberEntryInitializeTwoOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();
        $outputPort->overseasList = Member::CITIZENSHIP_OVERSEAS_LIST;
        // ?????????????????????
        $outputPort->prefectureList = $this->getPrefectureList();

        //????????????
        Log::infoOut();
    }

    /**
     * ?????????????????????
     *
     * @param MemberEntryToNextPageTwoInputPort $inputPort
     * @param MemberEntryToNextPageTwoOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function toNextPageTwo(MemberEntryToNextPageTwoInputPort $inputPort, MemberEntryToNextPageTwoOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        // ??????????????????????????????
        $this->getPrefectureById($inputPort->prefecture);

        //????????????
        Log::infoOut();
    }

    /**
     * ??????????????????
     *
     * @param MemberEntryInitializeThreeInputPort $inputPort
     * @param MemberEntryInitializeThreeOutputPort $outputPort
     */
    public function initializeThree(MemberEntryInitializeThreeInputPort $inputPort, MemberEntryInitializeThreeOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();
        $outputPort->facultyTypeList = School::FACULTY_TYPE_LIST;
        $outputPort->overseasFacultyTypeList = School::OVERSEAS_FACULTY_TYPE_LIST;
        $outputPort->schoolTypeList = School::SCHOOL_TYPE_LIST;
        $outputPort->yearList = ['' => "????????????????????????"] + self::getGraduationTwelveYearListYearAgo();
        $outputPort->monthList = School::getAllMonthList();
        //????????????
        Log::infoOut();
    }

    /**
     * ?????????????????????
     *
     * @param MemberEntryToNextPageThreeInputPort $inputPort
     * @param MemberEntryToNextPageThreeOutputPort $outputPort
     * @throws BusinessException
     */
    public function toNextPageThree(MemberEntryToNextPageThreeInputPort $inputPort, MemberEntryToNextPageThreeOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();
        $graduationPeriodYear = $inputPort->graduationPeriodYear;
        $graduationPeriodMonth = $inputPort->graduationPeriodMonth;

        $this->checkCanStoreGraduationPeriod($graduationPeriodYear, $graduationPeriodMonth);

        //????????????
        Log::infoOut();
    }

    /**
     * ??????????????????
     *
     * @param MemberEntryInitializeFourInputPort $inputPort
     * @param MemberEntryInitializeFourOutputPort $outputPort
     */
    public function initializeFour(MemberEntryInitializeFourInputPort $inputPort, MemberEntryInitializeFourOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        // ???????????????
        $businessTypes = $this->businessTypeRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $businessTypeNameList = array_column($businessTypes, "name");
        $businessTypeIdList = array_column($businessTypes, "id");
        $outputPort->businessTypeList = array_combine($businessTypeIdList, $businessTypeNameList);

        // ?????????????????????
        $outputPort->prefectureList = $this->getPrefectureList();

        //????????????
        Log::infoOut();
    }

    /**
     * ?????????????????????
     *
     * @param MemberEntryToNextPageFourInputPort $inputPort
     * @param MemberEntryToNextPageFourOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function toNextPageFour(MemberEntryToNextPageFourInputPort $inputPort, MemberEntryToNextPageFourOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        // ??????????????????????????????
        $this->getBusinessTypeById($inputPort->industry1);
        $this->getBusinessTypeById($inputPort->industry2);
        $this->getBusinessTypeById($inputPort->industry3);

        // ?????????????????????????????????
        $this->getPrefectureById($inputPort->location1);
        $this->getPrefectureById($inputPort->location2);
        $this->getPrefectureById($inputPort->location3);

        //????????????
        Log::infoOut();
    }

    /**
     * ??????????????????
     *
     * @param MemberEntryInitializeFiveInputPort $inputPort
     * @param MemberEntryInitializeFiveOutputPort $outputPort
     */
    public function initializeFive(MemberEntryInitializeFiveInputPort $inputPort, MemberEntryInitializeFiveOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();
        //????????????
        Log::infoOut();
    }

    /**
     * ???????????????
     *
     * @param MemberEntryToConfirmInputPort $inputPort
     * @param MemberEntryToConfirmOutputPort $outputPort
     * @throws BusinessException
     */
    public function toConfirm(MemberEntryToConfirmInputPort $inputPort, MemberEntryToConfirmOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();
        $this->checkDuplicateMailAddress($inputPort->mailAddress);
        //????????????
        Log::infoOut();
    }

    /**
     * ?????????????????????????????????
     *
     * @param MemberEntryConfirmInitializeInputPort $inputPort
     * @param MemberEntryConfirmInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function confirmInitialize(MemberEntryConfirmInitializeInputPort $inputPort, MemberEntryConfirmInitializeOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();
        $outputPort->lastName = $inputPort->lastName;
        $outputPort->firstName = $inputPort->firstName;
        $outputPort->lastNameKana = $inputPort->lastNameKana;
        $outputPort->firstNameKana = $inputPort->firstNameKana;
        $outputPort->englishName = $inputPort->englishName;
        $birthday = new Carbon($inputPort->birthday);
        $outputPort->birthday = $birthday->format('Y???n???j???');
        $outputPort->zipCode = $inputPort->zipCode;
        $outputPort->country = ($inputPort->country == 1) ? '??????' : Member::CITIZENSHIP_OVERSEAS_LIST[$inputPort->country];
        $outputPort->prefecture = $this->getPrefectureName($inputPort->prefecture);
        $outputPort->city = $inputPort->city;
        $outputPort->blockNumber = $inputPort->blockNumber;
        $outputPort->phoneNumber = $inputPort->phoneNumber;
        $outputPort->schoolType = School::SCHOOL_TYPE_LIST[$inputPort->schoolType];
        $outputPort->name = $inputPort->name;
        $outputPort->departmentName = $inputPort->departmentName;
        $outputPort->facultyType = (isset(School::FACULTY_TYPE_LIST[$inputPort->facultyType]) ? School::FACULTY_TYPE_LIST[$inputPort->facultyType] : School::OVERSEAS_FACULTY_TYPE_LIST[$inputPort->facultyType]);
        $graduationPeriodYear = $inputPort->graduationPeriodYear;
        $graduationPeriodMonth = $inputPort->graduationPeriodMonth;
        $outputPort->graduationPeriodYear = $graduationPeriodYear;
        $outputPort->graduationPeriodMonth = $graduationPeriodMonth;
        $school = new School();
        $school->setGraduationPeriod(new Carbon($graduationPeriodYear . sprintf('%02d', $graduationPeriodMonth) . '01'));
        $outputPort->graduationPeriodTypeLabel = $school->isAlreadyGraduated() ? '??????' : '????????????';
        $outputPort->industry1 = $this->getIndustryName($inputPort->industry1);
        $outputPort->industry2 = $this->getIndustryName($inputPort->industry2);
        $outputPort->industry3 = $this->getIndustryName($inputPort->industry3);
        $outputPort->location1 = $this->getPrefectureName($inputPort->location1);
        $outputPort->location2 = $this->getPrefectureName($inputPort->location2);
        $outputPort->location3 = $this->getPrefectureName($inputPort->location3);
        $outputPort->intern = ($inputPort->intern) ? '????????????' : '???????????????';
        $outputPort->recruitInfo = ($inputPort->recruitInfo) ? '????????????' : '???????????????';
        $outputPort->mailAddress = $inputPort->mailAddress;
        $outputPort->idPhotoUrl = $inputPort->idPhotoUrl;
        //????????????
        Log::infoOut();
    }

    /**
     * ????????????
     *
     * @param MemberEntryStoreInputPort $inputPort
     * @param MemberEntryStoreOutputPort $outputPort
     * @throws BusinessException
     * @throws FatalBusinessException
     */
    public function store(MemberEntryStoreInputPort $inputPort, MemberEntryStoreOutputPort $outputPort): void
    {

        //????????????
        Log::infoIn();

        $this->checkDuplicateMailAddress($inputPort->mailAddress);

        // ??????
        $member = new Member();
        $prefectureRepository = $this->prefectureRepository;

        Data::mappingToObject($inputPort, $member, [
            // ????????????
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
        $member->setCountry($inputPort->country);

        // ???????????????????????????
        $userAccount = new UserAccount();
        $userAccount->setPassword($inputPort->password);
        $userAccount->setMailAddress($inputPort->mailAddress);
        $member->setUserAccount($userAccount);
        $userAccount->setMember($member);

        // ?????????
        $school = new School();
        Data::mappingToObject($inputPort, $school);
        $member->setOldSchool($school);
        $school->setMember($member);

        // ????????????
        $graduationPeriodYear = $inputPort->graduationPeriodYear;
        $graduationPeriodMonth = $inputPort->graduationPeriodMonth;
        $this->checkCanStoreGraduationPeriod($graduationPeriodYear, $graduationPeriodMonth);
        $school->setGraduationPeriod(new Carbon($graduationPeriodYear . sprintf('%02d', $graduationPeriodMonth) . '01'));

        // ??????????????????????????????
        $member->setStatus(Member::STATUS_TEMPORARY_MEMBER);

        // ????????????
        $industry1 = $this->getBusinessTypeById($inputPort->industry1);
        $industry2 = $this->getBusinessTypeById($inputPort->industry2);
        $industry3 = $this->getBusinessTypeById($inputPort->industry3);
        $member->setAspirationBusinessTypes([$industry1, $industry2, $industry3]);

        // ???????????????
        $location1 = $this->getPrefectureById($inputPort->location1);
        $location2 = $this->getPrefectureById($inputPort->location2);
        $location3 = $this->getPrefectureById($inputPort->location3);
        $member->setAspirationPrefectures([$location1, $location2, $location3]);

        // ?????????????????????
        $member->setInternNeeded($inputPort->intern === '1');
        $member->setRecruitInfoNeeded($inputPort->recruitInfo === '1');

        // ID???????????????????????????????????????????????????????????????
        $this->memberRepository->saveOrUpdate($member, true);

        // ????????????
        $idPhotoName = $inputPort->idPhotoName;
        $idPhotoPath = $inputPort->idPhotoPath;
        $uploadedFile = null;
        if(!empty($idPhotoName) && !empty($idPhotoPath)){
            $realIdPhotoPath = STORAGE_PUBLIC_DIR_PATH . DS . $idPhotoPath;
            if (file_exists($realIdPhotoPath)) {
                $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realIdPhotoPath);
                $uploadedFile = new UploadedFile();
                $uploadedFile->setMember($member);
                $uploadedFile->setFileName($idPhotoName);
                $uploadedFile->setFilePath("member/" . $member->getId() . "/" . $physicalFileName);
                $uploadedFile->setPhysicalFileName($physicalFileName);
                $member->setIdentificationImage($uploadedFile);
            }
        }

        // ?????????
        $this->memberRepository->saveOrUpdate($member, true);

        // ????????????
        Log::infoOperationCreateLog("", ["member" => (array)$member, "school" => (array)$school, "userAccount" => (array)$userAccount], "");

        // ????????????????????????????????????
        $this->sendEntryReceptionMail($member);

        // ????????????????????? (??????????????????????????????????????????????????????????????????????????????????????????)
        if($uploadedFile){
            try {
                File::createDir($uploadedFile->getRealFileDir());
                File::rename($realIdPhotoPath, $uploadedFile->getRealFilePath());
            } catch (\Exception $e) {
                // ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
            }
        }

        //????????????
        Log::infoOut();
    }

    /**
     * ?????????????????????????????????
     *
     * @param MemberEntryReceptionInitializeInputPort $inputPort
     * @param MemberEntryReceptionInitializeOutputPort $outputPort
     */
    public function receptionInitialize(MemberEntryReceptionInitializeInputPort $inputPort, MemberEntryReceptionInitializeOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        //????????????
        Log::infoOut();
    }

    /**
     * ?????????????????????????????????
     *
     * @param MemberEntryCompleteInitializeInputPort $inputPort
     * @param MemberEntryCompleteInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function completeInitialize(MemberEntryCompleteInitializeInputPort $inputPort, MemberEntryCompleteInitializeOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        $param = $inputPort->param;
        $def = $inputPort->def;

        // ?????????????????????
        $pass = 'memberAccount';
        $encryptedCreatedDatetime = $this->decrypt($param, $pass);
        $encryptedId = $this->decrypt($def, $pass);

        // ???????????????????????????????????????
        try {
            $member = $this->memberRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "createdAt" => $encryptedCreatedDatetime,
                        "id" => $encryptedId
                    ]
                )
            );

        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("url_is_invalid");
        }

        if ($member->getStatus() === Member::STATUS_REAL_MEMBER) {
            throw new FatalBusinessException("already_real_member");
        }
        // ?????????????????????[?????????]???????????????
        $member->setStatus(Member::STATUS_REAL_MEMBER);
        $this->memberRepository->saveOrUpdate($member, true);

        // ????????????????????????????????????
        $this->sendEntryCompleteMail($member);

        //????????????
        Log::infoOut();
    }

    /**
     * ??????????????????????????????
     *
     * @return array
     */
    private function getPrefectureList()
    {
        $prefectures = $this->prefectureRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $prefectureNameList = array_column($prefectures, "name");
        $prefectureIdList = array_column($prefectures, "id");
        return array_combine($prefectureIdList, $prefectureNameList);
    }

    /**
     * ????????????????????????
     *
     * @param int|null $industryId
     * @return null
     * @throws FatalBusinessException
     */
    private function getIndustryName(?int $industryId)
    {
        $industryName = null;
        if (!empty($industryId)) {
            $industryNames = $this->businessTypeRepository->findValuesByCriteria(
                CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $industryId]), ["name"]
            );
            if (!empty($industryNames)) {
                $industryName = $industryNames[0]["name"];
            } else {
                throw new FatalBusinessException("select_target_not_found");
            }

        }
        return $industryName;
    }

    /**
     * ????????????????????????
     *
     * @param int|null $prefectureId
     * @return null
     * @throws FatalBusinessException
     */
    private function getPrefectureName(?int $prefectureId)
    {
        $prefectureName = null;
        if (!empty($prefectureId)) {
            $prefectureNames = $this->prefectureRepository->findValuesByCriteria(
                CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $prefectureId]), ["name"]
            );
            if (!empty($prefectureNames)) {
                $prefectureName = $prefectureNames[0]["name"];
            } else {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $prefectureName;
    }

    /**
     * ?????????????????????
     *
     * @param int|null $prefectureId
     * @return Prefecture|null
     * @throws FatalBusinessException
     */
    private function getPrefectureById(?int $prefectureId): ?Prefecture
    {
        $prefecture = null;
        if (!empty($prefectureId)) {
            try {
                $prefecture = $this->prefectureRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $prefectureId])
                );
            } catch (ObjectNotFoundException $e) {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $prefecture;
    }

    /**
     * ???????????????
     *
     * @param int|null $industryId
     * @return BusinessType|null
     * @throws FatalBusinessException
     */
    private function getBusinessTypeById(?int $industryId): ?BusinessType
    {
        $businessType = null;
        if (!empty($industryId)) {
            try {
                $businessType = $this->businessTypeRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $industryId])
                );
            } catch (ObjectNotFoundException $e) {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $businessType;
    }

    /**
     * ???????????????????????????????????????????????????
     *
     * @param string $mailAddress
     * @throws BusinessException
     */
    private function checkDuplicateMailAddress(string $mailAddress)
    {
        $userAccountSameMailAddress = $this->userAccountRepository->findByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class, ["mailAddress" => $mailAddress])
        );
        if (count($userAccountSameMailAddress) > 0) {
            foreach ($userAccountSameMailAddress as $userAccount) {
                if (($userAccount->getMember() !== null && $userAccount->getMember()->getStatus() !== Member::STATUS_WITHDRAWN_MEMBER)) {
                    // ????????????????????????????????????????????????????????????????????????????????????????????????????????????
                    throw new BusinessException('duplication.mail_address');
                }
            }
        }
    }

    /**
     * ?????????????????????????????????????????????
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
            // ????????????????????????2????????????10?????????????????????
            throw new BusinessException('cant_store_graduation_period');
        }
    }

}
