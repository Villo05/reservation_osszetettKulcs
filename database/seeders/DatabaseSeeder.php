<?php
namespace Database\Seeders;
use App\Models\Airline;
use App\Models\Flight;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(2)->create();
        $airline = Airline::create(['name' => 'Wizz Air', 'country' => 'Hungary']);
        $flight = Flight::create(['date' => '2026-11-01', 'airline_id' => $airline->id, 'limit' => 180]);
        $otherFlight = Flight::create(['date' => '2026-11-02', 'airline_id' => $airline->id, 'limit' => 180]);
        Travel::create(['user_id' => $users[0]->id, 'flight_id' => $flight->id, 'evaluation' => 'foglalva']);
        Travel::create(['user_id' => $users[0]->id, 'flight_id' => $otherFlight->id, 'evaluation' => 'fizetve']);
        Travel::create(['user_id' => $users[1]->id, 'flight_id' => $flight->id, 'evaluation' => 'foglalva']);
    }
}
