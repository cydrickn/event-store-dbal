<?php

declare(strict_types = 1);

namespace Cydrickn\EventStore\Dbal;

use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\FetchMode;

/**
 * Description of EventMessageIterator
 *
 * @author Cydrick Nonog <cydrick.dev@gmail.com>
 */
class EventMessageIterator implements \Iterator
{
    /**
     * @var ResultStatement
     */
    private $resultStatement;

    /**
     * @var bool
     */
    private $rewinded = false;

    /**
     * @var int
     */
    private $key = -1;

    /**
     * @var mixed
     */
    private $current = null;

    public function __construct(ResultStatement $resultStatement)
    {
        $this->resultStatement = $resultStatement;
    }

    public function current()
    {
        return $this->current;
    }

    public function key(): \scalar
    {
        return $this->key;
    }

    public function next()
    {
        $result = $this->resultStatement->fetch(FetchMode::ASSOCIATIVE);

        if ($result === false) {
            $this->current = false;
        } else {
            $this->current = EventMessageFactory::createMessageFromArray($result);
        }

        $this->key++;

        return $this->current;
    }

    public function rewind(): void
    {
        if ($this->rewinded == true) {
            throw new \LogicException("Can only iterate a Result once.");
        } else {
            $this->current = $this->next();
            $this->rewinded = true;
        }
    }

    public function valid(): bool
    {
        return ($this->current != false);
    }
}
