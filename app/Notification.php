<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Notification
 *
 * @property integer $id
 * @property integer $email_id
 * @property string $subject
 * @property string $data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Email $email
 * @property-read \Illuminate\Database\Eloquent\Collection|\ $related[] $morphedByMany
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereEmailId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereUpdatedAt($value)
 */
class Notification extends Model {

    protected $hidden = ['id', 'email_id', 'updated_at', 'user_id'];

    public function email() {
        return $this->belongsTo('App\Email');
    }

    public function toArray() {
        $array = parent::toArray();
        if (array_key_exists('email', $array)) {
            $array['email'] = $array['email']['email'];
        }
        if (array_key_exists('created_at', $array)) {
            $array['created_at'] = $this->created_at->timestamp * 1000;
        }
        return $array;
    }

}
