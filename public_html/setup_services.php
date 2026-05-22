<?php
require 'php/db.php';
$d=[
    ['General', 'Hair Cut & Styling','Expert cuts for men & women tailored to your face shape and personality.','₹299','30 min', 'service-haircut.jpg'],
    ['General', 'Hair Coloring','Global brands for vibrant, long-lasting colour — balayage, highlights & more.','₹1,499','90 min', 'service-haircolor.jpg'],
    ['General', 'Bridal Makeup','Flawless bridal looks crafted for your most special day with luxury products.','₹5,999','180 min', 'service-bridal.jpg'],
    ['General', 'Facial & Cleanup','Rejuvenating facials and deep pore cleansing for glowing, youthful skin.','₹999','60 min', 'service-facial.jpg'],
    ['General', 'Spa & Massage','Full-body relaxation and skin rejuvenation in our tranquil spa suite.','₹2,999','120 min', 'service-spa.jpg'],
    ['General', 'Nail Art & Care','Creative nail designs, manicure and pedicure by expert nail artists.','₹499','60 min', 'service-nails.jpg'],
    ['General', 'Keratin Treatment','Smooth, frizz-free hair that lasts 3–6 months using premium keratin.','₹3,999','120 min', 'service-haircut.jpg'],
    ['General', 'Pre-Bridal Package','Complete 3-session beauty prep from engagement to wedding day.','₹14,999','Multiple', 'service-bridal.jpg']
];
$s = $db->prepare('INSERT INTO services (category, title, description, price, duration, image) VALUES (?, ?, ?, ?, ?, ?)');
foreach($d as $row) { $s->execute($row); }
echo "Done.";
