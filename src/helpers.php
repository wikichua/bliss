<?php

if (! function_exists('encodeURIComponent')) {
    function encodeURIComponent($str)
    {
        return Bliss::encodeURIComponent($str);
    }
}
if (! function_exists('cronjob_frequencies')) {
    function cronjob_frequencies()
    {
        return Bliss::cronjob_frequencies();
    }
}
if (! function_exists('timezones')) {
    function timezones()
    {
        return Bliss::timezones();
    }
}
if (! function_exists('settings')) {
    function settings($name, $default = '')
    {
        return Bliss::settings($name, $default);
    }
}
if (! function_exists('findHashTag')) {
    function findHashTag($string)
    {
        return Bliss::findHashTag($string);
    }
}
if (! function_exists('getModels')) {
    function getModels($path, $namespace)
    {
        return Bliss::getModels($path, $namespace);
    }
}
if (! function_exists('getModelsList')) {
    function getModelsList()
    {
        return Bliss::getModelsList();
    }
}
if (! function_exists('audit')) {
    function audit($message, $data = [], $model = null, $ip = '')
    {
        return Bliss::audit($message, $data, $model, $ip);
    }
}
if (! function_exists('agent')) {
    function agent()
    {
        return Bliss::agent();
    }
}
if (! function_exists('agents')) {
    function agents($key = '')
    {
        return Bliss::agents($key);
    }
}
if (! function_exists('opendns')) {
    function opendns()
    {
        return Bliss::opendns();
    }
}
if (! function_exists('iplocation')) {
    function iplocation($ip = '')
    {
        return Bliss::iplocation($ip);
    }
}
if (! function_exists('sendAlertNotification')) {
    function sendAlertNotification(string $message, int $sender, array $receivers, $link = '', $icon = '')
    {
        $arguments = compact('message', 'sender', 'receivers', 'link', 'icon');

        return Bliss::sendAlertNotification(...$arguments);
    }
}
if (! function_exists('sendAlertNotificationNow')) {
    function sendAlertNotificationNow(string $message, int $sender, array $receivers, $link = '', $icon = '')
    {
        $arguments = compact('message', 'sender', 'receivers', 'link', 'icon');

        return Bliss::sendAlertNotificationNow(...$arguments);
    }
}
if (! function_exists('randomWords')) {
    function randomWords($length = 3)
    {
        return Bliss::randomWords($length);
    }
}
if (! function_exists('is_closure')) {
    function is_closure($t)
    {
        return $t instanceof \Closure;
    }
}
if (! function_exists('userIdsWithPermission')) {
    function userIdsWithPermission(string $permission)
    {
        return Bliss::userIdsWithPermission($permission);
    }
}
if (! function_exists('searchVariants')) {
    function searchVariants(string $search)
    {
        return Bliss::searchVariants($search);
    }
}
if (! function_exists('dispatchToWorker')) {
    function dispatchToWorker(mixed $closure, $onQueue = 'default', $afterResponse = false, $delay = null)
    {
        $arguments = [
            'closure' => $closure,
            'onQueue' => $onQueue,
            'afterResponse' => $afterResponse,
            'delay' => $delay,
        ];

        return Bliss::dispatchToWorker(...$arguments);
    }
}
if (! function_exists('is_collections')) {
    function is_collections($collection): bool
    {
        return $collection instanceof \Illuminate\Support\Collection;
    }
}
if (! function_exists('jsonp_decode')) {
    function jsonp_decode($jsonp, $assoc = false)
    {
        if ($jsonp[0] !== '[' && $jsonp[0] !== '{') { // we have JSONP
            $jsonp = substr($jsonp, strpos($jsonp, '('));
        }

        return json_decode(trim($jsonp, '();'), $assoc);
    }
}
if (! function_exists('unitrim')) {
    function unitrim(string $string): string
    {
        return trim(preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', ' ', $string));
    }
}
