<?php

class Mword extends \Eloquent {
	protected $fillable = ['user_id', 'word', 'answer', 'status', 'category_id'];
}