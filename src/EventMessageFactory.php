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
        $class = strtr($record['type'], '.', '\\');
        $payload = $record['payload'];
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }
        $metadata = $record['metadata'];
        if (is_string($metadata)) {
            $metadata = json_decode($metadata, true);
        }

        $message = new EventMessage(
            $record['id'],
            $record['aggregate_id'],
            (int) $record['playhead'],
            new EventMeta($metadata),
            $class::deserialize($payload),
            \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $record['recorded_on'], new \DateTimeZone('UTC'))
        );

        return $message;
    }
}
