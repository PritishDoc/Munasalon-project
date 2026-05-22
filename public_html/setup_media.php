<?php
require 'php/db.php';
$gallery = [
    ['Hair', 'Premium Haircut', 'gallery-hair.jpg'],
    ['Bridal', 'Traditional Bridal', 'service-bridal.jpg'],
    ['Nails', 'Nail Art', 'service-nails.jpg'],
    ['Spa', 'Spa Session', 'service-spa.jpg'],
    ['Grooming', 'Men\'s Grooming', 'gallery-grooming.jpg'],
    ['Skin', 'Skin Glow', 'service-facial.jpg'],
];
$sg = $db->prepare("INSERT INTO gallery (category, title, image) VALUES (?, ?, ?)");
foreach ($gallery as $d) { $sg->execute($d); }

$blogs = [
    ['Bridal', 'The Ultimate Bridal Beauty Prep Timeline', 'A comprehensive month-by-month guide to get you glowing on your big day, from skin prep to D-day makeup trials.', '<p>Content</p>', 'gallery-grooming.jpg'],
    ['Hair Care', 'Top 10 Hair Care Tips for Monsoon Season', 'Protect your hair from humidity, frizz and fungal infections during the rainy season.', '<p>Content</p>', 'gallery-hair.jpg'],
    ['Skin Care', 'The Best Skincare Routine for Indian Skin Types', 'Customised routines for oily, dry, combination and sensitive skin in Indian climate.', '<p>Content</p>', 'service-facial.jpg']
];
$sb = $db->prepare("INSERT INTO blogs (category, title, excerpt, content, image) VALUES (?, ?, ?, ?, ?)");
foreach ($blogs as $d) { $sb->execute($d); }
echo "Done.";
