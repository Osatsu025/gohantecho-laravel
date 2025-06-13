<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected array $new_menu_data;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->new_menu_data = array_merge(
            Menu::factory()->raw(),
            ['input_tags' => '']
        );
    }

    /**
     * @param User $user
     * @return Menu
     */
    protected function createMenu(User $user): Menu
    {
        return Menu::factory()->create(['user_id' => $user->id]);
    }

    /** 
     * @return User
     */
    protected function createOtherUser(): User
    {
        return User::factory()->create();
    }


    public function test_guest_cannot_access_menus_index()
    {
        $response = $this->get(route('menus.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_menus_index()
    {
        $response = $this->actingAs($this->user)
                        ->get(route('menus.index'));
        $response->assertOk();
    }

    public function test_guest_cannot_access_menus_create()
    {
        $response = $this->get(route('menus.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_menus_create()
    {
        $response = $this->actingAs($this->user)
                        ->get(route('menus.create'));
        $response->assertOk();
    }

    public function test_guest_cannot_access_menus_show()
    {
        $menu = $this->createMenu($this->user);
        $response = $this->get(route('menus.show', $menu));
        $response->assertRedirect(route('login'));
    }
    
    public function test_user_can_access_menus_show()
    {
        $menu = $this->createMenu($this->user);
        $response = $this->actingAs($this->user)
                        ->get(route('menus.show', $menu));
        $response->assertOk();
    }

    public function test_user_can_access_other_users_menus_show()
    {
        $other_user = $this->createOtherUser();
        $other_users_menu = $this->createMenu($other_user);
        $response = $this->actingAs($this->user)
                        ->get(route('menus.show', $other_users_menu));
        $response->assertOk();
    }

    public function test_guest_cannot_store_menus()
    {
        $initial_menu_count = Menu::count();
        $response = $this->post(route('menus.store'), $this->new_menu_data);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('menus', $initial_menu_count);
        $this->assertDatabaseMissing('menus', Arr::only($this->new_menu_data, ['title', 'content']));
    }

    public function test_user_can_store_menus()
    {
        $response = $this->actingAs($this->user)
                        ->post(route('menus.store'), $this->new_menu_data);
        $response->assertRedirect(route('menus.index'));
        $response->assertSessionHas('flash_message');

        $expected_data = Arr::only($this->new_menu_data, ['title', 'content', 'public']);
        $expected_data['user_id'] = $this->user->id;
        $this->assertDatabaseHas('menus', $expected_data);
    }

    public function test_guest_cannot_access_menus_edit()
    {
        $menu = $this->createMenu($this->user);
        $response = $this->get(route('menus.edit', $menu));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_menus_edit()
    {
        $menu = $this->createMenu($this->user);
        $response = $this->actingAs($this->user)
                        ->get(route('menus.edit', $menu));
        $response->assertOk();
    }

    public function test_user_cannot_access_other_users_menus_edit()
    {
        $other_user = $this->createOtherUser();
        $other_users_menu = $this->createMenu($other_user);
        $response = $this->actingAs($this->user)
                        ->get(route('menus.edit', $other_users_menu));
        $response->assertRedirect(route('menus.index'));
    }

    public function test_guest_cannot_update_menus()
    {
        $menu = $this->createMenu($this->user);
        $original_menu_data = $menu->only(['title', 'content', 'public']);
        $response = $this->patch(route('menus.update', $menu), $this->new_menu_data);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('menus', $this->new_menu_data);
        $this->assertDatabaseHas('menus', $original_menu_data);
    }

    public function test_user_can_update_menus()
    {
        $menu = $this->createMenu($this->user);
        $original_menu_data = $menu->only(['title', 'content', 'public']);
        $response = $this->actingAs($this->user)
                        ->patch(route('menus.update', $menu), $this->new_menu_data);
        $response->assertRedirect(route('menus.show', $menu));
        $response->assertSessionHas('flash_message');

        $expected_data = Arr::only($this->new_menu_data, ['title', 'content', 'public']);
        $expected_data['id'] = $menu->id;
        $expected_data['user_id'] = $this->user->id;
        $this->assertDatabaseHas('menus', $expected_data);
        $this->assertDatabaseMissing('menus', $original_menu_data);

    }

    public function test_user_cannot_update_other_users_menus()
    {
        $other_user = $this->createOtherUser();
        $other_users_menu = $this->createMenu($other_user);
        $original_other_menu_data = $other_users_menu->only(['title', 'content', 'public', 'user_id']);
        $response = $this->actingAs($this->user)
                        ->patch(route('menus.update', $other_users_menu), $this->new_menu_data);
        $response->assertRedirect(route('menus.index'));
        $this->assertDatabaseMissing('menus', $this->new_menu_data);
        $this->assertDatabaseHas('menus', $original_other_menu_data);
    }

    public function test_guest_cannot_destroy_menus()
    {
        $menu = $this->createMenu($this->user);
        $response = $this->delete(route('menus.destroy', $menu));
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
        ]);
    }

    public function test_user_can_destroy_menus()
    {
        $menu = $this->createMenu($this->user);
        $response = $this->actingAs($this->user)
                        ->delete(route('menus.destroy', $menu));
        $response->assertRedirect(route('menus.index'));
        $response->assertSessionHas('flash_message');
        $this->assertDatabaseMissing('menus', [
            'id' => $menu->id,
        ]);
    }

    public function test_user_cannot_destroy_other_users_menus()
    {
        $other_user = $this->createOtherUser();
        $other_users_menu = $this->createMenu($other_user);
        $response = $this->actingAs($this->user)
                        ->delete(route('menus.destroy', $other_users_menu));
        $response->assertRedirect(route('menus.index'));
        $this->assertDatabaseHas('menus', [
            'id' => $other_users_menu->id,
        ]);
    }
}
