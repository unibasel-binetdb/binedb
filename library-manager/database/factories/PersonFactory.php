<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'training_cataloging' => false,
            'training_indexing' => false,
            'training_acquisition' => false,
            'training_magazine' => false,
            'training_lending' => false,
            'slsp_acq' => false,
            'slsp_acq_plus' => false,
            'slsp_acq_certified' => false,
            'slsp_cat' => false,
            'slsp_cat_plus' => false,
            'slsp_cat_certified' => false,
            'slsp_emedia' => false,
            'slsp_emedia_plus' => false,
            'slsp_emedia_certified' => false,
            'slsp_circ' => false,
            'slsp_circ_plus' => false,
            'slsp_circ_certified' => false,
            'slsp_circ_desk' => false,
            'slsp_circ_limited' => false,
            'slsp_student_certified' => false,
            'slsp_analytics' => false,
            'slsp_analytics_admin' => false,
            'slsp_analytics_certified' => false,
            'slsp_sysadmin' => false,
            'slsp_staff_manager' => false,
            'sls_phere_access' => false,
            'alma_completed' => false,
            'edoc_login' => false,
            'edoc_full_text' => false,
            'edoc_bibliographic' => false
        ];
    }
}
