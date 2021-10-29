<?php

namespace App\Business\Services;

use App\Utilities\Log;
use Carbon\Carbon;

/**
 * Class ProductOptionMappingTrait
 *
 * 年度トレイト
 *
 * @package App\Business\UseCases\Admin
 */
trait YearMonthTrait
{

    /**
     * 年度を求める
     *
     * @param Carbon|null $date
     * @return int
     */
    public static function getThisYear(?Carbon $date = null): int
    {
        if ($date === null) {
            $date = Carbon::now();
        }

        return (string)($date->month > 3 ? intval($date->year) : intval($date->subYear()->year));
    }

    /**
     * 月リスト
     *
     * @return array
     */
    public static function getAllMonthList()
    {
        return [
            '1' => '1月',
            '2' => '2月',
            '3' => '3月',
            '4' => '4月',
            '5' => '5月',
            '6' => '6月',
            '7' => '7月',
            '8' => '8月',
            '9' => '9月',
            '10' => '10月',
            '11' => '11月',
            '12' => '12月',
        ];
    }

    /**
     * 年度の2年度前迄の年リスト
     *
     * @return array
     */
    public static function getGraduationYearListTwoYearAgo()
    {
        // 今年度の卒業年
        $thisGraduationYear = self::getThisYear() + 1;
        $nextGraduationYear = $thisGraduationYear + 1;
        $graduationYearOneYearAgo = $thisGraduationYear - 1;
        $graduationYearTwoYearAgo = $graduationYearOneYearAgo - 1;
        return [
            $nextGraduationYear => $nextGraduationYear . '年',
            $thisGraduationYear => $thisGraduationYear . '年',
            $graduationYearOneYearAgo => $graduationYearOneYearAgo . '年',
            $graduationYearTwoYearAgo => $graduationYearTwoYearAgo . '年',
        ];
    }

    /**
     * 20年前から現在の年までの年度リスト
     *
     * @return array
     */
    public static function getGraduationYearListTenYearAgo()
    {
        //昨年度の卒業年
        $graduationYearOneYearAgo = self::getThisYear();

        //過去10年分の年リストを作成
        for($i=0; $i<20; $i++){
            $GraduationYearListTenYearAgo[$graduationYearOneYearAgo - $i] = $graduationYearOneYearAgo - $i.'年';
        }

        return $GraduationYearListTenYearAgo;
    }

    /**
     * 2年前から10年先までの年度リスト
     *
     * @return array
     */
    public static function getGraduationTwelveYearListYearAgo()
    {
        //今年度の卒業年
        $graduationYearOneYearAgo = self::getThisYear() + 1;

        // 2年前から10年先までの年度リストを作成
        for($i= -11; $i<10; $i++){
            $GraduationYearListTwelveYearAgo[$graduationYearOneYearAgo + $i] = $graduationYearOneYearAgo + $i.'年';
        }

        return $GraduationYearListTwelveYearAgo;
    }
}
