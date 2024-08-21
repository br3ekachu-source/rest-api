<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('passport/token', [
    'uses' => 'AccessTokenController@issueToken',
    'as' => 'token',
    'middleware' => 'throttle',
]);

Route::get('passport/authorize', [
    'uses' => 'AuthorizationController@authorize',
    'as' => 'authorizations.authorize',
    'middleware' => 'web',
]);

$guard = config('passport.guard', null);

Route::middleware(['web', $guard ? 'auth:'.$guard : 'auth'])->group(function () {
    Route::post('passport/token/refresh', [
        'uses' => 'TransientTokenController@refresh',
        'as' => 'token.refresh',
    ]);

    Route::post('passport/authorize', [
        'uses' => 'ApproveAuthorizationController@approve',
        'as' => 'authorizations.approve',
    ]);

    Route::delete('passport/authorize', [
        'uses' => 'DenyAuthorizationController@deny',
        'as' => 'authorizations.deny',
    ]);

    Route::get('passport/tokens', [
        'uses' => 'AuthorizedAccessTokenController@forUser',
        'as' => 'tokens.index',
    ]);

    Route::delete('passport/tokens/{token_id}', [
        'uses' => 'AuthorizedAccessTokenController@destroy',
        'as' => 'tokens.destroy',
    ]);

    Route::get('passport/clients', [
        'uses' => 'ClientController@forUser',
        'as' => 'clients.index',
    ]);

    Route::post('passport/clients', [
        'uses' => 'ClientController@store',
        'as' => 'clients.store',
    ]);

    Route::put('passport/clients/{client_id}', [
        'uses' => 'ClientController@update',
        'as' => 'clients.update',
    ]);

    Route::delete('passport/clients/{client_id}', [
        'uses' => 'ClientController@destroy',
        'as' => 'clients.destroy',
    ]);

    Route::get('passport/scopes', [
        'uses' => 'ScopeController@all',
        'as' => 'scopes.index',
    ]);

    Route::get('passport/personal-access-tokens', [
        'uses' => 'PersonalAccessTokenController@forUser',
        'as' => 'personal.tokens.index',
    ]);

    Route::post('passport/personal-access-tokens', [
        'uses' => 'PersonalAccessTokenController@store',
        'as' => 'personal.tokens.store',
    ]);

    Route::delete('passport/personal-access-tokens/{token_id}', [
        'uses' => 'PersonalAccessTokenController@destroy',
        'as' => 'personal.tokens.destroy',
    ]);
});
