<?php
namespace Tests\Feature;
use App\Models\Airline;
use App\Models\Flight;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class TravelApiTest extends TestCase
{
    use RefreshDatabase;
    private function reservations(): array
    {
        $users = User::factory(2)->create();
        $airline = Airline::create(['name' => 'Test', 'country' => 'Hungary']);
        $first = Flight::create(['date' => '2026-11-01', 'airline_id' => $airline->id, 'limit' => 180]);
        $second = Flight::create(['date' => '2026-11-02', 'airline_id' => $airline->id, 'limit' => 180]);
        $target = Travel::create(['user_id' => $users[0]->id, 'flight_id' => $first->id, 'evaluation' => 'foglalva']);
        Travel::create(['user_id' => $users[0]->id, 'flight_id' => $second->id, 'evaluation' => 'fizetve']);
        Travel::create(['user_id' => $users[1]->id, 'flight_id' => $first->id, 'evaluation' => 'foglalva']);
        return [$target, $users[1]->id, $second->id];
    }
    public function test_show_uses_both_keys(): void
    {
        [$target] = $this->reservations();
        $this->getJson("/api/travel/{$target->user_id}/{$target->flight_id}")
            ->assertOk()->assertExactJson(['user_id' => $target->user_id, 'flight_id' => $target->flight_id, 'evaluation' => 'foglalva']);
    }
    public function test_destroy_removes_only_one_reservation(): void
    {
        [$target, $otherUser, $otherFlight] = $this->reservations();
        $this->deleteJson("/api/travel/{$target->user_id}/{$target->flight_id}")->assertNoContent();
        $this->assertDatabaseMissing('travel', ['user_id' => $target->user_id, 'flight_id' => $target->flight_id]);
        $this->assertDatabaseHas('travel', ['user_id' => $target->user_id, 'flight_id' => $otherFlight]);
        $this->assertDatabaseHas('travel', ['user_id' => $otherUser, 'flight_id' => $target->flight_id]);
        $this->assertDatabaseCount('travel', 2);
    }
    public function test_missing_pair_returns_404(): void
    {
        [, $otherUser, $otherFlight] = $this->reservations();
        $this->getJson("/api/travel/{$otherUser}/{$otherFlight}")->assertNotFound();
        $this->deleteJson("/api/travel/{$otherUser}/{$otherFlight}")->assertNotFound();
        $this->assertDatabaseCount('travel', 3);
    }
    public function test_duplicate_composite_key_is_rejected(): void
    {
        [$target] = $this->reservations();
        $this->expectException(QueryException::class);
        Travel::create(['user_id' => $target->user_id, 'flight_id' => $target->flight_id, 'evaluation' => 'fizetve']);
    }
    public function test_model_save_uses_both_keys(): void
    {
        [$target, $otherUser, $otherFlight] = $this->reservations();
        $target->evaluation = 'lemondva';
        $target->save();
        $this->assertSame('lemondva', $target->refresh()->evaluation);
        $this->assertDatabaseHas('travel', ['user_id' => $target->user_id, 'flight_id' => $otherFlight, 'evaluation' => 'fizetve']);
        $this->assertDatabaseHas('travel', ['user_id' => $otherUser, 'flight_id' => $target->flight_id, 'evaluation' => 'foglalva']);
    }
}
