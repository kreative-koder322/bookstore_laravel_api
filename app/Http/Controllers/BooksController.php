<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
class BooksController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('jwt.auth');
    // }

    public function index()
    {
        $Books = Books::all();
        return response()->json([
            'status' => 'success',
            'Bookss' => $Books,
        ]);
    }

    public function store(Request $request)
    {
       
        $rules = array(
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|integer|min:6',
            'published_at' => 'required|date',
            'copies' => 'required|integer',
           ); 
    
           $validator = \Validator::make( $request->all(), $rules );
    
           if ($validator->fails()) 
           {        
               return response()->json([
                   'status' => 'failed',
                   'message' => 'All fields are required',
                   'errors'=> $validator->errors()->all(),
               ]);  
           }
           else{

            $Books = Books::create([
                'title' => $request->title,
                'author' => $request->author,
                'isbn' => $request->isbn,
                'copies' => $request->copies,
                'published_at' => $request->published_at,
            ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Book created successfully',
                    'Books' => $Books,
                ]);
         }
    }

    public function show($id)
    {
        $Book = Books::find($id);
        return response()->json([
            'status' => 'success',
            'Book' => $Book,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_at' => 'required|date',
            'copies' => 'required|integer',
            'isbn' => 'required|integer|min:6',
           ); 
    
           $validator = \Validator::make( $request->all(), $rules );
    
           if ($validator->fails()) 
           {        
               return response()->json([
                   'status' => 'failed',
                   'message' => 'All fields are required',
                   'errors'=> $validator->errors()->all(),
               ]);  
           }
           else{
                $Books = Books::find($id);
                if($Books){
                    $Books->title = $request->title;
                    $Books->author = $request->author;
                    $Books->isbn = $request->isbn;
                    $Books->published_at = $request->published_at;
                    $Books->copies = $request->copies;
                    $Books->save();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Books updated successfully',
                        'Books' => $Books,
                    ]);
                }
                else{
                    return response()->json([
                        'status' => 'failure',
                        'message' => 'Book not found',
                    ]);
            
                }
            

       
            }
    }

    public function destroy($id)
    {
        $Books = Books::find($id);
        if($Books){
            $Books->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Books deleted successfully',
                'Books' => $Books,
            ]);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Id not found',
            ]);
        }
    }
  
}
