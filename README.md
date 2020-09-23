## Laravel daily Tasks

Laravel 8 with user authentication, password recovery, and individual user tasks lists.

This is a sample usage of my `laravel-temp-tag` package. When you mark a daily-task as done, it will automatically return back to in-complete state at the end of the day.

Super easy setup, can be done in 5 minutes or less.

### Quick Project Setup
1. Run `sudo git clone https://github.com/imanghafoori1/laravel-tasks.git laravel-tasks`
2. From the projects root run `cp .env.example .env`
3. Configure your `.env`  (optional since an sqlite database is provided)
4. Run `sudo composer update` from the projects root folder
5. From the projects root folder run `php artisan task:install` to migrate, seed and generate key

You can login with:
```
email: hi@example.com     pass: secret
email: bye@example.com    pass: secret
```
### Laravel-Tasks URL's (routes)
* ```/home```
* ```/tasks```
* ```/tasks/create```
* ```/tasks/{id}/edit```

---

### Long Story Short:

We Tag the tasks as 'complete' until the end of the day:

```php
$expireAt = Carbon::now()->endOfDay();   // 23:59:59 of today
tempTags($task)->tagIt('complete', $expireAt);
```

We remove the tag from the task when the user marks it as in-complete:
```php
tempTags($task)->unTag('complete');
```

We fetch the 'complete' and 'incomplete' tasks like this:

```php
Task::hasActiveTempTags('complete')->get();    // tasks which has tag
Task::hasNotActiveTempTags('complete')->get(); // tasks no tag

```

And that is all ! Super simple 

-----------

### Laravel Temp Tag:

https://github.com/imanghafoori1/laravel-temp-tag

