<?php
/**
 * Created by PhpStorm.
 * User: hong
 * Date: 9/29/16
 * Time: 3:47 PM
 * version: php5.6.
 */

namespace PhUtils;

/**
 * Class Benchmark.
 */
class Benchmark
{
    /**
     * @var array
     */
    public static $benchmark_results = [];
    /**
     * @var array
     */
    public static $benchmark_start_times = [];
    /**
     * @var array
     */
    public static $benchmark_start_count = [];
    /**
     * @var array
     */
    protected static $temporary_benchmarks = [];

    /**
     * @param      $identifier
     * @param bool $temporary_benchmark
     */
    public static function start($identifier, $temporary_benchmark = false)
    {
        self::$benchmark_start_times[$identifier] = self::getMicrotime();

        if (isset(self::$benchmark_start_count[$identifier])) {
            self::$benchmark_start_count[$identifier] = self::$benchmark_start_count[$identifier] + 1;
        } else {
            self::$benchmark_start_count[$identifier] = 1;
        }

        if ($temporary_benchmark == true) {
            self::$temporary_benchmarks[$identifier] = true;
        }
    }

    /**
     * @param $identifier
     *
     * @return mixed
     */
    public static function getCallCount($identifier)
    {
        return self::$benchmark_start_count[$identifier];
    }

    /**
     * @param $identifier
     *
     * @return float|null
     */
    public static function stop($identifier)
    {
        if (isset(self::$benchmark_start_times[$identifier])) {
            $elapsed_time = self::getMicrotime() - self::$benchmark_start_times[$identifier];

            if (isset(self::$benchmark_results[$identifier])) {
                self::$benchmark_results[$identifier] += $elapsed_time;
            } else {
                self::$benchmark_results[$identifier] = $elapsed_time;
            }

            return $elapsed_time;
        }

        return;
    }

    /**
     * @param $identifier
     *
     * @return int|mixed
     */
    public static function getElapsedTime($identifier)
    {
        if (isset(self::$benchmark_results[$identifier])) {
            return self::$benchmark_results[$identifier];
        }

        return 0;
    }

    /**
     * @param $identifier
     */
    public static function reset($identifier)
    {
        if (isset(self::$benchmark_results[$identifier])) {
            self::$benchmark_results[$identifier] = 0;
        }
    }

    /**
     * @param array $retain_benchmarks
     */
    public static function resetAll($retain_benchmarks = array())
    {
        // If no benchmarks should be retained
        if (count($retain_benchmarks) == 0) {
            self::$benchmark_results = array();

            return;
        }

        // Else reset all benchmarks BUT the retain_benachmarks
        @reset(self::$benchmark_results);
        while (list($identifier) = @each(self::$benchmark_results)) {
            if (!in_array($identifier, $retain_benchmarks)) {
                self::$benchmark_results[$identifier] = 0;
            }
        }
    }

    /**
     * @param string $linebreak
     */
    public static function printAllBenchmarks($linebreak = '<br />')
    {
        @reset(self::$benchmark_results);
        while (list($identifier, $elapsed_time) = @each(self::$benchmark_results)) {
            if (!isset(self::$temporary_benchmarks[$identifier])) {
                echo $identifier.': '.$elapsed_time.' sec'.$linebreak;
            }
        }
    }

    /**
     * Returns all registered benchmark-results.
     *
     * @return array associative Array. The keys are the benchmark-identifiers, the values the benchmark-times.
     */
    public static function getAllBenchmarks()
    {
        $benchmarks = array();

        @reset(self::$benchmark_results);
        while (list($identifier, $elapsed_time) = @each(self::$benchmark_results)) {
            if (!isset(self::$temporary_benchmarks[$identifier])) {
                $benchmarks[$identifier] = $elapsed_time;
            }
        }

        return $benchmarks;
    }

    /**
     * Returns the current time in seconds and milliseconds.
     *
     * @return float
     */
    public static function getMicrotime()
    {
        return microtime(true);
    }

    /**
     * @return array
     */
    public static function getTemporary()
    {
        return self::$temporary_benchmarks;
    }
}
