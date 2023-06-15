<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use Termwind\Components\Dd;

class BlockController extends Controller
{
    //
    public function blockUser(Request $request)
    {

        $block = Block::create([
            "blockerID" => $request->blockerID,
            "blockedID" => $request->blockedID,
        ]);
        $block->save();
        return [
            "blockerID" => $request->blockerID,
            "blockedID" => $request->blockedID,
            "message" => "Napupunta ka sa Back-End pero may prob sa database",
            "status" => 200,
            // "data" => $block,
            // "data" => blocked,    
        ];

        // $BlockerID = Block::where('blockerID', $blockerID)->get();
        // $fields = Validator::make($request->all(), [
        //     'blockerID' => ['required', 'integer'],
        //     'blockedID' => ['required', 'integer'],
        // ]);

        // $block = Block::insert([
        //     "blockerID" => $request->blockerID['blockerID'],
        //     "blockedID" => $request->blockedID['blockedID'],
        // ]);
        // $block->save();

        // return [
        //     "message" => "TesT",
        //     "data" => $Newblock,
        //     "status" => 300,
        // ];
    }
}
