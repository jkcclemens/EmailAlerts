<?php namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Email
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $user_id
 * @property string $email
 * @property boolean $verified
 * @property string $verification_key
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Notification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\$related[] $morphedByMany
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereVerificationKey($value)
 */
class Email extends Model {

    public function __construct() {
        parent::__construct();
        $this->verification_key = Str::random(16);
    }

    public function isPrimary() {
        return $this->email == $this->user->email;
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function notifications() {
        return $this->hasMany('App\Notification');
    }

}
