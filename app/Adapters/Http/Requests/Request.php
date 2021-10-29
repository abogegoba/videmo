<?php

namespace App\Adapters\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Request
 *
 * @package App\Adapters\Http\Requests
 */
class Request extends FormRequest
{
    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}