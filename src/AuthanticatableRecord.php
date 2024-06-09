<?php

namespace stm\jmmLaravel;
use Illuminate\Contracts\Auth\Authenticatable;
use stm\jmm\Record;


/**
 * @method static only()
 */
class AuthanticatableRecord extends Record implements Authenticatable {
    
    
    /**
     * The column name of the "remember me" token.
     *
     * @var string
     */
    public $rememberTokenName = 'rememberMe';

    public $primaryKey;


    public function __construct(Record $record, $primaryKey, $rememberTokenName)
    {
        $this->record = $record->toArray();
        $this->primaryKey = $primaryKey;
        if($rememberTokenName) $this->rememberTokenName = $rememberTokenName;
    } 

     /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier(){
        return $this->{$this->getAuthIdentifierName()};
    }


    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(){
        return $this->primaryKey;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword(){
        return $this->password;
    }

     /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken(){
        if (! empty($this->getRememberTokenName())) {
           return (string) $this->{$this->getRememberTokenName()};
        }
    }
    
    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return $this->rememberTokenName;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value){
       
        $this->{$this->getRememberTokenName()} = $value;
    }

}