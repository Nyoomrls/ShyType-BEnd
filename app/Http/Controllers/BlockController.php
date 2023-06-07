<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;

class BlockController extends Controller
{
    //
    public function blockUser(Request $request)
    {
        // $blockerID = $request->blocker;
        // $blockedID = $request->blocked;
        // $BlockerID = Block::where('blockerID', $blockerID)->get();
        $block = Block::create([
            "blockerID" => $request->blocker['blockerId'],
            "blockedID" => $request->blocked['blockedId'],
        ]);
        $block->save();
    }
}
