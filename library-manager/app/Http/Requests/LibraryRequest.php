<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LibraryRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'name_addition' => 'nullable|string|max:255',
            'short_name' => 'nullable|string|max:8',
            'alternative_name' => 'nullable|string|max:512',
            'alma_bibcode' => 'nullable|string|max:64',
            'existing_since' => 'nullable|string|max:256',
            'shipping_street' => 'nullable|string|max:255',
            'shipping_pobox' => 'nullable|string|max:128',
            'shipping_zip' => 'nullable|string|max:8',
            'shipping_location' => 'nullable|string|max:128',
            'billing_name' => 'nullable|string|max:255',
            'billing_name_addition' => 'nullable|string|max:255',
            'billing_street' => 'nullable|string|max:255',
            'billing_pobox' => 'nullable|string|max:128',
            'billing_zip' => 'nullable|string|max:8',
            'billing_location' => 'nullable|string|max:128',
            'billing_comment' => 'nullable|string',
            'library_comment' => 'nullable|string',
            'institution_url' => 'nullable|string',
            'library_url' => 'nullable|string',
            'associated_type' => 'nullable|string|max:64',
            'faculty' => 'nullable|string|max:64',
            'departement' => 'nullable|string',
            'uni_regulations' => 'nullable|string',
            'bibstats_identification' => 'nullable|string',
            'associated_comment' => 'nullable|string',
            'uni_costcenter' => 'nullable|string',
            'ub_costcenter' => 'nullable|string|max:64',
            'finance_comment' => 'nullable|string',
            'it_provider' => 'nullable|string|max:64',
            'ip_address' => 'nullable|string|max:128',
            'it_comment' => 'nullable|string',
            'location_comment' => 'nullable|string',
            'state_type' => 'nullable|string|max:64',
            'state_since' => 'nullable|string',
            'state_until' => 'nullable|string',
            'state_comment' => 'nullable|string',
            'alma_cataloging' => 'nullable|string|max:3',
            'alma_cataloging_comment' => 'nullable|string',
            'subject_idx_local' => 'nullable|string|max:3',
            'subject_idx_gnd' => 'nullable|string|max:16',
            'subject_idx_comment' => 'nullable|string',
            'acquisition' => 'nullable|string|max:3',
            'acquisition_comment' => 'nullable|string',
            'magazine_management' => 'nullable|string|max:3',
            'magazine_management_comment' => 'nullable|string',
            'lending' => 'nullable|string|max:3',
            'lending_comment' => 'nullable|string',
            'self_lending' => 'nullable|string|max:3',
            'self_lending_comment' => 'nullable|string',
            'basel_carrier' => 'nullable|string|max:3',
            'basel_carrier_comment' => 'nullable|string',
            'slsp_carrier' => 'nullable|string|max:16',
            'slsp_carrier_comment' => 'nullable|string',
            'rfid' => 'nullable|string|max:3',
            'rfid_comment' => 'nullable|string',
            'slsp_bursar' => 'nullable|string|max:16',
            'slsp_bursar_comment' => 'nullable|string',
            'print_daemon' => 'nullable|string|max:16',
            'print_daemon_comment' => 'nullable|string',
            'stock.special_stock_comment' => 'nullable|string',
            'stock.inst_depositum_comment' => 'nullable|string',
            'stock.pushback' => 'nullable|string|max:256',
            'stock.pushback_2010' => 'nullable|string|max:256',
            'stock.pushback_2020' => 'nullable|string|max:256',
            'stock.pushback_2030' => 'nullable|string|max:256',
            'stock.memory_library' => 'nullable|string|max:256',
            'stock.comment' => 'nullable|string'
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
