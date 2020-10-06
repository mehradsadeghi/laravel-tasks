<?php

use Imanghafoori\HeyMan\Facades\HeyMan;

HeyMan::onRoute('tasks.store')->validate([
    'name' => 'required|max:60',
    'description' => 'max:155',
]);


HeyMan::onRoute('tasks.update')->validate([
    'state' => 'required|in:not_started,done,doing,failed,wont_do'
]);
