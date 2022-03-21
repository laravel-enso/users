<?php

namespace LaravelEnso\Users\Models;

use Exception;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use LaravelEnso\Companies\Models\Company;
use LaravelEnso\Core\Exceptions\UserConflict;
use LaravelEnso\Core\Models\Login;
use LaravelEnso\Core\Models\Preference;
use LaravelEnso\Core\Services\DefaultPreferences;
use LaravelEnso\Core\Traits\HasPassword;
use LaravelEnso\DynamicMethods\Traits\Abilities;
use LaravelEnso\Files\Models\File;
use LaravelEnso\Helpers\Contracts\Activatable;
use LaravelEnso\Helpers\Traits\ActiveState;
use LaravelEnso\Helpers\Traits\AvoidsDeletionConflicts;
use LaravelEnso\Helpers\Traits\CascadesMorphMap;
use LaravelEnso\Helpers\Traits\CascadesObservers;
use LaravelEnso\People\Models\Person;
use LaravelEnso\People\Traits\IsPerson;
use LaravelEnso\Rememberable\Traits\Rememberable;
use LaravelEnso\Roles\Enums\Roles;
use LaravelEnso\Roles\Models\Role;
use LaravelEnso\Tables\Traits\TableCache;
use LaravelEnso\UserGroups\Enums\UserGroups;
use LaravelEnso\UserGroups\Models\UserGroup;
use stdClass;

class User extends Authenticatable implements Activatable, HasLocalePreference
{
    use ActiveState, AvoidsDeletionConflicts, CascadesMorphMap;
    use CascadesObservers, HasApiTokens, HasFactory, HasPassword, IsPerson;
    use Notifiable, Abilities, Rememberable, TableCache;

    protected $hidden = ['password', 'remember_token', 'password_updated_at'];

    protected $guarded = ['id', 'password'];

    protected $casts = [
        'is_active' => 'boolean', 'person_id' => 'int',
        'group_id'  => 'int', 'role_id' => 'int',
    ];

    protected $dates = ['password_updated_at'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function group()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'created_by');
    }

    public function logins()
    {
        return $this->hasMany(Login::class);
    }

    public function preference()
    {
        return $this->hasOne(Preference::class);
    }

    public function company(): ?Company
    {
        return $this->person->company();
    }

    public function isAdmin(): bool
    {
        return $this->role_id === App::make(Roles::class)::Admin;
    }

    public function isSupervisor(): bool
    {
        return $this->role_id === App::make(Roles::class)::Supervisor;
    }

    public function belongsToAdminGroup(): bool
    {
        return $this->group_id === App::make(UserGroups::class)::Admin;
    }

    public function isPerson(Person $person): bool
    {
        return $this->person_id === $person->id;
    }

    public function preferences(): stdClass
    {
        return Preference::cacheGetBy('user_id', $this->id)->value
            ?? $this->defaultPreferences()->value;
    }

    public function preferredLocale(): string
    {
        return $this->lang();
    }

    public function lang(): string
    {
        return $this->preferences()->global->lang;
    }

    public function scopeAdmins(Builder $builder): Builder
    {
        return $builder->whereRoleId(App::make(Roles::class)::Admin);
    }

    public function scopeSupervisors(Builder $builder): Builder
    {
        return $builder->whereRoleId(App::make(Roles::class)::Supervisor);
    }

    public function storeGlobalPreferences($global): void
    {
        $preferences = $this->preferences();
        $preferences->global = $global;

        $this->storePreferences($preferences);
    }

    public function storeLocalPreferences($route, $value): void
    {
        $preferences = $this->preferences();
        $preferences->local->$route = $value;

        $this->storePreferences($preferences);
    }

    public function erase(bool $person = false)
    {
        if ($person) {
            return DB::transaction(fn () => tap($this)->delete()->person->delete());
        }

        return $this->delete();
    }

    public function delete()
    {
        if ($this->logins()->exists()) {
            throw UserConflict::hasActivity();
        }

        try {
            return parent::delete();
        } catch (Exception) {
            throw UserConflict::hasActivity();
        }
    }

    public function resetPreferences(): void
    {
        $this->storePreferences($this->defaultPreferences()->value);
    }

    public function storePreferences($preferences): void
    {
        $this->preference()->updateOrCreate(
            ['user_id' => $this->id],
            ['value' => $preferences]
        );
    }

    protected function defaultPreferences(): Preference
    {
        return new Preference([
            'value' => DefaultPreferences::data(),
        ]);
    }
}
