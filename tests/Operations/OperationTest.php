<?php

namespace Studio1902\PeakCommands\Tests\Operations;

use Studio1902\PeakCommands\Facades\Registry;
use Studio1902\PeakCommands\Tests\TestCase;

class OperationTest extends TestCase
{
    public function test_it_resolves_correct_operation_class()
    {
        $config = [
            'type' => 'copy',
            'input' => '_call_to_action.antlers.html',
            'output' => 'resources/views/page_builder/_call_to_action.antlers.html',
        ];

        $expected = app(\Studio1902\PeakCommands\Operations\Copy::class, ['config' => $config]);

        $this->assertEquals($expected, Registry::resolveOperation('copy', $config));
        $this->assertEquals($expected, Registry::resolveOperation('\Studio1902\PeakCommands\Operations\Copy', $config));
        $this->assertEquals($expected, Registry::resolveOperation(\Studio1902\PeakCommands\Operations\Copy::class, $config));
    }
}
