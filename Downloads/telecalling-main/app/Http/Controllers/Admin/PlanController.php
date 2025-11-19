<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function addPlan()
    {
        $mode = 'add';
        return view('admin.menu-pages.add-plan', compact('mode'));
    }
    public function storeType(AddTypeRequest $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $Type = Type::create([
                    "category_id" => $request->category_id,
                    'name' => $request->name,
                    "status" => $request->status,
                ]);

                if ($Type) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Successfully Type added.',
                        'redirect_url' => route('type.list'),
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()]);
        }
    }

    // Update Product
    public function updateType(Request $request)
    {
        try {
            if ($request->ajax()) {
                $type = Type::findOrFail($request->type_id);
                if ($type) {
                    $rules = [
                        'name' => 'required',
                        'category_id' => 'required|exists:categories,id',
                    ];
                    $validator = Validator::make($request->all(), $rules);

                    if ($validator->fails()) {
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors(),
                        ], 422);
                    }

                    $type->update([
                        'name' => $request->name,
                        'category_id' => $request->category_id,
                        'status' => $request->status,
                    ]);


                    return response()->json([
                        'status' => true,
                        'message' => 'Type updated successfully.',
                        'redirect_url' => route('type.list'),
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Product not found.',
                    ]);
                }
            }

            $mode = 'edit';
            $type = Type::find($request->id);
            $categories = Category::where('status', 1)->get(['id', 'category_name']);
            return view('admin.menu-pages.add-type', compact('mode', 'type', 'categories'));
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()]);
        }
    }


    public function typeList()
    {
        return view('admin.menu-pages.type-list');
    }

    public function fetchTypeList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $types = Type::with('category:id,category_name')
                    ->select('id', 'category_id', 'name', 'status', 'created_at')
                    ->latest();

                return DataTables::of($types)
                    ->addIndexColumn()

                    // Category Name
                    ->addColumn('category_name', function ($types) {
                        return optional($types->category)->category_name ?? 'N/A';
                    })

                    // Created At (Formatted)
                    ->editColumn('created_at', function ($types) {
                        return Carbon::parse($types->created_at)->format('d-M-Y');
                    })

                    // Status Column
                    ->addColumn('status', function ($types) {
                        $statusClass = $types->status ? 'bg-label-success' : 'bg-label-secondary';
                        $statusText = $types->status ? 'Active' : 'Inactive';
                        return '<div class="change-statusss" data-id="' . $types->id . '">
                                <span class="badge ' . $statusClass . '">' . $statusText . '</span>
                            </div>';
                    })

                    // Actions Dropdown
                    ->addColumn('action', function ($types) {
                        $editRoute = route("update.type", ["id" => $types->id]);
                        return '
                    <div class="dropdown">
                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item types_edit_btn" href="' . $editRoute . '">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </a></li>
                            <li><a class="dropdown-item type_delete_btn" href="javascript:void(0);" data-id="' . $types->id . '">
                                <i class="bx bx-trash me-1"></i> Delete
                            </a></li>
                        </ul>
                    </div>';
                    })

                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }

            return response()->json(['status' => false, 'message' => 'Invalid request.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }

    public function deleteType()
    {
        try {
            if (request()->ajax()) {
                $type = Type::find(request()->typeId);
                $type->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Type deleted successfully',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function changeTypeStatus()
    {
        try {
            if (request()->ajax()) {
                $type = Type::find(request()->typeId);
                $type->status = !$type->status;
                $type->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Type status updated successfully',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}