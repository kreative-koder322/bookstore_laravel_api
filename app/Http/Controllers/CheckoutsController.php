<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkouts;
use App\Models\Books;
class CheckoutsController extends Controller
{
    public function checkouts(Request $request)
    {
        $rules = array(
            'user_id' => 'required||exists:users,id',
            'book_id' => 'required|string||exists:books,id',
            'checkout_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:today',
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

            $Books = Checkouts::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'checkout_date' => $request->checkout_date,
                'return_date' => $request->return_date,
            ]);

            if($Books){
                $Books = Books::where('id',$request->book_id)->get('copies');
                $data = json_decode($Books);
                for($i=1;$i<=$data[0]->copies;$i++){
                    $copies= $data[0]->copies - 1;
                    $update = Books::where('id',$request->book_id)->update(['copies'=>$copies]);
                    }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Book created successfully',
                'Books' => $Books,
            ]);
         }
    }
    public function return(Request $request, $id)
    {
        $rules = array(
            'user_id' => 'required||exists:users,id',
            'book_id' => 'required|string||exists:books,id',
            'checkout_date' => 'required|date',
            'return_date' => 'required|date',
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
                $Checkouts = Checkouts::find($id);
                if($Checkouts){
                    $Checkouts->user_id = $request->user_id;
                    $Checkouts->book_id = $request->book_id;
                    $Checkouts->checkout_date = $request->checkout_date;
                    $Checkouts->return_date = $request->return_date;
                    $Checkouts->save();



                    if($Checkouts){
                        $Books = Books::where('id',$request->book_id)->get('copies');
                        $data = json_decode($Books);
                        for($i=0;$i<=$data[0]->copies;$i++){
                        $copies= $i + 1;
                        $update = Books::where('id',$request->book_id)->update(['copies'=>$copies]);
                        }
                  
                      
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Checkouts updated successfully',
                        'Checkouts' => $Checkouts,
                        'Copies'=> $copies
                    ]);
                }
                else{
                    return response()->json([
                        'status' => 'failure',
                        'message' => 'id not found',
                    ]);
            
                }
            }
    }
}
