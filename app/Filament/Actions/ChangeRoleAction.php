<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use App\Events\UserRoleChanged;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ChangeRoleAction extends Action
{
    use CanCustomizeProcess;

    // public static function getDefaultName(): ?string
    // {
    //     return 'change-role';
    // }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn(): string => $this->getLocalizedValue('label', null, [
            'label' => $this->getRecordTitle(),
        ]));

        $this->modalHeading(fn(): string => $this->getLocalizedValue('modal.heading', null, [
            'label' => $this->getRecordTitle(),
        ]));

        $this->modalSubmitActionLabel(fn() => $this->getLocalizedValue('modal.actions.change-role.label', null, [
            'label' => $this->getRecordTitle(),
        ]));

        $this->successNotificationTitle(fn() => $this->getLocalizedValue('notifications.change-roled.title', null, [
            'label' => $this->getRecordTitle(),
        ], true));

        $this->defaultColor(fn() => $this->getMode($this->getRecord()) == "promoted" ? 'success' : 'warning');

        $this->groupedIcon(fn() => FilamentIcon::resolve('actions::' . $this->getDefaultName() . '-action.grouped') ?? $this->getDefaultIcon());

        $this->icon(fn() => $this->getDefaultIcon());

        $this->requiresConfirmation();

        $this->modalIcon(fn() => FilamentIcon::resolve('actions::' . $this->getDefaultName() . '-action.modal') ?? $this->getDefaultIcon());

        // $this->keyBindings(['mod+a']);

        $new_role = fn() => $this->getNextRole();

        $this->hidden(static function (Model $record) use ($new_role): bool {
            return !auth()->user()->can('changeRole', [$record, $new_role(), true]);
        });

        $this->authorize(function (Model $record) use ($new_role): bool {
            return auth()->user()->can('changeRole', [$record, $new_role()]);
        });

        $this->action(function ($record, $livewire) use ($new_role): void {
            $previous_role = $record->role_name;

            $result = $this->process(function (Model $record) use ($new_role) {
                $newRole = $new_role();

                // changeRole the user
                if ($ret = $record->setRole($newRole)) {
                    $record->save();
                }

                return $ret;
            });

            if (!$result) {
                $this->failure();
                return;
            }

            event(new UserRoleChanged($record, auth()->user(), $previous_role, $record->role_name));

            // Dispatch events to reload the users table and the single user view
            $livewire->dispatch('users-table-reload');
            $livewire->dispatch('user-single-reload', $record->id);

            $this->success();
        });
    }

    protected function getLocalizedValue($key, $default = null, $replace = null, $inverse = false)
    {
        $mode = $this->getMode($this->getRecord());
        $singleOn = $inverse ? "demoted" : "promoted";
        $mode = $mode == $singleOn ? "single" : "demote";

        return __("filament-actions.change-role.$mode.$key", $replace) ?? $default;
    }

    protected function getDefaultIcon(): string
    {
        return $this->getMode($this->getRecord()) === 'promoted'
            ? 'heroicon-m-arrow-up-circle'
            : 'heroicon-m-arrow-down-circle';
    }

    protected function getMode($user): string
    {
        return User::diffRoles($user->role_name, $this->getNextRole()) < 0 ? "promoted" : "demoted";
    }

    protected function getNextRole(): ?string
    {
        // Logic to determine the next role based on the current role or other criteria
        return 'user'; // Placeholder, should return the next role as a string
    }

    public function getRecordTitle(?Model $record = null): string
    {
        $record ??= $this->getRecord();

        return data_get(__('users.roles'), $this->getNextRole());
    }
}
