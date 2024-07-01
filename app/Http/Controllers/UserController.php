<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Adjust pagination as per your needs

        return view('users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        // Update DailyRecord counts based on gender
        $this->updateDailyRecordCounts($user->gender);

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    private function updateDailyRecordCounts($gender)
    {
        // Implement your logic to update DailyRecord counts based on gender
        // Example:
        // $dailyRecord = DailyRecord::where('date', now()->toDateString())->first();
        // if ($gender === 'male') {
        //     $dailyRecord->male_count -= 1;
        // } elseif ($gender === 'female') {
        //     $dailyRecord->female_count -= 1;
        // }
        // $dailyRecord->save();
    }
}
