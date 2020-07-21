<?php
namespace Tests\Unit;

use Tests\Unit;
use App\Gurunavi;
use PHPUnit\Framework\TestCase;

class GurunaviSessionTest extends TestCase
{
    private $target = null;
    private $statement = null;
    private $connection = null;

    //各テストメソッドの前に実行されるメソッド

    public function testExample()
    {
        $tester = new Gurunabi();
        $this->assertEquals($tester->test, 'test');

        $this->assertNULL($this->target);
    }
}
