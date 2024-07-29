<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    public function index()
    {
        $staff = Staff::all();
        return view('frontend.home.index', compact('staff'));
    }
    public function staffdataTable(DataTables $dataTables)
    {
        $staff = Staff::query(); // Get all staff data

        return $dataTables->eloquent($staff)
            ->editColumn('created_at', function ($staff) {
                return $staff->created_at->format('d M g:ia'); // Format: 29 Jul 5:54pm
            })
            ->addColumn('action', function ($staff) {
                return '<button class="btn btn-info btn-edit" data-id="' . $staff->id . '" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                        <button class="btn btn-danger btn-delete" data-id="' . $staff->id . '">Delete</button>';
            })
            ->toJson();
    }

    public function edit($id)
    {
        $staff = Staff::find($id);
        return response()->json($staff);
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);
        $staff->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $staff = Staff::find($id);
        if ($staff) {
            $staff->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }


}
