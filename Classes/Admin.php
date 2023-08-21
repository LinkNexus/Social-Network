<?php

class Admin extends User
{
    public function __construct(Database $link, Session $session, array $options = [])
    {
        parent::__construct($link, $session, $options);
    }
}