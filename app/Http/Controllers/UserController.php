<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate = $request->has('limit') ? $request->limit : 10;
        $users = new User();

        if ($request->has('filter')) {
            // error_log('filter ' . $request->filter);
            $users = $users->where('title', 'like', '%' . $request->filter . '%');
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $from = date("m-d-y", $request->date_from);
            $to = date("m-d-y", $request->date_to);
            $users = $users->whereBetween('created_at', [$from, $to]);
        }
        if ($request->has('sort_desc')) {
            // error_log('order: desc');
            $users =  $users->orderBy($request->sort_desc, 'desc');
        } else
              if ($request->has('sort_by')) {
            // error_log('order: asc');
            $users =  $users->orderBy($request->sort_by, 'asc');
        }

        if ($paginate > 0) {
            $users = $users->paginate($paginate);
        } else {
            $users = $users->get();
        }


        return response()->json($users, 200);
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        //dd($validator);
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 401);

        // dd($validator);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        //error_log($request->input('senha'));

        // $credentials = $request->only(['email', 'senha']);

        //$user->token = auth('api')->attempt($credentials);
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $user->shippingData;
        $user->orders;
        return  $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name     = $request->input("name");
        $user->email    = $request->input("email");
        $user->save();
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
