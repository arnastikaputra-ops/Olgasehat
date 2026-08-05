<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentSetting;

class PaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'bank_code' => 'BCA',
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_number' => '88008819203847',
                'account_holder' => 'PT OlgaSehat Indonesia',
                'category' => 'bank',
                'is_active' => true,
            ],
            [
                'bank_code' => 'BRI',
                'bank_name' => 'Bank Rakyat Indonesia (BRI)',
                'account_number' => '88002819203847',
                'account_holder' => 'PT OlgaSehat Indonesia',
                'category' => 'bank',
                'is_active' => true,
            ],
            [
                'bank_code' => 'BPD',
                'bank_name' => 'Bank BPD Bali',
                'account_number' => '88014819203847',
                'account_holder' => 'PT OlgaSehat Indonesia',
                'category' => 'bank',
                'is_active' => true,
            ],
            [
                'bank_code' => 'DANA',
                'bank_name' => 'DANA E-Wallet',
                'account_number' => '8528819203847',
                'account_holder' => 'PT OlgaSehat Indonesia',
                'category' => 'ewallet',
                'is_active' => true,
            ],
            [
                'bank_code' => 'GOPAY',
                'bank_name' => 'GoPay E-Wallet',
                'account_number' => '70001819203847',
                'account_holder' => 'PT OlgaSehat Indonesia',
                'category' => 'ewallet',
                'is_active' => true,
            ],
        ];

        foreach ($settings as $data) {
            PaymentSetting::updateOrCreate(
                ['bank_code' => $data['bank_code']],
                $data
            );
        }
    }
}
