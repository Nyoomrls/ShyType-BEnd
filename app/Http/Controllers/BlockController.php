<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;

class BlockController extends Controller
{
    //
    public function blockUser(Request $request)
    {
        // $block = Block::create([
        //     "blockerID" => $request->blockerID,
        //     "blockedID" => $request->blockedID,
        // ]);
        // $block->save();

        // $BlockerID = Block::where('blockerID', $blockerID)->get();
        // $fields = Validator::make($request->all(), [
        //     'blockerID' => ['required', 'integer'],
        //     'blockedID' => ['required', 'integer'],
        // ]);

        // $block = Block::create([
        //     "blockerID" => $request->blockerID['blockerId'],
        //     "blockedID" => $request->blockedID['blockedId'],
        // ]);
        // $Newblock = $block->save();

        // return [
        //     "message" => "TesT",
        //     "data" => $Newblock,
        //     "status" => 300,
        // ];
        return[
            "message" => "dito na ikaw bous",
            // "data" => $blockedID,
            // "data" => blocked,    
            "status" => 300,
        ];
    }
}
