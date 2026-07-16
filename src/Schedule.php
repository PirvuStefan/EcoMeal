<?php

namespace App;

use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule as SymfonySchedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Scheduler\Trigger\CronExpressionTrigger;
use Symfony\Component\Console\Messenger\RunCommandMessage;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
class Schedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): SymfonySchedule
    {
        return (new SymfonySchedule())
            ->stateful($this->cache)
            ->processOnlyLastMissedRun(true)
            ->add(
                RecurringMessage::trigger(
                    CronExpressionTrigger::fromSpec('0 1 * * *'),
                    new RunCommandMessage('app:package:cleanup'),
                )
            )
            // 17:00 → 20:30 (every 30 min)
            ->add(
                RecurringMessage::trigger(
                    CronExpressionTrigger::fromSpec('0,30 17-20 * * *'),
                    new RunCommandMessage('app:package:add-discount'),
                )
            )
            // 21:00 (final step)
            ->add(
                RecurringMessage::trigger(
                    CronExpressionTrigger::fromSpec('0 21 * * *'),
                    new RunCommandMessage('app:package:add-discount'),
                )
            )
            // testing: every 5 minutes
            ->add(
                RecurringMessage::trigger(
                    CronExpressionTrigger::fromSpec('*/5 * * * *'),
                    new RunCommandMessage('app:test:hello'),
                )
            )
        ;
    }
}
