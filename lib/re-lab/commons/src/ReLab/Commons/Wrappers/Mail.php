<?php

namespace ReLab\Commons\Wrappers;

/**
 * Class Mail
 *
 * @package ReLab\Commons\Wrappers
 */
abstract class Mail
{
    /**
     * インスタンス
     *
     * @var Mail
     */
    private static $mail;

    /**
     * FromAddress
     *
     * @var null|string
     */
    protected $fromAddress;

    /**
     * FromName
     *
     * @var null|string
     */
    protected $fromName;

    /**
     * To
     *
     * @var string[]
     */
    protected $to = [];

    /**
     * CC
     *
     * @var string[]
     */
    protected $cc = [];

    /**
     * BCC
     *
     * @var string[]
     */
    protected $bcc = [];

    /**
     * Subject
     *
     * @var null|string
     */
    protected $subject;

    /**
     * Data
     *
     * @var mixed
     */
    protected $data;

    /**
     * Template
     *
     * @var null|string
     */
    protected $template;

    /**
     * 実装する
     *
     * @param Mail $mail
     */
    public static function implement(Mail $mail): void
    {
        self::$mail = $mail;
    }

    /**
     * インスタンスを作成する
     *
     * @param string $template
     * @param string $to
     * @param null|string $subject
     * @param null|mixed $data
     * @return null|Mail
     */
    public static function getInstance(string $template, string $to, ?string $subject = null, $data = null): ?Mail
    {
        if (isset(self::$mail)) {
            $mail = clone self::$mail;
            $mail->template($template)->to($to)->subject($subject)->data($data);
            return $mail;
        } else {
            return null;
        }
    }

    /**
     * From
     *
     * @param string $fromAddress
     * @param null|string $fromName
     * @return Mail
     */
    public function from(string $fromAddress, ?string $fromName = null): Mail
    {
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * CC
     *
     * @param string $cc
     * @return Mail
     */
    public function cc(string $cc): Mail
    {
        $this->cc[] = $cc;
        return $this;
    }

    /**
     * BCC
     *
     * @param string $bcc
     * @return Mail
     */
    public function bcc(string $bcc): Mail
    {
        $this->bcc[] = $bcc;
        return $this;
    }

    /**
     * To
     *
     * @param string $to
     * @return Mail
     */
    public function to(string $to): Mail
    {
        $this->to[] = $to;
        return $this;
    }

    /**
     * Data
     *
     * @param mixed $data
     * @return Mail
     */
    public function data($data): Mail
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Subject
     *
     * @param string $subject
     * @return Mail
     */
    public function subject(string $subject): Mail
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Template
     *
     * @param string $template
     * @return Mail
     */
    public function template(string $template): Mail
    {
        $this->template = $template;
        return $this;
    }

    /**
     * 送信する
     *
     * @return bool
     */
    abstract function send(): bool;
}
