1. https://github.com/Jeroen-G/laravel-packager
1. https://alpinetoolbox.com
1. https://www.brandcrowd.com
1. https://tailwindcomponents.com/u/khatabwedaa
1. https://www.tailwindtoolbox.com/
1. https://github.com/404labfr/laravel-impersonate
1. https://codepen.io/mithicher/pen/jOVEVNW -- codemirror sample
1. https://pqina.nl/filepond/
1. https://livewire-wireui.com/
1. https://stitcher.io/
1. https://blade-ui-kit.com/
1. https://github.com/spatie/laravel-collection-macros
1. https://github.com/jenssegers/laravel-mongodb
1. https://leqc.renoki.org/cache-tags/query-caching
1. https://tailwindcomponents.com
1. https://flowbite.com/
1. https://cylab.be/blog/144/laravel-custom-logging
1. https://github.com/michael-rubel/livewire-best-practices

todo
1. crud generator -
    - views - done
    - navigation - done
    - livewire component - done
    - route - done
    - request - done
    - migration - done
    - config - done
    - fields
        - text - done
        - textarea
        - file
        - select
        - multiple-select
        - datepicker
        - multiple-input
        - editor
        - checkboxes
        - filepond
        - codemirror
1. individual model searchable (allow live search on listing)
1. login rate limit https://laravel.com/docs/9.x/rate-limiting (securing passwordless login too)
1. passwordless login - https://github.com/grosv/laravel-passwordless-login
1. chat message in admin panel - https://www.youtube.com/watch?v=jox1hx2i1Aw
1. test dropbox https://spatie.be/open-source?search=drop&sort=-downloads
1. queuejob into mongodb and sample chartjs in dashboard
1. failjob using mongodb?

to read
1. https://github.com/mithicher/file-attachment/blob/master/resources/views/components/file-attachment.blade.php
1. https://gist.github.com/mehranhadidi/38e38b80e3d533650ed2b94a0f95f7f1
1. https://www.tutsmake.com/laravel-9-backup-store-on-google-drive-tutorial-with-example/
1. https://github.com/cesargb/laravel-magiclink
1. https://github.com/voku/stop-words
1. https://codebrisk.com/blog/generate-youtube-like-ids-for-models-with-laravel-hashids
1. https://github.com/LaravelDaily/laravel-tips

setup:

```
laravel new bliss-playground
cd bliss-playground
git clone git@github.com:wikichua/bliss.git ./package/wikichua/bliss
```

paste this in composer.json

```
    "repositories": {
        "wikichua/bliss": {
            "type": "path",
            "url": "packages/wikichua/bliss",
            "options": {
                "symlink": true
            }
        }
    }
```

```
composer req wikichua/bliss:@dev
```

setup db. run

```
art bliss:install
art migrate
```

if need mongodb

```
composer req jenssegers/mongodb
art art migrate:fresh
```

features:

1.
    a. helper dispatchToWorker - use this to keep track work record and dynamically create worker in ASYNC using artisan bliss:work
    b. or adding use Wikichua\Bliss\Traits\Queueable into job class, then you can use onQueue to create worker in ASYNC using artisan bliss:work

```
[program:bliss-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/forge/app.com/artisan bliss:work --stop-when-empty --backoff=3
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=forge
numprocs=8
redirect_stderr=true
stdout_logfile=/home/forge/app.com/worker.log
stopwaitsecs=3600
```

2.
searchable
define model with
use \Wikichua\Bliss\Traits\AllModelTraits;
public $searchableFields = ['name', 'group'];

then in controller or livewire component
app(config('bliss.Models.Permission'))->query()->searching($this->search ?? '')->get();

3.
sendAlertNotification

sendAlertNotification(message: 'Your message here', sender: auth()->id(), receivers: userIdsWithPermission('read-permissions'), link: route('your.route.name'));

4.
settings helper vice versa

sample:
[
    queuejob_status => [
        "W": "Waiting",
        "P": "Processing",
        "C": "Completed",
        "E": "Error"
    ]
]

settings('queuejob_status.E') - Error
settings('queuejob_status.Error') - E
settings('queuejob_status.Nothing') - ''
settings('queuejob_status.Nothing', 'N') - N

5.
Searchable, Audit and Snapshot into observer, event and listener - https://www.youtube.com/watch?v=DvoaU6cQQHM

6.
Confirm Password using Laravel's feature instead of self made.

7.
DB logging and debug...

enable with

```
LOG_CHANNEL=db
```

LOG_CHANNEL=stack to disabled this feature
