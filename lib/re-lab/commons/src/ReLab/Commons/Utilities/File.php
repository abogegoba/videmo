<?php

namespace ReLab\Commons\Utilities;

use Carbon\Carbon;

/**
 * Class File
 *
 * @package ReLab\Commons\Utilities
 */
class File
{
    /**
     * ファイル名から拡張子を取得する
     *
     * @param string $fileName
     * @return null|string
     */
    public static function getExtensionToFileName(string $fileName): ?string
    {
        $extension = null;
        if (preg_match("@^.*\.(?<extension>.+)$@", $fileName, $result)) {
            $extension = $result['extension'];
        }
        return $extension;
    }

    /**
     * フォルダ及びファイル名を変更する
     *
     * @param string $beforePath
     * @param string $afterPath
     */
    public static function rename(string $beforePath, string $afterPath)
    {
        if (file_exists($beforePath) && !file_exists($afterPath)) {
            //ディレクトリ名「test_dir」を「hoge_dir」に変更する。
            rename($beforePath, $afterPath);
        }
    }

    /**
     * ランダムなファイル名を取得する
     *
     * @param null|string $extension
     * @param bool|null $onlyDatetime
     * @return string
     */
    public static function createName(?string $extension = null, ?bool $onlyDatetime = false): string
    {
        $fineName = Carbon::now()->format("YmdHis");
        if (!$onlyDatetime) {
            $fineName .= '-' . UUID::generate(UUID::UUID_RANDOM, UUID::FMT_STRING);
        }
        if (isset($extension)) {
            $fineName .= '.' . $extension;
        }
        return $fineName;
    }

    /**
     * フォルダを作成する
     *
     * @param string $fileDir
     */
    public static function createDir(string $fileDir): void
    {
        if (!file_exists($fileDir)) {
            $o = mkdir($fileDir, 0755, true);
        }
    }

    /**
     * ファイルを保存する
     *
     * @param string $fileDir
     * @param string $fileName
     * @param $data
     */
    public static function save(string $fileDir, string $fileName, $data): void
    {
        self::createDir($fileDir);
        file_put_contents($fileDir . '/' . $fileName, $data);
    }

    /**
     * ファイルを削除する
     *
     * @param string $filePath
     */
    public static function remove(string $filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * フォルダを削除する
     *
     * @param null|string $filePath
     */
    public static function removeDir(string $filePath)
    {
        // 一時ファイル削除
        if (file_exists($filePath)) {
            system('rm -rf ' . escapeshellarg($filePath), $retval);
//            $directoryIterator = new \DirectoryIterator($filePath);
//            foreach ($directoryIterator as $info) {
//                if ($info->getFilename() == "." || $info->getFilename() == "..") {
//                    continue;
//                }
//                if ($info->isFile()) {
//                    $pathName = $info->getPathname();
//                    File::remove($pathName);
//                } elseif ($info->isDir()) {
//                    // フォルダが在った場合、再帰的に削除処理を実行
//                    File::removeDir($info->getPathname());
//                }
//            }
//            rmdir($filePath);
        }
    }

    /**
     * 文字エンコードを変換する
     *
     * @param string $filePath
     * @param string $toEncoding
     * @param null|string $fromEncoding
     * @param string|null $convertedFilePath
     * @return string
     */
    public static function convertEncoding(string $filePath, string $toEncoding, ?string $fromEncoding = null, string $convertedFilePath = null): string
    {
        if (!isset($convertedFilePath)) {
            $convertedFilePath = $filePath;
        }
        file_put_contents($convertedFilePath, mb_convert_encoding(file_get_contents($filePath), $toEncoding, $fromEncoding));
        return $convertedFilePath;
    }
}