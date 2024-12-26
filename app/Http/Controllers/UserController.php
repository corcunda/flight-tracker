<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null)
    {
        try {

            if( !$id ) {

                $max        = 100;
                $per_page   = ($request->per_page) ? $request->per_page : null;
                $paginate   = ($per_page && ($per_page < $max)) ? $per_page : $max;
                $isPaginate = (isset($request->paginate)) ? filter_var($request->paginate, FILTER_VALIDATE_BOOLEAN) : true;
                $search     = $request->search ? trim($request->search) : null;

                // validate search payload
                $payload    = [
                    'search'    => $search,
                ];
                if( is_array($validate=$this->validateSearchPayload($payload)) ) {
                    return Controller::APIJsonReturn([array_key_first($validate) => $validate[array_key_first($validate)]], 'error', 400);
                }

                // start the query
                $users = User::query();

                // check if has search to filter
                if( $search ) {
                    $users->where(function ($query) use ($search) {
                        $query->orWhere('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
                }

                if( $isPaginate )   { $users = $users->orderBy('name', 'asc')->paginate($paginate); }  // paginate the result
                else                { $users = $users->orderBy('name', 'asc')->get(); }                // get all results

            } else {
                $users  = User::find($id);
                if( !$users ) {
                    return Controller::APIJsonReturn(['id' => 'User not found.'], 'error', 400);
                }
            }
            $data['users']  = $users;

            return Controller::APIJsonReturn($data, 'success');

        } catch (\Exception $e) {
            return Controller::APIJsonReturn(['errors' => $e->getMessage()], 'error', 400);
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());

        try {

            $user = User::create([
                'name' => trim($request->name),
                'email' => trim($request->email),
                'password' => Hash::make($request->password),
            ]);

            $authController = new AuthController();
            $auth = $authController->login($request);

            $data = [
                'auth' => $auth,
                'user' => $user,
            ];

            return Controller::APIJsonReturn($data, 'success', 201); // Use 201 for resource creation

        } catch (\Exception $e) {
            return Controller::APIJsonReturn(['errors' => $e->getMessage()], 'error', 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {

            $user = $request->user();
            if( !$user ) {
                return Controller::APIJsonReturn(['user' => 'User not found.'], 'error', 400);
            }

            $user->delete();
            $data['user']    = $user;
            return Controller::APIJsonReturn($data, 'success');

        } catch (\Exception $e) {
            return Controller::APIJsonReturn(['errors' => $e->getMessage()], 'error', 400);
        }
    }





     /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }



    /**
     * Validate the search payload.
     *
     * @param  array        $payload
     * @return true|array
     */
    private function validateSearchPayload(array $payload)
    {
        $search = (isset($payload['search'])) ? $payload['search'] : null;
        if( $search && strlen(trim($search)) < 3 ) {
            return ['search' => 'At least 3 characters must be setted.'];
        }
        return true;
    }

}
