<?php 
namespace App\Http\Controllers\Auth; 

use App\Http\Controllers\Controller; 
use App\Providers\RouteServiceProvider; 
use Illuminate\Foundation\Auth\AuthenticatesUsers; 
use Illuminate\Support\Facades\Mail; 

class RegisterController extends Controller 
{ 

    use AuthenticatesUsers; 

    protected function create(array $data) 
    { 
      $data['confirmation_code'] = '12345'; 
      $user = User::create([ 
        'name' => $data['name'], 
        'email' => $data['email'], 
        'password' => bcrypt($data['password']), 
        'confirmation_code' => $data['confirmation_code'] 
      ]); 
    
      return $user; 
    }

    public function verify($code) 
    { 
        $user = User::where('confirmation_code', $code)->first(); 

        if (! $user){ 
            return redirect('/'); 
        } 

        $user->confirmed = true; 
        $user->confirmation_code = null; 
        $user->save(); 

        return redirect('/home')->with('notification', ' You have successfully confirmed your email! '); 
    } 
}