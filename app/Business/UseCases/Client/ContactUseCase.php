<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Interactors\Client\Contact\ContactCompleteInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\Contact\ContactCompleteInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\Contact\ContactCompleteInitializeOutputPort;
use App\Business\Interfaces\Interactors\Client\Contact\ContactConfirmInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\Contact\ContactConfirmInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\Contact\ContactConfirmInitializeOutputPort;
use App\Business\Interfaces\Interactors\Client\Contact\ContactExecuteInputPort;
use App\Business\Interfaces\Interactors\Client\Contact\ContactExecuteInteractor;
use App\Business\Interfaces\Interactors\Client\Contact\ContactExecuteOutputPort;
use App\Business\Interfaces\Interactors\Client\Contact\ContactInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\Contact\ContactInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\Contact\ContactInitializeOutputPort;
use App\Business\Interfaces\Interactors\Client\Contact\ContactToConfirmInputPort;
use App\Business\Interfaces\Interactors\Client\Contact\ContactToConfirmInteractor;
use App\Business\Interfaces\Interactors\Client\Contact\ContactToConfirmOutputPort;
use App\Business\Services\UseLoggedInCompanyAccountTrait;
use App\Domain\Model\ClientAuthentication;
use App\Utilities\Log;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;

/**
 * Class ContactUseCase
 *
 * @package App\Business\UseCases\Client
 */
class ContactUseCase implements ContactInitializeInteractor, ContactConfirmInitializeInteractor, ContactCompleteInitializeInteractor, ContactExecuteInteractor, ContactToConfirmInteractor
{
    use UseLoggedInCompanyAccountTrait;

    /** お問合せ種類 */
    const CONTACT_TYPE_A = 1;
    const CONTACT_TYPE_B = 2;
    const CONTACT_TYPE_C = 3;
    const CONTACT_TYPE_D = 4;
    const CONTACT_TYPE_E = 5;

    /** お問合せ種類リスト */
    const CONTACT_TYPE_LIST = [
        self::CONTACT_TYPE_A => 'LinkT 掲載希望（企業会員登録）',
        self::CONTACT_TYPE_B => '使い方について',
        self::CONTACT_TYPE_C => '規約・各ポリシー違反しているコンテンツ',
        self::CONTACT_TYPE_D => '資料請求',
        self::CONTACT_TYPE_E => 'その他',
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

        $clientAuthentication = ClientAuthentication::loadSession();
        if(!empty($clientAuthentication))
        {
            $member = $this->getLoggedInCompanyAccount($inputPort);
            $company = $member->getCompany();
            $userAccount = $member->getUserAccount();

            $outputPort->firstName = $member->getFirstName();
            $outputPort->lastName = $member->getLastName();
            $outputPort->firstNameKana = $member->getFirstNameKana();
            $outputPort->lastNameKana = $member->getLastNameKana();
            $outputPort->companyName = $company->getName();
            $outputPort->tel = $company->getPicPhoneNumber();
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
     * @param ContactToConfirmInputPort $inputPort
     * @param ContactToConfirmOutputPort $outputPort
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
        $outputPort->companyName = $inputPort->companyName;
        $outputPort->departmentName = $inputPort->departmentName;
        $outputPort->tel = $inputPort->tel;
        $outputPort->mail = $inputPort->mail;
        $outputPort->contact = $inputPort->contact;
        //ログ出力
        Log::infoOut();
    }

    /**
     * 問い合わせ実行
     *
     * @param ContactConfirmInitializeInputPort $inputPort
     * @param ContactConfirmInitializeOutputPort $outputPort
     */
    public function execute(ContactExecuteInputPort $inputPort, ContactExecuteOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        //お問い合わせ内容メール送信
        $template = "mail.front.client.contact_mail";
        $companyTemplate = "mail.front.client.contact_mail_company";
        $mailAddress = $inputPort->mail;
        $companyMailAddress = env("COMPANY_MAIL_ADDRESS");
        $titleToCustomers = "【LinkT】お問い合わせいただきありがとうございます。";
        $titleToOperatingCompany = "【LinKT】お問い合わせがありました。";
        $dataList["contact"] = $inputPort;
        $dataList["contactKindName"] = self::CONTACT_TYPE_LIST[$inputPort->kind];
        $data = Data::wrap($dataList);

        //会員にお問い合わせメールを送信する
        $mail = Mail::getInstance($template,$mailAddress,trans($titleToCustomers),$data);
        $mail->send();

        //運営会社にお問い合わせメールを送信する
        $companyMail = Mail::getInstance($companyTemplate, $companyMailAddress, trans($titleToOperatingCompany), $data);
        $companyMail->send();

        //ログ出力
        Log::infoOut();
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
