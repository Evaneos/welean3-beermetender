<?php
class Beer extends Eloquent {

	protected $table = 'beers';

	public function userFrom()
    {
        return $this->hasOne('User', 'id', 'user_from_id');
    }

    public function userTo()
    {
        return $this->hasOne('User', 'id', 'user_to_id');
    }
}