<?php namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
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
 * @method static Builder|\App\Email whereId($value)
 * @method static Builder|\App\Email whereCreatedAt($value)
 * @method static Builder|\App\Email whereUpdatedAt($value)
 * @method static Builder|\App\Email whereUserId($value)
 * @method static Builder|\App\Email whereEmail($value)
 * @method static Builder|\App\Email whereVerified($value)
 * @property-read \App\User $user
 * @property string $verification_key
 * @property-read \Illuminate\Database\Eloquent\Collection|\ $related[] $morphedByMany
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereVerificationKey($value)
 */
class Email extends Model {

    public function __construct() {
        parent::__construct();
        $this->verification_key = Str::random(16);
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}
