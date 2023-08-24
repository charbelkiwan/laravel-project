<?php

namespace App\Imports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\ToModel;

class ProjectsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $excel_serial_date = $row[2];
        $unix_timestamp = ($excel_serial_date - 25569) * 86400;

        return new Project([
            'title'     => $row[0],
            'description'    => $row[1],
            'due_date' => date("Y-m-d", $unix_timestamp),
            'user_id' => $row[3],
        ]);
    }
}
