<?php

namespace App\Http\Livewire\LogActivity;

use Livewire\Component;
use App\Models\LogActivity;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $data = LogActivity::orderBy('id','DESC');

        return view('livewire.log-activity.index')->with(['data'=>$data->paginate(100)]);
    }
}
