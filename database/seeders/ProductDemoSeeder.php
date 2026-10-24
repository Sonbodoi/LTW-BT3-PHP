<?php
namespace Database\Seeders;

use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Database\Seeder;

class ProductDemoSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'tra-sua', 'name' => 'Trà sữa', 'description' => 'Các loại trà sữa thơm béo, topping hấp dẫn.', 'sort_order' => 1],
            ['slug' => 'tra-trai-cay', 'name' => 'Trà trái cây', 'description' => 'Trà tươi mát kết hợp trái cây.', 'sort_order' => 2],
            ['slug' => 'ca-phe', 'name' => 'Cà phê', 'description' => 'Cà phê rang xay và các món kết hợp sữa.', 'sort_order' => 3],
            ['slug' => 'da-xay', 'name' => 'Đá xay', 'description' => 'Món đá xay mát lạnh, vị kem.', 'sort_order' => 4],
            ['slug' => 'banh-ngot', 'name' => 'Bánh ngọt', 'description' => 'Bánh dùng kèm trà và cà phê.', 'sort_order' => 5],
        ];
        foreach ($categories as $category) {
            FoodCategory::updateOrCreate(['slug' => $category['slug']], [
                'name' => $category['name'], 'description' => $category['description'],
                'sort_order' => $category['sort_order'], 'is_active' => true,
            ]);
        }

        $products = [
            ['tra-sua', 'tra-sua-tran-chau-hoang-gia', 'Trà sữa trân châu hoàng gia', 45000, 50, 'Trà đen thơm, sữa béo, trân châu mềm dai.', 'images/menu/tra-sua-tran-chau-hoang-gia.svg'],
            ['tra-sua', 'tra-sua-matcha', 'Trà sữa matcha', 49000, 36, 'Matcha thanh dịu quyện cùng sữa tươi.', 'images/menu/tra-sua-matcha.svg'],
            ['tra-sua', 'tra-sua-khoai-mon', 'Trà sữa khoai môn', 47000, 42, 'Khoai môn bùi thơm và lớp sữa mịn.', 'images/menu/tra-sua-khoai-mon.svg'],
            ['tra-sua', 'tra-sua-duong-den', 'Trà sữa đường đen', 49000, 32, 'Đường đen sóng sánh cùng trân châu.', 'images/menu/tra-sua-duong-den.svg'],
            ['tra-sua', 'tra-sua-oolong', 'Trà sữa ô long', 46000, 48, 'Ô long rang thơm và hậu vị nhẹ.', 'images/menu/tra-sua-oolong.svg'],
            ['tra-sua', 'tra-sua-dau', 'Trà sữa dâu', 48000, 28, 'Dâu chua ngọt kết hợp trà sữa.', 'images/menu/tra-sua-dau.svg'],
            ['tra-sua', 'tra-sua-socola', 'Trà sữa socola', 51000, 26, 'Hương cacao đậm đà, ít ngọt.', 'images/menu/tra-sua-socola.svg'],
            ['tra-sua', 'tra-sua-hoa-nhai', 'Trà sữa hoa nhài', 45000, 45, 'Trà nhài dịu nhẹ, sữa thơm mát.', 'images/menu/tra-sua-hoa-nhai.svg'],
            ['tra-trai-cay', 'tra-dao-cam-sa', 'Trà đào cam sả', 47000, 52, 'Đào, cam vàng và hương sả thanh mát.', 'images/menu/tra-dao-cam-sa.svg'],
            ['tra-trai-cay', 'tra-vai', 'Trà vải', 45000, 37, 'Trà hoa nhài kết hợp vải tươi.', 'images/menu/tra-vai.svg'],
            ['tra-trai-cay', 'tra-chanh-day', 'Trà chanh dây', 43000, 55, 'Vị chanh dây chua ngọt tươi tỉnh.', 'images/menu/tra-chanh-day.svg'],
            ['tra-trai-cay', 'tra-dau-tay', 'Trà dâu tây', 47000, 40, 'Dâu tây mọng nước và trà thanh.', 'images/menu/tra-dau-tay.svg'],
            ['tra-trai-cay', 'tra-buoi-hong', 'Trà bưởi hồng', 49000, 25, 'Bưởi hồng thanh, ít ngọt.', 'images/menu/tra-buoi-hong.svg'],
            ['tra-trai-cay', 'tra-xoai-nhiet-doi', 'Trà xoài nhiệt đới', 48000, 31, 'Xoài chín và nền trà nhẹ nhàng.', 'images/menu/tra-xoai-nhiet-doi.svg'],
            ['ca-phe', 'ca-phe-den-da', 'Cà phê đen đá', 30000, 60, 'Cà phê đậm vị, hậu thơm dài.', 'images/menu/ca-phe-den-da.svg'],
            ['ca-phe', 'ca-phe-sua-da', 'Cà phê sữa đá', 35000, 58, 'Cà phê Việt cùng sữa đặc.', 'images/menu/ca-phe-sua-da.svg'],
            ['ca-phe', 'bac-xiu', 'Bạc xỉu', 39000, 46, 'Sữa nhiều, cà phê vừa đủ thơm.', 'images/menu/bac-xiu.svg'],
            ['ca-phe', 'latte-da', 'Latte đá', 45000, 34, 'Espresso và sữa tươi cân bằng.', 'images/menu/latte-da.svg'],
            ['ca-phe', 'mocha-da', 'Mocha đá', 49000, 24, 'Espresso và chocolate dịu ngọt.', 'images/menu/mocha-da.svg'],
            ['ca-phe', 'ca-phe-caramel', 'Cà phê caramel', 49000, 30, 'Caramel thơm cùng cà phê rang.', 'images/menu/ca-phe-caramel.svg'],
            ['da-xay', 'matcha-da-xay', 'Matcha đá xay', 56000, 28, 'Matcha đá xay mềm mịn, thơm ngậy.', 'images/menu/matcha-da-xay.svg'],
            ['da-xay', 'socola-da-xay', 'Socola đá xay', 55000, 32, 'Chocolate đậm vị, lớp kem nhẹ.', 'images/menu/socola-da-xay.svg'],
            ['da-xay', 'dau-da-xay', 'Dâu đá xay', 53000, 26, 'Dâu tươi thơm mát, màu hồng dịu.', 'images/menu/dau-da-xay.svg'],
            ['da-xay', 'cookie-da-xay', 'Cookie đá xay', 59000, 19, 'Bánh quy cacao xay cùng sữa.', 'images/menu/cookie-da-xay.svg'],
            ['da-xay', 'xoai-da-xay', 'Xoài đá xay', 55000, 23, 'Xoài chín thơm và đá xay mịn.', 'images/menu/xoai-da-xay.svg'],
            ['banh-ngot', 'banh-tiramisu', 'Bánh tiramisu', 59000, 18, 'Kem mascarpone và cacao mềm xốp.', 'images/menu/banh-tiramisu.svg'],
            ['banh-ngot', 'banh-cheesecake-dau', 'Cheesecake dâu', 62000, 22, 'Cheesecake béo mịn và dâu chua nhẹ.', 'images/menu/banh-cheesecake-dau.svg'],
            ['banh-ngot', 'banh-croissant-bo', 'Croissant bơ', 39000, 40, 'Vỏ bánh nhiều lớp vàng giòn.', 'images/menu/banh-croissant-bo.svg'],
            ['banh-ngot', 'banh-mousse-socola', 'Mousse socola', 58000, 21, 'Mousse chocolate mịn như nhung.', 'images/menu/banh-mousse-socola.svg'],
            ['banh-ngot', 'banh-bong-lan-trung-muoi', 'Bông lan trứng muối', 52000, 24, 'Bông lan mềm, nhân trứng muối béo bùi.', 'images/menu/banh-bong-lan-trung-muoi.svg'],
        ];
        foreach ($products as $index => [$categorySlug, $slug, $name, $price, $stock, $description, $image]) {
            $category = FoodCategory::where('slug', $categorySlug)->firstOrFail();
            $product = Food::firstOrCreate(['slug' => $slug], [
                'category_id' => $category->id, 'name' => $name,
                'short_description' => $description,
                'description' => $description.' Nguyên liệu chọn lọc, phù hợp thưởng thức mỗi ngày.',
                'price' => $price, 'stock' => $stock,
                'is_available' => true, 'is_featured' => $index < 6,
                'image_path' => $image,
            ]);
            if (! $product->image_path) {
                $product->update(['image_path' => $image]);
            }
        }
    }
}