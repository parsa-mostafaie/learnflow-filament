<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum Status
 *
 * Represents the status of a question.
 * Provides label, icon, and color representations for use in Filament UI.
 *
 * @method static self Pending()
 * @method static self Approved()
 * @method static self Rejected()
 */
enum Status: string implements HasLabel, HasIcon, HasColor
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    /**
     * Get the translated label for the status.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('questions.statuses.pending'),    // Localized "Pending"
            self::Approved => __('questions.statuses.approved'),  // Localized "Approved"
            self::Rejected => __('questions.statuses.rejected'),  // Localized "Rejected"
        };
    }

    /**
     * Get the associated icon for the status.
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Approved => 'heroicon-o-check-circle',
            self::Rejected => 'heroicon-o-x-circle',
        };
    }

    /**
     * Get the color representation of the status.
     *
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => Color::Amber,
            self::Approved => Color::Teal,
            self::Rejected => Color::Rose,
        };
    }
}
