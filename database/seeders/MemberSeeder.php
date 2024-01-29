<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberCard;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $memberIds = [];
        $generateCard = 300;
        $maxZero = strlen(300);

        $memberCard = MemberCard::where(['status' => 'ACTIVE'])->first()->toArray();

        for ($x = 1; $x <= 300; $x++) {
            $insertZero = '000';

            for ($z = 1; $z <= ($maxZero - strlen($x)); $z++) {
                $insertZero .= '0';
            }

            $memberCard = [
                'user_id' => null,
                'member_id' => $insertZero.$x,
                'membercard_id' => null,
            ];

            Member::create($memberCard);
        }
    }
}
