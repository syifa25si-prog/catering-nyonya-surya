<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CateringTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'username' => 'admin']);
    }

    private function checkoutData(): array
    {
        return ['customer_name' => 'Syifa', 'phone' => '081234567890', 'address' => 'Jalan Melati Nomor 10, Pekanbaru', 'event_date' => now()->addDays(3)->toDateString(), 'notes' => 'Tanpa sambal', 'checkout_token' => 'checkout-test'];
    }

    public function test_guests_must_login_to_access_features(): void
    {
        foreach (['/', '/katalog', '/tentang', '/keranjang', '/checkout', '/pesanan', '/admin', '/admin/menus'] as $path) {
            $this->get($path)->assertRedirect('/login');
        } $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }

    public function test_registration_always_creates_customer(): void
    {
        $this->post('/register', ['name' => 'Syifa', 'email' => 'syifa@example.com', 'password' => 'Password123!', 'password_confirmation' => 'Password123!', 'role' => 'admin'])->assertRedirect('/');
        $this->assertAuthenticated();
        $user = User::first();
        $this->assertSame('customer', $user->role);
        $this->assertTrue(password_verify('Password123!', $user->password));
    }

    public function test_registration_validates_confirmation_and_unique_email(): void
    {
        $user = User::factory()->create();
        $this->post('/register', ['name' => 'Syifa', 'email' => $user->email, 'password' => '12345678', 'password_confirmation' => 'wrong'])->assertSessionHasErrors(['email', 'password']);
    }

    public function test_admin_can_login_with_username_and_logout(): void
    {
        $admin = $this->admin();
        $this->post('/login', ['login' => 'admin', 'password' => 'password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_invalid_password_is_rejected(): void
    {
        $user = User::factory()->create();
        $this->post('/login', ['login' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_customers_cannot_access_admin_routes(): void
    {
        $this->actingAs(User::factory()->create());
        $category = Category::factory()->create();
        $menu = Menu::factory()->create();
        foreach (['/admin', '/admin/categories', '/admin/menus', '/admin/orders'] as $path) {
            $this->get($path)->assertForbidden();
        }
        $this->post('/admin/categories', ['name' => 'Illegal'])->assertForbidden();
        $this->delete('/admin/menus/'.$menu->id)->assertForbidden();
        $this->delete('/admin/categories/'.$category->id)->assertForbidden();
    }

    public function test_catalog_search_category_and_availability(): void
    {
        $this->actingAs(User::factory()->create());
        $menu = Menu::factory()->create(['name' => 'Ayam Istimewa']);
        $other = Menu::factory()->create(['name' => 'Rendang']);
        $hidden = Menu::factory()->create(['name' => 'Paket Rahasia', 'is_available' => false]);
        $this->get('/katalog?q=Ayam&category='.$menu->category_id)->assertOk()->assertSee('Ayam Istimewa')->assertDontSee('Rendang')->assertDontSee('Paket Rahasia');
        $this->get('/paket/'.$hidden->id)->assertNotFound();
        $this->get('/paket/'.$menu->id)->assertOk();
    }

    public function test_customer_pages_render(): void
    {
        $this->actingAs(User::factory()->create());
        Menu::factory()->create();
        foreach (['/', '/katalog', '/tentang', '/keranjang', '/pesanan'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_cart_add_update_remove_and_validation(): void
    {
        $this->actingAs(User::factory()->create());
        $menu = Menu::factory()->create();
        $this->post('/keranjang/'.$menu->id, ['quantity' => 2])->assertSessionHas('cart.'.$menu->id, 2);
        $this->patch('/keranjang/'.$menu->id, ['quantity' => 4])->assertSessionHas('cart.'.$menu->id, 4);
        $this->patch('/keranjang/'.$menu->id, ['quantity' => 0])->assertSessionHasErrors('quantity');
        $this->get('/keranjang')->assertOk()->assertSee('140.000');
        $this->delete('/keranjang/'.$menu->id)->assertSessionHas('cart', []);
    }

    public function test_checkout_saves_multiple_packages_and_ignores_client_price(): void
    {
        $user = User::factory()->create();
        $menus = Menu::factory()->count(2)->create(['price' => 35000]);
        $cart = [$menus[0]->id => 2, $menus[1]->id => 3];
        $this->actingAs($user)->withSession(['cart' => $cart, 'checkout_token' => 'checkout-test']);
        $this->get('/checkout')->assertOk();
        $response = $this->post('/checkout', $this->checkoutData() + ['total' => 1, 'user_id' => 999, 'status' => 'Selesai']);
        $order = Order::firstOrFail();
        $response->assertRedirect(route('orders.show', $order))->assertSessionMissing('cart');
        $this->assertSame(175000, $order->total);
        $this->assertSame($user->id, $order->user_id);
        $this->assertSame('Menunggu', $order->status);
        $this->assertCount(2, $order->items);
        $this->assertSame(175000, (int) $order->items->sum('subtotal'));
        $this->post('/checkout', $this->checkoutData())->assertRedirect('/keranjang');
        $this->assertDatabaseCount('orders', 1);
    }

    public function test_empty_unavailable_and_deleted_cart_cannot_checkout(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get('/checkout')->assertRedirect('/keranjang');
        $menu = Menu::factory()->create(['is_available' => false]);
        $this->withSession(['cart' => [$menu->id => 2], 'checkout_token' => 'checkout-test'])->post('/checkout', $this->checkoutData())->assertSessionHasErrors('cart');
        $this->assertDatabaseCount('orders', 0);
        $menu->delete();
        $this->post('/checkout', $this->checkoutData())->assertSessionHasErrors('cart');
    }

    public function test_checkout_validates_date_phone_and_required_address(): void
    {
        $this->actingAs(User::factory()->create());
        $menu = Menu::factory()->create();
        $this->withSession(['cart' => [$menu->id => 1], 'checkout_token' => 'checkout-test'])->post('/checkout', array_replace($this->checkoutData(), ['event_date' => now()->subDay()->toDateString(), 'phone' => 'invalid', 'address' => '']))->assertSessionHasErrors(['event_date', 'phone', 'address']);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_can_only_view_own_orders(): void
    {
        $user = User::factory()->create();
        $own = Order::factory()->for($user)->create();
        $other = Order::factory()->create();
        $this->actingAs($user)->get('/pesanan')->assertOk()->assertSee($own->number)->assertDontSee($other->number);
        $this->get('/pesanan/'.$other->id)->assertForbidden();
        $this->get('/pesanan/'.$own->id)->assertOk();
    }

    public function test_admin_category_crud_and_referenced_category_protection(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/categories', ['name' => 'Snack'])->assertRedirect('/admin/categories');
        $category = Category::where('name', 'Snack')->firstOrFail();
        $this->put('/admin/categories/'.$category->id, ['name' => 'Snack Box'])->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Snack Box']);
        $menu = Menu::factory()->for($category)->create();
        $this->delete('/admin/categories/'.$category->id)->assertSessionHasErrors('category');
        $menu->delete();
        $this->delete('/admin/categories/'.$category->id)->assertRedirect();
        $this->assertModelMissing($category);
    }

    public function test_admin_menu_crud_image_and_order_snapshot(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        $category = Category::factory()->create();
        $data = ['name' => 'Ayam Bakar', 'description' => 'Nasi dan ayam bakar', 'price' => 35000, 'category_id' => $category->id, 'is_available' => 1, 'image' => UploadedFile::fake()->image('menu.jpg')];
        $this->post('/admin/menus', $data)->assertRedirect('/admin/menus');
        $menu = Menu::firstOrFail();
        Storage::disk('public')->assertExists($menu->image);
        $path = $menu->image;
        $item = OrderItem::factory()->create(['menu_id' => $menu->id, 'menu_name' => $menu->name]);
        unset($data['image']);
        $data['price'] = 40000;
        $this->put('/admin/menus/'.$menu->id, $data)->assertRedirect('/admin/menus');
        $this->delete('/admin/menus/'.$menu->id)->assertRedirect();
        Storage::disk('public')->assertMissing($path);
        $this->assertNull($item->fresh()->menu_id);
        $this->assertSame('Ayam Bakar', $item->fresh()->menu_name);
    }

    public function test_admin_validates_forms_and_status(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/categories', [])->assertSessionHasErrors('name');
        $this->post('/admin/menus', [])->assertSessionHasErrors(['name', 'category_id', 'price', 'description']);
        $order = Order::factory()->create();
        $this->patch('/admin/orders/'.$order->id, ['status' => 'Paid'])->assertSessionHasErrors('status');
        foreach (Order::STATUSES as $status) {
            $this->patch('/admin/orders/'.$order->id, ['status' => $status])->assertRedirect();
            $this->assertSame($status, $order->fresh()->status);
        }
    }

    public function test_admin_pages_render(): void
    {
        $this->actingAs($this->admin());
        $menu = Menu::factory()->create();
        $order = Order::factory()->create();
        foreach (['/admin', '/admin/categories', '/admin/categories/create', '/admin/categories/'.$menu->category_id.'/edit', '/admin/menus', '/admin/menus/create', '/admin/menus/'.$menu->id.'/edit', '/admin/orders', '/admin/orders/'.$order->id] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_deleted_menu_can_be_removed_from_cart(): void
    {
        $this->actingAs(User::factory()->create());
        $menu = Menu::factory()->create();
        $this->withSession(['cart' => [$menu->id => 2]]);
        $menu->delete();
        $this->get('/keranjang')->assertOk()->assertSee('telah dihapus dari katalog');
        $this->delete('/keranjang/'.$menu->id)->assertSessionHas('cart', []);
    }

    public function test_admin_cannot_upload_executable_files_as_menu_images(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        $category = Category::factory()->create();
        $this->post('/admin/menus', [
            'category_id' => $category->id,
            'name' => 'Paket contoh',
            'description' => 'Deskripsi paket',
            'price' => 35000,
            'is_available' => 1,
            'image' => UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
        ])->assertSessionHasErrors('image');
        $this->assertDatabaseCount('menus', 0);
    }
}
