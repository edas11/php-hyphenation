<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 17.15
 */

namespace Edvardas\Hyphenation\UtilityComponents\Timer;

class Timer
{
    private $time = -1;

    public function start()
    {
        $this->time = microtime(true);
    }

    public function getInterval(): float
    {
        if ($this->time === -1) {
            throw new \Exception('Timer not started');
        }
        $endTime = microtime(true);
        $timeInterval = $endTime - $this->time;
        return $timeInterval;
    }

}