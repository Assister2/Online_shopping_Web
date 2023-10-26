<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('wallets')->delete();

        \DB::table('wallets')->insert(
        	array(
			  array('id' => '1','user_id' => '55','amount' => '100.00','payment_method' => 'Paypal','payment_details' => '{"statusCode":201,"result":{"id":"3W982791M28054547","intent":"CAPTURE","status":"COMPLETED","purchase_units":[{"reference_id":"708462","amount":{"currency_code":"EUR","value":"100.00"},"payee":{"email_address":"gofer@trioangle.com","merchant_id":"2AB9QV3LEEZ52"},"shipping":{"name":{"full_name":"Brian Robinson"},"address":{"address_line_1":"4th Floor","address_line_2":"Unit #34","admin_area_2":"San Jose","admin_area_1":"CA","postal_code":"95131","country_code":"US"}},"payments":{"captures":[{"id":"72W76452GJ700830C","status":"COMPLETED","amount":{"currency_code":"EUR","value":"100.00"},"final_capture":true,"seller_protection":{"status":"ELIGIBLE","dispute_categories":["ITEM_NOT_RECEIVED","UNAUTHORIZED_TRANSACTION"]},"seller_receivable_breakdown":{"gross_amount":{"currency_code":"EUR","value":"100.00"},"paypal_fee":{"currency_code":"EUR","value":"3.88"},"net_amount":{"currency_code":"EUR","value":"96.12"}},"links":[{"href":"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/72W76452GJ700830C","rel":"self","method":"GET"},{"href":"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/72W76452GJ700830C\\/refund","rel":"refund","method":"POST"},{"href":"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/3W982791M28054547","rel":"up","method":"GET"}],"create_time":"2021-11-23T13:59:55Z","update_time":"2021-11-23T13:59:55Z"}]}}],"payer":{"name":{"given_name":"jeeva","surname":"vanna"},"email_address":"test@trioangle.com","payer_id":"Q5TYRRXCKE5YS","address":{"country_code":"US"}},"create_time":"2021-11-23T13:59:16Z","update_time":"2021-11-23T13:59:55Z","links":[{"href":"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/3W982791M28054547","rel":"self","method":"GET"}]},"headers":{"":"","Content-Type":"application\\/json","Content-Length":"1659","Connection":"keep-alive","Date":"Tue, 23 Nov 2021 13","Application_id":"APP-80W284485P519543T","Cache-Control":"max-age=0, no-cache, no-store, must-revalidate","Caller_acct_num":"2AB9QV3LEEZ52","Paypal-Debug-Id":"9d628773778f","Strict-Transport-Security":"max-age=31536000; includeSubDomains"}}','created_at' => '2021-11-24 00:59:55','updated_at' => '2021-11-24 00:59:55')
			),
        );
    }
}
