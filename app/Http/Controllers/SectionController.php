<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Section;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Random\Engine\Secure;
use Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $sections = Section::orderBy('created_at', 'desc')->with('packages')->get();
        foreach ($sections as $section) {
            $section->packages;
        }
        return view('sections.index', compact('sections'));
    }

    public function create()
    {
        $packages = Package::where('is_online', 1)->get();

        return view('sections.create', compact('packages'));
    }

    public function validateData(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'name_ar' => 'required',
            'description' => 'required',
            'type' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'package_ids' => 'required|array',
            'package_ids.*' => 'exists:packages,id',
        ], [
            'package_ids.required' => 'At least one package must be selected.',
            'package_ids.array' => 'Package IDs must be an array.',
            'package_ids.*.exists' => 'One or more selected packages are invalid.',
        ]);

        return $data;
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('img')) {
            $data['img'] = $this->uploadImage($request->file('img'), 'images/sectionImages');
        }

        $section = Section::create($data);

        $section->packages()->attach($data['package_ids']);

        return redirect()->route('sections.index')->with('success', 'Section was created successfully');
    }

    public function edit($uuid)
    {
        $section = Section::where('uuid', $uuid)->first();
        $packages = Package::where('is_online', 1)->get();

        return view('sections.edit', compact('section', 'packages'));
    }

    public function update(Request $request, $uuid)
    {
        $section = Section::where('uuid', $uuid)->first();
        $data = $this->validateData($request);

        $is_online = ['is_online' => $request->has('is_online') ? 1 : 0];

        $finalData = array_merge($data, $is_online);

        if ($request->hasFile('img')) {
            $this->deleteOldImage($section->img, 'images/sectionImages');
            $finalData['img'] = $this->uploadImage($request->file('img'), 'images/sectionImages');
        }

        $section->update($finalData);

        $section->packages()->sync($data['package_ids']);

        return redirect()->route('sections.index')->with('success', 'Section was updated successfully');
    }
    public function destroy($uuid)
    {
        $section = Section::where('uuid', $uuid)->firstOrFail();
        $section->delete();

        return redirect()->route('sections.index')->with('success', 'Section deleted successfully.');
    }
    // display stores that belong to a certain section

    public function showStores($uuid)
    {

        $section = Section::where('uuid', $uuid)->first();

        $stores = $section->stores()->orderBy('points', 'desc')->get();
        return view('sections.showStores', compact('section', 'stores'));
    }

    // return the view to attach a store to a section

    public function showAttachStore($uuid)
    {
        $section = Section::where('uuid', $uuid)->first();
        $stores = Store::whereNull('section_id')->get();

        return view('sections.showAttachStore', compact('section', 'stores'));
    }

    // attach a store to a section

    public function attachStore(Request $request, $uuid)
    {
        $store = Store::findOrFail($request->input('store_id'));
        $section = Section::where('uuid', $uuid)->first();

        if ($store) {

            $store->section_id = $section->id;
            $store->save();

            return redirect()->route('sections.showStores', $uuid);
        } else {

            throw new NotFoundHttpException();
        }
    }


    // private function uploadImage($image, $path)
    // {
    //     $imageName = time() . '.' . $image->getClientOriginalExtension();
    //     $image->move(public_path($path), $imageName);
    //     return $path . '/' . $imageName;
    // }

    /**
     * Uploads a new image and returns the file name.
     */
    private function uploadImage(UploadedFile $image, string $directory): string
    {
        $imageName = hash('sha256', time() . Str::random(10)) . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path($directory);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $image->move($destinationPath, $imageName);
        return $imageName;
    }

    private function deleteOldImage($imagePath, $basePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
    }
}
