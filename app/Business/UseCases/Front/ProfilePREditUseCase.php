<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\UploadedFileRepository;
use App\Business\Interfaces\Interactors\Front\ProfilePREdit\ProfilePREditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfilePREdit\ProfilePREditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePREdit\ProfilePREditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfilePREdit\ProfilePREditUpdateInputPort;
use App\Business\Interfaces\Interactors\Front\ProfilePREdit\ProfilePREditUpdateInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePREdit\ProfilePREditUpdateOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\Member;
use App\Domain\Entities\UploadedFile;
use App\Utilities\Log;
use ReLab\Commons\Utilities\File;
use ReLab\Commons\Wrappers\Data;

/**
 * Class ProfilePREditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfilePREditUseCase implements ProfilePREditInitializeInteractor, ProfilePREditUpdateInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * @var UploadedFileRepository
     */
    private $uploadedFileRepository;

    /**
     * MemberHistoryListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param UploadedFileRepository $uploadedFileRepository
     */
    public function __construct(MemberRepository $memberRepository, UploadedFileRepository $uploadedFileRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->uploadedFileRepository = $uploadedFileRepository;
    }

    /**
     * 初期表示
     *
     * @param ProfilePREditInitializeInputPort $inputPort
     * @param ProfilePREditInitializeOutputPort $outputPort
     */
    public function initialize(ProfilePREditInitializeInputPort $inputPort, ProfilePREditInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        $member = $this->getLoggedInMember($inputPort);
        $outputPort->instagramFollowerNumber = $member->getInstagramFollowerNumber();
        $outputPort->affiliationExperience = $member->getAffiliationExperience();
        $outputPort->introduction = $member->getIntroduction();
        $outputPort->instagramFollowerNumberLabelList = Member::INSTAGRAM_FOLLOWER_NUMBER_LABEL_LIST;
        $outputPort->affiliationExperienceLabelList = Member::AFFILIATION_EXPERIENCE_LABEL_LIST;
        $prVideos = [];
        foreach ($member->getPrVideos() as $prVideo) {
            $prVideos[] = [
                "name" => $prVideo->getFileName(),
                "url" => $prVideo->getFilePathForFrontShow(),
                "path" => $prVideo->getFilePath(),
                "title" => $prVideo->getTitle(),
                "description" => $prVideo->getDescription(),
                "type" => $prVideo->getFileType(),
            ];
        }
        $outputPort->prVideos = $prVideos;

        // ログ出力
        Log::infoOut();
    }

    /**
     * 登録変更する
     *
     * @param ProfilePREditUpdateInputPort $inputPort
     * @param ProfilePREditUpdateOutputPort $outputPort
     */
    public function update(ProfilePREditUpdateInputPort $inputPort, ProfilePREditUpdateOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // 対象の会員を取得する
        $member = $this->getLoggedInMember($inputPort);

        /** @var UploadedFile[] $newPrVideos */
        $newPrVideos = [];
        /** @var UploadedFile[] $deletePrVideos */
        $deletePrVideos = [];

        // 入力値を会員へマッピングする
        Data::mappingToObject($inputPort, $member, [
            "prVideoNames" => function ($prVideoNames, $fromObject, $toObject) use (&$newPrVideos, &$deletePrVideos) {
                /** @var Data $prVideoNames */
                /** @var ProfilePREditUpdateInputPort $fromObject */
                /** @var Member $toObject */
                /** @var UploadedFile[] $existPrVideos */
                $existPrVideos = [];
                $prVideos = $toObject->getPrVideos();
                foreach ($prVideos as $prVideo) {
                    $contentType = $prVideo->getContentType();
                    if ($contentType === UploadedFile::FILE_TYPE_PR_MOVIE) {
                        $existPrVideos[$prVideo->getRealFilePath()] = $prVideo;
                    }
                }

                $prVideoPaths = $fromObject->prVideoPaths;
                $prVideoTitles = $fromObject->prVideoTitles;
                $prVideoDescriptions = $fromObject->prVideoDescriptions;
                foreach ($prVideoNames as $index => $prVideoName) {
                    if (isset($prVideoPaths[$index])) {
                        $prVideoPath = STORAGE_PUBLIC_DIR_PATH . DS . $prVideoPaths[$index];
                        if (isset($existPrVideos[$prVideoPath])) {
                            // 既存のPR動画変更の場合は内容を変更する
                            $existPrVideos[$prVideoPath]->setSortNumber($index + 1);
                            if (isset($prVideoTitles[$index])) {
                                $existPrVideos[$prVideoPath]->setTitle($prVideoTitles[$index]);
                            }
                            if (isset($prVideoDescriptions[$index])) {
                                $existPrVideos[$prVideoPath]->setDescription($prVideoDescriptions[$index]);
                            }
                            unset($existPrVideos[$prVideoPath]);
                        } else {
                            if (file_exists($prVideoPath)) {
                                // 新規PR動画の場合は新規にUploadedFileを作成して内容を格納する
                                $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $prVideoPath);
                                $uploadedFile = new UploadedFile();
                                $uploadedFile->setMember($toObject);
                                $uploadedFile->setFileName($prVideoName);
                                $uploadedFile->setFilePath("member/" . $toObject->getId() . "/" . $physicalFileName);
                                $uploadedFile->setPhysicalFileName($physicalFileName);
                                $uploadedFile->setSortNumber($index + 1);
                                if (isset($prVideoTitles[$index])) {
                                    $uploadedFile->setTitle($prVideoTitles[$index]);
                                }
                                if (isset($prVideoDescriptions[$index])) {
                                    $uploadedFile->setDescription($prVideoDescriptions[$index]);
                                }
                                $newPrVideos[$prVideoPath] = $uploadedFile;
                            }
                        }
                    }
                }
                $toObject->setPrVideos($newPrVideos);
                $deletePrVideos = $existPrVideos;
            }
        ]);

        // リクエストするPR動画が存在しない場合は現在登録されているPR動画を全て削除する
        $prVideoNames = $inputPort->prVideoNames;
        $prVideoPaths = $inputPort->prVideoPaths;
        if (empty($prVideoNames) || empty($prVideoPaths)) {
            $deletePrVideos = $member->getPrVideos();
        }

        // 変更を実行する
        $this->memberRepository->saveOrUpdate($member, true);
        $this->uploadedFileRepository->delete($deletePrVideos);

        // 新規PR動画をTMPフォルダから会員のフォルダへ移動する
        foreach ($newPrVideos as $prVideoPath => $newPrVideo) {
            File::createDir($newPrVideo->getRealFileDir());
            File::rename($prVideoPath, $newPrVideo->getRealFilePath());
        }

        // 削除対象のPR動画を削除する
        foreach ($deletePrVideos as $deletePrVideo) {
            File::remove($deletePrVideo->getRealFilePath());
        }

        // ログ出力
        Log::infoOut();
    }
}