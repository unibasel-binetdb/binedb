<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable|string|max:16',
            'first_name' => 'nullable|string|max:128',
            'last_name' => 'nullable|string|max:128',
            'seal' => 'nullable|string',
            'comment' => 'nullable|string',
            'training' => 'nullable|string|max:32',
            'training_cataloging' => 'required',
            'training_indexing' => 'required',
            'training_acquisition' => 'required',
            'training_magazine' => 'required',
            'training_lending' => 'required',
            'training_emedia' => 'required',
            'education' => 'nullable|string|max:32',
            'slsp_acq' => 'required',
            'slsp_acq_plus' => 'required',
            'slsp_acq_certified' => 'required',
            'digirech_share' => 'required',
            'slsp_cat' => 'required',
            'slsp_cat_plus' => 'required',
            'slsp_cat_certified' => 'required',
            'slsp_emedia' => 'required',
            'slsp_emedia_plus' => 'required',
            'slsp_emedia_certified' => 'required',
            'slsp_circ' => 'required',
            'slsp_circ_plus' => 'required',
            'slsp_circ_certified' => 'required',
            'slsp_circ_desk' => 'required',
            'slsp_circ_limited' => 'required',
            'slsp_student_certified' => 'required',
            'slsp_analytics' => 'required',
            'slsp_analytics_admin' => 'required',
            'slsp_analytics_certified' => 'required',
            'slsp_sysadmin' => 'required',
            'slsp_staff_manager' => 'required',
            'access_right_comment' => 'nullable|string',
            'slsp_certification_comment' => 'nullable|string',
            'cataloging_level' => 'nullable|string|max:16',
            'sls_phere_access' => 'required',
            'sls_phere_access_comment' => 'nullable|string',
            'alma_completed' => 'required',
            'edoc_login' => 'required',
            'edoc_full_text' => 'required',
            'edoc_bibliographic' => 'required',
            'edoc_comment' => 'nullable|string',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
