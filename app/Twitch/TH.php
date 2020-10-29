<?php

namespace App\Twitch;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class TH // TwitchHelpers
{
    public static function getOAuthToken($client)
    {
        $res = $client->post('https://id.twitch.tv/oauth2/token', [
            'query' => [
                'client_id' => env('TWITCH_CLIENT_ID'),
                'client_secret' => env('TWITCH_CLIENT_SECRET'),
                'grant_type' => 'client_credentials',
                'scope' => 'user:read:email',
            ],
        ]);

        $json = json_decode($res->getBody(), true);

        return $json['access_token'];
    }

    public static function getJsonFromTwitch($id)
    {
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $token = TH::getOAuthToken($client);

        $res = $client->get('https://api.twitch.tv/helix/clips', [
            'query' => ['id' => $id],
            'headers' => ['Client-ID' => env('TWITCH_CLIENT_ID'), 'Authorization' => 'Bearer ' . $token]
        ]);

        $json = json_decode($res->getBody(), true);

        return $json;
    }

    public static function getJsonFromTwitchUser($twitch_id)
    {
        $client = new \GuzzleHttp\Client(['http_errors' => false]);

        $res = $client->get('https://api.twitch.tv/helix/users', [
            'query' => ['id' => $twitch_id],
            'headers' => ['Client-ID' => env('TWITCH_CLIENT_ID')]
        ]);

        $json = json_decode($res->getBody(), true);

        return $json;
    }

    public static function clipExists($json)
    {
        if (array_key_exists('status', $json) && $json['status'] >= 400) {
            return false;
        } else if (count($json['data']) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getLinkID($link)
    {
        $url = parse_url($link);

        if (
            Arr::has($url, 'host')
            && Str::contains($url['host'], 'clips')
            && Str::contains($url['host'], 'twitch')
            && Str::contains($url['host'], 'tv')
            && Arr::has($url, 'path')
        ) {

            $id = Str::after($url['path'], '/');
            return $id;
        }

        if (
            Arr::has($url, 'host')
            && Str::contains($url['host'], 'm.')
            && Str::contains($url['host'], 'twitch')
            && Str::contains($url['host'], 'tv')
            && Arr::has($url, 'path')
        ) {

            $id = Str::after($url['path'], "/clip/");
            return $id;
        }

        return '_';
    }
}
