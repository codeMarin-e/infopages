{{--   INFOPAGES --}}
<li class="nav-item @if(request()->route()->named("{$whereIam}.infopages.*")) active @endif">
    <a class="nav-link " href="{{route("{$whereIam}.infopages.index")}}">
        <i class="fa fa-fw fa-users mr-1"></i>
        <span>@lang("admin/infopages/infopages.sidebar")</span>
    </a>
</li>
