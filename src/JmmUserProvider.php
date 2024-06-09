<?php 

namespace stm\jmmLaravel;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

class JmmUserProvider implements UserProvider  
{

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    protected function createModel()
    {
        $class = $this->model;
        return new $class;
    }


    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return AuthanticatableRecord|null
     */
    public function retrieveByCredentials(array $credentials) : AuthanticatableRecord|null
    {
        
        $credentials = array_filter($credentials, function($key){
            return $key != 'password'; 
        }, ARRAY_FILTER_USE_KEY);

        if (empty($credentials)) return null;


        $model = $this->createModel();
        foreach($credentials as $key => $value)
        {
            $model = $model->where($key, $value);
        }
        $user = $model->first();
        $rememberTokenName = isset($model->rememberTokenName) ? $model->rememberTokenName : null;
        if($user) return new AuthanticatableRecord($user, $model->primaryKey, $rememberTokenName);
        return null;
    }


    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return AuthanticatableRecord|null
     */
    public function retrieveById($identifier) : AuthanticatableRecord|null
    {
        $model = $this->createModel();
        $user = $model->where($model->primaryKey, $identifier)->first();
        return new AuthanticatableRecord($user, $model->primaryKey, $model->rememberTokenName);
    }



    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return AuthanticatableRecord|null
     */
    public function retrieveByToken($identifier, $token) : AuthanticatableRecord|null
    {
        $user = $this->retrieveById($identifier);
        $rememberToken = $user->getRememberToken();
        return $rememberToken && hash_equals($rememberToken, $token) ? $user : null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  AuthanticatableRecord  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken($user, $token)
    {
        $model = $this->createModel();
        $model->where($model->primaryKey, $user->getAuthIdentifier())
            ->update([$user->getRememberTokenName() => $token]);
    }


    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials) : bool
    {
        if (is_null($plain = $credentials['password'])) {
            return false;
        }
        return Hash::check($plain, $user->getAuthPassword());
    }

}