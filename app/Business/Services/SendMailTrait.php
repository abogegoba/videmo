<?php

namespace App\Business\Services;

use App\Domain\Entities\Member;
use App\Domain\Entities\UserAccount;
use App\Utilities\Log;
use Carbon\Carbon;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;

/**
 * Trait SendMailTrait
 *
 * メール送信トレイト
 *
 * @package App\Business\Services
 */
trait SendMailTrait
{

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
        //ログ出力
        Log::infoIn();

        $mailAddress = $userAccount->getMailAddress();

        // メールクライアントをgetInstance 引数(viewのテンプレート名、toのアドレス(１個)、件名(任意)、データ)
        $mail = Mail::getInstance($template, $mailAddress, trans($title), $data);
        //ログ出力
        Log::infoOut();

        $result = $mail->send();

        if ($result !== true) {
        throw new FatalBusinessException("not_send_mail");
    }

        return $result;
    }

    /**
     * 会員登録受付完了メール送信
     *
     * @param Member $member
     * @throws FatalBusinessException
     */
    public function sendEntryReceptionMail(Member $member)
    {
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
        if($userAccount->getMember()->getCountry() == 1){
            $completionURL = route("front.member-entry.complete") . "?param=" . $urlEncodeCreatedAt . "&def=" . $urlEncodeId;
        }else{
            $completionURL = route("front.overseas-member-entry.complete") . "?param=" . $urlEncodeCreatedAt . "&def=" . $urlEncodeId;
        }
        $dataList["completionURL"] = $completionURL;
        $dataList["member"] = $member;
        $data = Data::wrap($dataList);

        $template = "mail.front.member.entry_reception_mail";
        $title = "【LinkT】 会員登録を受け付けいたしました。";

        $this->sendMail($userAccount, $template, $title, $data);

    }

    /**
     * 会員登録受付完了メール送信
     *
     * @param Member $member
     * @throws FatalBusinessException
     */
    public function sendEntryCompleteMail(Member $member)
    {
        $userAccount = $member->getUserAccount();
        $dataList["member"] = $member;
        $data = Data::wrap($dataList);
        $template = "mail.front.member.entry_complete_mail";
        $title = "【LinkT】 会員登録が完了いたしました。";

        $this->sendMail($userAccount, $template, $title, $data);
    }
}
