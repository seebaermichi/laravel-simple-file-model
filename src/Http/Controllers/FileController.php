<?php

namespace MichaelBecker\SimpleFile\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MichaelBecker\SimpleFile\Models\File;

class FileController
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, $disk, $folder, $name)
    {
        $file = File::where('disk', $disk)->where('path', $folder)->where('name', $name)->first();

        if ($file === null) {
            abort(404);
        }

        $storagePath = Storage::disk($disk)->path($file->getFullPath());
        $contentDisposition = $file->isPreviewable() ? 'inline' : 'attachment';

        return response()->file($storagePath, [
            'Content-Disposition' => $contentDisposition . '; filename="' . basename($file->name) . '"',
            'Content-Type' => mime_content_type($storagePath),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $file->delete();

        session()->flash('success', __('File successfully deleted.'));

        return redirect()->back();
    }
}
