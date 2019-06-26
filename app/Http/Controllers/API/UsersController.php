<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withCount('books')->orderBy('created_at', 'desc')->get();
        $count = count($users);

        return response()->json([
            'users' => $users,
            'count' => $count
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->umur < 17) {
            return response()->json([
                'message' => 'Mohon maaf, belum cukup umur'
            ], 400);
        } else {
            User::create($request->all());

            return response()->json([
                'message' => 'Data berhasil ditambah'
            ], 200);
        }
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
        if($request->umur < 17) {
            return response()->json([
                'message' => 'Mohon maaf, belum cukup umur'
            ], 400);
        } else {
            User::where('id', '=', $request->id)->update($request->all());

            return response()->json([
                'message' => 'Data berhasil diubah'
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        
        return response()->json([
            'message' => 'Data berhasil dihapus'
        ], 200);
        
    }
}
