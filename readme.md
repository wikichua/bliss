setup:

```
laravel new bliss-playground
cd bliss-playground
git clone git@github.com:wikichua/bliss.git ./packages/wikichua/bliss
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
    b. or adding use Wikichua\Bliss\Concerns\Queueable into job class, then you can use onQueue to create worker in ASYNC using artisan bliss:work

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

```
use \Wikichua\Bliss\Concerns\AllModelTraits;
public $searchableFields = ['name', 'group'];
```

then in controller or livewire component

```
app(config('bliss.Models.Permission'))->query()->searching($this->search ?? '')->get();
```

3.
sendAlertNotification

```
sendAlertNotification(
    message: 'Your message here',
    sender: auth()->id(),
    receivers: userIdsWithPermission('read-permissions'),
    link: route('your.route.name')
);
```

4.
settings helper vice versa

sample:

```
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
```

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



Vite Config

npm i vite-plugin-mkcert -D

import {defineConfig} from'vite'
import mkcert from'vite-plugin-mkcert'

// https://vitejs.dev/config/
export default defineConfig({
  server: {
    https: true
  },
  plugins: [mkcert()]
})
