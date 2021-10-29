<?php

use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Transaction;
use ReLab\Commons\Wrappers\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__ . '/../')
);

/*
|--------------------------------------------------------------------------
| Define
|--------------------------------------------------------------------------
*/
// ディレクトリセパレーター
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
// ルートディレクトリパス
if (!defined('ROOT_DIR_PATH')) {
    define('ROOT_DIR_PATH', base_path());
}
// ストレージディレクトリパス
if (!defined('STORAGE_DIR_PATH')) {
    define('STORAGE_DIR_PATH', storage_path("app"));
}
// 公開ストレージディレクトリパス
if (!defined('STORAGE_PUBLIC_DIR_PATH')) {
    define('STORAGE_PUBLIC_DIR_PATH', STORAGE_DIR_PATH . DS . 'public');
}
// 非公開ストレージディレクトリパス
if (!defined('STORAGE_PRIVATE_DIR_PATH')) {
    define('STORAGE_PRIVATE_DIR_PATH', STORAGE_DIR_PATH . DS . 'private');
}
// 公開テンポラリディレクトリパス
if (!defined('STORAGE_PUBLIC_TEMP_DIR_PATH')) {
    define('STORAGE_PUBLIC_TEMP_DIR_PATH', STORAGE_PUBLIC_DIR_PATH . DS . 'tmp');
}
// 非公開テンポラリディレクトリパス
if (!defined('STORAGE_PRIVATE_TEMP_DIR_PATH')) {
    define('STORAGE_PRIVATE_TEMP_DIR_PATH', STORAGE_PRIVATE_DIR_PATH . DS . 'tmp');
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Adapters\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Adapters\Console\Kernel::class
);

// WEBまたはAPIのリクエスト毎にExceptionHandlerを切り替える
if (isset($_SERVER["REDIRECT_URL"]) && preg_match("@^/api/.+\.json$@", $_SERVER["REDIRECT_URL"])) {
    $app->singleton(
        Illuminate\Contracts\Debug\ExceptionHandler::class,
        App\Exceptions\ApiHandler::class
    );
} else {
    $app->singleton(
        Illuminate\Contracts\Debug\ExceptionHandler::class,
        App\Exceptions\WebHandler::class
    );
}

/*
|--------------------------------------------------------------------------
| Wrapper Implementation
|--------------------------------------------------------------------------
|
| 各種Wrapperクラスの実装を行います。
|
*/

CriteriaFactory::implement(new class() extends CriteriaFactory
{
    /**
     * 指定されたインターフェースに該当するCriteriaのインスタンスを作成する
     *
     * @param string $criteriaInterface
     * @param null|string $expressionBuilderInterface
     * @param null $expressionBuilderValues
     * @param null $criteriaOptions
     * @return \ReLab\Commons\Interfaces\Criteria
     */
    public function create(string $criteriaInterface, ?string $expressionBuilderInterface = null, $expressionBuilderValues = null, $criteriaOptions = null)
    {
        $criteria = App::make($criteriaInterface);
        if ($criteria instanceof \ReLab\Doctrine\Criteria\DoctrineCriteria) {
            if (isset($expressionBuilderInterface)) {
                $criteria->setExpressionBuilder(App::make($expressionBuilderInterface));
            }
            $expressionBuilder = $criteria->expressionBuilder();
            if (isset($expressionBuilder) && isset($expressionBuilderValues)) {
                if ($expressionBuilder instanceof \ReLab\Doctrine\Expression\Builders\GeneralDoctrineExpressionBuilder) {
                    $expressionBuilderValues = new \ReLab\Commons\Wrappers\Data($expressionBuilderValues);
                    foreach ($expressionBuilderValues->toArray() as $field => $value) {
                        $expressionBuilder->setValue($field, $value);
                    }
                } else {
                    \ReLab\Commons\Wrappers\Data::mappingToObject($expressionBuilderValues, $expressionBuilder);
                }
            }
            if (isset($criteriaOptions)) {
                \ReLab\Commons\Wrappers\Data::mappingToObject($criteriaOptions, $criteria);
            }
        }
        return $criteria;
    }
});

Transaction::implement(new class() extends Transaction
{
    /**
     * 開始
     */
    public function start(): void
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = App::make('Doctrine\ORM\EntityManagerInterface');
        $connection = $entityManager->getConnection();
        if (!$connection->isTransactionActive()) {
            $connection->beginTransaction();
        }
    }

    /**
     * ロールバック
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function rollBack(): void
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = App::make('Doctrine\ORM\EntityManagerInterface');
        $connection = $entityManager->getConnection();
        if ($connection->isTransactionActive()) {
            $connection->rollBack();
            $entityManager->clear();
        }
    }

    /**
     * コミット
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function commit(): void
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = App::make('Doctrine\ORM\EntityManagerInterface');
        $connection = $entityManager->getConnection();
        if ($connection->isTransactionActive() && !$connection->isRollbackOnly()) {
            $entityManager->flush();
            $connection->commit();
        }
    }
});

Mail::implement(new class() extends Mail
{
    /**
     * 送信する
     *
     * @return bool
     */
    function send(): bool
    {
        $result = true;
        try {
            \Illuminate\Support\Facades\Mail::send($this->template, ["data" => $this->data],
                function (Message $message) {
                    if (isset($this->fromAddress)) {
                        $message->from($this->fromAddress, $this->fromName);
                    }
                    $message->to($this->to);
                    $message->cc($this->cc);
                    $message->bcc($this->bcc);
                    $message->subject($this->subject);
                }
            );
        } catch (Exception $e) {
            Log::error("[ ERROR SEND MAIL ]");
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            $result = false;
        }
        return $result;
    }
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
