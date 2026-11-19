<?php

namespace Tests\Feature;

use App\Models\Menu;
use Tests\TestCase;

class MenuImageTest extends TestCase
{
    public function test_every_seeded_package_has_a_distinct_valid_webp_image(): void
    {
        $images = config('menu-images');
        $this->assertCount(8, $images);
        $this->assertCount(8, array_unique($images));

        foreach ($images as $name => $image) {
            $menu = Menu::factory()->make(['category_id' => 1, 'name' => $name, 'image' => null]);
            $this->assertSame(asset($image), $menu->imageUrl());
            $this->assertFileExists(public_path($image));
            $this->assertSame('image/webp', getimagesize(public_path($image))['mime']);
        }
    }

    public function test_admin_uploaded_image_takes_priority_over_default_image(): void
    {
        $menu = Menu::factory()->make(['category_id' => 1, 'name' => 'Nasi Kotak Ayam Bakar', 'image' => 'menus/upload.jpg']);
        $menu->id = 123;
        $this->assertSame(route('menu.image', $menu), $menu->imageUrl());
    }

    public function test_new_package_without_image_uses_generic_fallback(): void
    {
        $menu = Menu::factory()->make(['category_id' => 1, 'name' => 'Paket Baru', 'image' => null]);
        $this->assertSame(asset('images/meal.svg'), $menu->imageUrl());
    }
}
