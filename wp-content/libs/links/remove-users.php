<?php
/* 
 * 
 */

require_once('../../../wp-load.php');
include_once(__DIR__ . '/../functions.php');

// Remove users
$linkResource = new LinkUserCron();
$linkResource->remove();

/**
 * LinkUserCron
 * 
 * 
 * @author     Fahad Farrukh <fahad.curriki@nxvt.com>
 */
class LinkUserCron
{
    /**
     * remove
     * 
     * Remove users.
     *
     * @return string response of remove
     */
    public function remove()
    {
        global $wpdb;

        $users = $wpdb->get_results( 
            "
            SELECT userid
            FROM users
            WHERE
                userid IN (360328,30911,73696,10000,180151,43411,66790)
            LIMIT 10
            "
        );

        if ($users) {
            $this->removeUsers($users);
        }
    }

    /**
     * removeUsers
     *
     * Remove Users
     *
     *
     * @param array $users Users to remove
     * @return void
     */
    public function removeUsers($users)
    {
        $usersIds = [];

        foreach ($users as $user) 
        {
            $usersIds[] = $user->userid;
        }

        deleteUsersData($usersIds);
    }
}
