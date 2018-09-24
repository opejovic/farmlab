<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    const PROCESSED = 'PROCESSED';
    const UNPROCESSED = 'UNPROCESSED';
    protected $guarded = [];

    /**
     * query scope
     *
     * @param        $query
     *
     * Return all unprocessed results (if not specified differently) for the practice of the currently auth user.
     *
     * @return mixed
     */
    public function scopeResults($query, $status = LabResult::UNPROCESSED)
    {
        return $query->latest('id')
            ->where('practice_id', auth()->user()->practice_id)
            ->where('status', $status);
    }

    /**
     * queryScope for the results that were created today.
     *
     * @return mixed
     */
    public function scopeToday($query)
    {
        return $query->where('date_of_test', today()->toDateString());
    }

    /**
     * @param $status (by default LabResult::UNPROCESSED, unless specified differently).
     *
     * Returns (UNPROCESSED, if not specified differently) results from today if there are any, if not returns all
     * results for the practice of a currently auth user.
     *
     * @return mixed
     */
    public function withStatus($status = LabResult::UNPROCESSED)
    {
        $unprocessedToday = $this->results($status)->today()->paginate(15);
        if ($unprocessedToday->isNotEmpty()) {
            return $unprocessedToday;
        }
        return $this->results($status)->paginate(15);

    }

    /**
     * Returns results based on their status (by default, returns Unprocessed (if there are any), upload date (if there
     * are any uploaded today), for the practice of the currently auth user.
     */
    public function fetchByStatus()
    {
        $results = $this->withStatus();
        if ($results->isEmpty()) {
            return $this->withStatus(LabResult::PROCESSED);
        }
        return $results;
    }

    /**
     * Returns all results for the practice  of the currently authenticated vet
     *
     * @return Collection
     */
    public function fetchAll()
    {
        return $this->where('practice_id',  auth()->user()->practice_id)
                ->latest('date_of_test')
                ->paginate(15);
    }

    /**
     * Returns the results for a farmer of the current practice
     *
     * @param $farmer
     *
     * @return Collection
     */
    public function fetchByFarmer($farmer)
    {
        return $this->where('farmer_name', $farmer)
            ->where('practice_id', auth()->user()->practice_id)
            ->latest()
            ->paginate(15);
    }

    /**
     * @param File $file
     *  Parse the result from the request()->file, and save it to DB.
     *  
     *
     */
    public function parseAndSave($file)
    {
        $handle = fopen($file, 'r');
        fgetcsv($handle);         // Adding this line will skip the reading of the 
        // first line from the csv file and the reading process will begin 
        // from the second line onwards. 
        // ((fgetcsv parses the first line (header) and returns an array 
        // with those columns. Implicitly, the file pointer is now on the 2nd row.))

        while (($column = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $this->create([
                'herd_number'     => $column[0],
                'date_of_arrival' => $column[1],
                'date_of_test'    => $column[2],
                'animal_id'       => $column[3],
                'lab_code'        => $column[4],
                'test_name'       => $column[5],
                'type_of_samples' => $column[6],
                'reading'         => $column[7],
                'interpretation'  => $column[8],
                'farmer_name'     => $column[9],
                'vet_comment'     => $column[10],
                'vet_indicator'   => $column[11],
                'practice_id'     => $column[12],
                'practice_name'   => Practice::name($column[12])
            ]);

            // For this to work, it needs a queue:listen command in terminal and .env file QUEUE_DRIVER set to database.
            // Need to refactor this -- use Eventing instead?
            // \Mail::to(App\User::wherePracticeId($data[12])->first()->email)->queue(new Welcome);
        }
        fclose($handle);
    }

    /**
     * @param form $request
     *
     * Vets processes the result through a form
     */
    public function process()
    {
        $this->whereId($this->id)
            ->update([
                'vet_comment'   => request('vet_comment'),
                'vet_indicator' => request('vet_indicator'),
                'vet_id'        => auth()->user()->id,
                'status'        => LabResult::PROCESSED
            ]);
        session()->flash('message', 'Lab result successfully processed');
    }
}