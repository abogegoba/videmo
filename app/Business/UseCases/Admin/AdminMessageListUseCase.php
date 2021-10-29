<?php


namespace App\Business\UseCases\Admin;


use App\Business\Interfaces\Gateways\Criteria\AdminMessageListSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\AdminMessageListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MessageRepository;
use App\Business\Interfaces\Interactors\Admin\MessageList\AdminMessageListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\MessageList\AdminMessageListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MessageList\AdminMessageListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\MessageList\AdminMessageListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\MessageList\AdminMessageListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\MessageList\AdminMessageListSearchOutputPort;
use App\Domain\Entities\Message;
use App\Utilities\Log;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;


/**
 * Class AdminMessageListUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class AdminMessageListUseCase implements AdminMessageListInitializeInteractor, AdminMessageListSearchInteractor
{
    /**
     *  1ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 50;

    /**
     * @var MessageRepository
     */
    private $messageRepository;


    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param AdminMessageListInitializeInputPort $inputPort
     * @param AdminMessageListInitializeOutputPort $outputPort
     */
    public function initialize(AdminMessageListInitializeInputPort $inputPort, AdminMessageListInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // ステータスリストを取得
        $statusList = Message::STATUS_LIST;
        $outputPort->statusList = $statusList;

        // ログ出力
        Log::infoOut();
    }


    /**
     * @param AdminMessageListSearchInputPort $inputPort
     * @param AdminMessageListSearchOutputPort $outputPort
     */
    public function search(AdminMessageListSearchInputPort $inputPort, AdminMessageListSearchOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // Inputにページ指定が存在しない場合は新規で作成する
        $pager = $inputPort->pager;
        if(!isset($pager)){
            $pager = new class() extends Data implements Pager
            {
            };
        }

        // 1ページの最大件数を指定する
        $pager->limit = self::PAGE_LIMIT_COUNT;
        $outputPort->pager = $pager;

        // 検索する
        $criteriaFactory = CriteriaFactory::getInstance();
        $messages = $this->messageRepository->findByCriteria(
            $criteriaFactory->create(AdminMessageListSearchCriteria::class,AdminMessageListSearchExpressionBuilder::class,
                $inputPort,
                [
                    "pager" => $pager
                ]
            )
        );

        $outputPort->messages = $messages;

        // ログ出力
        Log::infoOut();
    }
}