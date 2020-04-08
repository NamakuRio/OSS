<?php

if (!function_exists('checkUsername')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function checkUsername($username)
    {
        preg_match('/^[a-zA-Z0-9_][a-zA-Z0-9_]{2,18}[a-zA-Z0-9_]$/', $username, $matches);

        return (bool) count($matches);
    }
}

if (!function_exists('checkPhone')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function checkPhone($phone)
    {
        preg_match('/^[6][2][2][0-9]{7,10}$/', $phone, $matches);

        return (bool) count($matches);
    }
}

if (!function_exists('checkPermission')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function checkPermission($permission)
    {
        return (bool) (!auth()->user()->can($permission));
    }
}

if (!function_exists('formatPhone')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function formatPhone($phone)
    {
        $phone_dot = str_replace('.', '', $phone);
        $phone_space = str_replace(' ', '', $phone_dot);
        $phone_id = $phone_space[0] . $phone_space[1];
        $phone_id_2 = $phone_space[0] . $phone_space[1] . $phone_space[2];
        $phone_id_3 = $phone_space[0];

        if ($phone_id == "08") {
            $phone_space = '628' . substr($phone_space, 2);
        }

        if ($phone_id_2 == "+62") {
            $phone_space = '62' . substr($phone_space, 3);
        }

        if ($phone_id_3 == "8") {
            $phone_space = '62' . substr($phone_space, 1);
        }

        return $phone_space;
    }
}

if (!function_exists('formatEmail')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function formatEmail($email)
    {
        list($username, $domain) = explode('@', $email);

        if($domain == 'gmail.com'){
            $username = str_replace('.', '', $username);

            $email = $username."@".$domain;
        }

        return $email;
    }
}

if (!function_exists('checkDev')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function checkDev()
    {
        return (auth()->user()->getFirstRole() == 'developer');
    }
}
