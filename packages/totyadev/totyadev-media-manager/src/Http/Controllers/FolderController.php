<?php

namespace TotyaDev\TotyaDevMediaManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TotyaDev\TotyaDevMediaManager\Http\Resources\FolderResource;
use TotyaDev\TotyaDevMediaManager\Http\Resources\FoldersResource;
use TotyaDev\TotyaDevMediaManager\Http\Resources\MediaResource;
use TotyaDev\TotyaDevMediaManager\Models\{Folder, Model};

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $folders = Folder::query();

        if ($request->has('search')) {
            $folders->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'data' => config('totyadev-media-manager.api.resources.folders')::collection($folders->paginate(10))
        ], 200);
    }

    public function show(int $id)
    {
        $folder = Folder::query()->findOrFail($id);

        return response()->json([
            'data' => config('totyadev-media-manager.api.resources.folder')::make($folder)
        ], 200);
    }
}
