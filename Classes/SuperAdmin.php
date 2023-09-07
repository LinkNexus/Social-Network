<?php

class SuperAdmin extends Admin
{
    public function __construct(Database $link, Session $session, array $options = [])
    {
        parent::__construct($link, $session, $options);
    }

    public function superAdminApproval($result, $answer): bool
    {
        if ($answer == 1){
            $this->link->query('UPDATE users SET admin_request = 1 WHERE id = :id', [
                'id' => $result->id,
            ]);

            return true;
        } else {
            $this->link->query('UPDATE users SET admin_request = :request WHERE id = :id', [
                'request' => NULL,
                'id' => $result->id,
            ]);

            return false;
        }
    }

    public function approveAdminRemoval($result, $answer): bool
    {
        $this->link->query('UPDATE users SET remove_admin = NULL WHERE id = :id', [
            'id' => $result->id,
        ]);

        if ($answer == 1){
            $this->link->query('UPDATE users SET status = :status WHERE id = :id', [
                'status' => 'user',
                'id' => $result->id,
            ]);

            return true;
        }

        return false;
    }
}