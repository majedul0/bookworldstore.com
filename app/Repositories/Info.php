<?php

// namespace App\Repositories;

use App\Models\Settings;

class Info {
    // Site Info
    public static function Settings($group, $name){
        $q = Settings::where('group', $group)->where('name', $name)->first();

        // Null Check
        if ($q){
            return $q->value;
        }
        return null;
    }

    // Site Info by Group
    public static function SettingsGroup($group){
        return Settings::where('group', $group)->get();
    }

    // Site Info by Keys
    public static function SettingsGroupKey($group = 'general'){
        $query = Settings::where('group', $group)->get();

        // Generate Output
        $output = [];
        foreach($query as $data){
            if($data->name == 'logo' || $data->name == 'favicon' || $data->name == 'og_image' || $data->name == 'home_banner_1' || $data->name == 'home_banner_2'){
                $output[$data->name] = asset('uploads/info/' . $data->value);
            }else{
                $output[$data->name] = $data->value;
            }
        }

        // // Return Default
        // foreach($keys as $key){
        //     if(!isset($output[$key])){
        //         $output[$key] = null;
        //     }
        // }

        return $output;
    }

    // Tax Calculation
    public static function tax($amount){
        $tax = (new static)->Settings('general', 'tax');
        $tax_type = (new static)->Settings('general', 'tax_type');

        if($tax_type == 'Percent'){
            return ($amount * $tax) / 100;
        }

        return $tax;
    }

    public static function routesItems(){
        // Product
        $items[] = [
            'group_name' => 'Product',
            'routes' => [
                ['name' => 'Product', 'route' => 'back.products.create||back.products.store||back.products.edit||back.products.update||back.products.index||back.products.table||back.products.destroy']
            ]
        ];
        // Category
        $items[] = [
            'group_name' => 'Category',
            'routes' => [
                ['name' => 'Category', 'route' => 'back.categories.create||back.categories.store||back.categories.edit||back.categories.update||back.categories.index||back.categories.delete']
            ]
        ];
        // Brand
        $items[] = [
            'group_name' => 'Brand',
            'routes' => [
                ['name' => 'Brand', 'route' => 'back.brands.create||back.brands.store||back.brands.edit||back.brands.update||back.brands.index||back.brands.delete']
            ]
        ];
        // // Order
        // $items[] = [
        //     'group_name' => 'Order',
        //     'routes' => [
        //         ['name' => 'Order', 'route' => 'back.orders.index||back.orders.create||back.orders.store||back.orders.show||back.orders.update']
        //     ]
        // ];
        // // Customer
        // $items[] = [
        //     'group_name' => 'Customer',
        //     'routes' => [
        //         ['name' => 'Customer', 'route' => 'back.customers.create||back.customers.store||back.customers.edit||back.customers.update||back.customers.index||back.customers.destroy']
        //     ]
        // ];
        // // Report
        // $items[] = [
        //     'group_name' => 'Report',
        //     'routes' => [
        //         ['name' => 'Report', 'route' => 'back.report.overview||back.report.product||back.report.productDetails||back.report.orders']
        //     ]
        // ];
        // Product Attribute
        $items[] = [
            'group_name' => 'Attribute',
            'routes' => [
                ['name' => 'Attribute', 'route' => 'back.attributes.index||back.attributes.store']
            ]
        ];
        // Purchase
        $items[] = [
            'group_name' => 'Purchase',
            'routes' => [
                ['name' => 'Purchase', 'route' => 'back.purchases.index||back.purchases.show||back.adjustments.create||back.adjustments.store']
            ]
        ];
        // Settings
        $items[] = [
            'group_name' => 'Settings',
            'routes' => [
                ['name' => 'General Settings', 'route' => 'back.frontend.general'],
                ['name' => 'Pages', 'route' => 'back.pages.index||back.pages.create||back.pages.store||back.pages.edit||back.pages.update||back.pages.destroy||back.pages.removeImage'],
                ['name' => 'Menus', 'route' => 'back.menus.index||back.menus.store||back.menus.storeMenuItem||back.menus.menuItemPosition||back.menus.destroy||back.menus.destroyItem||back.menus.editItemAjax||back.menus.updateItem||back.menus.category'],
                ['name' => 'Sliders', 'route' => 'back.sliders.position||back.sliders.delete||back.sliders.index||back.sliders.create||back.sliders.store||back.sliders.edit||back.sliders.update||back.sliders.destroy'],
                ['name' => 'Media Settings', 'route' => 'back.media.settings||back.media.settingsUpdate'],
                ['name' => 'Courier', 'route' => 'back.courier.config||back.courier.update'],
                ['name' => 'SMS', 'route' => 'back.sms.config||back.sms.updateConfig'],
            ]
        ];
        // Accounts
        $items[] = [
            'group_name' => 'Accounts',
            'routes' => [
                ['name' => 'Report Summary', 'route' => 'back.accounts.report'],
                ['name' => 'Other Expense', 'route' => 'back.accounts.otherExpenses||back.accounts.otherExpensesStore||back.accounts.otherExpensesDelete||back.accounts.otherExpensesEdit'],
                ['name' => 'Courier', 'route' => 'back.accounts.courier||back.accounts.courierReceive||back.accounts.courierDelete'],
            ]
        ];
        // Location
        $items[] = [
            'group_name' => 'Location',
            'routes' => [
                ['name' => 'Location', 'route' => 'back.locations.index||back.locations.statesUpdate'],
            ]
        ];
        // Role Permission
        $items[] = [
            'group_name' => 'Role Permission',
            'routes' => [
                ['name' => 'Role Permission', 'route' => 'back.roles.create||back.roles.store||back.roles.index||back.roles.edit||back.roles.update||back.roles.destroy']
            ]
        ];
        // Recycle Bin
        $items[] = [
            'group_name' => 'Recycle Bin',
            'routes' => [
                ['name' => 'Recycle Bin', 'route' => 'back.recycleBun.index||back.recycleBun.restoreProduct||back.recycleBun.restoreCategory||back.recycleBun.restoreBrand']
            ]
        ];
        // Supplier
        $items[] = [
            'group_name' => 'Supplier',
            'routes' => [
                ['name' => 'Add Supplier', 'route' => 'back.suppliers.create||back.suppliers.store||back.suppliers.index||back.suppliers.edit||back.suppliers.update||back.suppliers.ledger||back.suppliers.addPayment||back.suppliers.paymentDetails']
            ]
        ];

        // Admin
        $items[] = [
            'group_name' => 'Admin',
            'routes' => [
                ['name' => 'Admin', 'route' => 'back.admins.create||back.admins.store||back.admins.index||back.admins.edit||back.admins.update||back.admins.destroy||back.admins.removeImage']
            ]
        ];

        // Blogs
        $items[] = [
            'group_name' => 'Blogs',
            'module' => 'Blogs',
            'routes' => [
                ['name' => 'Blog Categories', 'route' => 'back.articles.categories||back.articles.categories.create||back.articles.categories.store||back.articles.categories.edit||back.articles.categories.update||back.articles.categories.destroy'],
                ['name' => 'Blogs', 'route' => 'back.articles.index||back.articles.create||back.articles.store||back.articles.edit||back.articles.update||back.articles.destroy']
            ]
        ];

        // Blogs
        $items[] = [
            'group_name' => 'Testimonials',
            'routes' => [
                ['name' => 'Testimonials', 'route' => 'back.testimonials.index||back.testimonials.create||back.testimonials.store||back.testimonials.edit||back.testimonials.update||back.testimonials.destroy']
            ]
        ];

        return $items;
    }

    public static function getSuperRole(){
        $items = (new static)->routesItems();

        $groups = [];
        $routes = [];
        foreach($items as $item){
            $groups[] = $item['group_name'];

            foreach($item['routes'] as $route){
                $route_explode = explode('||', $route['route']);

                foreach($route_explode as $route_explode_item){
                    $routes[] = $route_explode_item;
                }
            }
        }

        $groups = array_unique($groups);

        return [
            'routes' => $routes,
            'groups' => $groups
        ];
    }
}

function amount($amount){
    return $amount . ' tk';
}

function amount_f($amount){
    return '৳ ' . number_format($amount);
}

function view_loader($path, $default = 'landing.contents.watch_common'){
    if(view()->exists($path)){
        return $path;
    }

    return $default;
}
