<?php
namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\ $related[] $morphedByMany
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $email
 * @property string $password
 * @method static Builder|\App\User whereId($value)
 * @method static Builder|\App\User whereCreatedAt($value)
 * @method static Builder|\App\User whereUpdatedAt($value)
 * @method static Builder|\App\User whereEmail($value)
 * @method static Builder|\App\User wherePassword($value)
 * @property string $remember_token
 * @method static Builder|\App\User whereRememberToken($value)
 */
class User extends Model implements Authenticatable {

    public function emails() {
        return $this->hasMany('App\Email');
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken() {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName() {
        return "remember_token";
    }
}
