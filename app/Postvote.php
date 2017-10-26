<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Postvote extends Model
{
    protected $table = 'post_vote';
    protected $fillable = ['post_id','user_id','vote_status'];
    
    /**
     * used to join with Modules\Admin\Models\User
     * 
     * @return type
     */
    public function voterProfile()
    {
        return $this->hasMany('App\Users', 'id', 'user_id');
    }
    
    /**
     * Store post vote data.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function store($postVoteData) {
       $postVote = Postvote::create($postVoteData);
       return $postVote;
    }
    
    /**
     * Check user is able to vote or not.
     *
     * @param  int  $id, $userId, $voteFlag
     * @return \Illuminate\Http\Response
     */
    public static function checkUserPostVote($id, $userId, $voteFlag) {
        $postVoteData = collect(Postvote::select('id')->where('post_id', '=', $id)->where('user_id', '=', $userId)->where('vote_status', '=', $voteFlag)->get());
        $postVoteData = $postVoteData->toArray();
        
        if(!empty($postVoteData)) {
           return true;
        } else {
           return false;
        }
    }
}
