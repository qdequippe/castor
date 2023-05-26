<?php

namespace context;

use Castor\Attribute\AsContext;
use Castor\Attribute\AsTask;
use Castor\Context;
use Castor\VerbosityLevel;

use function Castor\exec;

#[AsContext(name: 'production')]
function productionContext(): Context
{
    return defaultContext()->withData(['production' => true]);
}

#[AsContext(default: true)]
function defaultContext(VerbosityLevel $verbosityLevel = VerbosityLevel::NORMAL): Context
{
    return new Context(['production' => false, 'foo' => 'bar'], verbosityLevel: $verbosityLevel);
}

#[AsContext(name: 'exec')]
function execContext(): Context
{
    $production = trim(exec('echo $PRODUCTION', quiet: true)->getOutput());

    return new Context(['production' => (bool) $production]);
}

#[AsTask(description: 'Displays information about the context')]
function context(Context $context)
{
    if ($context['production']) {
        echo "production\n";
    } else {
        echo "development\n";
    }

    echo "verbosity: {$context->verbosityLevel->value}\n";

    echo "foo: {$context['foo']}\n";
}
