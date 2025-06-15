@props(['course'])

<?php

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use App\Filament\Resources\CourseResource;

use function Livewire\Volt\{uses, state, on};

uses([HasForms::class, HasInfolists::class, InteractsWithForms::class, InteractsWithInfolists::class]);

state('course');

on([
    'course-single-reload' => function ($course) {
        if ($course == $this->course->id) {
            $this->course->refresh();
        }
    },
]);

$courseInfolist = function (Infolist $infolist) {
    return CourseResource::infolist($infolist)->record($this->course);
};

?>

<div>
  <div class="flex justify-end mb-3">
    <livewire:courses.infolist-actions :$course />
  </div>

  {{ $this->courseInfolist }}
</div>
