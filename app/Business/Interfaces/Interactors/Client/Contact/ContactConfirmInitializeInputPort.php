<?php

namespace App\Business\Interfaces\Interactors\Client\Contact;

/**
 * Interface ContactConfirmInitializeInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\Contact
 *
 * @property int $kind お問合せ項目
 * @property string $lastName 氏名(姓)
 * @property string $firstName 氏名(名)
 * @property string $lastNameKana 氏名かな(せい)
 * @property string $firstNameKana 氏名かな(めい)
 * @property string $companyName 会社名
 * @property string $departmentName 部署名
 * @property string $tel 電話番号
 * @property string $mail メールアドレス
 * @property string $contact お問合せ内容
 */
interface ContactConfirmInitializeInputPort
{
}