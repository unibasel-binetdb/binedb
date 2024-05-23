
# Library Manager Universitätsbibliothek Basel

A PHP / Laravel based app to manage libraries developed for the Universitätsbibliothek Basel

## Installation

See https://laravel.com/docs/10.x/installation how to setup the docker environment. After docker / WSL is configured, start up the app with start.bat or:

```bash
cmd
wsl
cd ~/librarymanager/library-manager
./vendor/bin/sail up
```

Create the database with:
```bash
php artisan migrate:fresh --seed
```

The initial database seed will create the user:

> Username: info@unibas.ch
> Password: TemporaryPassword!



## Troubleshooting

Do not use *php artisan storage:link* because it does absolute symlinks that docker doesn't support. Instead create a file system symlink like this:

```bash
rm storage
ln -s ../storage/app/public storage
```


## Misc

Useful commands:

```bash
composer dump-autoload
php artisan db:seed
php artisan config:clear
```