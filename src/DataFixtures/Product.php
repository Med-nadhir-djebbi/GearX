<?php

namespace App\DataFixtures;

use App\Entity\Product as ProductEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class Product extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            // Gaming Consoles
            [
                'name' => 'PlayStation 5 Pro',
                'description' => 'The latest PlayStation console featuring 8K graphics, ray tracing, ultra-high speed SSD, and adaptive triggers for immersive gaming experiences.',
                'price' => 599.99,
                'rating' => 4.9,
                'stock' => 25,
                'category' => 'category_Consoles',
                'image' => 'playstation.jpg'
            ],
            [
                'name' => 'Xbox Series X',
                'description' => 'Microsoft\'s flagship gaming console with 4K gaming at up to 120 FPS, 1TB SSD, and backward compatibility with thousands of Xbox games.',
                'price' => 499.99,
                'rating' => 4.8,
                'stock' => 30,
                'category' => 'category_Consoles',
                'image' => 'xbox-series-x.jpg'
            ],
            [
                'name' => 'Nintendo Switch OLED',
                'description' => 'Versatile gaming system with vibrant 7-inch OLED screen, enhanced audio, wired LAN port, and a wide selection of games for on-the-go or home play.',
                'price' => 349.99,
                'rating' => 4.7,
                'stock' => 35,
                'category' => 'category_Consoles',
                'image' => 'nintendo-switch.jpg'
            ],
            // Jeux vidéo
            [
                'name' => 'Cyberpunk 2077',
                'description' => 'Open-world action-adventure RPG set in Night City, a megalopolis obsessed with power, glamour, and body modification.',
                'price' => 59.99,
                'rating' => 4.5,
                'stock' => 100,
                'category' => 'category_Jeux vidéo',
                'image' => 'cyberpunk-game.jpg'
            ],
            [
                'name' => 'Elden Ring',
                'description' => 'An action RPG developed by FromSoftware and written by George R.R. Martin, featuring an expansive fantasy world and challenging combat.',
                'price' => 69.99,
                'rating' => 4.9,
                'stock' => 120,
                'category' => 'category_Jeux vidéo',
                'image' => 'digital-ronin.jpg'
            ],
            [
                'name' => 'Forza Horizon 5',
                'description' => 'Racing game set in an open-world environment based in Mexico, featuring over 500 cars and dynamic weather systems.',
                'price' => 59.99,
                'rating' => 4.7,
                'stock' => 85,
                'category' => 'category_Jeux vidéo',
                'image' => 'chroma-shift.jpg'
            ],
            [
                'name' => 'Assassin\'s Creed Valhalla',
                'description' => 'Action role-playing game where you lead epic Viking raids against Saxon troops and fortresses throughout England.',
                'price' => 49.99,
                'rating' => 4.6,
                'stock' => 90,
                'category' => 'category_Jeux vidéo',
                'image' => 'synth-hack.jpg'
            ],
            // Périphériques - Souris
            [
                'name' => 'Logitech G Pro X Superlight',
                'description' => 'Ultra-lightweight wireless gaming mouse weighing less than 63 grams with HERO 25K sensor and up to 70 hours of battery life.',
                'price' => 149.99,
                'rating' => 4.8,
                'stock' => 45,
                'category' => 'category_Souris',
                'image' => 'glitch-mouse.jpg'
            ],
            [
                'name' => 'Razer DeathAdder V3 Pro',
                'description' => 'Ergonomic esports wireless mouse with Focus Pro 30K optical sensor, 90-hour battery life, and weighing only 64 grams.',
                'price' => 159.99,
                'rating' => 4.7,
                'stock' => 35,
                'category' => 'category_Souris',
                'image' => 'neural-tracker.jpg'
            ],
            // Périphériques - Claviers
            [
                'name' => 'SteelSeries Apex Pro 2',
                'description' => 'Mechanical gaming keyboard with adjustable actuation, OmniPoint 2.0 switches, OLED smart display, and aircraft-grade aluminum alloy frame.',
                'price' => 199.99,
                'rating' => 4.6,
                'stock' => 30,
                'category' => 'category_Claviers',
                'image' => 'hyperglitch-keyboard.jpg'
            ],
            [
                'name' => 'Corsair K100 RGB',
                'description' => 'Premium mechanical gaming keyboard with optical-mechanical switches, customizable per-key RGB, 44-zone LightEdge, and 6 dedicated macro keys.',
                'price' => 229.99,
                'rating' => 4.7,
                'stock' => 25,
                'category' => 'category_Claviers',
                'image' => 'neonwire-keyboard.jpg'
            ],
            // Périphériques - Casques
            [
                'name' => 'SteelSeries Arctis Nova Pro Wireless',
                'description' => 'Premium wireless gaming headset with active noise cancellation, hot-swappable batteries, multi-system connectivity, and high fidelity audio.',
                'price' => 349.99,
                'rating' => 4.8,
                'stock' => 40,
                'category' => 'category_Casques',
                'image' => 'brainwave-headset.jpg'
            ],
            [
                'name' => 'Logitech G Pro X 2 Lightspeed',
                'description' => 'Professional-grade wireless gaming headset with PRO-G graphene drivers, advanced microphone, and DTS Headphone:X 2.0 surround sound.',
                'price' => 249.99,
                'rating' => 4.7,
                'stock' => 35,
                'category' => 'category_Casques',
                'image' => 'synthsound-headset.jpg'
            ],
            // Périphériques - Moniteurs
            [
                'name' => 'Samsung Odyssey G9',
                'description' => '49-inch curved gaming monitor with Dual QHD resolution, 240Hz refresh rate, 1ms response time, and HDR1000 for immersive gameplay.',
                'price' => 1299.99,
                'rating' => 4.8,
                'stock' => 20,
                'category' => 'category_Moniteurs',
                'image' => 'ultravision-monitor.jpg'
            ],
            [
                'name' => 'LG UltraGear 27GP950-B',
                'description' => '27-inch Nano IPS gaming monitor with 4K UHD resolution, 144Hz refresh rate (overclockable to 160Hz), 1ms response time, and NVIDIA G-SYNC compatibility.',
                'price' => 899.99,
                'rating' => 4.7,
                'stock' => 15,
                'category' => 'category_Moniteurs',
                'image' => 'neoreality-monitors.jpg'
            ],
            // Périphériques - Webcams
            [
                'name' => 'Elgato Facecam Pro',
                'description' => 'Professional streaming camera with 4K resolution at 60 FPS, Sony STARVIS CMOS sensor, and adjustable field of view from 49° to 91°.',
                'price' => 299.99,
                'rating' => 4.7,
                'stock' => 40,
                'category' => 'category_Webcams',
                'image' => 'cybereye-camera.jpg'
            ],
            // Composants - Cartes graphiques
            [
                'name' => 'NVIDIA GeForce RTX 4090',
                'description' => 'Flagship graphics card with 24GB GDDR6X memory, 16384 CUDA cores, and advanced ray tracing capabilities for incredible gaming performance.',
                'price' => 1599.99,
                'rating' => 4.9,
                'stock' => 15,
                'category' => 'category_NVIDIA',
                'image' => 'nvidia-gpu.jpg'
            ],
            [
                'name' => 'AMD Radeon RX 7900 XTX',
                'description' => 'High-end graphics card with 24GB GDDR6 memory, AMD RDNA 3 architecture, and hardware-accelerated ray tracing for premium gaming experiences.',
                'price' => 999.99,
                'rating' => 4.7,
                'stock' => 18,
                'category' => 'category_AMD Radeon',
                'image' => 'amd-gpu.jpg'
            ],
            // Composants - Processeurs
            [
                'name' => 'AMD Ryzen 9 7950X3D',
                'description' => '16-core, 32-thread processor with 3D V-Cache technology, 5.7 GHz max boost, and PCIe 5.0 support for extreme gaming and content creation.',
                'price' => 699.99,
                'rating' => 4.8,
                'stock' => 25,
                'category' => 'category_AMD Ryzen',
                'image' => 'amd-ryzen.jpg'
            ],
            [
                'name' => 'Intel Core i9-14900K',
                'description' => 'Flagship gaming processor with 24 cores (8P+16E), 32 threads, up to 6.0 GHz turbo frequency, and overclocking capabilities.',
                'price' => 589.99,
                'rating' => 4.7,
                'stock' => 22,
                'category' => 'category_Intel',
                'image' => 'intel-core.jpg'
            ],
            // Composants - RAM
            [
                'name' => 'Corsair Dominator Platinum RGB DDR5 32GB',
                'description' => 'High-performance DDR5 memory kit (2x16GB) with customizable RGB lighting, CL36 timing, and DHX cooling technology for optimal performance.',
                'price' => 259.99,
                'rating' => 4.8,
                'stock' => 30,
                'category' => 'category_RAM',
                'image' => 'hyperram.jpg'
            ],
            // Composants - Stockage
            [
                'name' => 'Samsung 990 PRO NVMe SSD 2TB',
                'description' => 'High-performance PCIe 4.0 NVMe SSD with sequential read speeds up to 7,450 MB/s and write speeds up to 6,900 MB/s for gaming and content creation.',
                'price' => 249.99,
                'rating' => 4.8,
                'stock' => 25,
                'category' => 'category_SSD',
                'image' => 'quantum-drive.jpg'
            ],
            // Composants - Refroidissements
            [
                'name' => 'NZXT Kraken Z73 RGB',
                'description' => '360mm all-in-one liquid CPU cooler with customizable LCD display, RGB lighting, and three 120mm Aer RGB 2 fans for efficient cooling.',
                'price' => 279.99,
                'rating' => 4.7,
                'stock' => 20,
                'category' => 'category_Liquide',
                'image' => 'cryotech-cooling.jpg'
            ],
            // PC Gaming
            [
                'name' => 'ASUS ROG Strix G35CZ Gaming Desktop',
                'description' => 'Pre-built gaming desktop with Intel Core i9, NVIDIA RTX 4080, 32GB DDR5 RAM, 2TB SSD, and custom RGB lighting for high-performance gaming.',
                'price' => 2999.99,
                'rating' => 4.9,
                'stock' => 10,
                'category' => 'category_PC de bureau',
                'image' => 'neoforge-pc.jpg'
            ],
            [
                'name' => 'MSI Raider GE78 HX Gaming Laptop',
                'description' => 'High-performance gaming laptop with 17-inch QHD+ 240Hz display, Intel Core i9, NVIDIA RTX 4090 Laptop GPU, 64GB RAM, and per-key RGB keyboard.',
                'price' => 3499.99,
                'rating' => 4.8,
                'stock' => 15,
                'category' => 'category_Ordinateurs portables',
                'image' => 'synthwave-laptop.jpg'
            ]
        ];
        
        foreach ($products as $productData) {
            $product = new ProductEntity();
            $product->setName($productData['name']);
            $product->setDescription($productData['description']);
            $product->setPrice($productData['price']);
            $product->setRating($productData['rating']);
            $product->setStock($productData['stock']);
            $product->setImage($productData['image']);
            
            try {
                $category = $this->getReference($productData['category'], \App\Entity\Category::class);
                $product->setCategory($category);
            } catch (\Exception $e) {
                // If category reference not found, use a main category
                try {
                    $mainCategory = $this->getReference('category_Informatique', \App\Entity\Category::class);
                    $product->setCategory($mainCategory);
                } catch (\Exception $e2) {
                    // Skip this product if no category can be found
                    continue;
                }
            }
            
            $manager->persist($product);
        }

        $manager->flush();
    }
    
    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}
