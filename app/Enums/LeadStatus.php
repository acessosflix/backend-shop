<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'novo';
    case Contacted = 'contato';
    case Qualified = 'qualificado';
    case Lost = 'perdido';
    case Converted = 'convertido';
}
