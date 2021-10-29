<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Gateways\Repositories\SchoolRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Gateways\Validators\MemberCsvValidator;
use App\Business\Interfaces\Interactors\Client\MemberCsvImport\MemberCsvImportInputPort;
use App\Business\Interfaces\Interactors\Client\MemberCsvImport\MemberCsvImportInteractor;
use App\Business\Interfaces\Interactors\Client\MemberCsvImport\MemberCsvImportOutputPort;
use App\Business\Services\EncryptTrait;
use App\Business\UseCases\Backend\CsvImportUseCase;
use App\Domain\Entities\BusinessType;
use App\Domain\Entities\Member;
use App\Domain\Entities\Prefecture;
use App\Domain\Entities\School;
use App\Domain\Entities\UserAccount;
use Carbon\Carbon;
use App\Utilities\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Utilities\File;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;

/**
 * Class MemberCsvImportUseCase
 *
 * 会員CSV取込み
 *
 * @package App\Business\UseCases\Client
 */
class MemberCsvImportUseCase extends CsvImportUseCase implements MemberCsvImportInteractor
{
    use encryptTrait;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * @var SchoolRepository
     */
    private $schoolRepository;

    /**
     * @var userAccountRepository
     */
    private $userAccountRepository;

    /**
     * @var businessTypeRepository
     */
    private $businessTypeRepository;

    /**
     * @var memberRepository
     */
    private $memberRepository;

    /**
     * @var MemberCsvValidator
     */
    private $memberCsvValidator;

    /**
     * MemberCsvImportUseCase constructor.
     *
     * @param MemberCsvValidator $memberCsvValidator
     * @param PrefectureRepository $prefectureRepository
     * @param SchoolRepository $schoolRepository
     * @param UserAccountRepository $userAccountRepository
     * @param BusinessTypeRepository $businessTypeRepository
     * @param memberRepository $memberRepository
     */
    public function __construct(
        MemberCsvValidator $memberCsvValidator,
        PrefectureRepository $prefectureRepository,
        SchoolRepository $schoolRepository,
        UserAccountRepository $userAccountRepository,
        BusinessTypeRepository $businessTypeRepository,
        MemberRepository $memberRepository
    ) {
        $this->memberCsvValidator = $memberCsvValidator;
        $this->prefectureRepository = $prefectureRepository;
        $this->schoolRepository = $schoolRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->businessTypeRepository = $businessTypeRepository;
        $this->memberRepository = $memberRepository;
    }

    /**
     * CSV取込の前処理を実行する
     *
     * @param MemberCsvImportInputPort $inputPort
     * @param MemberCsvImportOutputPort $outputPort
     */
    protected function before($inputPort, $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // 一時的にメモリリミットを引き上げ
        ini_set('memory_limit', '2048M');

        // CSVの文字エンコードをSJIS-WINからUTF-8へ変換する
        $csvFilePath = ROOT_DIR_PATH.DS.'public_client'.DS.'assets'.DS.'members.csv';
        $convertedCsvFilePath = strstr($csvFilePath, '.csv', true) . "_utf8.csv";
        File::convertEncoding($csvFilePath, "UTF-8", "SJIS-WIN", $convertedCsvFilePath);
        $inputPort->csvFilePath = $convertedCsvFilePath;

        // ログ出力
        Log::infoOut();
    }

    /**
     * CSV取込の後処理を実行する
     *
     * @param array $validValues
     * @param array $invalidValues
     * @param array $headers
     * @param array $errors
     * @param MemberCsvImportInputPort $inputPort
     * @param MemberCsvImportOutputPort $outputPort
     */
    protected function after(array $validValues, array $invalidValues, array $headers, array $errors, $inputPort, $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // 文字エンコードに使用したUTF-8のファイルを削除
        File::remove($inputPort->csvFilePath);

        // エラーメッセージを設定
        $outputPort->errorMessages = $errors;

        // ログ出力
        Log::infoOut();
    }

    /**
     * CSV行のバリデーションを実行する
     *
     * @param array $values
     * @param int $index
     * @param array $headers
     * @param array $checkedValues
     * @param MemberCsvImportInputPort $inputPort
     * @param MemberCsvImportOutputPort $outputPort
     * @return array
     */
    protected function validate(array $values, int $index, array $headers, array $checkedValues, $inputPort, $outputPort): array
    {
        // 入力値エラーが存在する場合はその内容を返却する。
        $errors = $this->memberCsvValidator->validate($values, $index);

        // 業務エラーメッセージに行文言を追加して返却する
        return $this->memberCsvValidator->messagesWithIndex($errors, $index);

    }

    /**
     * 登録or更新する
     *
     * @param array $validValues
     * @param array $headers
     * @param MemberCsvImportInputPort $inputPort
     * @param MemberCsvImportOutputPort $outputPort
     * @throws FatalBusinessException
     */
    protected function saveOrUpdate(array $validValues, array $headers, $inputPort, $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        foreach ($validValues as $values) {
            // CSVの項目名と値をマッピングしたデータを取得
            $mappingValue = $this->memberCsvValidator->mappingColumnValues($values);

            $intern = null;
            if (!empty($mappingValue['intern'])) {
                $intern = 1;
            }

            $prefecture = $this->prefectureRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        'name' => $mappingValue['prefecture'],
                    ]
                )
            );

            $birthday = new Carbon($mappingValue['birthday']);

            $member = new Member();
            $member->setStatus(Member::STATUS_TEMPORARY_MEMBER);
            $member->setLastName($mappingValue['lastName']);
            $member->setFirstName($mappingValue['firstName']);
            $member->setLastNameKana($mappingValue['lastNameKana']);
            $member->setFirstNameKana($mappingValue['firstNameKana']);
            $member->setBirthday($birthday);
            $member->setZipCode($mappingValue['zipCode']);
            $member->setCity($mappingValue['city']);
            $member->setBlockNumber($mappingValue['blockNumber']);
            $member->setPhoneNumber($mappingValue['phoneNumber']);
            $member->setInternNeeded($intern);
            $member->setPrefecture($prefecture);

            // ランダムパスワード生成
            $min = 2;
            $max = 4;
                //「 8文字以上の英数字記号大文字小文字 ※1(数字イチ)とl(英小文字エル)、0(数字ゼロ)とO(英大文字オー)は含まない」
                $alphabetSmall = array_merge(range('a', 'k'), range('m', 'z'));
                $collectionA = collect($alphabetSmall)->random(rand($min, $max))->all();
                $alphabetLarge = array_merge(range('A', 'N'), range('P', 'Z'));
                $collectionB = collect($alphabetLarge)->random(rand($min, $max))->all();
                $collectionC = collect(range(2, 9))->random(rand($min, $max))->all();
                $mark = ['!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '~'];
                $collectionD = collect($mark)->random(rand($min, $max))->all();
                $passwordStr = array_merge($collectionA, $collectionB, $collectionC, $collectionD);
                $randomPass = str_shuffle(implode($passwordStr));

            // ユーザーアカウント
            $userAccount = new UserAccount();
            $userAccount->setPassword($randomPass);
            $userAccount->setMailAddress($mappingValue['mailAddress']);
            $member->setUserAccount($userAccount);
            $userAccount->setMember($member);

            // 出身校
            // 学校種別取得
            $facultyTypeList = School::FACULTY_TYPE_LIST;
            $facultyType = array_search($mappingValue['facultyType'], $facultyTypeList);
            // 学部系統取得
            $schoolTypeList = School::SCHOOL_TYPE_LIST;
            $schoolType = array_search($mappingValue['schoolType'], $schoolTypeList);
            // 卒業年月取得・フォーマット
            $graduationPeriod = $mappingValue['graduationPeriod'];
            $splittedGraduationPeriodList = explode("/", $graduationPeriod);
            $formattedGraduationPeriod = (new Carbon($splittedGraduationPeriodList[0] . sprintf('%02d', $splittedGraduationPeriodList[1]) . '01'));

            $school = new School();
            $school->setSchoolType($schoolType);
            $school->setname($mappingValue['schoolName']);
            $school->setDepartmentName($mappingValue['departmentName']);
            $school->setFacultyType($facultyType);
            $school->setGraduationPeriod($formattedGraduationPeriod);
            $member->setOldSchool($school);
            $school->setMember($member);

            // 志望職種
            $industry1 = $this->getBusinessTypeByName($mappingValue['industry1']);
            $industry2 = $this->getBusinessTypeByName($mappingValue['industry2']);
            $industry3 = $this->getBusinessTypeByName($mappingValue['industry3']);
            $member->setAspirationBusinessTypes([$industry1, $industry2, $industry3]);

            // 志望勤務地
            $location1 = $this->getPrefectureByName($mappingValue['location1']);
            $location2 = $this->getPrefectureByName($mappingValue['location2']);
            $location3 = $this->getPrefectureByName($mappingValue['location3']);
            $member->setAspirationPrefectures([$location1, $location2, $location3]);

            // 登録する
            $this->memberRepository->saveOrUpdate($member, true);

            Log::info("[".__CLASS__."] id = :id, name = :name", [
                "id" => $member->getId(),
                "name" => $member->getLastName().'', $member->getFirstName(),
            ]);

            // 事前登録受付メールを送信する
            $this->sendEntryReceptionMail($member, $randomPass);

            // ログ出力
            Log::infoOut();
        }
    }

    /**
     * 業種を取得
     *
     * @param string|null $industryName
     * @return BusinessType|null
     * @throws FatalBusinessException
     */
    private function getBusinessTypeByName(?string $industryName): ?BusinessType
    {
        $businessType = null;
        if (!empty($industryName)) {
            try {
                $businessType = $this->businessTypeRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class, ["name" => $industryName])
                );
            } catch (ObjectNotFoundException $e) {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $businessType;
    }

    /**
     * 都道府県を取得
     *
     * @param string|null $prefectureName
     * @return Prefecture|null
     * @throws FatalBusinessException
     */
    private function getPrefectureByName(?string $prefectureName): ?Prefecture
    {
        $prefecture = null;
        if (!empty($prefectureName)) {
            try {
                $prefecture = $this->prefectureRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class, ["name" => $prefectureName])
                );
            } catch (ObjectNotFoundException $e) {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $prefecture;
    }

    /**
     * 会員に事前登録受付メールを送信する
     *
     * @param Member $member
     * @param string $randomPass
     */
    private function sendEntryReceptionMail(Member $member, string $randomPass): void
    {
        // ログ出力
        Log::infoIn();

        // 登録受付メールを送信する
        $userAccount = $member->getUserAccount();
        $template = "mail.front.member.pre-registration_mail";
        $title = "【LinkT】 ご登録ありがとうございました。";
        $mailAddress = $member->getUserAccount()->getMailAddress();

        $pass = 'memberAccount';
        $encryptedCreatedDatetime = $userAccount->encrypt($userAccount->getCreatedAt(), $pass);
        $encryptedId = $userAccount->encrypt($member->getId(), $pass);
        $urlEncodeCreatedDatetime = urlencode($encryptedCreatedDatetime);
        $urlEncodeId = urlencode($encryptedId);
        $frontAppURL = env('FRONT_APP_URL');
        $URL = "$frontAppURL/entry/complete";
        $completionURL = $URL . "?param=" . $urlEncodeCreatedDatetime . "&def=" . $urlEncodeId;
        $dataList["member"] = $member;
        $dataList["completionURL"] = $completionURL;
        $contactURL = "$frontAppURL/mypage/contact";
        $dataList["contactURL"] = $contactURL;
        $dataList{"password"} = $randomPass;
        $bccURL = "dev@life-innovation.com";
        $data = Data::wrap($dataList);

        $mail = Mail::getInstance($template, $mailAddress, trans($title), $data)->bcc($bccURL);
        $result = $mail->send();

        Log::info("[".__CLASS__."] id = :id, name = :name, sendmail = :result", [
            "id" => $member->getId(),
            "name" => $member->getLastName().'', $member->getFirstName(),
            "result" => $result,
        ]);

        // ログ出力
        Log::infoOut();
    }
}