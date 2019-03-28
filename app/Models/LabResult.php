<?php

namespace App\Models;

use App\Events\LabResultCreated;
use App\Facades\LabResultHashid;
use App\Mail\NewResultNotification;
use App\Models\Practice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class LabResult extends Model
{
    /**
     * Lab result status.
     *
     * @var string
     */
    const PROCESSED   = 'Processed';
    const UNPROCESSED = 'Unprocessed';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that fire off the events.
     * 
     */
    protected $dispatchesEvents = [
        'created' => LabResultCreated::class
    ];
    
    /**
     * Lab result belongs to a vet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id');
    }

    /**
     * Lab result belongs to a practice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function practice()
    {
        return $this->belongsTo(Practice::class);
    }

    /**
     * Vets processes the result via form/modal.
     *
     */
    public function process($comment, $indicator)
    {
        $this->update([
            'vet_id'        => auth()->id(),
            'vet_comment'   => $comment,
            'vet_indicator' => $indicator,
            'processed_at'  => $this->freshTimestamp(),
        ]);
    }

    /**
     * Query scope
     *
     * Returns all processed results.
     *
     * @param $query
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeProcessed($query)
    {
        return $query->whereNotNull('processed_at');
    }

    /**
     * Returns the lab results by its hash id.
     *
     * @param $hashid
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function scopeFindByHashid($query, $hashid)
    {
        return $query->where('hash_id', $hashid)->firstOrFail();
    }

    /**
     * Check it the Lab result is proccessed.
     *
     * @return boolean
     */
    public function isProcessed()
    {
        return $this->processed_at !== null;
    }

    /**
     *  Create a lab result using the results from the parsed csv file.
     *  
     *  @param CsvParser $parsedResults
     */
    public static function generateFrom($parsedResults)
    {
        $parsedResults->each(function ($result) {
            $labresult = self::create($result);
            $labresult->update([
                'practice_name' => Practice::name($result['practice_id']),
                'hash_id' => LabResultHashid::generateFor($labresult),
            ]);
        });
    }

    /**
     * Returns the status of the labresult - processed or unprocessed.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        return $this->isProcessed() ? self::PROCESSED : self::UNPROCESSED;
    }
}
