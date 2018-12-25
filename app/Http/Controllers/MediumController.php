<?php

namespace App\Http\Controllers;

use App\Medium;
use App\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $media = Medium::orderByDesc('created_at')
            ->with('pages')
            ->paginate(12);

        return view('medium.index', compact('media'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('medium.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'description' => 'required|string|max:2000',
            'image' => 'required|file|image',
            'medium' => 'required|file',
        ]);

        DB::transaction(function () use ($request) {
            $disk = 'public';
            $filename = sprintf('%s.%s', (string) Str::uuid(), $request->medium->extension());
            $imageFilename = sprintf('%s.%s', (string) Str::uuid(), $request->image->extension());
            $directory = now()->format('/Y/m');

            $path = $request->medium->storeAs('books' . $directory, $filename, $disk);
            $imagePath = $request->image->storeAs('images' . $directory, $imageFilename, $disk);

            $medium = Medium::create([
                'path' => $path,
                'image_path' => $imagePath,
                'title' => $request->title,
                'description' => $request->description ?? '',
                'disk' => $disk,
                'metadata' => [],
            ]);
        });

        return redirect()->route('media.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Medium $medium)
    {
        $medium->load('pages');

        $pages = [];
        foreach ($medium->pages as $page) {
            $pages[] = [$page->title, (int) $page->page];
        }

        return view('medium.show', compact('medium', 'pages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Medium $medium
     * @return View
     */
    public function edit(Medium $medium): View
    {
        $medium->load('pages');

        return view('medium.edit', compact('medium'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Medium $medium
     * @return RedirectResponse
     */
    public function update(Request $request, Medium $medium): RedirectResponse
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'description' => 'required|string|max:2000',
            'image' => 'nullable|image',
            'file' => 'nullable|file',
            'titles' => 'array',
            'pages' => 'array',
        ]);

        DB::transaction(function () use ($medium, $request) {
            $disk = 'public';
            $directory = now()->format('/Y/m');

            if ($request->file) {
                $media->path = $request->file->storeAs(
                    'books' . $directory,
                    sprintf('%s.%s', (string) Str::uuid(), $request->file->extension()),
                    $disk
                );
            }

            if ($request->image) {
                $medium->image_path = $request->image->storeAs(
                    'images' . $directory,
                    sprintf('%s.%s', (string) Str::uuid(), $request->image->extension()),
                    $disk
                );
            }

            $medium->disk = $disk;
            $medium->title = $request->title;
            $medium->description = $request->description;
            $medium->save();

            if (count($request->titles) >= 1) {
                Page::whereMediumId($medium->id)->delete();

                foreach ($request->titles as $index => $title) {
                    if (!empty($request->pages[$index]) and !empty($title)) {
                        Page::create([
                            'medium_id' => $medium->id,
                            'title' => $title,
                            'page' => $request->pages[$index],
                        ]);
                    }
                }
            }
        });

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Medium $medium
     * @return RedirectResponse
     */
    public function destroy(Medium $medium): RedirectResponse
    {
        $medium->delete();

        return redirect()->route('media.index');
    }

    public function pages(Request $request, Medium $medium): JsonResponse
    {
        $medium->load('pages');

        if ($request->preformat) {
            return response()->json($medium->pages->map(function ($page) {
                return [$page->title, (int) $page->page];
            }));
        }

        return response()->json($medium->pages);
    }
}
