@if($authUser->can('view', \App\Models\Infopage::class))
    {{--   INFOPAGES --}}
    <li class="nav-item @if(request()->route()->named("{$whereIam}.infopages.*")) active @endif">
        <a class="nav-link " href="{{route("{$whereIam}.infopages.index")}}">
            <i class="fa fa-fw fa-file-alt mr-1"></i>
            <span>@lang("admin/infopages/infopages.sidebar")</span>
        </a>
    </li>
@endif
