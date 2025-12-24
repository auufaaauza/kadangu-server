<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Get user's wishlist
     */
    public function index(Request $request)
    {
        $wishlists = Wishlist::where('user_id', $request->user()->id)
            ->with(['talent', 'pertunjukan.artistGroup'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $wishlists,
        ]);
    }

    /**
     * Add item to wishlist
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_type' => 'required|in:talent,show',
            'item_id' => 'required|integer',
        ]);

        // Map item_type to database column names
        $data = [
            'user_id' => $request->user()->id,
        ];

        if ($validated['item_type'] === 'talent') {
            $column = 'talent_id';
            $data['talent_id'] = $validated['item_id'];
        } else {
            $column = 'pertunjukan_id';
            $data['pertunjukan_id'] = $validated['item_id'];
        }

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', $request->user()->id)
            ->where($column, $validated['item_id'])
            ->first();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Item already in wishlist',
            ], 400);
        }

        $wishlist = Wishlist::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist',
            'data' => $wishlist,
        ], 201);
    }

    /**
     * Remove item from wishlist
     */
    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist item not found',
            ], 404);
        }

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist',
        ]);
    }

    /**
     * Check if item is in wishlist
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'item_type' => 'required|in:talent,show',
            'item_id' => 'required|integer',
        ]);

        $column = $validated['item_type'] === 'talent' ? 'talent_id' : 'pertunjukan_id';

        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->where($column, $validated['item_id'])
            ->first();

        return response()->json([
            'success' => true,
            'inWishlist' => $wishlist !== null,
            'wishlistId' => $wishlist?->id,
        ]);
    }
}
