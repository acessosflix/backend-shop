<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Open = 'aberto';
    case Ongoing = 'andamento';
    case Resolved = 'resolvido';
    case Closed = 'fechado';
}
