<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasLabel, HasIcon, HasColor
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('questions.statuses.pending'),    // Localized "Pending"
            self::Approved => __('questions.statuses.approved'),  // Localized "Approved"
            self::Rejected => __('questions.statuses.rejected'),  // Localized "Rejected"
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Approved => 'heroicon-o-check-circle',
            self::Rejected => 'heroicon-o-x-circle',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => Color::Amber,
            self::Approved => Color::Teal,
            self::Rejected => Color::Rose,
        };
    }
}
