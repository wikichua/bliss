<?php

namespace Wikichua\Bliss;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Bliss
{
    public function getSettings(string $name, mixed $default = null)
    {
        if (! is_array(config('settings.'.$name)) && json_decode(config('settings.'.$name), 1)) {
            return json_decode(config('settings.'.$name), 1) ? json_decode(config('settings.'.$name), 1) : $default;
        }

        return config('settings.'.$name, $default);
    }

    public function settings(string $name, mixed $default = null)
    {
        $originalName = $name;
        $name = explode('.', $name);
        if (count($name) > 1) {
            $lastName = last($name);
            array_pop($name);
            $name = implode('.', $name);

            return array_flip($this->getSettings($name, $default))[$lastName] ?? $this->getSettings($originalName, $default);
        }

        return $this->getSettings($originalName, $default);
    }

    public function findHashTag(string $string)
    {
        preg_match_all('/#(\\w+)/', $string, $matches);

        return $matches[1];
    }

    public function getModels()
    {
        $autoload = array_keys(include base_path('/vendor/composer/autoload_classmap.php'));
        $models = collect(config('bliss.Models'))->values()->toArray();
        foreach ($autoload as $namespace) {
            if (Str::contains($namespace, [app()->getNamespace(), 'Wikichua\Bliss\Exp\Models']) && ! Str::endsWith($namespace, 'Models\User')) {
                if (Str::contains($namespace, 'Models')) {
                    $models[] = $namespace;
                }
            }
        }

        return array_unique($models);
    }

    public function getModelsList()
    {
        return $this->getModels();
    }

    public function opendns()
    {
        return trim(shell_exec('dig +short myip.opendns.com @resolver1.opendns.com'));
    }

    public function iplocation(string $ip = '')
    {
        if ('' == $ip) {
            /*$ip = Cache::remember('sessions-ip-'.session()->getId(), (60 * 60 * 24 * 30), function () {
                return $this->opendns();
            });*/
            $ip = $this->opendns();
        }
        $fields = [
            'status', 'message', 'continent', 'continentCode', 'country', 'countryCode', 'region', 'regionName', 'city', 'district', 'zip', 'lat', 'lon', 'timezone', 'offset', 'currency', 'isp', 'org', 'as', 'asname', 'reverse', 'mobile', 'proxy', 'hosting', 'query',
        ];
        $results = Cache::remember('iplocation-'.$ip, (60 * 60 * 24 * 30), function () use ($fields) {
            return json_decode(\Http::get('//ip-api.com/json/', ['fields' => implode(',', $fields)]), 1);
        });

        return array_merge($results, ['locale' => request()->route('locale')]);
    }

    public function agent()
    {
        return new \Jenssegers\Agent\Agent();
    }

    public function agents(string $key = '')
    {
        $agent = $this->agent();
        $data = [
            'languages' => $agent->languages(),
            'device' => $agent->device(),
            'platform' => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
            'browser' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'isDesktop' => $agent->isDesktop(),
            'isPhone' => $agent->isPhone(),
            'isRobot' => $agent->isRobot(),
            'headers' => request()->headers->all(),
            'ips' => request()->ips(),
        ];
        if ('' != $key && isset($data[$key])) {
            return $data[$key];
        }

        return $data;
    }

    public function audit(string $message, array $data = [], $model = null, string $ip = '')
    {
        // unset hidden form fields
        foreach (['_token', '_method', '_submit'] as $unset_key) {
            if (isset($data[$unset_key])) {
                unset($data[$unset_key]);
            }
        }

        $iplocation = $this->iplocation();
        if ('' == $ip) {
            $ip = $iplocation['query'];
        }

        app(config('bliss.Models.Audit'))->create([
            'user_id' => auth()->check() ? auth()->user()->id : 1,
            'model_id' => $model ? $model->id : null,
            'model_class' => $model ? $model::class : null,
            'message' => $message,
            'data' => $data ? $data : null,
            'opendns' => $ip,
            'agents' => $this->agents(),
            'iplocation' => $iplocation,
        ]);
    }

    public function timezones()
    {
        return array_combine(timezone_identifiers_list(), timezone_identifiers_list());
    }

    public function cronjob_frequencies()
    {
        return [
            'everyMinute' => 'Every Minute',
            'everyTwoMinutes' => 'Every Two Minutes',
            'everyThreeMinutes' => 'Every Three Minutes',
            'everyFourMinutes' => 'Every Four Minutes',
            'everyFiveMinutes' => 'Every Five Minutes',
            'everyTenMinutes' => 'Every Ten Minutes',
            'everyFifteenMinutes' => 'Every Fifteen Minutes',
            'everyThirtyMinutes' => 'Every Thirty Minutes',
            'everyTwoHours' => 'Every Two Hours',
            'everyThreeHours' => 'Every Three Hours',
            'everyFourHours' => 'Every Four Hours',
            'everySixHours' => 'Every Six Hours',
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'yearly' => 'Yearly',
        ];
    }

    public function sendAlertNotification(string $message, int $sender, array $receivers, $link = '', $icon = '')
    {
        dispatch(function () use ($message, $sender, $receivers, $link, $icon) {
            $chunkedReceivers = array_chunk($receivers, 1000);
            foreach ($chunkedReceivers as $receivers) {
                foreach ($receivers as $receiver) {
                    if ($receiver != $sender || $sender == 1) {
                        app(config('bliss.Models.Alert'))->query()->create([
                            'icon' => $icon,
                            'link' => $link,
                            'message' => $message,
                            'sender_id' => $sender,
                            'receiver_id' => $receiver,
                        ]);
                    }
                }
            }
        })->afterResponse();

        return true;
    }

    public function sendAlertNotificationNow(string $message, int $sender, array $receivers, $link = '', $icon = '')
    {
        $chunkedReceivers = array_chunk($receivers, 1000);
        foreach ($chunkedReceivers as $receivers) {
            foreach ($receivers as $receiver) {
                if ($receiver != $sender || $sender == 1) {
                    app(config('bliss.Models.Alert'))->query()->create([
                        'icon' => $icon,
                        'link' => $link,
                        'message' => $message,
                        'sender_id' => $sender,
                        'receiver_id' => $receiver,
                    ]);
                }
            }
        }

        return true;
    }

    public function randomWords(int $length = 3)
    {
        $words = cache()->rememberForever('humanRandomWord', function () {
            $responses = \Illuminate\Support\Facades\Http::pool(fn (\Illuminate\Http\Client\Pool $pool) => [
                $pool->get('https://raw.githubusercontent.com/AlessandroMinoccheri/human-names/master/data/male-human-names-en.json'),
                $pool->get('https://raw.githubusercontent.com/AlessandroMinoccheri/human-names/master/data/female-human-names-en.json'),
            ]);
            $words = [];
            foreach ($responses as $response) {
                if ($response->ok()) {
                    $words = array_merge($words, $response->json());
                }
            }

            return $words;
        });
        $blocks = \Arr::random($words, $length);

        return implode('-', $blocks);
    }

    public function userIdsWithPermission(string $permission)
    {
        $permission = app(config('bliss.Models.Permission'))->query()->where('name', str_slug($permission))->first();

        return cache()
            ->rememberForever('permission_users:'.$permission->id,
                function () use ($permission) {
                    $ids = app(config('bliss.Models.Role'))->query()->where('name', 'Admin')->first()->users()->pluck('users.id')->toArray();
                    $roles = $permission->roles;
                    foreach ($roles as $role) {
                        $ids = array_merge($ids, $role->users()->pluck('users.id')->toArray());
                    }

                    return $ids = array_unique($ids);
                });
    }

    public function searchVariants(string $search): array
    {
        $search = '.*'.$search;
        $searches = [
            $search,
            str($search)->camel(),
            str($search)->headline(),
            str($search)->kebab(),
            str($search)->lcfirst(),
            str($search)->lower(),
            str($search)->plural(),
            str($search)->pluralStudly(),
            str($search)->singular(),
            str($search)->slug(),
            str($search)->snake(),
            str($search)->studly(),
            str($search)->title(),
            str($search)->ucfirst(),
            str($search)->upper(),
        ] + str($search)->ucsplit()->toArray();

        return $searches = array_unique($searches);
    }

    public function dispatchToWorker(mixed $closure, string $onQueue = 'default', bool $afterResponse = false, Carbon $delay = null)
    {
        $toInvokes = [];
        if (! is_array($closure)) {
            $toInvokes[] = $closure;
        } else {
            $toInvokes = $closure;
        }

        foreach ($toInvokes as $index => $toInvoke) {
            if (! is_closure($toInvoke) && ($toInvoke instanceof \Illuminate\Contracts\Queue\ShouldQueue) == false) {
                throw new \Exception('Error Processing Request', 1);
            }
            if ($afterResponse) {
                dispatch($toInvoke)->afterResponse();
            } else {
                $batch = $onQueue;
                $onQueueName = $onQueue.':'.str()->random(10);
                if ($delay instanceof Carbon) {
                    dispatch($toInvoke)->delay($delay)->onQueue($onQueueName);
                } else {
                    dispatch($toInvoke)->onQueue($onQueueName);
                }
            }
        }
    }
}
