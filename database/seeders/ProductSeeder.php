<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // 2 Step Cakes (2 Kgs)
            ['category' => '2 Step Cakes (2 Kgs)', 'product_name' => 'Buttersctoch 2step', 'item_code' => 'buttersctoch 2step', 'selling_price' => 1500],
            ['category' => '2 Step Cakes (2 Kgs)', 'product_name' => 'Pineapple Cake 2step', 'item_code' => '[O]31', 'selling_price' => 1500],

            // Basic Pastries
            ['category' => 'Basic Pastries', 'product_name' => 'Butterscotch Pastry', 'item_code' => '258', 'selling_price' => 79],
            ['category' => 'Basic Pastries', 'product_name' => 'Choco Lava', 'item_code' => '4009', 'selling_price' => 60],
            ['category' => 'Basic Pastries', 'product_name' => 'Panda Pastry', 'item_code' => '273', 'selling_price' => 99],
            ['category' => 'Basic Pastries', 'product_name' => 'Pineapple Pastry', 'item_code' => '255', 'selling_price' => 79],
            ['category' => 'Basic Pastries', 'product_name' => 'Vanilla Pastry', 'item_code' => '256', 'selling_price' => 79],

            // Birthday Items
            ['category' => 'Birthday Items', 'product_name' => 'Birthday Cap 55rs', 'item_code' => '3076', 'selling_price' => 55],
            ['category' => 'Birthday Items', 'product_name' => 'Cake Topper', 'item_code' => 'cake topper', 'selling_price' => 99],
            ['category' => 'Birthday Items', 'product_name' => 'Color Smoke', 'item_code' => '3030', 'selling_price' => 50],
            ['category' => 'Birthday Items', 'product_name' => 'Flower Candle', 'item_code' => '3037', 'selling_price' => 50],
            ['category' => 'Birthday Items', 'product_name' => 'Foil Fringe Curtain', 'item_code' => '3079', 'selling_price' => 80],
            ['category' => 'Birthday Items', 'product_name' => 'Glitter Hbd Banner', 'item_code' => '3144', 'selling_price' => 199],
            ['category' => 'Birthday Items', 'product_name' => 'Gold Candles', 'item_code' => '3027', 'selling_price' => 50],
            ['category' => 'Birthday Items', 'product_name' => 'Happy Birthday Foil', 'item_code' => '3050', 'selling_price' => 150],
            ['category' => 'Birthday Items', 'product_name' => 'Hbd Full Set (Small)', 'item_code' => '3088', 'selling_price' => 300],
            ['category' => 'Birthday Items', 'product_name' => 'Heart Shape Balloons', 'item_code' => '3047', 'selling_price' => 69],
            ['category' => 'Birthday Items', 'product_name' => 'Magic Candle', 'item_code' => '3041', 'selling_price' => 25],
            ['category' => 'Birthday Items', 'product_name' => 'Multi Color Balloons', 'item_code' => '3046', 'selling_price' => 80],
            ['category' => 'Birthday Items', 'product_name' => 'Ola Birthday Caps', 'item_code' => '3142', 'selling_price' => 25],
            ['category' => 'Birthday Items', 'product_name' => 'Party Popp(m)', 'item_code' => '3039', 'selling_price' => 90],
            ['category' => 'Birthday Items', 'product_name' => 'Ribbon Spray', 'item_code' => '3038', 'selling_price' => 70],
            ['category' => 'Birthday Items', 'product_name' => 'Rotating Candle', 'item_code' => '3036', 'selling_price' => 70],
            ['category' => 'Birthday Items', 'product_name' => 'Silver Crown', 'item_code' => '3029', 'selling_price' => 50],
            ['category' => 'Birthday Items', 'product_name' => 'Snow Spray', 'item_code' => '3035', 'selling_price' => 65],
            ['category' => 'Birthday Items', 'product_name' => 'Sparkle Candle', 'item_code' => '501', 'selling_price' => 30],

            // BISCUITS BOXES
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Almond Sticks 250gms', 'item_code' => '2034', 'selling_price' => 179],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Badam Cookies', 'item_code' => '2077', 'selling_price' => 159],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Butterscotch Biscuits 250gm', 'item_code' => '4008', 'selling_price' => 135],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'BUTTERSCOTCH Biscuits [o]', 'item_code' => '4007', 'selling_price' => 135],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Chand Cookies 400gms', 'item_code' => '1032', 'selling_price' => 165],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Chocolate Kaju Biscuits 300gms', 'item_code' => '2054', 'selling_price' => 165],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Coconut Badam Cookies 300gms', 'item_code' => '2081', 'selling_price' => 145],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Coconut Kaju Cookies 300gm', 'item_code' => '2090', 'selling_price' => 145],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Fruit Biscuits 300gms', 'item_code' => '2043', 'selling_price' => 149],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Kaju Biscuits Box (300gms)', 'item_code' => '2051', 'selling_price' => 135],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Kesar Badam Cookies 250gms', 'item_code' => '2044', 'selling_price' => 129],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Pista Sticks (250 Gms)', 'item_code' => '218', 'selling_price' => 179],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Ragi Biscuits 300 Gms', 'item_code' => '2058', 'selling_price' => 145],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Rasmalai Cookies', 'item_code' => '1909', 'selling_price' => 139],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Salt Cookies 350g', 'item_code' => '2094', 'selling_price' => 135],
            ['category' => 'BISCUITS BOXES', 'product_name' => 'Sugarless Cookies 300gms', 'item_code' => '2512', 'selling_price' => 125],

            // Breads
            ['category' => 'Breads', 'product_name' => 'Brown Bread (300 Gms)', 'item_code' => '171', 'selling_price' => 55],
            ['category' => 'Breads', 'product_name' => 'Burger Buns (Medium)', 'item_code' => '190', 'selling_price' => 35],
            ['category' => 'Breads', 'product_name' => 'Burger Buns (Mini)', 'item_code' => '190', 'selling_price' => 29],
            ['category' => 'Breads', 'product_name' => 'Choco Muffins', 'item_code' => '185', 'selling_price' => 109],
            ['category' => 'Breads', 'product_name' => 'Cream Bun', 'item_code' => '177', 'selling_price' => 33],
            ['category' => 'Breads', 'product_name' => 'Cream Rolls Box(4 Pc)', 'item_code' => '2085', 'selling_price' => 120],
            ['category' => 'Breads', 'product_name' => 'Dil-pasand (1 Pc)', 'item_code' => '179', 'selling_price' => 30],
            ['category' => 'Breads', 'product_name' => 'Dilkush', 'item_code' => '176', 'selling_price' => 29],
            ['category' => 'Breads', 'product_name' => 'Dilpasand Round', 'item_code' => 'dlipasand round', 'selling_price' => 156],
            ['category' => 'Breads', 'product_name' => 'Fine Biscuit 600gms', 'item_code' => '205', 'selling_price' => 110],
            ['category' => 'Breads', 'product_name' => 'Fruit Bread', 'item_code' => '173', 'selling_price' => 35],
            ['category' => 'Breads', 'product_name' => 'Maska Bun', 'item_code' => '120', 'selling_price' => 35],
            ['category' => 'Breads', 'product_name' => 'Milk Bread 300gms', 'item_code' => '168', 'selling_price' => 55],
            ['category' => 'Breads', 'product_name' => 'Milk Bread 900gms', 'item_code' => '170', 'selling_price' => 99],
            ['category' => 'Breads', 'product_name' => 'Milk Breads 600gms', 'item_code' => '169', 'selling_price' => 75],
            ['category' => 'Breads', 'product_name' => 'Pav Bhaji Bread', 'item_code' => '174', 'selling_price' => 40],
            ['category' => 'Breads', 'product_name' => 'Shirmal Bun', 'item_code' => '175', 'selling_price' => 40],
            ['category' => 'Breads', 'product_name' => 'Tea Bun', 'item_code' => '4056', 'selling_price' => 20],
            ['category' => 'Breads', 'product_name' => 'Vanilla Muffins', 'item_code' => '184', 'selling_price' => 109],

            // Butter Cream Cakes
            ['category' => 'Butter Cream Cakes', 'product_name' => 'Buttersctoch Cake (Buttercream) (500 Gms)', 'item_code' => 'buttersctoch cake 50', 'selling_price' => 300],

            // Chicken Puffs
            ['category' => 'Chicken Puffs', 'product_name' => 'Butter Chicken Puff', 'item_code' => '17', 'selling_price' => 75],
            ['category' => 'Chicken Puffs', 'product_name' => 'Chicken Cheese Puff', 'item_code' => '16', 'selling_price' => 50],
            ['category' => 'Chicken Puffs', 'product_name' => 'Chicken Puff', 'item_code' => '14', 'selling_price' => 45],
            ['category' => 'Chicken Puffs', 'product_name' => 'Ginger Chicken Puff', 'item_code' => '15', 'selling_price' => 50],

            // Chips
            ['category' => 'Chips', 'product_name' => 'Chips', 'item_code' => 'chips', 'selling_price' => 35],

            // Chocolate Pastry
            ['category' => 'Chocolate Pastry', 'product_name' => 'Black Forest Pastry', 'item_code' => '289', 'selling_price' => 145],
            ['category' => 'Chocolate Pastry', 'product_name' => 'Butter Almond Pastry', 'item_code' => '293', 'selling_price' => 99],
            ['category' => 'Chocolate Pastry', 'product_name' => 'Choco Chips Pastry', 'item_code' => '291', 'selling_price' => 99],
            ['category' => 'Chocolate Pastry', 'product_name' => 'Chocolate Pastry', 'item_code' => '288', 'selling_price' => 145],
            ['category' => 'Chocolate Pastry', 'product_name' => 'Death Pie Chocolate Pastry', 'item_code' => 'death pie chocolate', 'selling_price' => 109],
            ['category' => 'Chocolate Pastry', 'product_name' => 'Donut', 'item_code' => '300', 'selling_price' => 145],
            ['category' => 'Chocolate Pastry', 'product_name' => 'German Chocolate Pastry', 'item_code' => '295', 'selling_price' => 99],
            ['category' => 'Chocolate Pastry', 'product_name' => 'Italian Chocolate Pastry', 'item_code' => '294', 'selling_price' => 99],
            ['category' => 'Chocolate Pastry', 'product_name' => 'Vanilla Chocolate Pastry', 'item_code' => '299', 'selling_price' => 99],

            // Chocolates
            ['category' => 'Chocolates', 'product_name' => 'Dairy Milk 165', 'item_code' => '3000', 'selling_price' => 250],
            ['category' => 'Chocolates', 'product_name' => 'Gourmet Wafer 90mrp', 'item_code' => '3008', 'selling_price' => 90],
            ['category' => 'Chocolates', 'product_name' => 'Merryland 1kg', 'item_code' => '3161', 'selling_price' => 350],
            ['category' => 'Chocolates', 'product_name' => 'Quentella Chocolates 240gms', 'item_code' => '3109', 'selling_price' => 125],
            ['category' => 'Chocolates', 'product_name' => 'Rainbow Jopadi', 'item_code' => '3025', 'selling_price' => 100],
            ['category' => 'Chocolates', 'product_name' => 'Truffles 5', 'item_code' => '8906098025708', 'selling_price' => 150],
            ['category' => 'Chocolates', 'product_name' => 'V Chocolate', 'item_code' => '0101', 'selling_price' => 39],

            // COOL DRINKS
            ['category' => 'COOL DRINKS', 'product_name' => 'Cool Drinks', 'item_code' => 'cool drinks', 'selling_price' => 28],
            ['category' => 'COOL DRINKS', 'product_name' => 'Thums Up', 'item_code' => '8901764042911', 'selling_price' => 20],

            // Dry Cakes
            ['category' => 'Dry Cakes', 'product_name' => 'Almond Dry Cake 200gms', 'item_code' => '4038', 'selling_price' => 99],
            ['category' => 'Dry Cakes', 'product_name' => 'Banana Cake 200gms', 'item_code' => '163', 'selling_price' => 135],
            ['category' => 'Dry Cakes', 'product_name' => 'Blue Berry Dry Cake', 'item_code' => '4048', 'selling_price' => 99],
            ['category' => 'Dry Cakes', 'product_name' => 'Chocolate Cup Cake', 'item_code' => '183', 'selling_price' => 65],
            ['category' => 'Dry Cakes', 'product_name' => 'Chocolate Donuts Box 4pc', 'item_code' => 'chocolate donuts box', 'selling_price' => 135],
            ['category' => 'Dry Cakes', 'product_name' => 'Cup Cake 6pc', 'item_code' => '155', 'selling_price' => 99],
            ['category' => 'Dry Cakes', 'product_name' => 'Dilpasand 1kg', 'item_code' => '180', 'selling_price' => 120],
            ['category' => 'Dry Cakes', 'product_name' => 'Dilpasand[450gms]', 'item_code' => '167', 'selling_price' => 80],
            ['category' => 'Dry Cakes', 'product_name' => 'Fruit Cake (300gms)', 'item_code' => '165', 'selling_price' => 140],
            ['category' => 'Dry Cakes', 'product_name' => 'Ghee Cake 200gms', 'item_code' => '161', 'selling_price' => 119],
            ['category' => 'Dry Cakes', 'product_name' => 'Kaari Biscuits Box', 'item_code' => '4005', 'selling_price' => 100],
            ['category' => 'Dry Cakes', 'product_name' => 'Orange Dry CAKE', 'item_code' => '4047', 'selling_price' => 99],
            ['category' => 'Dry Cakes', 'product_name' => 'Pine Apple Dry Cake 200gms', 'item_code' => '4039', 'selling_price' => 99],
            ['category' => 'Dry Cakes', 'product_name' => 'Rich Plum Cake', 'item_code' => '164', 'selling_price' => 190],
            ['category' => 'Dry Cakes', 'product_name' => 'Swiss Roll [250grms]', 'item_code' => '3199', 'selling_price' => 120],
            ['category' => 'Dry Cakes', 'product_name' => 'Tie Biscuits Box', 'item_code' => '2039', 'selling_price' => 100],
            ['category' => 'Dry Cakes', 'product_name' => 'Vanilla Cream Sponge Slices 250gms', 'item_code' => '2076', 'selling_price' => 129],
            ['category' => 'Dry Cakes', 'product_name' => 'Vanilla Cup Cake', 'item_code' => '182', 'selling_price' => 60],
            ['category' => 'Dry Cakes', 'product_name' => 'Vanilla Sponge Cake (250 Gms)', 'item_code' => '162', 'selling_price' => 185],

            // Egg Puffs
            ['category' => 'Egg Puffs', 'product_name' => 'Double Egg Puff', 'item_code' => '7', 'selling_price' => 38],
            ['category' => 'Egg Puffs', 'product_name' => 'Egg Puff', 'item_code' => '6', 'selling_price' => 32],

            // ICE CREAMS
            ['category' => 'ICE CREAMS', 'product_name' => 'Icecream', 'item_code' => 'icecream', 'selling_price' => 40],

            // Joy Cakes 500 gms
            ['category' => 'Joy Cakes 500 gms', 'product_name' => 'Buttersctoch Cake 500gms', 'item_code' => 'buttersctoch 500gms', 'selling_price' => 429],
            ['category' => 'Joy Cakes 500 gms', 'product_name' => 'Chocolate Joycake 500gms', 'item_code' => 'chocolate joycake 50', 'selling_price' => 479],
            ['category' => 'Joy Cakes 500 gms', 'product_name' => 'Gulabjamun Cake 500gms', 'item_code' => 'gulabjamun cake 500g', 'selling_price' => 649],
            ['category' => 'Joy Cakes 500 gms', 'product_name' => 'Pineapple Cake 500gms', 'item_code' => 'Pineapple Cake 500gm', 'selling_price' => 429],

            // Joy Cakes 700gms
            ['category' => 'Joy Cakes 700gms', 'product_name' => 'Choco Fruit Joy Cake (700gms)', 'item_code' => '250', 'selling_price' => 609],
            ['category' => 'Joy Cakes 700gms', 'product_name' => 'Choco Joy Cake (eggless) 700gms', 'item_code' => '248', 'selling_price' => 569],
            ['category' => 'Joy Cakes 700gms', 'product_name' => 'Fruit Joy Cake 700gms (eggless)', 'item_code' => '249', 'selling_price' => 839],
            ['category' => 'Joy Cakes 700gms', 'product_name' => 'Joy Cake 700gms', 'item_code' => '247', 'selling_price' => 529],
            ['category' => 'Joy Cakes 700gms', 'product_name' => 'Kit-kat Cake 700gms', 'item_code' => 'kit-kat 700gms', 'selling_price' => 1230],
            ['category' => 'Joy Cakes 700gms', 'product_name' => 'Red Velvet Cake 700gms (eggless)', 'item_code' => 'red velvet cake 700g', 'selling_price' => 679],

            // Loose Biscuits
            ['category' => 'Loose Biscuits (250 Grams)', 'product_name' => 'All Mix Cookies 1 Kg', 'item_code' => 'all mix', 'selling_price' => 220],
            ['category' => 'Loose Biscuits (250 Grams)', 'product_name' => 'Kaju Biscuits 250gms', 'item_code' => '194', 'selling_price' => 110],

            // Raw items
            ['category' => 'miyapur raw items', 'product_name' => '2 KG BOX (14*14)/PC', 'item_code' => '2 KG BOX (14*14)/PC', 'selling_price' => 33],
            ['category' => 'miyapur raw items', 'product_name' => 'bill no;71 order cake', 'item_code' => 'bill no;71 order cake', 'selling_price' => 3925],
            ['category' => 'miyapur raw items', 'product_name' => 'BURGER CUTLET/PC', 'item_code' => 'BURGER CUTLET/PC', 'selling_price' => 10],
            ['category' => 'miyapur raw items', 'product_name' => 'CARAT (COVERLUX DARK CHOCOLATES) /KG', 'item_code' => 'CARAT (COVERLUX DARK', 'selling_price' => 250],
            ['category' => 'miyapur raw items', 'product_name' => 'Chocolate Sponge', 'item_code' => 'chocolate sponge', 'selling_price' => 220],
            ['category' => 'miyapur raw items', 'product_name' => 'Chocolate Flowers 25gm- (1pc)', 'item_code' => 'chocolate flowers', 'selling_price' => 10],
            ['category' => 'miyapur raw items', 'product_name' => 'CHURRA KAJU 8PC/KG', 'item_code' => 'CHURRA KAJU 8PC/KG', 'selling_price' => 600],
            ['category' => 'miyapur raw items', 'product_name' => 'EGGS /PC', 'item_code' => 'EGGS /PC', 'selling_price' => 5],
            ['category' => 'miyapur raw items', 'product_name' => 'MAIDA /KG', 'item_code' => 'MAIDA /KG', 'selling_price' => 41.8],
            ['category' => 'miyapur raw items', 'product_name' => 'PANNER/KG', 'item_code' => 'PANNER/KG', 'selling_price' => 350],
            ['category' => 'miyapur raw items', 'product_name' => 'PURATOS COLD GLAZE NEUTRAL (1 BOX -SKG) /KG', 'item_code' => 'PURATOS COLD GLAZE N', 'selling_price' => 200],
            ['category' => 'miyapur raw items', 'product_name' => 'SUGAR BAGS (1 KG)', 'item_code' => 'SUGAR BAGS (1 KG)', 'selling_price' => 41.4],
            ['category' => 'miyapur raw items', 'product_name' => 'Vanilla Sponge (maida)', 'item_code' => 'vanilla sponge (maid', 'selling_price' => 140],

            // Non-Veg
            ['category' => 'Non-Veg Bites', 'product_name' => 'Chicken 65 Cup', 'item_code' => '65', 'selling_price' => 60],
            ['category' => 'Non-Veg Bites', 'product_name' => 'Tangdi Chicken', 'item_code' => '64', 'selling_price' => 60],
            ['category' => 'Non-Veg Burgers', 'product_name' => 'Chicken Burger (Medium)', 'item_code' => '92', 'selling_price' => 168],
            ['category' => 'Non-Veg Pizza', 'product_name' => 'Chicken Hot Mix Cheese Pizza (Medium)', 'item_code' => 'chicken hot mix chee', 'selling_price' => 239],
            ['category' => 'Non-Veg Pizza', 'product_name' => 'Chicken Hot Mix Cheese Pizza (Mini)', 'item_code' => 'chicken hot mix chee', 'selling_price' => 145],
            ['category' => 'Non-Veg Pizza', 'product_name' => 'Chicken Mix Cheese Pizza (Medium)', 'item_code' => 'chicken mix cheese p', 'selling_price' => 239],
            ['category' => 'Non-Veg Pizza', 'product_name' => 'Chicken Mix Cheese Pizza (Mini)', 'item_code' => 'chicken mix cheese p', 'selling_price' => 145],
            ['category' => 'Non-Veg Pizza', 'product_name' => 'Chicken-65 Pizza (Medium)', 'item_code' => 'chicken-65 pizza', 'selling_price' => 259],
            ['category' => 'Non-Veg Rolls', 'product_name' => 'Chicken 65 Cheese Roll', 'item_code' => '43', 'selling_price' => 98],
            ['category' => 'Non-Veg Rolls', 'product_name' => 'Chicken Cheese Roll', 'item_code' => '40', 'selling_price' => 69],
            ['category' => 'Non-Veg Rolls', 'product_name' => 'Chicken Roll', 'item_code' => '27', 'selling_price' => 49],
            ['category' => 'Non-Veg Sandwich', 'product_name' => 'Chicken 65 Sandwich', 'item_code' => '158', 'selling_price' => 120],
            ['category' => 'Non-Veg Sandwich', 'product_name' => 'Chicken Grill Sandwich', 'item_code' => '156', 'selling_price' => 95],

            // Plum Cake
            ['category' => 'Plum Cake', 'product_name' => 'Plum Cake (50 Gms)', 'item_code' => '221', 'selling_price' => 30],

            // Premium Pastries
            ['category' => 'Premium Pastries', 'product_name' => 'Angry Bird Pastry', 'item_code' => '272', 'selling_price' => 99],
            ['category' => 'Premium Pastries', 'product_name' => 'Gulab Jamun Pastry', 'item_code' => '275', 'selling_price' => 109],
            ['category' => 'Premium Pastries', 'product_name' => 'Honey Almond Pastry', 'item_code' => '267', 'selling_price' => 99],
            ['category' => 'Premium Pastries', 'product_name' => 'Milk Badam Pastry', 'item_code' => 'Milk Badam Pastry', 'selling_price' => 99],
            ['category' => 'Premium Pastries', 'product_name' => 'Oreo Chocolate Pastry', 'item_code' => '[O]21', 'selling_price' => 99],
            ['category' => 'Premium Pastries', 'product_name' => 'Pista Pastry', 'item_code' => '260', 'selling_price' => 99],
            ['category' => 'Premium Pastries', 'product_name' => 'Rasmalai Pastryyy', 'item_code' => '3200', 'selling_price' => 109],
            ['category' => 'Premium Pastries', 'product_name' => 'Red Velvet Pastry', 'item_code' => 'red velvet pastry', 'selling_price' => 109],

            // Regular Cakes 1kg
            ['category' => 'Regular Cakes 1kg', 'product_name' => 'Black Forest Cake 1kg', 'item_code' => '363', 'selling_price' => 749],
            ['category' => 'Regular Cakes 1kg', 'product_name' => 'Butterscotch Cake 1kg', 'item_code' => '333', 'selling_price' => 750],
            ['category' => 'Regular Cakes 1kg', 'product_name' => 'Chocolate Cake 1kg', 'item_code' => '362', 'selling_price' => 749],
            ['category' => 'Regular Cakes 1kg', 'product_name' => 'Fancy Cake 1kg', 'item_code' => 'fancy cake 1kg', 'selling_price' => 1000],
            ['category' => 'Regular Cakes 1kg', 'product_name' => 'Gulabjamun Cake 1kg', 'item_code' => '370', 'selling_price' => 1100],
            ['category' => 'Regular Cakes 1kg', 'product_name' => 'Pineapple Cake 1kg', 'item_code' => '330', 'selling_price' => 750],

            // RETAIL ITEM
            ['category' => 'RETAIL ITEM', 'product_name' => 'Imli Khata', 'item_code' => '20230052', 'selling_price' => 35],
            ['category' => 'RETAIL ITEM', 'product_name' => 'Jelly 100gms', 'item_code' => '20230010', 'selling_price' => 50],
            ['category' => 'RETAIL ITEM', 'product_name' => 'Palli Chikki', 'item_code' => '20230001', 'selling_price' => 65],
            ['category' => 'RETAIL ITEM', 'product_name' => 'Til Chikki 200gms', 'item_code' => '20230017', 'selling_price' => 75],
            ['category' => 'RETAIL ITEM', 'product_name' => 'Totty Fruity Mix', 'item_code' => '20230015', 'selling_price' => 30],

            // Samosa
            ['category' => 'Samosa', 'product_name' => 'Chicken Samosa', 'item_code' => '24', 'selling_price' => 35],
            ['category' => 'Samosa', 'product_name' => 'Paneer Samosa', 'item_code' => '26', 'selling_price' => 30],
            ['category' => 'Samosa', 'product_name' => 'Veg Samosa', 'item_code' => '23', 'selling_price' => 25],

            // Veg
            ['category' => 'Veg Bites', 'product_name' => 'Egg Roll', 'item_code' => '001', 'selling_price' => 40],
            ['category' => 'Veg Bites', 'product_name' => 'Salted French Fries (Mini)', 'item_code' => '54', 'selling_price' => 116],
            ['category' => 'Veg Bites', 'product_name' => 'Veg Mushroom Roll', 'item_code' => '22', 'selling_price' => 30],
            ['category' => 'Veg Bites', 'product_name' => 'Veg Nuggets', 'item_code' => 'veg nuggets', 'selling_price' => 119],
            ['category' => 'Veg Burgers', 'product_name' => 'Veg Burger (Medium)', 'item_code' => '74', 'selling_price' => 119],
            ['category' => 'Veg Pizza', 'product_name' => 'Margharetria Pizza (Medium)', 'item_code' => 'margharetria pizza', 'selling_price' => 199],
            ['category' => 'Veg Pizza', 'product_name' => 'Onion Capsicum Cheese Pizza (Mini)', 'item_code' => 'onion capsicum chees', 'selling_price' => 168],
            ['category' => 'Veg Pizza', 'product_name' => 'Veg Cheese Pizza (Mini)', 'item_code' => 'veg cheese pizza', 'selling_price' => 149],
            ['category' => 'Veg Pizza', 'product_name' => 'Veg Hot Mix Cheese Pizza (Medium)', 'item_code' => 'veg hot mix cheese p', 'selling_price' => 272],
            ['category' => 'Veg Pizza', 'product_name' => 'Veg Paneer Pizza (Medium)', 'item_code' => 'veg paneer pizza', 'selling_price' => 229],
            ['category' => 'Veg Pizza', 'product_name' => 'Veg Paneer Tikka Pizza (Mini)', 'item_code' => 'veg paneer tikka piz', 'selling_price' => 196],
            ['category' => 'Veg Puffs', 'product_name' => 'Palak Paneer Puff', 'item_code' => '3', 'selling_price' => 42],
            ['category' => 'Veg Puffs', 'product_name' => 'Paneer Puff', 'item_code' => '2', 'selling_price' => 42],
            ['category' => 'Veg Puffs', 'product_name' => 'Veg Cheese Puff', 'item_code' => '13', 'selling_price' => 49],
            ['category' => 'Veg Puffs', 'product_name' => 'Veg Puff', 'item_code' => '1', 'selling_price' => 30],
            ['category' => 'Veg Rolls', 'product_name' => 'Paneer Cheese Roll', 'item_code' => '35', 'selling_price' => 59],
            ['category' => 'Veg Rolls', 'product_name' => 'Paneer Roll', 'item_code' => '20', 'selling_price' => 49],
            ['category' => 'Veg Rolls', 'product_name' => 'Veg Burger Roll', 'item_code' => '32', 'selling_price' => 59],
            ['category' => 'Veg Rolls', 'product_name' => 'Veg Hot Dog', 'item_code' => '36', 'selling_price' => 59],
            ['category' => 'Veg Rolls', 'product_name' => 'Veg Roll', 'item_code' => '29', 'selling_price' => 40],
            ['category' => 'Veg Rolls', 'product_name' => 'Veg Sweet Corn Roll', 'item_code' => '126549', 'selling_price' => 30],
            ['category' => 'Veg Sandwich', 'product_name' => 'Paneer Grill Sandwich', 'item_code' => 'Paneer Grill Sandwic', 'selling_price' => 99],
            ['category' => 'Veg Sandwich', 'product_name' => 'Paneer Tikka Sandwich', 'item_code' => '152', 'selling_price' => 99],
            ['category' => 'Veg Sandwich', 'product_name' => 'Veg Grill Sandwich', 'item_code' => '149', 'selling_price' => 89],

            // ZEPTO ITEMS
            ['category' => 'ZEPTO ITEMS', 'product_name' => 'Open Secrets', 'item_code' => '8908013328192', 'selling_price' => 105],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                [
                    'product_name' => $product['product_name'],
                    'category' => $product['category'],
                ],
                [
                    'item_code' => $product['item_code'],
                    'cost_price' => 0,
                    'selling_price' => $product['selling_price'],
                    'shelf_life_days' => 0,
                    'reorder_level' => 0,
                    'status' => 'active',
                ]
            );
        }
    }
}