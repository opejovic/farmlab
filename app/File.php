<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class File extends Model
{
    protected $fillable = ['name', 'file_path'];

    /**
     * Upload the file from the request, put it in storage,
     * and if everything is ok, trigger the LabResult parseAndSave method.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload()
    {
        $file = request('csv_file');
        $fileName = $file->getClientOriginalName();

        if (!Storage::exists("labresults/{$fileName}")) {
            $filePath = Storage::putFileAs('labresults', $file, $fileName);

            $this->create([
                'name'      => $fileName,
                'file_path' => storage_path($filePath)
            ]);

            $labResult = new LabResult;
            $labResult->parseAndSave($file);

        } else {

            return redirect()->back()
                ->withErrors(["The {$fileName} already exists in the storage."]);
        }

        session()->flash('message', 'File successfully uploaded.');

    }
}
