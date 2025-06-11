@props(['course'])

<?php

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use App\Events\CourseEnrollment;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Action;
use App\Filament\Resources\CourseResource\Pages\ViewCourse;
use App\Models\Course;
use Filament\Support\Facades\FilamentIcon;
use App\Filament\Actions\EnrollToggleSimpleAction;

use function Livewire\Volt\{uses, state};

uses([HasForms::class, HasActions::class, InteractsWithActions::class, InteractsWithForms::class]);

state('course');

$viewAction = function () {
    return Action::make('view')->color('primary')->label(__('filament-actions::view.single.label'))->record($this->course)->url(fn($record) => ViewCourse::getUrl([$record]))->icon('heroicon-o-eye');
};

$enrollAction = function () {
    return EnrollToggleSimpleAction::make('enroll')->record($this->course);
};
?>

<div>
  <x-filament-actions::group :actions="[$this->enrollAction, $this->viewAction]" />

  <x-filament-actions::modals />
</div>
