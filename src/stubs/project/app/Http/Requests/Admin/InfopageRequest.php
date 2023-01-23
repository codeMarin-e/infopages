<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Models\Infopage;
use Illuminate\Validation\ValidationException;

class InfopageRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $chInfopage = request()->route('chInfopage');
        return [
            'parent_id' => [ function($attribute, $value, $fail) {
                if(!$value) return; //Root
                if( !($parent = Infopage::find($value)) ) {
                    return $fail(trans('admin/infopages/validation.parent_id.not_found'));
                }
            }],
            'add.title' => 'required|max:255',
            'add.content' => 'nullable',
            'active' => 'boolean',
            'system' => [Rule::excludeIf(!auth()->user()->hasRole('Super Admin', 'admin')), 'nullable', 'max:255',
                function($attribute, $value, $fail) use ($chInfopage){
                    $qryBld = Infopage::whereSystem($value);
                    if($chInfopage) $qryBld->where('id','!=', $chInfopage->id);
                    if($qryBld->first()) {
                        return $fail(trans('admin/infopages/validation.system.unique'));
                    }
            }],

            // @HOOK_INFOPAGE_REQUEST_RULES
        ];
    }

    public function messages() {
        $return = Arr::dot((array)trans('admin/infopages/validation'));

        // @HOOK_INFOPAGE_REQUEST_MESSAGES

        return $return;
    }

    public function validationData() {
        $inputBag = 'infopage';
        $this->errorBag = $inputBag;
        $inputs = $this->all();
        if(!isset($inputs[$inputBag])) {
            throw new ValidationException(trans('admin/infopages/validation.no_inputs') );
        }
        $inputs[$inputBag]['active'] = isset($inputs[$inputBag]['active']);

        // @HOOK_INFOPAGE_REQUEST_PREPARE

        $this->replace($inputs);
        request()->replace($inputs); //global request should be replaced, too
        return $inputs[$inputBag];
    }

    public function validated($key = null, $default = null) {
        $validatedData = parent::validated($key, $default);

        // @HOOK_INFOPAGE_REQUEST_VALIDATED

        if(is_null($key)) {

            // @HOOK_INFOPAGE_REQUEST_AFTER_VALIDATED

            return $validatedData;
        }

        // @HOOK_INFOPAGE_REQUEST_AFTER_VALIDATED_KEY

        return $validatedData;
    }
}
