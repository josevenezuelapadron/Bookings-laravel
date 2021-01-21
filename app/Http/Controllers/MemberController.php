<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function index(Request $request) {
        $validated = Validator::make($request->all(), [
            'joinAfter' => 'date',
            'joinBefore' => 'date|after:joinAfter'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors()->first(), 400);
        } else {

            $members = Member::query();
            if ($request->has('joinAfter')) {
                $members = $members->whereDate('joindate', '>=', $request->joinAfter);
            }

            if ($request->has('joinBefore')) {
                $members = $members->whereDate('joindate', '<=', $request->joinBefore);
            }

            return $members->orderBy('memid')->get();
        }
    }

    public function reservations($id) {
        return Booking::whereHas('member', function ($query) use ($id) {
            $query->where('memid', '=', $id);
        })->orderBy('bookid')->get();
    }

    public function show($id) {
        return Member::where('memid', '=', $id)->get();
    }

    public function store(Request $request) {
        $validated = Validator::make($request->all(), [
            'surname' => 'required|string|min:3|max:255',
            'firstname' => 'required|string|min:3|max:255',
            'address' => 'required|string|min:3|max:255',
            'zipcode' => 'required|integer|min:3',
            'telephone' => 'required|min:3',
            'recommendedby' => 'integer|exists:members,memid',
            'createdby' => 'required|integer|exists:users,userid'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors()->first(), 400);
        } else {
            $member = Member::create($request->all());

            return response()->json($member, 201);
        }
    }

    public function update(Request $request, $id) {
        $validated = Validator::make($request->all(), [
            'surname' => 'string|min:3|max:255',
            'firstname' => 'string|min:3|max:255',
            'address' => 'string|min:3|max:255',
            'zipcode' => 'integer|min:3',
            'telephone' => 'min:3',
            'recommendedby' => 'integer|exists:members,memid'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors()->first(), 400);
        } else {
            Member::findOrFail($id)->update($request->all());

            return response()->json(Member::findOrFail($id), 200);
        }
    }

    public function delete($id) {
        Member::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}