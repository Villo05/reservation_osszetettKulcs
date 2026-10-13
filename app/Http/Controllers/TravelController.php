<?php
namespace App\Http\Controllers;
use App\Models\Travel;
use Illuminate\Http\Response;
class TravelController extends Controller
{
    public function show(int $user_id, int $flight_id): Travel
    {
        return Travel::where('user_id', $user_id)->where('flight_id', $flight_id)->firstOrFail();
    }
    public function destroy(int $user_id, int $flight_id): Response
    {
        $travel = $this->show($user_id, $flight_id);
        $travel->delete();
        return response()->noContent();
    }
}
