<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProjectStatus: string implements HasLabel, HasColor
{
    case Proposal = 'proposta';
    case Ongoing = 'andamento';
    case Concluded = 'concluido';
    case Canceled = 'cancelado';
    
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Proposal => 'Proposta',
            self::Ongoing => 'Em Andamento',
            self::Concluded => 'Concluído',
            self::Canceled => 'Cancelado',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Proposal => 'gray',
            self::Ongoing => 'info',
            self::Concluded => 'success',
            self::Canceled => 'danger',
        };
    }
}