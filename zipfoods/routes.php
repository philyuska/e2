<?php

return [
    '/' => ['AppController', 'index'],
    '/products' => ['ProductController', 'index'],
    '/product' => ['ProductController', 'show'],
    '/products/save-review' => ['ProductController', 'saveReview'],
    '/products/new' => ['ProductController', 'addNew'],
    '/products/save-new' => ['ProductController', 'saveNew'],
    '/about' => ['AppController', 'about'],
    '/practice' => ['AppController', 'practice'],
];
