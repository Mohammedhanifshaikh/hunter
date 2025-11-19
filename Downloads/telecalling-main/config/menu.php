<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'bx bx-home-circle',
        'route' => 'dashboard',
        'module' => 'dashboard',
    ],
    [
        'title' => 'Hospitals',
        'icon' => 'bx bx-dock-top',
        'sub_menu' => [
            [
                'title' => 'Add Hospital',
                'route' => 'add.hospital',
                'module' => 'hospitals',
            ],
            [
                'title' => 'Hospitals List',
                'route' => 'hospital.list',
                'module' => 'hospitals',
            ],
        ],
    ],
    [
        'title' => 'Projects',
        'icon' => 'bx bx-dock-top',
        'sub_menu' => [
            [
                'title' => 'New Project',
                'route' => 'add.project',
                'module' => 'projects',
            ],
            [
                'title' => 'Project List',
                'route' => 'project.list',
                'module' => 'projects',
            ],
        ],
    ],
    [
        'title' => 'AMC',
        'icon' => 'bx bx-dock-top',
        'sub_menu' => [
            [
                'title' => 'New AMC',
                'route' => 'add.amc',
                'module' => 'amc',
            ],
            [
                'title' => 'AMC List',
                'route' => 'amc.list',
                'module' => 'amc',
            ],
            [
                'title' => 'AMC Schedule',
                'route' => 'amc.schedule',
                'module' => 'amc',
            ],
        ],
    ],
    [
        'title' => 'Complaints',
        'icon' => 'bx bx-dock-top',
        'sub_menu' => [
            [
                'title' => 'Add Complaint',
                'route' => 'add.complaint',
                'module' => 'complaints',
            ],
            [
                'title' => 'Complaints List',
                'route' => 'complaint.list',
                'module' => 'complaints',
            ],
        ],
    ],
    [
        'title' => 'Products',
        'icon' => 'bx bx-dock-top',
        'sub_menu' => [
            [
                'title' => 'Add Category',
                'route' => 'add.category',
                'module' => 'products',
            ],
            [
                'title' => 'Category List',
                'route' => 'category.list',
                'module' => 'products',

            ],
            [
                'title' => 'Add Product',
                'route' => 'add.product',
                'module' => 'products',

            ],
            [
                'title' => 'Product List',
                'route' => 'product.list',
                'module' => 'products',

            ],
        ],
    ],
    [
        'title' => 'Technicians',
        'icon' => 'bx bx-dock-top',
        'sub_menu' => [
            [
                'title' => 'Add Technician',
                'route' => 'add.technician',
                'module' => 'technicians',
            ],
            [
                'title' => 'Technicians List',
                'route' => 'technician.list',
                'module' => 'technicians',
            ]
        ],
    ],
    [
        'title' => 'Sales Eng.',
        'icon' => 'bx bx-dock-top',
        'sub_menu' => [
            [
                'title' => 'Add Sales Eng.',
                'route' => 'add.sales.eng',
                'module' => 'sales-eng',
            ],
            [
                'title' => 'Sales Eng. List',
                'route' => 'sales.eng.list',
                'module' => 'sales-eng',
            ]
        ],
    ],
];
