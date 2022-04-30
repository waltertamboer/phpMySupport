<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Ticket;

enum TicketStatus: string
{
    case Closed = 'closed';
    case Open = 'open';
    case Reopened = 'reopened';
    case Resolved = 'resolved';
}
