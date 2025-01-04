<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserResource;
use Vinkla\Hashids\Facades\Hashids;

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
                $decodedId = Hashids::decode($id);
                if( !count($decodedId) ) {
                    return Controller::APIJsonReturn(['id' => 'User not found.'], 'error', 400);
                }
                $users  = User::find($decodedId[0]);
                if( !$users ) {
                    return Controller::APIJsonReturn(['id' => 'User not found.'], 'error', 400);
                }
            }

            // $data['users']  = $users;
            if ($users instanceof \Illuminate\Database\Eloquent\Collection || $users instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $data['users'] = UserResource::collection($users); // Paginated or non-paginated collection
            } else {
                $data['users'] = new UserResource($users); // Single user (in case of non-paginated result with one user)
            }

            return Controller::APIJsonReturn($data, 'success');

        } catch (\Exception $e) {
            return Controller::APIJsonReturn(['errors' => $e->getMessage()], 'error', 400);
        }

    }


    /**
     * Retrieve the authenticated user's information.
     *
     * This method returns the data of the currently authenticated user. It
     * uses the API token passed in the Authorization header to identify the
     * user, and responds with the user's information in a structured format.
     * 
     * @authenticated
     * @param \Illuminate\Http\Request $request The incoming HTTP request, including the authenticated user.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the authenticated user's data.
     *
     * @throws \Illuminate\Auth\AuthenticationException If the user is not authenticated.
     */
    public function findMe(Request $request)
    {
        $user = $request->user();
        return Controller::APIJsonReturn(['user' => new UserResource($user)], 'success');
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
     * Update the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate($this->rulesEdit());

        try {

            $user = $request->user();
            if( !$user ) {
                return Controller::APIJsonReturn(['user' => 'User not found.'], 'error', 400);
            }

            $user->name = $request->name;

            // If password is provided and matches confirmation, update password
            if ($request->has('password') && $request->password) {
                $user->password = bcrypt($request->password); // Hash password before saving
            }

            $user->save();
            $data['user'] = new UserResource($user);
            return Controller::APIJsonReturn($data, 'success');

        } catch (\Exception $e) {
            return Controller::APIJsonReturn(['errors' => $e->getMessage()], 'error', 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * Validation rules
     */
    public function rulesEdit()
    {
        return [
            'name' => 'required|string|min:3',
            'password' => 'nullable|string|min:4',
            'password_confirmation' => 'nullable|string|same:password|required_with:password',
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
