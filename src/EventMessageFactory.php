<?php

declare(strict_types = 1);

namespace Cydrickn\EventStore\Dbal;

use Cydrickn\DDD\Common\EventStore\EventMessage;
use Cydrickn\DDD\Common\EventStore\EventMeta;

/**
 * Description of EventMessageFactory
 *
 * @author Cydrick Nonog <cydrick.dev@gmail.com>
 */
class EventMessageFactory
{
    public static function createMessageFromArray(array $record): EventMessage
    {
        $class = strtr(get_class($record['type']), '.', '\\');
        $message = new EventMessage(
            $record['id'],
            $record['aggregate_id'],
            $record['playhead'],
            new EventMeta($record['metadata']),
            $class::deserialize($record['payload']),
            \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $record['recorded_on'], new \DateTimeZone('UTC'))
        );

        return $message;
    }
}
