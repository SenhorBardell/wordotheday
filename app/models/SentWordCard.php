<?php

class SentWordCard extends \Eloquent {
	protected $fillable = ['word_id', 'category_id'];
    protected $hidden = ['created_at', 'updated_at', 'id'];

	public function word() {
		return $this->belongsToMany('WordCard');
	}

    public function category() {
        return $this->belongsToMany('Category');
    }

    public function scopeLast($query, $id) {
        return $query->where('word_id', '>', $id)->orderBy('word_id');
    }

	public function scopeByCat($query, $category) {
		return $query->where('category_id', $category->id);
	}
}