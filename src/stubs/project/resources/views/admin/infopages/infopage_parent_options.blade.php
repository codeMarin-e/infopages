@foreach($mainInfopages as $infopage)
    <option value="{{$infopage->id}}"
            @if($sParentId == $infopage->id)selected='selected'@endif
        >{!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!}{{$infopage->aVar('title')}}</option>
    @php
        $subInfopages = $infopage->childrenQry($subParentBldQry)->get();
    @endphp
    @includeWhen( $subInfopages->count(), 'admin/infopages/infopage_parent_options', [
        'mainInfopages' => $subInfopages,
        'sParentId' => $sParentId,
        'subParentBldQry' => $subParentBldQry,
        'level' => $level+1
    ])
@endforeach
