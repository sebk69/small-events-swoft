<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Sebk\SmallEventsSwoft\Console\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use function input;
use function output;
use function sprintf;

/**
 * Class ListenCommand
 *
 * @since 2.0
 *
 * @Command(name="small-events",coroutine=false)
 */
class ListenCommand
{
    /**
     * @CommandMapping(name="listen")
     */
    public function listen(): void
    {
        $queueName = input()->getOption('queue', null);
        bean("smallListener")->listen($queueName);
    }

}
