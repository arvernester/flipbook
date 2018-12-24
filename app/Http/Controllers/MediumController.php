<?php

namespace App\Http\Controllers;

use App\Medium;
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
            'title' => 'required|string',
            'medium' => 'required|file',
        ]);

        DB::transaction(function () use ($request) {
            $disk = 'public';
            $filename = sprintf('%s.%s', (string) Str::uuid(), $request->medium->extension());
            $directory = sprintf('books/%s', now()->format('Y/m'));

            $path = $request->medium->storeAs($directory, $filename, $disk);

            $medium = Medium::create([
                'path' => $path,
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
        return view('medium.show', compact('medium'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
