@php
    $levelColoring = [
        'title' => [ 'primary', 'warning', 'dark' ]
    ];
@endphp

@pushonceOnReady('below_js_on_ready')
<script>
    function toggleChilds($parent, visible) {
        if(visible) {
            $('tr[data-parent="' + $parent.attr('data-id' ) + '"]').each(function(index, el){
                var $el = $(el);
                toggleChilds( $el, parseInt($el.attr('data-show')) );
                $el.show();
            });
            return;
        }

        $('tr[data-parent="' + $parent.attr('data-id' ) + '"]').each(function(index, el){
            var $el = $(el);
            toggleChilds( $el, 0 );
            $el.hide();
        });
    }
    $(document).on('click', '.js_childs_toggle', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $thistr = $this.parents('tr').first();
        visible = parseInt($thistr.attr('data-show'))? 0 : 1; //reverse;
        toggleChilds( $thistr, visible );
        $thistr.attr( 'data-show', visible );
        $this.html( visible?
            '<i class="fa fa-angle-down"></i>' : '<i class="fa fa-angle-up"></i>'
        );
    });
</script>
@endpushonceOnReady

@foreach($infopages as $infopage)
    @php
        $infopageEditUri = route("{$route_namespace}.infopages.edit", $infopage);
        $subInfopages = $infopage->childrenQry($subBldQry)->get();
    @endphp
    <tr data-id="{{$infopage->id}}"
        data-parent="{{$infopage->parent_id}}"
        data-show="1">
        <td scope="row" class="text-center align-middle"><a href="{{ $infopageEditUri }}"
                                               title="@lang('admin/infopages/infopages.edit')"
            >{{ $infopage->id }}</a></td>

        {{-- @HOOK_INFOPAGES_AFTER_ID --}}

        {{--    TITLE    --}}
        <td class="w-75 align-middle">
            {!! str_repeat('<i class="fa fa-arrow-right text-success mr-2"></i>', $level) !!}
            <a href="{{ $infopageEditUri }}"
                            title="@lang('admin/infopages/infopages.edit')"
                            class="@if($infopage->active) text-{{$levelColoring['title'][$level%count($levelColoring['title'])]}} @else text-danger @endif"
            >{{ \Illuminate\Support\Str::words($infopage->aVar('title'), 12,'....') }}</a>
            @if(count($subInfopages))
                <a href="#"
                   class="js_childs_toggle text-dark"
                   data-show="1"
                   data-id="{{$infopage->id}}"><i class="fa fa-angle-down"></i></a>
            @endif
        </td>

        {{-- @HOOK_INFOPAGES_AFTER_TITLE --}}

        {{--    EDIT    --}}
        <td class="text-center">
            <a class="btn btn-link text-success"
               href="{{ $infopageEditUri }}"
               title="@lang('admin/infopages/infopages.edit')"><i class="fa fa-edit"></i></a></td>

        {{-- @HOOK_INFOPAGES_AFTER_EDIT --}}

        {{--    DELETE    --}}
        <td class="text-center">
            @can('delete', $infopage)
                <form action="{{ route("{$route_namespace}.infopages.destroy", $infopage) }}"
                      method="POST"
                      id="delete[{{$infopage->id}}]">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-link text-danger"
                            title="@lang('admin/infopages/infopages.remove')"
                            onclick="if(confirm('@lang("admin/infopages/infopages.remove_ask")')) document.querySelector( '#delete\\[{{$infopage->id}}\\] ').submit();"
                            type="button"><i class="fa fa-trash"></i></button>
                </form>
            @endcan
        </td>
    </tr>
    @includeWhen(count($subInfopages), 'admin/infopages/infopages_list', [
        'infopages' => $subInfopages,
        'level' => $level+1,
    ])
@endforeach
