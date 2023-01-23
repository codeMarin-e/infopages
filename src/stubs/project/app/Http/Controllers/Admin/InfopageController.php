<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\InfopageRequest;
use App\Models\Infopage;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;


class InfopageController extends Controller {
    public function __construct() {
        if(!request()->route()) return;

        $this->db_table = Infopage::getModel()->getTable();
        $this->routeNamespace = Str::before(request()->route()->getName(), '.infopages');
        View::composer('admin/infopages/*', function($view)  {
            $viewData = [
                'route_namespace' => $this->routeNamespace,
            ];
            // @HOOK_INFOPAGES_VIEW_COMPOSERS
            $view->with($viewData);
        });
        // @HOOK_INFOPAGES_CONSTRUCT
    }

    public function index() {
        $viewData = [];
        $bldQry =  Infopage::where("{$this->db_table}.site_id", app()->make('Site')->id)->orderBy("{$this->db_table}.id", 'ASC');
        $subBldQry = clone $bldQry;

        // @HOOK_INFOPAGES_INDEX_END

        $viewData['subBldQry'] = $subBldQry;
        $viewData['infopages'] = $bldQry->where("{$this->db_table}.parent_id", 0)
            ->paginate(20)->appends( request()->query() );

        return view('admin/infopages/infopages', $viewData);
    }

    public function create() {
        $viewData = [];
        $viewData['subParentBldQry'] = Infopage::where("{$this->db_table}.site_id", app()->make('Site')->id)
            ->orderBy("{$this->db_table}.id", 'ASC');
        $viewData['mainInfopages'] = (clone $viewData['subParentBldQry'])->where('parent_id', 0)->get();

        // @HOOK_INFOPAGES_CREATE

        return view('admin/infopages/infopage', $viewData);
    }

    public function edit(Infopage $chInfopage) {
        $viewData = [];
        $viewData['subParentBldQry'] = Infopage::where("{$this->db_table}.site_id", app()->make('Site')->id)
            ->orderBy("{$this->db_table}.id", 'ASC')
            ->where('id', '!=', $chInfopage->id);
        $viewData['mainInfopages'] = (clone $viewData['subParentBldQry'])->where('parent_id', 0)->get();

        // @HOOK_INFOPAGES_EDIT

        $viewData['chInfopage'] = $chInfopage;
        return view('admin/infopages/infopage', $viewData);
    }

    public function store(InfopageRequest $request) {
        $validatedData = $request->validated();

        // @HOOK_INFOPAGES_STORE_VALIDATE

        $chInfopage = Infopage::create( array_merge([
            'site_id' => app()->make('Site')->id,
        ], $validatedData));

        // @HOOK_INFOPAGES_STORE_INSTANCE

        $chInfopage->setAVars($validatedData['add']);

        // @HOOK_INFOPAGES_STORE_END
        event( 'infopage.submited', [$chInfopage, $validatedData] );

        return redirect()->route($this->routeNamespace.'.infopages.edit', $chInfopage)
            ->with('message_success', trans('admin/infopages/infopage.created'));
    }

    public function update(Infopage $chInfopage, InfopageRequest $request) {
        $validatedData = $request->validated();

        // @HOOK_INFOPAGES_UPDATE_VALIDATE

        $chInfopage->update( $validatedData );
        $chInfopage->setAVars($validatedData['add']);

        // @HOOK_INFOPAGES_UPDATE_END

        event( 'infopage.submited', [$chInfopage, $validatedData] );
        if($request->has('action')) {
            return redirect()->route($this->routeNamespace.'.infopages.index')
                ->with('message_success', trans('admin/infopages/infopage.updated'));
        }
        return back()->with('message_success', trans('admin/infopages/infopage.updated'));
    }

    public function destroy(Infopage $chInfopage) {

        // @HOOK_INFOPAGES_DESTROY

        $chInfopage->delete();

        // @HOOK_INFOPAGES_DESTROY_END

        return redirect()->route($this->routeNamespace.'.infopages.index')
            ->with('message_danger', trans('admin/infopages/infopage.deleted'));
    }
}
