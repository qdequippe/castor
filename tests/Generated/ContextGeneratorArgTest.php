<?php

namespace Castor\Tests\Generated;

use Castor\Tests\TaskTestCase;

class ContextGeneratorArgTest extends TaskTestCase
{
    // no task
    public function test(): void
    {
        $process = $this->runTask([], '{{ base }}/tests/fixtures/broken/context-generator-arg', needRemote: true);

        $this->assertSame(1, $process->getExitCode());
        $this->assertStringEqualsFile(__FILE__ . '.output.txt', $process->getOutput());
        $this->assertStringEqualsFile(__FILE__ . '.err.txt', $process->getErrorOutput());
    }
}