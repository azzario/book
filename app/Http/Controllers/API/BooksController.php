<?php

namespace App\Http\Controllers\API;

use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::with('users')->orderBy('created_at', 'desc')->get();
        $count = count($books);

        return response()->json([
            'books' => $books,
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
        $user_id = $request->user_id;

        $q = Book::where('user_id', '=', $user_id)->get();
        $count = count($q);

        if($count > 2) {
            return response()->json([
                'message' => 'Maaf, anda harus punya maksimal 3 buku'
            ], 400);
        } else {
            Book::create($request->all());

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
        $qFindOldId = Book::findOrFail($id);
        
        $old_user_id = $qFindOldId->user_id;
        $new_user_id = $request->user_id;

        $q = Book::where('user_id', '=', $new_user_id)->get();
        $count = count($q);

        if($old_user_id !== $new_user_id) {
            $c = 2;
        } else {
            $c = 3;
        }

        if($count > $c) {
            return response()->json([
                'message' => 'Maaf, anda harus punya maksimal 3 buku'
            ], 400);
        } else {
            Book::where('id', '=', $request->id)->update($request->all());

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
        Book::findOrFail($id)->delete();
        
        return response()->json([
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
