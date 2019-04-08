<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Company; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class CompanyController extends Controller 
{
	public $successStatus = 200;

	public function index () {
        return Company::all();
    }

	public function store (Request $request) {
		$validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'cnpj' => 'required',  
            'address' => 'required',  
        ]); 
        if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        if(validate_cnpj($input['cnpj'])) {
        	$input['cnpj'] = Mask("##.###.###/####-##", preg_replace('/[^0-9]/', '', $input['cnpj']));
        	$checkCnpj = Company::where('cnpj', '=', $input['cnpj'])->first();

	        if ($checkCnpj !== null) {
	            return response()->json(['error'=> 'CNPJ is already registred!'], 401);
	        } else {
	            $input = $request->all();
	        	$company = Company::create($input); 
	        	return response()->json(['success'=>$company], $this->successStatus);
	        }
	    } else {
	    	return response()->json(['error'=> 'CNPJ is not valid!'], 401);
	    }
	}

	public function show ($id) {
		$company = Company::find($id);
		return response()->json(['success'=>$company], $this->successStatus);
	}

	public function update (Request $request, $id) {
		$company = Company::find($id);

		if($company) {
			$validator = Validator::make($request->all(), [ 
	            'name' => 'required',
	            'cnpj' => 'required',  
	            'address' => 'required',  
	        ]); 
	        if ($validator->fails()) {          
	            return response()->json(['error'=>$validator->errors()], 401);
	        }
	        $input = $request->all();
	        if(validate_cnpj($input['cnpj'])) {
	        	$input['cnpj'] = Mask("##.###.###/####-##", preg_replace('/[^0-9]/', '', $input['cnpj']));
	        	$checkCnpj = Company::where('cnpj', '=', $input['cnpj'])->where('cnpj', '!=', $company->cnpj)->first();

		        if ($checkCnpj !== null) {
		            return response()->json(['error'=> 'CNPJ is already registred!'], 401);
		        } else {
		            $input = $request->all();
		        	$company->name =  $input['name'];
			        $company->cnpj = $input['cnpj'];
			        $company->address = $input['address'];
			        $company->save();
		        	return response()->json(['success'=>$company], $this->successStatus);
		        }
		    } else {
		    	return response()->json(['error'=> 'CNPJ is not valid!'], 401);
		    }
		} else {
			return response()->json(['error'=> 'Company is not exists!'], 401);
		}
	}

	public function destroy (Request $request, $id) {
		$company = Company::findOrFail($id);
        $company->delete();

        return response()->json(null, 204);
	}

	public function users ($id) {
    	$company = Company::find($id);
    	if($company) {
    		$users = $company->users;
        	$users = ['users' => $company->users];
        	return response()->json(['success' => $users], $this->successStatus);
        } else {
        	return response()->json(['error'=> 'Company is not exists!'], 401);
        }
 	}

 	public function storeUser (Request $request, $id) {
        $company = Company::find($id);
        if($company) {
            $input = $request->all();

            $user = User::find(preg_replace('/[^0-9]/', '', $input['user_id']));
            if($user) {
                $company->companies()->save($user);
                return response()->json(['success' => $company], $this->successStatus);
            } else {
                return response()->json(['error'=> 'User is not exists!'], 401);
            }
        } else {
            return response()->json(['error' => 'Company is not exists!'], 401);
        }
    }
}
