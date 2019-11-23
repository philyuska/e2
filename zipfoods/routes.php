<?php

return [
    '/' => ['AppController', 'index'],
    '/products' => ['ProductController', 'index'],
    '/product' => ['ProductController', 'show'],
    '/products/save-review' => ['ProductController', 'saveReview'],
    '/about' => ['AppController', 'about'],
    '/practice' => ['AppController', 'practice'],
];
