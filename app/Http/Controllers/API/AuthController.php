<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Http\Controllers\API\BaseController;

use Validator;

class AuthController extends BaseController
{
    /**
     * Register API user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        /**
         * Validation of transmitted parameters.
         */
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'required|email',
            'password'   => 'required',
            'c_password' => 'required|same:password',
        ]);

        /**
         * Sending error response if the validation fails.
         */
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        /**
         * Request parameters.
         * Building password secuirity using `bcrypt`.
         */
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        /**
         * Create user.
         */
        $user = User::create($input);

        /**
         * Success result.
         */
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name']  = $user->name;

        return $this->sendResponse($success, 'User register successfuly');
    }

    /**
     * Login API user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        /**
         * Trying to authorize.
         */
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            /**
             * Authorized user.
             */
            $user = Auth::user();

            /**
             * Success result.
             */
            $success['token'] = $user->createToken('MyApp')-> accessToken; 
            $success['name']  = $user->name;

            return $this->sendResponse($success, 'User login successfully');
        } else {
            return $this->sendError('Unauthorized', ['error' => 'Unauthorized']);
        }
    }

    /**
     * Logout API user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        /**
         * User logout
         */
        Auth::logout();

        return $this->sendResponse([], 'User logout successfully');
    }
}