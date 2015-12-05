<?php

class sap_model extends Model {

    public function registerEntry($data) {
        $this->load_library("db_lib");

        // Optional form field
        $organisedEvent = isset($data['organised-event']) ? $data['organised-event'] : NULL;
        return $this->db_lib->prepared_execute(
            $this->DB->sap,
            "INSERT INTO `sap_ambassadors` (
                `name`, `email`, `phone_number`, `college`, `program_of_study`, `year_of_study`,
                `facebook_profile_link`, `why_apply`, `about_you`, `organised_event`
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            "sssssssss", [
                $data['name'], $data['email'], $data['phone-number'], $data['college'], $data['program-of-study'],
                $data['year-of-study'], $data['facebook-profile-link'], $data['why-apply'],
                $data['about-you'], $organisedEvent
            ],
            false
        );
    }

}
