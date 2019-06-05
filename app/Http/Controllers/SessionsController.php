<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
class SessionsController extends Controller
{
  
    
    public function store()
    {
        if (auth()->attempt(request(['email', 'password'])) == false) {
            return response("user not found",404);
        }
        
        return response("succesfully logged in!",200);
    }
    
    public function destroy()
    {
        auth()->logout();
        
        return response("succesfully logged out!",200);;
    }
}