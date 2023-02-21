<x-admin.main>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item active">@lang('admin/infopages/infopages.infopages')</li>
        </ol>

        <div class="row">
            <div class="col-12">
                @can('create', App\Models\Infopage::class)
                    <a href="{{ route("{$route_namespace}.infopages.create") }}"
                       class="btn btn-sm btn-primary h5"
                       title="create">
                        <i class="fa fa-plus mr-1"></i>@lang('admin/infopages/infopages.create')
                    </a>
                @endcan

                {{-- @HOOK_INFOPAGES_ADDON_LINKS --}}

            </div>
        </div>

        <x-admin.box_messages />

        <div class="table-responsive rounded ">
            <table class="table table-sm">
                <thead class="thead-light">
                <tr class="">
                    <th scope="col" class="text-center" >@lang('admin/infopages/infopages.id')</th>
                    {{-- @HOOK_INFOPAGES_AFTER_ID_TH --}}
                    <th scope="col" class="w-75">@lang('admin/infopages/infopages.title')</th>
                    {{-- @HOOK_INFOPAGES_AFTER_TITLE_TH --}}
                    <th scope="col" class="text-center">@lang('admin/infopages/infopages.edit')</th>
                    {{-- @HOOK_INFOPAGES_AFTER_EDIT_TH --}}
                    <th scope="col" class="text-center">@lang('admin/infopages/infopages.remove')</th>
                </tr>
                </thead>
                <tbody>
                @if(count($infopages))
                    @include('admin/infopages/infopages_list', [
                        'infopagess' => $infopages,
                        'level' => 0,
                    ])
                @else
                    <tr>
                        <td colspan="100%">@lang('admin/infopages/infopages.no_infopages')</td>
                    </tr>
                @endif
                </tbody>
            </table>

            {{$infopages->links('admin.paging')}}

        </div>
    </div>
</x-admin.main>
