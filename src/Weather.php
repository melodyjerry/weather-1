<?php

/*
 * This file is part of the zhoubohan/weather.
 *
 * (c) zhoubohan <zhoubohan.pro@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhoubohan\Weather;

use GuzzleHttp\Client;
use Zhoubohan\Weather\Exceptions\HttpException;
use Zhoubohan\Weather\Exceptions\InvalidArgumentException;

class Weather
{
    const TYPE_DOUGA = 'douga';

    const TYPE_MUSIC = 'music';

    const TYPE_GAME = 'game';

    const TYPE_ENT = 'ent';

    const TYPE_TELEPLAY = 'teleplay';

    const TYPE_BANGUMI = 'bangumi';

    const TYPE_MOVIE = 'movie';

    const TYPE_TECHNOLGY = 'technology';

    const TYPE_KICHIKU = 'kichiku';

    const TYPE_DANCE = 'dance';

    const TYPE_FASHION = 'fashion';

    const TYPE_LIFE = 'life';

    const TYPE_TIME_GUOCHUANG = 'timing_guochuang';

    const TYPE_GUOCHUANG = 'guochuang';

    const TYPE_DOCUMENTARY = 'documentary';

    const TYPE_CINEPHILE = 'cinephile';

    protected static $typeMap = [
        self::TYPE_DOUGA => 1,
        self::TYPE_MUSIC => 3,
        self::TYPE_GAME => 4,
        self::TYPE_ENT => 5,
        self::TYPE_TELEPLAY => 11,
        self::TYPE_BANGUMI => 13,
        self::TYPE_MOVIE => 23,
        self::TYPE_TECHNOLGY => 36,
        self::TYPE_KICHIKU => 119,
        self::TYPE_DANCE => 129,
        self::TYPE_FASHION => 155,
        self::TYPE_LIFE => 160,
        self::TYPE_TIME_GUOCHUANG => 167,
        self::TYPE_GUOCHUANG => 168,
        self::TYPE_DOCUMENTARY => 177,
        self::TYPE_CINEPHILE => 181,
    ];

    protected $key;

    protected $requestUrl = 'https://free-api.heweather.com/s6/weather/';

    protected $guzzleOptions = [];

    public function __construct($key)
    {
        $this->key = $key;
    }

    //获取http client
    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    //设置guzzle http client参数
    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    //获取天气
    public function getWeather($location, $type = 'now', $format = 'json', $lang = 'zh', $unit = 'i')
    {
        if (!\in_array(\strtolower($format), ['json'])) {
            throw new InvalidArgumentException('Invalid response format:'.$format);
        }

        if (!\in_array($type, ['now', 'forecast', 'lifestyle', 'grid-minute', 'hourly'])) {
            throw new InvalidArgumentException('Invalid type value:'.$type);
        }

        $requestUrl = $this->requestUrl.$type;

        $query = array_filter([
            'key' => $this->key,
            'location' => $location,
            'unit' => $unit,
            'lang' => $lang,
        ]);

        try {
            $response = $this->getHttpClient()->get($requestUrl, [
                    'query' => $query,
                ])->getBody()->getContents();

            return 'json' === $format ? \json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    //获取实时天气
    public function getLiveWeather($location)
    {
        return $this->getWeather($location, 'now');
    }

    //获取生活指数
    public function getLifestyleWeather($location)
    {
        return $this->getWeather($location, 'lifestyle');
    }

    //获取3-10天气预报
    public function getForecastWeather($location)
    {
        return $this->getWeather($location, 'forecast');
    }
}
