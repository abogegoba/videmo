<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\UploadedFileRepository;
use App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit\ProfilePhotoEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit\ProfilePhotoEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit\ProfilePhotoEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit\ProfilePhotoEditUpdateInputPort;
use App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit\ProfilePhotoEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit\ProfilePhotoEditUpdateOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\Member;
use App\Domain\Entities\Tag;
use App\Domain\Entities\UploadedFile;
use App\Utilities\Log;
use ReLab\Commons\Utilities\File;
use ReLab\Commons\Wrappers\Data;

/**
 * Class ProfilePhotoEditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfilePhotoEditUseCase implements ProfilePhotoEditInitializeInteractor, ProfilePhotoEditUpdateInteractor
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
     * @param ProfilePhotoEditInitializeInputPort $inputPort
     * @param ProfilePhotoEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfilePhotoEditInitializeInputPort $inputPort, ProfilePhotoEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $outputPort->hashTagColorClassList = Tag::TAG_COLLAR_CLASS_LIST;
        $outputPort->hashTagColorCodeList = Tag::TAG_COLLAR_CODE_LIST;

        // 会員
        $member = $this->getLoggedInMember($inputPort);
        if (!empty($member)) {
            $hashTag = $member->getHashTag();
            if (!empty($hashTag)) {
                $outputPort->hashTag = $hashTag->getName();
                $outputPort->hashTagColor = $hashTag->getColor();
            }
            $idPhoto = $member->getIdentificationImage();
            if (isset($idPhoto)) {
                $outputPort->idPhoto = [
                    "name" => $idPhoto->getFileName(),
                    "url" => $idPhoto->getFilePathForFrontShow(),
                    "path" => $idPhoto->getFilePath()
                ];
            } else {
                $outputPort->idPhoto = [];
            }

            $privatePhoto = $member->getPrivateImage();
            if (isset($privatePhoto)) {
                $outputPort->privatePhoto = [
                    "name" => $privatePhoto->getFileName(),
                    "url" => $privatePhoto->getFilePathForFrontShow(),
                    "path" => $privatePhoto->getFilePath()
                ];
            } else {
                $outputPort->privatePhoto = [];
            }
        }

        //ログ出力
        Log::infoOut();
    }

    /**
     *  登録変更する
     *
     * @param ProfilePhotoEditUpdateInputPort $inputPort
     * @param ProfilePhotoEditUpdateOutputPort $outputPort
     */
    public function update(ProfilePhotoEditUpdateInputPort $inputPort, ProfilePhotoEditUpdateOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 対象の会員を取得する
        $member = $this->getLoggedInMember($inputPort);

        /** @var UploadedFile[] $newPhotos */
        $newPhotos = [];
        /** @var UploadedFile[] $deletePhotos */
        $deletePhotos = [];

        // 入力値を会員へマッピングする
        Data::mappingToObject($inputPort, $member, [
            // 証明写真
            "idPhotoName" => function ($value, $fromObject, $toObject) use (&$newPhotos, &$deletePhotos) {
                /** @var string $value */
                /** @var ProfilePhotoEditUpdateInputPort $fromObject */
                /** @var Member $toObject */
                $idPhotoName = $fromObject->idPhotoName;
                $idPhotoPath = $fromObject->idPhotoPath;
                if(!empty($idPhotoName) && !empty($idPhotoPath)){
                    $realIdPhotoPath = STORAGE_PUBLIC_DIR_PATH . DS . $fromObject->idPhotoPath;

                    $newPhoto = false;
                    $existIdentificationImage = $toObject->getIdentificationImage();
                    if (isset($existIdentificationImage) && $realIdPhotoPath != $existIdentificationImage->getRealFilePath()) {
                        $deletePhotos[] = $existIdentificationImage;
                        $newPhoto = true;
                    } else if (!isset($existIdentificationImage)) {
                        $newPhoto = true;
                    }

                    if ($newPhoto && file_exists($realIdPhotoPath)) {
                        $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realIdPhotoPath);
                        $uploadedFile = new UploadedFile();
                        $uploadedFile->setMember($toObject);
                        $uploadedFile->setFileName($value);
                        $uploadedFile->setFilePath("member/" . $toObject->getId() . "/" . $physicalFileName);
                        $uploadedFile->setPhysicalFileName($physicalFileName);
                        $toObject->setIdentificationImage($uploadedFile);
                        $newPhotos[$realIdPhotoPath] = $uploadedFile;
                    }
                }
            },
            // プライベート写真
            "privatePhotoName" => function ($value, $fromObject, $toObject) use (&$newPhotos, &$deletePhotos) {
                /** @var string $value */
                /** @var ProfilePhotoEditUpdateInputPort $fromObject */
                /** @var Member $toObject */
                $privatePhotoName = $fromObject->privatePhotoName;
                $privatePhotoPath = $fromObject->privatePhotoPath;
                if(!empty($privatePhotoName) && !empty($privatePhotoPath)){
                    $realPrivatePhotoPath = STORAGE_PUBLIC_DIR_PATH . DS . $fromObject->privatePhotoPath;

                    $newPhoto = false;
                    $existPrivateImage = $toObject->getPrivateImage();
                    if (isset($existPrivateImage) && $realPrivatePhotoPath != $existPrivateImage->getRealFilePath()) {
                        $deletePhotos[] = $existPrivateImage;
                        $newPhoto = true;
                    } else if (!isset($existPrivateImage)) {
                        $newPhoto = true;
                    }

                    if ($newPhoto && file_exists($realPrivatePhotoPath)) {
                        $physicalFileName = str_replace(STORAGE_PUBLIC_TEMP_DIR_PATH . DS, "", $realPrivatePhotoPath);
                        $uploadedFile = new UploadedFile();
                        $uploadedFile->setMember($toObject);
                        $uploadedFile->setFileName($value);
                        $uploadedFile->setFilePath("member/" . $toObject->getId() . "/" . $physicalFileName);
                        $uploadedFile->setPhysicalFileName($physicalFileName);
                        $toObject->setPrivateImage($uploadedFile);
                        $newPhotos[$realPrivatePhotoPath] = $uploadedFile;
                    }
                }
            },
            // ハッシュタグ
            "hashTag" => function ($value, $fromObject, $toObject) use (&$newPhotos, &$deletePhotos) {
                /** @var string $value */
                /** @var ProfilePhotoEditUpdateInputPort $fromObject */
                /** @var Member $toObject */
                $hashTag = $toObject->getHashTag();
                if (empty($hashTag)) {
                    $hashTag = new Tag();
                    $hashTag->setMember($toObject);
                    $hashTag->setFixingFlag(Tag::HASH_TAG);
                }
                $hashTag->setName($value);
                $hashTag->setColor($fromObject->hashTagColor);
                $toObject->setHashTag($hashTag);
            }
        ]);

        // 変更を実行する
        $this->memberRepository->saveOrUpdate($member, true);
        $this->uploadedFileRepository->delete($deletePhotos);

        foreach ($newPhotos as $path => $photo) {
            File::createDir($photo->getRealFileDir());
            File::rename($path, $photo->getRealFilePath());
        }

        foreach ($deletePhotos as $photo) {
            File::remove($photo->getRealFilePath());
        }

        // リクエストする証明写真が存在しない場合は現在登録されている証明写真を削除する
        $idPhotoName = $inputPort->idPhotoName;
        $idPhotoPath = $inputPort->idPhotoPath;
        if(empty($idPhotoName) || empty($idPhotoPath)){
            $deleteIdPhoto = $member->getIdentificationImage();
            if (!empty($deleteIdPhoto)) {
                $this->uploadedFileRepository->delete($deleteIdPhoto);
                File::remove($deleteIdPhoto->getRealFilePath());
            }
        }

        // リクエストするプライベート画像が存在しない場合は現在登録されているプライベート画像を削除する
        $privatePhotoName = $inputPort->privatePhotoName;
        $privatePhotoPath = $inputPort->privatePhotoPath;
        if(empty($privatePhotoName) || empty($privatePhotoPath)){
            $deletePrivatePhoto = $member->getPrivateImage();
            if (!empty($deletePrivatePhoto)) {
                $this->uploadedFileRepository->delete($deletePrivatePhoto);
                File::remove($deletePrivatePhoto->getRealFilePath());
            }
        }

        //ログ出力
        Log::infoIn();
    }

//    /**
//     * 登録する
//     *
//     * @param ProfilePhotoEditStoreInputPort $inputPort
//     * @param ProfilePhotoEditStoreOutputPort $outputPort
//     */
//    public function store($inputPort, $outputPort): void
//    {
//        //ログ出力
//        Log::infoIn();
//        // 会員
//        $member = $this->getLoggedInMember($inputPort);
//
//        // タグ
//        $hashTag = $member->getHashTag();
//        if (empty($hashTag)) {
//            $hashTag = new Tag();
//            $hashTag->setMember($member);
//            $hashTag->setFixingFlag(Tag::HASH_TAG);
//        }
//        $hashTag->setName($inputPort->hashTag);
//        $hashTag->setColor($inputPort->hashTagColor);
//        $member->setHashTag($hashTag);
//
//        // 照明写真
//        $beforeIdPhotoFilePath = null;
//        if ($inputPort->isChangeIdPhoto === "true") {
//            $idPhoto = $member->getIdentificationImage();
//            if(empty($idPhoto)){
//                $idPhoto = new UploadedFile();
//                $idPhoto->setMember($member);
//            }else{
//                $beforeIdPhotoFilePath = $idPhoto->getFilePath();
//            }
//            $idPhoto->setFilePath($inputPort->idPhotoPath);
//            $idPhoto->setPhysicalFileName($inputPort->idPhotoPhysicalFileName);
//            $idPhoto->setFileName($inputPort->idPhotoOriginalFileName);
//            $member->setIdentificationImage($idPhoto);
//        }
//
//        // プライベート写真
//        $beforePrivatePhotoFilePath = null;
//        if ($inputPort->isChangePrivatePhoto === "true") {
//            $privatePhoto = $member->getPrivateImage();
//            if (empty($privatePhoto)) {
//                $privatePhoto = new UploadedFile();
//                $privatePhoto->setMember($member);
//            }else{
//                $beforePrivatePhotoFilePath = $privatePhoto->getFilePath();
//            }
//            $privatePhoto->setFilePath($inputPort->privatePhotoPath);
//            $privatePhoto->setPhysicalFileName($inputPort->privatePhotoPhysicalFileName);
//            $privatePhoto->setFileName($inputPort->privatePhotoOriginalFileName);
//            $member->setPrivateImage($privatePhoto);
//        }
//        $this->memberRepository->saveOrUpdate($member, true);
//
//        Log::infoOperationCreateLog("", ["member" => (array)$member], "");
//
//        // リダイレクト先を指定
//        $outputPort->url = route('front.profile');
//        $outputPort->beforeIdPhotoFilePath = $beforeIdPhotoFilePath;
//        $outputPort->beforePrivatePhotoFilePath =$beforePrivatePhotoFilePath;
//
//        //ログ出力
//        Log::infoIn();
//    }
}
