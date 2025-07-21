<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use App\Events\UserRoleChanged;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * ChangeRoleAction is a Filament action to change the role of a user.
 * It supports dynamic labeling, icons, confirmation, authorization, and event dispatching.
 *
 * @see \App\Filament\Actions\ChangeRoleUserAction
 * @see \App\Filament\Actions\ChangeRoleInstructorAction
 * @see \App\Filament\Actions\ChangeRoleManagerAction
 * @see \App\Filament\Actions\ChangeRoleAdminAction
 * @see \App\Filament\Actions\ChangeRoleDeveloperAction
 * 
 * @package \App\Filament\Actions
*/
class ChangeRoleAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Set up the action.
     *
     * @return void
     */
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

    /**
     * Get a localized string for the action, based on the current mode.
     *
     * @param string $key Translation key suffix
     * @param mixed|null $default Default value if translation missing
     * @param array|null $replace Replacement parameters for translation
     * @param bool $inverse Whether to inverse the mode mapping (default false)
     * @return string|null
     */
    protected function getLocalizedValue($key, $default = null, $replace = null, $inverse = false)
    {
        $mode = $this->getMode($this->getRecord());
        $singleOn = $inverse ? "demoted" : "promoted";
        $mode = $mode == $singleOn ? "single" : "demote";

        return __("filament-actions.change-role.$mode.$key", $replace) ?? $default;
    }

    /**
     * Get the default icon name for the action based on the role change mode.
     *
     * @return string
     */
    protected function getDefaultIcon(): string
    {
        return $this->getMode($this->getRecord()) === 'promoted'
            ? 'heroicon-m-arrow-up-circle'
            : 'heroicon-m-arrow-down-circle';
    }

    /**
     * Determine if the user is being promoted or demoted.
     *
     * @param \App\Models\User|Model $user User model or compatible Model
     * @return string 'promoted' or 'demoted'
     */
    protected function getMode($user): string
    {
        return User::diffRoles($user->role_name, $this->getNextRole()) < 0 ? "promoted" : "demoted";
    }

    /**
     * Get the next role for the user.
     *
     * @return string|null Next role as string or null if undefined
     */
    protected function getNextRole(): ?string
    {
        // Logic to determine the next role based on the current role or other criteria
        return 'user'; // Placeholder, should return the next role as a string
    }

    /**
     * Get the title for the current record.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $record Optional record model; defaults to current
     * @return string
     */
    public function getRecordTitle(?Model $record = null): string
    {
        $record ??= $this->getRecord();

        return data_get(__('users.roles'), $this->getNextRole());
    }
}
