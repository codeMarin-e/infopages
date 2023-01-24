@pushonce('below_templates')
@if(isset($chInfopage) && $authUser->can('delete', $chInfopage))
    <form action="{{ route("{$route_namespace}.infopages.destroy", $chInfopage->id) }}"
          method="POST"
          id="delete[{{$chInfopage->id}}]">
        @csrf
        @method('DELETE')
    </form>
@endif
@endpushonce

<x-admin.main>
    @php $inputBag = 'infopage'; @endphp
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route("{$route_namespace}.infopages.index") }}">@lang('admin/infopages/infopages.infopages')</a></li>
            <li class="breadcrumb-item active">@isset($chInfopage){{ $chInfopage->id }}@else @lang('admin/infopages/infopage.create') @endisset</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form action="@isset($chInfopage){{ route("{$route_namespace}.infopages.update", $chInfopage) }}@else{{ route("{$route_namespace}.infopages.store") }}@endisset"
                      method="POST"
                      autocomplete="off"
                      enctype="multipart/form-data">
                    @csrf
                    @isset($chInfopage)@method('PATCH')@endisset

                    <x-admin.box_messages />

                    <x-admin.box_errors :inputBag="$inputBag" />

                    {{-- @HOOK_INFOPAGE_BEGINNING --}}

                    @php
                        $sParentId = old("{$inputBag}.parent_id", (isset($chInfopage)? $chInfopage->parent_id : 0));
                    @endphp
                    <div class="form-group row">
                        <label for="{{$inputBag}}[parent_id]"
                               class="col-lg-2 col-form-label">@lang('admin/infopages/infopage.parent_id')</label>
                        <div class="col-lg-4">
                            <select class="form-control"
                                    id="{{$inputBag}}[parent_id]"
                                    name="{{$inputBag}}[parent_id]">
                                <option value="0"
                                        @if(!$sParentId)selected="selected"@endif
                                >@lang('admin/infopages/infopage.parent_id_none')</option>
                                @includeWhen(count($mainInfopages), 'admin/infopages/infopage_parent_options', [
                                    'mainInfopages' => $mainInfopages,
                                    'sParentId' => $sParentId,
                                    'subParentBldQry' => $subParentBldQry,
                                    'level' => 0
                                ])
                            </select>
                        </div>
                    </div>

                    {{-- @HOOK_INFOPAGE_AFTER_PARENT --}}

                    {{-- TITLE --}}
                    <div class="form-group row">
                        <label for="{{$inputBag}}[add][title]"
                               class="col-lg-1 col-form-label"
                        >@lang('admin/infopages/infopage.title')</label>
                        <div class="col-lg-11">
                            <input type="text"
                                   name="{{$inputBag}}[add][title]"
                                   id="{{$inputBag}}[add][title]"
                                   value="{{ old("{$inputBag}.add.title", (isset($chInfopage)? $chInfopage->aVar('title') : '')) }}"
                                   class="form-control @if($errors->$inputBag->has('add.title')) is-invalid @endif"
                            />
                        </div>
                    </div>

                    {{-- @HOOK_INFOPAGE_AFTER_TITLE --}}

                    @if($authUser->hasRole('Super Admin', 'admin'))
                        {{-- SYSTEM --}}
                        <div class="form-group row">
                            <label for="{{$inputBag}}[system]"
                                   class="col-lg-1 col-form-label"
                            >@lang('admin/infopages/infopage.system')</label>
                            <div class="col-lg-3">
                                <input type="text"
                                       name="{{$inputBag}}[system]"
                                       id="{{$inputBag}}[system]"
                                       value="{{ old("{$inputBag}.system", (isset($chInfopage)? $chInfopage->system : '')) }}"
                                       class="form-control @if($errors->$inputBag->has('system')) is-invalid @endif"
                                />
                            </div>
                        </div>

                        {{-- @HOOK_INFOPAGE_AFTER_SYSTEM --}}

                    @endif

                    {{-- CONTENT --}}
                    <div class="form-group row">
                        <label for="{{$inputBag}}[add][content]"
                               class="col-lg-1 col-form-label @if($errors->$inputBag->has('add.content')) text-danger @endif"
                        >@lang('admin/infopages/infopage.content')</label>
                        <div class="col-lg-11">
                            <x-admin.editor
                                :inputName="$inputBag.'[add][content]'"
                                :otherClasses="[ 'form-controll', ]"
                            >{{old("{$inputBag}.add.content", (isset($chInfopage)? $chInfopage->aVar('content') : ''))}}</x-admin.editor>
                        </div>
                    </div>

                    {{-- @HOOK_INFOPAGE_AFTER_CONTENT --}}

                    <div class="form-group row form-check">
                        <div class="col-lg-6">
                            <input type="checkbox"
                                   value="1"
                                   id="{{$inputBag}}[active]"
                                   name="{{$inputBag}}[active]"
                                   class="form-check-input @if($errors->$inputBag->has('active'))is-invalid @endif"
                                   @if(old("{$inputBag}.active") || (is_null(old("{$inputBag}.active")) && isset($chInfopage) && $chInfopage->active ))checked="checked"@endif
                            />
                            <label class="form-check-label"
                                   for="{{$inputBag}}[active]">@lang('admin/infopages/infopage.active')</label>
                        </div>
                    </div>

                    {{-- @HOOK_INFOPAGE_AFTER_CHECKBOXES --}}

                    <div class="form-group row">
                        @isset($chInfopage)
                            @can('update', $chInfopage)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='action'>@lang('admin/infopages/infopage.save')
                                </button>

                                <button class='btn btn-primary mr-2'
                                        type='submit'
                                        name='update'>@lang('admin/infopages/infopage.update')</button>
                            @endcan

                            @can('delete', $chInfopage)
                                <button class='btn btn-danger mr-2'
                                        type='button'
                                        onclick="if(confirm('@lang("admin/infopages/infopage.delete_ask")')) document.querySelector( '#delete\\[{{$chInfopage->id}}\\] ').submit() "
                                        name='delete'>@lang('admin/infopages/infopage.delete')</button>
                            @endcan
                        @else
                            @can('create', App\Models\Infopage::class)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='create'>@lang('admin/infopages/infopage.create')</button>
                            @endcan
                        @endisset
                        <a class='btn btn-warning'
                           href="{{ route("{$route_namespace}.infopages.index") }}"
                        >@lang('admin/infopages/infopage.cancel')</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-admin.main>
