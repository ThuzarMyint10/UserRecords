<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Events\UserRecordDeleted;

class UserController extends Controller
{

    public function __construct(protected UserService $service)
    {
    }
    
    public function index(Request $request)
    {
        $users = User::paginate(10);
        foreach ($users as $user) {
            $name = json_decode($user->name);
            $user->formatted_name = $name->title . ' ' . $name->first . ' ' . $name->last;
        }

        return view('users.index', compact('users'));
    }

    public function store()
    {  
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->service->delete($id);

        // Fire the event with the gender of the deleted user
        event(new UserRecordDeleted($user->created_at->format('Y-m-d'), $user->gender));

        $currentPage = request()->get('page', 1);
        return redirect()->route('users.index', ['page' => $currentPage])->with('success', 'User deleted successfully');
    }
 
}
