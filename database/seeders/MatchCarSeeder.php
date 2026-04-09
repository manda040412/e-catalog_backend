use Illuminate\Support\Facades\DB;

public function run(): void
{
    DB::table('match_cars')->insert([
        [
            'item_code' => 1,
            'car_brand' => 'Toyota',
            'car_type' => 'Avanza',
            'year' => 2020,
            'engine_code' => '1NR',
            'chassis_code' => 'ABC123',
            'car_body' => 'MPV',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'item_code' => 1,
            'car_brand' => 'Honda',
            'car_type' => 'Brio',
            'year' => 2022,
            'engine_code' => 'L12B',
            'chassis_code' => 'XYZ789',
            'car_body' => 'Hatchback',
            'created_at' => now(),
            'updated_at' => now(),
        ]
    ]);
}