<?php
require 'php/db.php';
$d=[
    ['Retail', 'LuxeGlow Keratin Shampoo', '₹899', '₹1,299', '4.9', 'product-1.jpg'],
    ['Retail', 'Argan Oil Hair Serum', '₹599', '₹899', '4.8', 'product-2.jpg'],
    ['Retail', '24K Gold Glow Facial Kit', '₹1,499', '₹2,199', '4.9', 'product-3.jpg'],
    ['Retail', 'Vitamin C Skin Serum', '₹799', '₹1,099', '4.7', 'product-4.jpg']
];
$s = $db->prepare('INSERT INTO products (category, title, price, old_price, rating, image) VALUES (?, ?, ?, ?, ?, ?)');
foreach($d as $row) { $s->execute($row); }
echo "Done.";
