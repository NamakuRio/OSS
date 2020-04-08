<?php

if (!function_exists('formatLoginDestination')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function formatLoginDestination($login_destination)
    {
        $cek_login_destination = $login_destination[0];

        if ($cek_login_destination != "/") {
            $login_destination = '/' . substr($login_destination, 1);
        }

        return $login_destination;
    }
}

if (!function_exists('formatRole')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function formatRole($role)
    {
        $role = explode("_", $role);

        foreach ($role as $key => $record) {
            $role[$key] = ucfirst($record);
        }

        return implode(" ", $role);
    }
}
