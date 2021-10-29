<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Interactors\Front\Contact\ContactCompleteInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\Contact\ContactCompleteInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactCompleteInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\Contact\ContactConfirmInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\Contact\ContactConfirmInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactConfirmInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\Contact\ContactExecuteInputPort;
use App\Business\Interfaces\Interactors\Front\Contact\ContactExecuteInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactExecuteOutputPort;
use App\Business\Interfaces\Interactors\Front\Contact\ContactInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\Contact\ContactInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\Contact\ContactToConfirmInputPort;
use App\Business\Interfaces\Interactors\Front\Contact\ContactToConfirmInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactToConfirmOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Model\MemberAuthentication;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;

/**
 * Class ContactUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ContactUseCase implements ContactInitializeInteractor, ContactConfirmInitializeInteractor, ContactCompleteInitializeInteractor, ContactExecuteInteractor, ContactToConfirmInteractor
{
    use UseLoggedInMemberTrait;

    /** お問合せ種類 */
    const CONTACT_TYPE_A = 1;
    const CONTACT_TYPE_B = 2;
    const CONTACT_TYPE_C = 3;
    const CONTACT_TYPE_D = 4;

    /** お問合せ種類リスト */
    const CONTACT_TYPE_LIST = [
        self::CONTACT_TYPE_A => '使い方について',
        self::CONTACT_TYPE_B => '規約・各ポリシー違反しているコンテンツ',
        self::CONTACT_TYPE_C => 'その他',
        self::CONTACT_TYPE_D => '退会希望連絡',
    ];

    /**
     * 初期表示
     *
     * @param ContactInitializeInputPort $inputPort
     * @param ContactInitializeOutputPort $outputPort
     */
    public function initialize(ContactInitializeInputPort $inputPort, ContactInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $memberAuthentication = MemberAuthentication::loadSession();
        if(!empty($memberAuthentication))
        {
            $member = $this->getLoggedInMember($inputPort);
            $school = $member->getOldSchool();
            $userAccount = $member->getUserAccount();

            $outputPort->firstName = $member->getFirstName();
            $outputPort->lastName = $member->getLastName();
            $outputPort->firstNameKana = $member->getFirstNameKana();
            $outputPort->lastNameKana = $member->getLastNameKana();
            $outputPort->schoolName = $school->getName();
            $outputPort->faculty = $school->getDepartmentName();
            $outputPort->tel = $member->getPhoneNumber();
            $outputPort->mail = $userAccount->getMailAddress();
        }

        // お問い合わせ種類リスト作成
        $contactTypeList = self::CONTACT_TYPE_LIST;
        $outputPort->kind = $contactTypeList;

        //ログ出力
        Log::infoOut();
    }

    /**
     * 確認
     *
     * @param ContactConfirmInitializeInputPort $inputPort
     * @param ContactConfirmInitializeOutputPort $outputPort
     */
    public function toConfirm(ContactToConfirmInputPort $inputPort, ContactToConfirmOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 確認
     *
     * @param ContactConfirmInitializeInputPort $inputPort
     * @param ContactConfirmInitializeOutputPort $outputPort
     */
    public function confirmInitialize(ContactConfirmInitializeInputPort $inputPort, ContactConfirmInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $contactTypeList = self::CONTACT_TYPE_LIST;

        $outputPort->kind = $contactTypeList[$inputPort->kind];
        $outputPort->lastName = $inputPort->lastName;
        $outputPort->firstName = $inputPort->firstName;
        $outputPort->lastNameKana = $inputPort->lastNameKana;
        $outputPort->firstNameKana = $inputPort->firstNameKana;
        $outputPort->schoolName = $inputPort->schoolName;
        $outputPort->faculty = $inputPort->faculty;
        $outputPort->tel = $inputPort->tel;
        $outputPort->mail = $inputPort->mail;
        $outputPort->contact = $inputPort->contact;

        //ログ出力
        Log::infoOut();
    }

    /**
     * @param ContactExecuteInputPort $inputPort
     * @param ContactExecuteOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function execute(ContactExecuteInputPort $inputPort, ContactExecuteOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // お問い合わせ内容メール送信
        $template = "mail.front.member.contact_mail";
        $companyTemplate = "mail.front.member.contact_mail_company";
        $mailAddress = $inputPort->mail;
        $companyMailAddress = env("COMPANY_MAIL_ADDRESS");
        $titleToCustomers = "【LinkT】お問い合わせいただきありがとうございます。";
        $titleToOperatingCompany = "【LinKT】お問い合わせがありました。";
        $dataList["contact"] = $inputPort;
        $dataList["contactKindName"] = self::CONTACT_TYPE_LIST[$inputPort->kind];
        $data = Data::wrap($dataList);

        // 会員にお問い合わせメールを送信する
        $mail = Mail::getInstance($template, $mailAddress, trans($titleToCustomers), $data);
        $mail->send();

        // 運営会社にお問い合わせメールを送信する
        $companyMail = Mail::getInstance($companyTemplate, $companyMailAddress, trans($titleToOperatingCompany), $data);
        $companyMail->send();
    }

    /**
     * 完了画面初期表示
     *
     * @param ContactCompleteInitializeInputPort $inputPort
     * @param ContactCompleteInitializeOutputPort $outputPort
     */
    public function completeInitialize(ContactCompleteInitializeInputPort $inputPort, ContactCompleteInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        //ログ出力
        Log::infoOut();
    }
}
