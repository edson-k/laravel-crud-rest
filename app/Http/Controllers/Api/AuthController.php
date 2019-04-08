<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class AuthController extends Controller 
{
    public $successStatus = 200;

    public function register(Request $request) {    
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'login' => 'required',
            'cpf' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'password' => 'required',  
            'c_password' => 'required|same:password', 
        ]); 
        if ($validator->fails()) {          
            return response()->json(['error' => $validator->errors()], 401);
        }    
        $input = $request->all();
        $input['cpf'] = Mask("###.###.###-##", preg_replace('/[^0-9]/', '', $input['cpf']));

        $checkEmail = User::where('email', '=', $input['email'])->first();
        $checkLogin = User::where('login', '=', $input['login'])->first();
        $checkCpf = User::where('cpf', '=', $input['cpf'])->first();

        if ($checkEmail !== null) {
            return response()->json(['error' => 'E-Mail is already registred!'], 401);
        } else if($checkLogin !== null) {
            return response()->json(['error' => 'Login is already registred!'], 401);
        } else if($checkCpf !== null) {
            return response()->json(['error' => 'CPF is already registred!'], 401);
        } else {
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input); 
            $success['token'] =  $user->createToken('AppName')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
    }

    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('AppName')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } else if(Auth::attempt(['login' => request('login'), 'password' => request('password')])){  
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('AppName')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } else {
            return response()->json(['error' => 'Unauthorised'], 401); 
        } 
    }

    public function update(Request $request, $id) {
        $user = User::find($id);

        if($user) {
            $validator = Validator::make($request->all(), [ 
                'name' => 'required',
                'login' => 'required',
                'cpf' => 'required',
                'address' => 'required',
                'email' => 'required|email',
                'password' => 'required',  
                'c_password' => 'required|same:password', 
            ]); 
            if ($validator->fails()) {          
                return response()->json(['error' => $validator->errors()], 401);
            }    
            $input = $request->all();
            $input['cpf'] = Mask("###.###.###-##", preg_replace('/[^0-9]/', '', $input['cpf']));

            $checkEmail = User::where('email', '=', $input['email'])->where('email', '!=', $user->email)->first();
            $checkLogin = User::where('login', '=', $input['login'])->where('login', '!=', $user->login)->first();
            $checkCpf = User::where('cpf', '=', $input['cpf'])->where('cpf', '!=', $user->cpf)->first();

            if ($checkEmail !== null) {
                return response()->json(['error' => 'E-Mail is already registred!'], 401);
            } else if($checkLogin !== null) {
                return response()->json(['error' => 'Login is already registred!'], 401);
            } else if($checkCpf !== null) {
                return response()->json(['error' => 'CPF is already registred!'], 401);
            } else {
                $input['password'] = bcrypt($input['password']);
                $user->name =  $input['name'];
                $user->login =  $input['login'];
                $user->cpf = $input['cpf'];
                $user->address = $input['address'];
                $user->email = $input['email'];
                $user->password = $input['password'];
                $user->save();
                return response()->json(['success' => $user], $this->successStatus);
            }
        } else {
            return response()->json(['error' => 'User is not exists!'], 401);
        }
    }

    public function destroy (Request $request, $id) {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    public function show ($id) {
        $user = User::find($id);
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function companies ($id) {
        // $user = Auth::user();
        // $company = Company::find(3);
        // $user->companies()->save($company);

        $user = User::find($id);
        if($user) {
            $companies = ['companies' => $user->companies];
            return response()->json(['success' => $companies], $this->successStatus);
        } else {
            return response()->json(['error' => 'User is not exists!'], 401);
        }
    }

    public function getUser() {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus); 
    }
} 