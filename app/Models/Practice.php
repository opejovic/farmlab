<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vets()
    {
        return $this->hasMany(User::class, 'practice_id');
    }

    /**
     * Practice has a creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Returns the name of the practice creator.
     *
     * @return void
     */
    public function creatorName()
    {
        return $this->creator->name;
    }

    /**
     * Returns the results for the practice of the authenticated user. Global scope from LabResult model is applied.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(LabResult::class, 'practice_id');
    }    

    /**
     * Returns the results for the practice without the global scope from the LabResult model.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function noScopeResults()
    {
        return $this->results()->withoutGlobalScopes();
    }

    /**
     * Returns the percentage of processed results for the practice.
     *
     * @return integer
     */
    public function processedResultsPercentage()
    {
        if (count($this->noScopeResults) > 0) {
            return number_format(
                (count($this->noScopeResults->where('status', LabResult::PROCESSED)) / 
                count($this->noScopeResults)) * 100);
        }

        return '0';
    }

    /**
     * Query scope
     *
     * @param $query
     * @param $column from CSV file column practice_id.
     *                returns the name of the practice.
     *
     * @return mixed
     */
    public function scopeName($query, $column)
    {
        return $query->whereId($column)->first()->name;
    }

    /**
     * Returns the admins of practices.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function admin()
    {
        return $this->vets()->where('type', User::PRACTICE_ADMIN);
    }
}
