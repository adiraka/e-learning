<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    protected $table = 'forum_comment';

    protected $fillable = [
    	'forum_post_id', 'users_account_id', 'konten', 'created_at', 'updated_at'
    ];

    public function post() {
    	return $this->belongsTo('App\Models\ForumPost', 'forum_post_id', 'id');
    }

    public function user_account() {
    	return $this->belongsTo('App\Models\UserAccount', 'users_account_id', 'id');
    }
}
