<?php

namespace LaravelEnso\Users\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use LaravelEnso\Core\Notifications\ResetPassword;
use LaravelEnso\Forms\TestTraits\DestroyForm;
use LaravelEnso\Forms\TestTraits\EditForm;
use LaravelEnso\Permissions\Models\Permission;
use LaravelEnso\Roles\Models\Role;
use LaravelEnso\Tables\Traits\Tests\Datatable;
use LaravelEnso\Users\Models\Session;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use Datatable;
    use DestroyForm;
    use EditForm;
    use RefreshDatabase;

    private $permissionGroup = 'administration.users';
    private $testModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed()
            ->actingAs(User::first());

        $this->testModel = User::factory()->make();
    }

    #[Test]
    public function can_view_create_form()
    {
        $this->get(route($this->permissionGroup.'.create', [$this->testModel->person->id], false))
            ->assertStatus(200)
            ->assertJsonStructure(['form']);
    }

    #[Test]
    public function can_store_user()
    {
        Notification::fake();

        $response = $this->post(
            route('administration.users.store', [], false),
            $this->testModel->toArray()
        );

        $user = App::make(User::class)->whereEmail($this->testModel->email)
            ->first();

        $response->assertStatus(200)
            ->assertJsonStructure(['message'])
            ->assertJsonFragment([
                'redirect' => 'administration.users.edit',
                'param' => ['user' => $user->id],
            ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    #[Test]
    public function can_update_user()
    {
        $this->testModel->save();

        $this->testModel->is_active = ! $this->testModel->is_active;

        $this->patch(
            route('administration.users.update', $this->testModel->id, false),
            $this->testModel->toArray()
        )->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertEquals($this->testModel->is_active, $this->testModel->fresh()->is_active);
    }

    #[Test]
    public function get_option_list()
    {
        $this->testModel->is_active = true;
        $this->testModel->save();

        $this->get(route('administration.users.options', [
            'query' => $this->testModel->person->name,
            'limit' => 10,
        ], false))
            ->assertStatus(200)
            ->assertJsonFragment(['name' => $this->testModel->person->name]);
    }

    #[Test]
    public function can_show_user_profile()
    {
        $this->testModel->save();

        $this->get(route('administration.users.show', $this->testModel->id, false))
            ->assertStatus(200)
            ->assertJsonPath('user.id', $this->testModel->id)
            ->assertJsonPath('user.person.id', $this->testModel->person_id);
    }

    #[Test]
    public function can_access_uses_cached_permissions_without_loading_role(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $permission = Permission::factory()->create([
            'name' => 'testing.users.cached-access',
        ]);
        $user = User::factory()->create();

        $user->role->permissions()->sync([$permission->id]);
        Role::permissionList($user->role_id);

        $freshUser = User::query()->findOrFail($user->id);

        DB::flushQueryLog();
        DB::enableQueryLog();

        $this->assertTrue($freshUser->canAccess($permission->name));
        $this->assertSame([], DB::getQueryLog());

        DB::disableQueryLog();
    }

    #[Test]
    public function can_reset_password_for_a_user()
    {
        Notification::fake();
        $this->testModel->save();

        $this->post(route('administration.users.resetPassword', $this->testModel->id, false))
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'We have e-mailed password reset link!']);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $this->testModel->email,
        ]);
    }

    #[Test]
    public function can_create_list_and_destroy_api_tokens()
    {
        $this->testModel->save();

        $this->get(route('administration.users.tokens.create', $this->testModel->id, false))
            ->assertStatus(200)
            ->assertJsonStructure(['form']);

        $response = $this->post(route('administration.users.tokens.store', $this->testModel->id, false), [
            'name' => 'Integration token',
        ])->assertStatus(200)
            ->assertJsonStructure(['message', 'token']);

        $tokenId = $this->testModel->tokens()->firstOrFail()->id;

        $this->get(route('administration.users.tokens.index', $this->testModel->id, false))
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $tokenId,
                'name' => 'Integration token',
            ]);

        $this->delete(route('administration.users.tokens.destroy', $this->testModel->id, false), [
            'id' => $tokenId,
        ])->assertStatus(200)
            ->assertJsonFragment(['message' => 'The token was deleted successfully']);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId,
        ]);
    }

    #[Test]
    public function can_list_and_destroy_user_sessions()
    {
        $this->testModel->save();

        Session::query()->insert([
            'id' => Str::random(40),
            'user_id' => $this->testModel->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'payload' => 'payload',
            'last_activity' => now()->timestamp,
        ]);

        $session = Session::query()->firstOrFail();

        $this->get(route('administration.users.sessions.index', $this->testModel->id, false))
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $session->id]);

        $this->delete(route('administration.users.sessions.destroy', $this->testModel->id, false), [
            'id' => $session->id,
        ])->assertStatus(200)
            ->assertJsonFragment(['message' => 'The session was deleted successfully']);

        $this->assertDatabaseMissing('sessions', [
            'id' => $session->id,
        ]);
    }
}
