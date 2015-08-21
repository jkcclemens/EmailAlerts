<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Notification
 *
 * @property integer $id
 * @property integer $email_id
 * @property string $subject
 * @property string $data
 * @property-read \Illuminate\Database\Eloquent\Collection|\$related[] $morphedByMany
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereEmailId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereData($value)
 */
class Notification extends Model {

    public function email() {
        return $this->belongsTo('App\Email');
    }

}
