<?php

namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
  

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call(ShipEngineTableSeeder::class);
        $this->call(BusinessSettingsTableSeeder::class);
        $this->call(GeneralSettingsTableSeeder::class);
        $this->call(SeoSettingsTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(TaxTableSeeder::class);
        $this->call(SearchTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(BannersTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);
        $this->call(BlogsTableSeeder::class);
        $this->call(BlogCategoriesTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(BrandTranslationsTableSeeder::class);
        $this->call(AttributesTableSeeder::class);
        $this->call(AppSettingsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AddressTableSeeder::class);
        $this->call(CartsTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(CategoryTranslationsTableSeeder::class);
        $this->call(ColorsTableSeeder::class);
        $this->call(CommissionHistoryTableSeeder::class);
        $this->call(ConversationsTableSeeder::class);
        $this->call(FlashDealTableSeeder::class);
        $this->call(FlashDealProductTableSeeder::class);
        $this->call(FlashDealTranslationsTableSeeder::class);
        $this->call(HomeCategoryTableSeeder::class);
        $this->call(MessagesTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);
        $this->call(SellersTableSeeder::class);
        $this->call(StaffTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(OrderDetailTableSeeder::class);
        $this->call(PageTableSeeder::class);
        $this->call(PaymentTableSeeder::class);
        $this->call(PolicyTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(ProductStockTableSeeder::class);
        $this->call(ProductTaxTableSeeder::class);
        $this->call(SellerRequestTableSeeder::class);
        $this->call(ShopTableSeeder::class);
        $this->call(SliderTableSeeder::class);
        $this->call(UploadTableSeeder::class);
        $this->call(WalletTableSeeder::class);
        $this->call(WishlistTableSeeder::class);
        //$this->call(TranslationsEnTableSeeder::class);
        // $this->call(TranslationsSaTableSeeder::class);
        // $this->call(TranslationsEsTableSeeder::class);
        // $this->call(TranslationsJpTableSeeder::class);
        // $this->call(TranslationsFrTableSeeder::class);
        // $this->call(TranslationsInTableSeeder::class);
        // $this->call(TranslationsNlTableSeeder::class);
        // $this->call(TranslationsAfTableSeeder::class);
        // $this->call(TranslationsEgTableSeeder::class);
        // $this->call(TranslationsZaTableSeeder::class);
        // $this->call(TranslationsIeTableSeeder::class);
        // $this->call(TranslationsSgTableSeeder::class);
        // $this->call(TranslationsVnTableSeeder::class);
        // $this->call(TranslationsMyTableSeeder::class);
        // $this->call(TranslationsLkTableSeeder::class);
        //$this->call(TranslationsTableSeeder::class);
        $this->call(SubscriptionPlanTableSeeder::class);
        $this->call(UserSubscriptionPlanTableSeeder::class);
        $this->call(UserSubscriptionRenewalTableSeeder::class);
        
    }
}
