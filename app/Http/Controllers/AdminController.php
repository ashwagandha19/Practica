<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminController
 *
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function users()
    {
        $users = DB::table('users')->paginate(10);

        return view(
            'users.index',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function createUser(Request $request) {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role
        ]);

        return redirect('users.index');
    }

    public function updateUser(Request $request): RedirectResponse
    {
        $error = '';
        $success = '';

        if ($request->has('id')) {
            /** @var User $user */
            $user = User::find($request->get('id'));

            if ($user) {
                $role = $request->get('role');

                if (in_array($role, [User::ROLE_USER, User::ROLE_ADMIN])) {
                    $user->role = $role;
                    $user->save();

                    $success = 'User saved';
                } else {
                    $error = 'Role selected is not valid!';
                }
            } else {
                $error = 'User not found!';
            }
        } else {
            $error = 'Invalid request';
        }

        return redirect()->back()->with([
            'error' => $error, 'success' => $success
        ]);
    }

    /**
     * @param  Request  $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function updateUserAjax(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        $error = '';
        $success = '';

        if ($user) {
            $role = $request->get('role');

            if (in_array($role, [User::ROLE_USER, User::ROLE_ADMIN])) {
                $user->role = $role;
                $user->save();
                $user->refresh();

                $success = 'User saved';
            } else {
                $error = 'Role selected is not valid!';
            }
        } else {
            $error = 'User not found!';
        }

        return response()->json(['error' => $error, 'success' => $success, 'user' => $user]);
    }

    /**
     * @param  Request  $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function deleteUser(Request $request, $id): JsonResponse
    {
        $user = User::find($id);
        $user->delete();

        return response()->json('Task deleted', 200);
    }
}
