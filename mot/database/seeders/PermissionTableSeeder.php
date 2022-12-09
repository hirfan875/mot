<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $permissions = [
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            'product-import',
            'pending-products-list',
            'bundled-products-list',
            'categories-list',
            'categories-create',
            'categories-edit',
            'categories-delete',
            'brands-list',
            'brands-create',
            'brands-edit',
            'brands-delete',
            'pending-brands-list',
            'pending-brands-create',
            'pending-brands-edit',
            'pending-brands-delete',
            'attributes-list',
            'attributes-create',
            'attributes-edit',
            'attributes-delete',
            'attributes-options-list',
            'attributes-options-create',
            'attributes-options-edit',
            'attributes-options-delete',
            'tags-list',
            'tags-create',
            'tags-edit',
            'tags-delete',
            'orders-list',
            'orders-create',
            'orders-edit',
            'orders-delete',
            'pending-orders-list',
            'product-reviews-list',
            'product-reviews-show',
            'product-reviews-approve',
            'product-reviews-reject',
            'product-banners-list',
            'product-banners-create',
            'product-banners-edit',
            'product-banners-delete',
            'return-requests-list',
            'return-requests-create',
            'return-requests-edit',
            'return-requests-delete',
            'cancel-requests-list',
            'cancel-requests-edit',
            'customers-list',
            'customers-create',
            'customers-edit',
            'customers-delete',
            'addresses-list',
            'addresses-create',
            'addresses-edit',
            'addresses-delete',
            'stores-list',
            'stores-create',
            'stores-edit',
            'stores-delete',
            'staff-list',
            'staff-create',
            'staff-edit',
            'staff-delete',
            'stores-profile-list',
            'stores-profile-create',
            'stores-profile-edit',
            'stores-profile-delete',
            'pending-stores-list',
            'promotions-list',
            'coupons-list',
            'coupons-create',
            'coupons-edit',
            'coupons-delete',
            'free-delivery-list',
            'free-delivery-create',
            'free-delivery-edit',
            'free-delivery-delete',
            'countries-list',
            'countries-create',
            'countries-edit',
            'countries-delete',
            'states-list',
            'states-create',
            'states-edit',
            'states-delete',
            'cities-list',
            'cities-create',
            'cities-edit',
            'cities-delete',
            'pages-list',
            'pages-create',
            'pages-edit',
            'pages-delete',
            'flash-deals-list',
            'flash-deals-create',
            'flash-deals-edit',
            'flash-deals-delete',
            'currencies-list',
            'currencies-create',
            'currencies-edit',
            'currencies-delete',
            'languages-list',
            'languages-create',
            'languages-edit',
            'languages-delete',
            'contact-inquiries-list',
            'contact-inquiries-create',
            'contact-inquiries-edit',
            'contact-inquiries-delete',
            'home-page-setup',
            'sliders-list',
            'sliders-create',
            'sliders-edit',
            'sliders-delete',
            'sponsored-categories-list',
            'sponsored-categories-create',
            'sponsored-categories-edit',
            'sponsored-categories-delete',
            'tabbed-products-list',
            'tabbed-products-create',
            'tabbed-products-edit',
            'tabbed-products-delete',
            'trending-products-list',
            'trending-products-create',
            'trending-products-edit',
            'trending-products-delete',
            'banners-list',
            'banners-create',
            'banners-edit',
            'banners-delete',
            'sections-sorting',
            'reports-list',
            'group-sales-products',
            'group-sales-stores',
            'group-sales-customers',
            'group-sale',
            'sales',
            'coupon-usage',
            'most-searches',
            'store-questions-list',
            'store-questions-create',
            'store-questions-edit',
            'store-questions-delete',
            'return-address-list',
            'return-address-create',
            'return-address-edit',
            'return-address-delete',
            'media-settings',
            'settings',
            'user-setup',
            'profile',
            'dashboard',
            'configuration',
            'translation-list',
            'translation-create',
            'translation-edit',
            'translation-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

}
