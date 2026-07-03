<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Business;
use App\Entity\BusinessType;
use App\Entity\Category;
use App\Entity\Consumer;
use App\Entity\Order;
use App\Entity\Package;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Business Types ---
        $restaurantType = new BusinessType();
        $restaurantType->setName('Restaurant');
        $manager->persist($restaurantType);

        $bakeryType = new BusinessType();
        $bakeryType->setName('Bakery');
        $manager->persist($bakeryType);

        $cafeType = new BusinessType();
        $cafeType->setName('Cafe');
        $manager->persist($cafeType);

        $groceryType = new BusinessType();
        $groceryType->setName('Grocery Store');
        $manager->persist($groceryType);

        // --- Categories ---
        $mealsCategory = new Category();
        $mealsCategory->setName('Meals');
        $manager->persist($mealsCategory);

        $bakedGoodsCategory = new Category();
        $bakedGoodsCategory->setName('Baked Goods');
        $manager->persist($bakedGoodsCategory);

        $dessertsCategory = new Category();
        $dessertsCategory->setName('Desserts');
        $manager->persist($dessertsCategory);

        $groceriesCategory = new Category();
        $groceriesCategory->setName('Groceries');
        $manager->persist($groceriesCategory);

        // --- Businesses ---
        $business1 = new Business();
        $business1->setName('Green Bistro');
        $business1->setCity('Craiova');
        $business1->setStreet('Calea Bucuresti');
        $business1->setHouseNumber('12A');
        $business1->setPhoneNumber('0740123456');
        $business1->setBusinessType($restaurantType);
        $manager->persist($business1);

        $business2 = new Business();
        $business2->setName('Sunrise Bakery');
        $business2->setCity('Craiova');
        $business2->setStreet('Str. Unirii');
        $business2->setHouseNumber('5');
        $business2->setPhoneNumber('0741987654');
        $business2->setBusinessType($bakeryType);
        $manager->persist($business2);

        $business3 = new Business();
        $business3->setName('Coffee Corner');
        $business3->setCity('Craiova');
        $business3->setStreet('Str. Traian');
        $business3->setHouseNumber('22');
        $business3->setPhoneNumber('0745112233');
        $business3->setBusinessType($cafeType);
        $manager->persist($business3);

        $business4 = new Business();
        $business4->setName('Fresh Market');
        $business4->setCity('Craiova');
        $business4->setStreet('Bd. Decebal');
        $business4->setHouseNumber('101');
        $business4->setPhoneNumber('0746998877');
        $business4->setBusinessType($groceryType);
        $manager->persist($business4);

        $business5 = new Business();
        $business5->setName('La Mama Trattoria');
        $business5->setCity('Craiova');
        $business5->setStreet('Str. Brestei');
        $business5->setHouseNumber('9');
        $business5->setPhoneNumber('0747556644');
        $business5->setBusinessType($restaurantType);
        $manager->persist($business5);

        // --- Consumers ---
        $consumer1 = new Consumer();
        $consumer1->setFirstName('Andrei');
        $consumer1->setLastName('Popescu');
        $consumer1->setPhoneNumber('0722111222');
        $manager->persist($consumer1);

        $consumer2 = new Consumer();
        $consumer2->setFirstName('Maria');
        $consumer2->setLastName('Ionescu');
        $consumer2->setPhoneNumber('0733444555');
        $manager->persist($consumer2);

        $consumer3 = new Consumer();
        $consumer3->setFirstName('Ioana');
        $consumer3->setLastName('Dumitrescu');
        $consumer3->setPhoneNumber('0744667788');
        $manager->persist($consumer3);

        $consumer4 = new Consumer();
        $consumer4->setFirstName('Radu');
        $consumer4->setLastName('Stanescu');
        $consumer4->setPhoneNumber('0755223344');
        $manager->persist($consumer4);

        $consumer5 = new Consumer();
        $consumer5->setFirstName('Elena');
        $consumer5->setLastName('Vasilescu');
        $consumer5->setPhoneNumber('0766778899');
        $manager->persist($consumer5);

        $consumer6 = new Consumer();
        $consumer6->setFirstName('Cristian');
        $consumer6->setLastName('Marin');
        $consumer6->setPhoneNumber('0777889900');
        $manager->persist($consumer6);

        // --- Packages ---
        $packagesData = [
            ['name' => 'Surprise Lunch Box', 'description' => 'Leftover lunch dishes at a discounted price.', 'price' => 19.99, 'category' => $mealsCategory, 'business' => $business1],
            ['name' => "Chef's Mystery Box", 'description' => "A mix of today's unsold specials.", 'price' => 24.00, 'category' => $mealsCategory, 'business' => $business1],
            ['name' => 'Evening Dinner Bag', 'description' => 'End-of-day dinner portions, chef selection.', 'price' => 22.50, 'category' => $mealsCategory, 'business' => $business1],
            ['name' => 'Pasta Special Box', 'description' => 'Unsold pasta dishes from lunch service.', 'price' => 18.00, 'category' => $mealsCategory, 'business' => $business1],
            ['name' => 'End of Day Pastries', 'description' => "Assorted pastries from today's batch.", 'price' => 12.50, 'category' => $bakedGoodsCategory, 'business' => $business2],
            ['name' => 'Bread Basket Deal', 'description' => 'Unsold artisan bread from the day.', 'price' => 9.99, 'category' => $bakedGoodsCategory, 'business' => $business2],
            ['name' => 'Sweet Treats Box', 'description' => 'Mixed cookies, croissants and muffins.', 'price' => 14.00, 'category' => $dessertsCategory, 'business' => $business2],
            ['name' => 'Cake Slice Bundle', 'description' => 'Leftover cake slices, various flavors.', 'price' => 11.00, 'category' => $dessertsCategory, 'business' => $business2],
            ['name' => 'Coffee & Snack Box', 'description' => 'Pastry and coffee combo, end of day.', 'price' => 8.50, 'category' => $bakedGoodsCategory, 'business' => $business3],
            ['name' => 'Sandwich Surprise', 'description' => "Today's unsold sandwiches, chef's pick.", 'price' => 10.50, 'category' => $mealsCategory, 'business' => $business3],
            ['name' => 'Muffin Mix Bag', 'description' => 'Assorted muffins from the display case.', 'price' => 7.50, 'category' => $dessertsCategory, 'business' => $business3],
            ['name' => 'Veggie Box Deal', 'description' => 'Assorted vegetables nearing best-by date.', 'price' => 15.00, 'category' => $groceriesCategory, 'business' => $business4],
            ['name' => 'Fruit Rescue Box', 'description' => 'Fresh fruit close to expiry, mixed selection.', 'price' => 13.50, 'category' => $groceriesCategory, 'business' => $business4],
            ['name' => 'Dairy Surprise Bag', 'description' => 'Dairy products nearing sell-by date.', 'price' => 16.00, 'category' => $groceriesCategory, 'business' => $business4],
            ['name' => 'Pantry Essentials Box', 'description' => 'Mixed pantry items close to date.', 'price' => 20.00, 'category' => $groceriesCategory, 'business' => $business4],
            ['name' => 'Trattoria Family Box', 'description' => 'Family-size portions from dinner service.', 'price' => 29.99, 'category' => $mealsCategory, 'business' => $business5],
            ['name' => 'Antipasto Leftover Box', 'description' => 'Assorted antipasti from today.', 'price' => 17.50, 'category' => $mealsCategory, 'business' => $business5],
            ['name' => 'Pizza Rescue Box', 'description' => 'Unsold pizza slices, mixed toppings.', 'price' => 16.50, 'category' => $mealsCategory, 'business' => $business5],
        ];

        $packages = [];
        foreach ($packagesData as $data) {
            $package = new Package();
            $package->setName($data['name']);
            $package->setDescription($data['description']);
            $package->setPrice($data['price']);
            $package->setPhoto((string)null);
            $package->setCreatedAt(new \DateTimeImmutable());
            $package->setCategory($data['category']);
            $package->setBusiness($data['business']);
            $manager->persist($package);
            $packages[] = $package;
        }

        // --- Orders ---
        // package_id is UNIQUE on order, so each package can be used in at most one order.
        $consumers = [$consumer1, $consumer2, $consumer3, $consumer4, $consumer5, $consumer6];

        // Use 16 of the 18 packages for orders, cycling through consumers.
        for ($i = 0; $i < 16; $i++) {
            $order = new Order();
            $order->setCreatedAt(new \DateTimeImmutable(sprintf('-%d hours', $i * 3)));
            $order->setPackage($packages[$i]);
            $order->setConsumer($consumers[$i % count($consumers)]);
            $manager->persist($order);
        }

        $manager->flush();
    }
}
