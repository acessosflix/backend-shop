<?php

namespace App\Enums;

enum TicketPriority: string
{
    case Low = 'baixa';
    case Medium = 'media';
    case High = 'alta';
}
