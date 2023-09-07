<?php

class Admin extends User
{
    public function __construct(Database $link, Session $session, array $options = [])
    {
        parent::__construct($link, $session, $options);
    }

    public function banUser($result, $period): void
    {
        $period = (int) $period;
        try {
            $this->link->query('UPDATE users SET banned = :banned, banned_till = :banned_till WHERE id = :id', [
                'banned' => 1,
                'banned_till' => date_add(new DateTime(), new DateInterval('P' . $period . 'D'))->format('Y-m-d H:i:s'),
                'id' => $result->id
            ]);
        } catch (Exception $e) {
        }
    }

    public function sendAdminRequest($result): bool
    {
        if ($this->session->getKey('user_infos')->status === 'super_admin') {
            $this->link->query('UPDATE users SET admin_request = :request WHERE id = :id', [
                'request' => 1,
                'id' => $result->id
            ]);

            return true;
        } else {
            $this->link->query('UPDATE users SET admin_request = :request WHERE id = :id', [
                'request' => 0,
                'id' => $result->id
            ]);

            return false;
        }
    }

    public function removeAdmin($result): bool
    {
        if ($this->session->getKey('user_infos')->status === 'super_admin') {
            $this->link->query('UPDATE users SET status = :status WHERE id = :id', [
                'status' => 'user',
                'id' => $result->id
            ]);

            return true;
        } else {
            $this->link->query('UPDATE users SET remove_admin = 1 WHERE id = :id', [
                'id' => $result->id
            ]);

            return false;
        }
    }

    public function warnUser($result)
    {
        $result->warnings++;
        $this->link->query('UPDATE users SET warnings = :warnings, warned_till = :warned_till WHERE id = :id', [
            'warnings' => $result->warnings,
            'warned_till' => $result->warned_till .', '. date_add(new DateTime(), new DateInterval('P7D'))->format('Y-m-d H:i:s'),
            'id' => $result->id
        ]);
    }

    public function muteUser($result, $period): void
    {
        $period = (int) $period;
        try {
            $this->link->query('UPDATE users SET muted = 1, muted_till = :muted_till WHERE id = :id', [
                'id' => $result->id,
                'muted_till' => date_add(new DateTime(), new DateInterval('P' . $period . 'D')),
            ]);
        } catch (Exception $e) {
        }
    }
}