## Laravel daily Tasks

### A non-trivial task app written in laravel 8

### Features:
- Users get banned for 2 minutes if they tamper with task ids in url. (implemented by `laravel-temp-tag` and `laravel-heyman` package)
- Banned users are allowed to see the task list but can not manage them.
- No user can have to than 10 daily tasks.
- It logs any validation errors, banning or tampering with url parameters for admin to review.
- Only one task can be at `doing` state

This is a sample usage of my `laravel-temp-tag` package. When you mark a daily-task as `done`, `failed`, `skipped`, etc and they will be automatically rollback to the default  (`not started`) state at the end of the day. (without using any cron job)

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

![image](https://user-images.githubusercontent.com/6961695/94300742-4f4ea780-ff76-11ea-9165-8b77df24c25c.png)




### Long Story Short:

We Tag the tasks as 'complete' until the end of the day:

```php
$expireAt = Carbon::now()->endOfDay();   // 23:59:59 of today
tempTags($task)->tagIt('state', $expireAt, ['value' => 'done']);
```

We remove the tag from the task when the user marks it as in-complete:
```php
tempTags($task)->unTag('state');
```

We fetch the 'complete' and 'incomplete' tasks like this:

```php
Task::hasActiveTempTags('state', ['value' => 'done'])->get();           // tasks with "complete" tag.
Task::hasNotActiveTempTags('state', ['value' => 'not_started'])->get(); // tasks with no tag are incomplete ones.

```

And that is all ! Super simple 

-----------

### Laravel Temp Tag:

https://github.com/imanghafoori1/laravel-temp-tag

