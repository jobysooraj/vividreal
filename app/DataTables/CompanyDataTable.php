<?
namespace App\DataTables;

use App\Models\Company;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CompanyDataTable
{
    public function dataTable($query){

        return datatables()
            ->eloquent($query);
    }

    public function query(Comapny $model)
    {
        return $model->newQuery();
    }

    public function columns()
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('logo'),
            Column::make('action'),
           
        ];
    }
}
