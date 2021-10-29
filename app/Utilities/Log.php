<?php

namespace App\Utilities;

use App\Adapters\Http\Requests\Request;
use App\Domain\Model\AdminAuthentication;
use App\Domain\Model\MemberAuthentication;
use ReLab\Commons\Wrappers\Transaction;

/**
 * Created by PhpStorm.
 * User: inoshita
 * Date: 2019/03/31
 * Time: 19:14
 */
class Log
{
    const OPERATION_LABEL_CREATE = "create";
    const OPERATION_LABEL_READ = "read";
    const OPERATION_LABEL_UPDATE = "update";
    const OPERATION_LABEL_DELETE = "delete";

    /**
     * 異常終了時（PHPのネイティブExceptionやERROR）に出力。
     * Exceptionのstacktraceと必要情報を記載。
     *
     * @param null|string $message
     * @param array $context
     */
    public static function emergency(?string $message = null, array $context = [])
    {
        \Illuminate\Support\Facades\Log::emergency(self::createKeyUserIpAddress() . self::createKeyTransactionId() . self::createKeyUserId() . self::bracketKey($message), $context);
    }

    /**
     * 予期しないエラー（BusinessFatal）発生時に出力。
     * Exceptionのstacktraceと必要情報を記載。
     *
     * @param null|string $message
     * @param array $context
     */
    public static function error(?string $message = null, array $context = [])
    {
        \Illuminate\Support\Facades\Log::error(self::createKeyUserIpAddress() . self::createKeyTransactionId() . self::createKeyUserId() . self::bracketKey($message), $context);
    }

    /**
     * 業務チェック（BusinessException）発生時に出力。
     * Exceptionのstacktraceと必要情報を記載。
     * [IPアドレス][トランザクションID][ユーザーID][メッセージ][値（エラー内容）]を想定
     *
     * @param null|string $message
     * @param array $context
     */
    public static function warning(?string $message = null, array $context = [])
    {
        \Illuminate\Support\Facades\Log::warning(self::createKeyUserIpAddress() . self::createKeyTransactionId() . self::createKeyUserId() . self::bracketKey($message), $context);
    }

    /**
     * publicメソッドのIN/OUTの案内と必要な情報等
     * [IPアドレス][トランザクションID][ユーザーID][メッセージ][値]を想定
     *
     * @param null|string $message
     * @param array $context
     */
    public static function info(?string $message = null, array $context = [])
    {
        \Illuminate\Support\Facades\Log::info(self::createKeyUserIpAddress() . self::createKeyTransactionId() . self::createKeyUserId() . self::bracketKey($message), $context);
    }

    /**
     * システムの動作状況がわかる内容（テスト環境まで反映）
     * [IPアドレス][トランザクションID][ユーザーID][メッセージ][値]を想定
     *
     * @param null|string $message
     * @param array $context
     */
    public static function debug(?string $message = null, array $context = [])
    {
        \Illuminate\Support\Facades\Log::debug(self::createKeyUserIpAddress() . self::createKeyTransactionId() . self::createKeyUserId() . self::bracketKey($message), $context);
    }

    /**
     * システムの動作状況がわかる内容（テスト環境まで反映）
     * [trace][IPアドレス][トランザクションID][ユーザーID][メッセージ][値]を想定
     *
     * @param null|string $message
     * @param array $context
     */
    public static function trace(?string $message = null, array $context = [])
    {
        \Illuminate\Support\Facades\Log::debug('[trace]' . self::createKeyUserIpAddress() . self::createKeyTransactionId() . self::createKeyUserId() . self::bracketKey($message), $context);
    }

    /**
     * publicメソッドのIN/OUTの案内と必要な情報
     * [IPアドレス][トランザクションID][ユーザーID][操作種別][クラス名.メソッド名.In.メッセージ][値]
     *
     * @param null|string $message
     * @param array $context
     */
    public static function infoIn(?string $message = null, array $context = [])
    {
        $dbg = debug_backtrace();
        // 呼び出し元のメソッド名
        $functionNameByCaller = $dbg[1]['function'];
        // 呼び出し元のクラス名
        $classNameByCaller = $dbg[1]['class'];

        self::info($classNameByCaller . "." . $functionNameByCaller . ".in" . $message, $context);
    }

    /**
     * publicメソッドのIN/OUTの案内と必要な情報
     * [IPアドレス][トランザクションID][ユーザーID][操作種別][クラス名.メソッド名.Out .メッセージ][値]
     *
     * @param null|string $message
     * @param array $context
     */
    public static function infoOut(?string $message = null, array $context = [])
    {
        $dbg = debug_backtrace();
        // 呼び出し元のメソッド名
        $functionNameByCaller = $dbg[1]['function'];
        // 呼び出し元のクラス名
        $classNameByCaller = $dbg[1]['class'];

        self::info($classNameByCaller . "." . $functionNameByCaller . ".out" . $message, $context);
    }

    /**
     * 操作ログ（operationlog.phpに出力）
     * [IPアドレス][トランザクションID][ユーザーID][操作種別][機能ID][操作クラス][メッセージ][値]
     *
     * @param null|string $message
     * @param array $context
     * @param string|null $operationType
     * @param null|string $userFunctionId
     * @param null|string $classNameByCaller
     */
    public static function infoOperationLog(?string $message = null, array $context = [], ?string $operationType = "", ?string $userFunctionId = "", ?string $classNameByCaller = "")
    {
        \Illuminate\Support\Facades\Log::stack([
            "operationlog"
        ])->info(self::createKeyUserIpAddress() . self::createKeyTransactionId() . self::createKeyUserId() . self::bracketKey($operationType) . self::bracketKey($userFunctionId) . self::bracketKey($classNameByCaller) . self::bracketKey($message),
            $context);
    }

    /**
     * システムの動作状況がわかる内容（削除時）
     *
     * @param null|string $message
     * @param array $context
     * @param null|string $userFunctionId
     */
    public static function infoOperationDeleteLog(?string $message = null, array $context = [], ?string $userFunctionId)
    {
        $dbg = debug_backtrace();
        // 呼び出し元のクラス名
        $classNameByCaller = $dbg[1]['class'];
        self::infoOperationLog($message, $context, self::OPERATION_LABEL_DELETE, $userFunctionId, $classNameByCaller);
    }

    /**
     * システムの動作状況がわかる内容（作成時）
     *
     * @param null|string $message
     * @param array $context
     * @param null|string $userFunctionId
     */
    public static function infoOperationCreateLog(?string $message = null, array $context = [], ?string $userFunctionId)
    {
        $dbg = debug_backtrace();
        // 呼び出し元のクラス名
        $classNameByCaller = $dbg[1]['class'];
        self::infoOperationLog($message, $context, self::OPERATION_LABEL_CREATE, $userFunctionId, $classNameByCaller);
    }

    /**
     * システムの動作状況がわかる内容（変更時）
     *
     * @param null|string $message
     * @param array $context
     */
    public static function infoOperationUpdateLog(?string $message = null, array $context = [], ?string $userFunctionId)
    {
        $dbg = debug_backtrace();
        // 呼び出し元のクラス名
        $classNameByCaller = $dbg[1]['class'];
        self::infoOperationLog($message, $context, self::OPERATION_LABEL_UPDATE, $userFunctionId, $classNameByCaller);
    }

    /**
     * システムの動作状況がわかる内容（読み込み時）
     *
     * @param null|string $message
     * @param array $context
     */
    public static function infoOperationReadLog(?string $message = null, array $context = [], ?string $userFunctionId)
    {
        $dbg = debug_backtrace();
        // 呼び出し元のクラス名
        $classNameByCaller = $dbg[1]['class'];
        self::infoOperationLog($message, $context, self::OPERATION_LABEL_READ, $userFunctionId, $classNameByCaller);
    }

    /**
     * アクセス元のIPアドレスのキーを作成する
     *
     * @return string
     */
    private static function createKeyUserIpAddress(): string
    {
        $userIpAddress = "-";
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $userIpAddress) {
                    $userIpAddress = trim($userIpAddress); // just to be safe
                    if (filter_var($userIpAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        break;
                    }
                }
            }
        }
        return self::bracketKey($userIpAddress);
    }

    /**
     * トランザクションIDのキーを作成する
     *
     * @return string
     */
    private static function createKeyTransactionId(): string
    {
        $transactionId = Transaction::getInstance()->getTransactionId();
        return self::bracketKey($transactionId);
    }

    /**
     * ユーザーIDのキーを作成する
     *
     * @return string
     */
    private static function createKeyUserId(): string
    {
        // 未ログインの場合は-を記載。
        $userAccountId = "-";
        // 当該アカウントのユーザーIDを更新者IDに追加
        $adminAuthentication = AdminAuthentication::loadSession();
        $memberAuthentication = MemberAuthentication::loadSession();
        if (!empty($adminAuthentication)) {
            $userAccountId = $adminAuthentication->getUserAccountId();
        } elseif (!empty($memberAuthentication)) {
            $userAccountId = $memberAuthentication->getUserAccountId();
        }
        return self::bracketKey($userAccountId);
    }

    /**
     * カッコでくくる
     *
     * @param $key
     * @return string
     */
    private static function bracketKey($key)
    {
        if (!empty($key)) {
            return "[" . $key . "]";
        }
        return "";
    }
}