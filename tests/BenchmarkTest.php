<?php

/**
 * Created by PhpStorm.
 * User: hong
 * Date: 16-10-15
 * Time: 下午6:26
 */

use PHPUnit\Framework\TestCase;

use PhUtils\Benchmark;

class BenchmarkTest extends TestCase
{

    public function testGetCallCount() {

        Benchmark::$benchmark_start_count['test1'] = 1;

        $this->assertEquals(1, Benchmark::getCallCount('test1'));
    }

    /**
     * @depends testGetCallCount
     */
    public function testStart() {

        $identify = 'test';
        Benchmark::start($identify);

        $this->assertEquals(1, Benchmark::getCallCount($identify));

        Benchmark::start($identify);

        $this->assertEquals(2, Benchmark::getCallCount($identify));


        $this->assertNotNull(Benchmark::$benchmark_start_times[$identify]);

         //start temporary
        Benchmark::start('test1', true);

        $result = Benchmark::getTemporary()['test1'];

        $this->assertEquals(true, $result);
    }


    public function testGetMicrotime() {
        $this->assertNotNull(Benchmark::getMicrotime());
    }

    public function testGetElapsedTime() {
        Benchmark::$benchmark_results['test1'] = 0;
        $time = Benchmark::getElapsedTime('test1');

        $this->assertEquals(0, $time);

        $this->assertEquals(0, Benchmark::getElapsedTime('noExists'));
    }

    public function testStop() {

        $identify = 'test2';

        Benchmark::start($identify);

        $elapsedTime = Benchmark::stop($identify);

        $this->assertNotNull($elapsedTime);

        $this->assertNotNull(Benchmark::getElapsedTime($identify));


        Benchmark::start($identify);
        $elapsedTime2 = Benchmark::stop($identify);

        $this->assertNotNull($elapsedTime2);

        $result = Benchmark::stop('noExists');

        $this->assertNull($result);

    }

    /**
     * @depends testStop
     */
    public function testReset() {

        $identify = 'test2';

        Benchmark::reset($identify);

        $this->assertEquals(0, Benchmark::getElapsedTime($identify));

    }

    /**
     * @depends testStop
     */
    public function testResetAll() {
        Benchmark::resetAll();

        $this->assertEmpty(Benchmark::$benchmark_results);
    }


    public function testGetAllBenchmarks() {

        $identify = 'test3';

        Benchmark::start($identify);
        Benchmark::stop($identify);

        $benchmarks = Benchmark::getAllBenchmarks();

        $this->assertArrayHasKey($identify, $benchmarks);
    }

    /**
     * @depends testStop
     * @depends testGetAllBenchmarks
     */
    public function testResetAllWithRetain() {
        Benchmark::start('willClear');
        Benchmark::stop('willClear');
        Benchmark::resetAll(['test3']);

        $this->assertEquals(0, Benchmark::getElapsedTime('willClear'));
    }

    public function testPrintAllBenchmarks() {

        Benchmark::resetAll();

        $identity = 'test';

        Benchmark::start($identity);
        Benchmark::stop($identity);

        ob_start();
        Benchmark::printAllBenchmarks();
        $content = ob_end_clean();

        $this->assertNotEmpty($content);
    }

}
