<?php

namespace App\ApiEvents;

/**
 * Class DoctorEvents
 * 
 * this class contains all events related to doctor
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
final class DoctorEvents
{
    /**
     * The DOCTOR_REGISTRATION_SUCCESS event occurs when the doctor registration form is submitted successfully.
     *
     * @Event("App\Event\DoctorEvent")
     */
    const DOCTOR_REGISTRATION_SUCCESS = 'doctor.registration.success';
    
    /**
     * The DOCTOR_REGISTRATION_CONFIRM event occurs after confirming the doctor account.
     *
     * @Event("App\Event\DoctorEvent")
     */
    const DOCTOR_REGISTRATION_CONFIRM = 'doctor.registration.confirm';

    /**
     * The DOCTOR_LOGIN event occurs after doctor login.
     *
     * @Event("App\Event\DoctorEvent")
     */
    const DOCTOR_LOGIN = 'doctor.login';

    /**
     * The DOCTOR_PROFILE_UPDATE_SUCCESS event occurs after updating doctor profile.
     *
     * @Event("App\Event\DoctorEvent")
     */
    const DOCTOR_PROFILE_UPDATE_SUCCESS = 'doctor.profile.update.success';

    /**
     * The DOCTOR_CHANGE_PASSWORD_SUCCESS event occurs after changing doctor password.
     *
     * @Event("App\Event\DoctorEvent")
     */
    const DOCTOR_CHANGE_PASSWORD_SUCCESS = 'doctor.change.password.success';

    /**
     * The DOCTOR_REQUEST_PASSWORD event occurs after requesting new password for the doctor.
     *
     * @Event("App\Event\DoctorEvent")
     */
    const DOCTOR_REQUEST_PASSWORD = 'doctor.request.password';

    /**
     * The DOCTOR_RESET_PASSWORD_SUCCESS event occurs after resetting doctor password.
     *
     * @Event("App\Event\DoctorEvent")
     */
    const DOCTOR_RESET_PASSWORD_SUCCESS = 'doctor.reset.password.success';

}