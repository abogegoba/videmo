<?php

namespace ReLab\Commons\Exceptions;

use Throwable;

/**
 * Class 例外
 *
 * @package ReLab\Commons\Exceptions
 */
class Exception extends \Exception
{
    /**
     * 例外キー
     *
     * @var array
     */
    private $keys = [];

    /**
     * 補足情報
     *
     * @var array
     */
    protected $options = [];

    /**
     * Exception constructor.
     *
     * @param string|array $key
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($key, string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        if (is_array($key)) {
            foreach ($key as $k) {
                $this->addKey($k);
            }
        } else {
            $this->addKey($key);
        }
    }

    /**
     * 例外キーを追加する
     *
     * @param string $key
     * @param array|null $options
     */
    public function addKey(string $key, array $options = null): void
    {
        $key = $this->createKey($key);
        $this->keys[] = $key;
        if (!empty($options)) {
            $this->options[$key] = $options;
        }
    }

    /**
     * 補足情報を追加する
     *
     * @param string $key
     * @param array $options
     */
    public function addOptions(string $key, array $options): void
    {
        $key = $this->createKey($key);
        $this->options[$key] = $options;
    }

    /**
     * 例外キーを取得する
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->keys[0];
    }

    /**
     * 指定された例外キーに該当する補足情報を取得する
     *
     * @param string $key
     * @return array|null
     */
    public function getOption(string $key): ?array
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }
        return [];
    }

    /**
     * 全ての例外キーを取得する
     *
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * 全ての補足情報を取得する
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * 例外キーを作成する
     *
     * {ベース例外キー}.{指定された例外キー}
     *
     * @param string $key
     * @return string
     */
    private function createKey(string $key): string
    {
        $baseKey = $this->getBaseKey();
        if (!isset($baseKey) || preg_match("@^$baseKey\.@", $key)) {
            return $key;
        } else {
            return $this->getBaseKey() . '.' . $key;
        }
    }

    /**
     * 本例外のベースとなる例外キーを取得する
     *
     * @return null|string
     */
    protected function getBaseKey(): ?string
    {
        return null;
    }
}