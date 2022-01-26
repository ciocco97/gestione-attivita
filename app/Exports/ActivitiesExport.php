<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpParser\Node\Expr\Array_;

class ActivitiesExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
    protected $activities;
    protected $attributes;

    public function __construct(Collection $activities)
    {
        $this->activities = $activities;
        $activity = $activities->first();
        foreach ($activity as $k => $a) {
            $this->attributes[] = $k;
        }
    }

    public function collection()
    {
        return $this->activities;
    }

    public function headings(): array
    {
        return $this->attributes;
    }

}
