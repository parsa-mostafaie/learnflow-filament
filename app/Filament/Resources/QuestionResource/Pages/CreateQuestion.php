<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use App\Enums\Status;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $data['status'] = auth()->user()->can("create approved questions") ? Status::Approved : Status::Pending;

        return $data;
    }
}
