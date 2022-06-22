<?php

namespace App\DataTables;

use App\Models\Artists;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Dompdf\Dompdf;

class ArtistsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function($row){
       
               $btn = '<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#artistModal"  data-id="'.$row->id.'"  > Edit</button>';
                return $btn;
              });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ArtistsDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ArtistsDataTable $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('artists-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                   ->buttons(
                       Button::make('create'),
                       Button::make('export'),
                       Button::make('print'),
                       Button::make('reset'),
                       Button::make('reload')
                   );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            
            Column::make('id'),
            Column::make('artist_name')->title('artist'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::make('action')
                  ->exportable(false)
                  ->printable(false)
                  
            //       ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Artists_' . date('YmdHis');
    }
}
