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

    public function email() {
        return $this->belongsTo('App\Email');
    }

}
