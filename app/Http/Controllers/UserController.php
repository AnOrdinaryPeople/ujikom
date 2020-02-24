<?php

namespace App\Http\Controllers;

use App\Post;
use App\Reply;
use App\Report;
use App\Status as Warn;
use App\User;
use App\Vote;
use App\Notification as Notif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Send user feed.
     *
     * @param  int $user
     *
     * @return \Illuminate\Http\Response
     */
    public function home($user){
        return response()->json([
            'art' => Post::sideRand(2, 5),
            'work' => Post::sideRand(3, 3),
            'voted' => Vote::findVoted($user)
        ]);
    }

    /**
     * Get user post.
     *
     * @param int $user
     * @param int $skip
     * 
     * @return \Illuminate\Http\Response
     */
    public function mine($user, $skip){
        return response()->json(Post::getMine($user, $skip));
    }

    /**
     * Load more posts.
     *
     * @param  int $skip
     * @param  int $user
     *
     * @return \Illuminate\Http\Response
     */
    public function homeSkip($skip, $user){
        return response()->json(Post::getHome($skip));
    }

    /**
     * Send random info at sidebar.
     *
     * @param  int $type
     * @param  int $take
     *
     * @return \Illuminate\Http\Response
     */
    public function sideRand($type, $take){
        return response()->json(Post::sideRand($type, $take));
    }

    /**
     * Store vote to database.
     *
     * @param  int $id
     * @param  int $type
     * @param  int $user
     * @param  \Illuminate\Http\Request $req
     *
     * @return \Illuminate\Http\Response
     */
    public function vote($id, $type, $user, Request $req){
        if(Vote::checker($id, $type, $user))
            Vote::checker($id, $type, $user, true)->update([
                'vote' => $req->vote
            ]);
        else Vote::create([
            'type' => $type,
            'type_id' => $id,
            'user_id' => $user,
            'vote' => $req->vote,
            'parent_id' => $req->parent
        ]);
        $count = Vote::countAll($type, $id);

        switch ($type) {
            case 1:
            case 2:
            case 3:
                Post::updater($id, $count);
                break;
            case 4:
                Reply::updater($id, $count);
                break;
            
            default:
                # code...
                break;
        }

        return response()->json(Vote::findVoted($user));
    }

    /**
     * Get more info from selected data.
     *
     * @param  int $id
     * @param  int $user
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($id, $user){
        $d = Post::getDetail($id);
        $b = Reply::best($id);

        return response()->json([
            'post' => $d,
            'voted' => Vote::votedDetail($user, $d->type, $id),
            'child_vote' => Vote::votedDetail($user, 4, 0, $id),
            'reply' => $this->reply($id, $user, true),
            'best' => $b,
            'child_best' => $b ? Reply::getChildReply($b->id) : [],
            'warn' => Warn::getWarn($id)
        ]);
    }

    /**
     * Get replies from selected data.
     *
     * @param  int $id
     * @param  int $user
     * @param  bool $r
     *
     * @return object
     */
    public function reply($id, $user, $r = false){
        $i = 0;
        $data = Reply::getReply($id);

        if(count($data)){
            foreach($data as $key){
                if($child = Reply::getChildReply($key->id)){
                    foreach($child as $c) $yeet[] = [
                        'id' => $c->id,
                        'desc' => $c->desc,
                        'votes' => $c->votes,
                        'created_at' => $c->created_at,
                        'updated_at' => $c->updated_at,
                        'name' => $c->name,
                        'avatar' => $c->avatar,
                        'user_id' => $c->user_id
                    ];
                }
                $data[$i] = [
                    'id' => $key->id,
                    'desc' => $key->desc,
                    'votes' => $key->votes,
                    'created_at' => $key->created_at,
                    'updated_at' => $key->updated_at,
                    'name' => $key->name,
                    'avatar' => $key->avatar,
                    'user_id' => $key->user_id,
                    'has_child' => !empty($yeet) ? $yeet : []
                ];
                unset($yeet);
                $i++;
            }
        }

        return $r ? $data : response()->json([
            'reply' => $data,
            'voted' => Vote::votedDetail($user, Post::getDetail($id)->type, $id),
            'child_vote' => Vote::votedDetail($user, 4, 0, $id)
        ]);
    }

    /**
     * Load more child reply.
     *
     * @param int $id
     * @param int $skip
     * 
     * @return \Illuminate\Http\Response
     */
    public function moreChild($id, $skip){
        return response()->json(Reply::getChildReply($id, $skip));
    }

    /**
     * Set reply to best answer.
     *
     * @param int $id
     * @param int $post
     * @param int $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function best($id, $post, $user){
        if($hasBest = Reply::best($post))
            Reply::setBest($hasBest->id, 0);

        Reply::setBest($id, 1);

        $uzer = Post::find($post);
        if($user != $uzer->user_id) Notif::create([
            'user_id' => $uzer->user_id,
            'desc' => User::find($user)->name. ' menandai jawabanmu di postingan '.$uzer->title,
            'sauce' => url('home/detail/'.$post)
        ]);
        $b = Reply::best($post);

        return response()->json([
            'best' => $b,
            'child_best' => $b ? Reply::getChildReply($b->id) : []
        ]);
    }

    /**
     * Set warning to selected post.
     *
     * @param int $post
     * @param int $user
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function warn($post, $user, Request $req){
        $w = Warn::create([
            'user_id' => $user,
            'post_id' => $post,
            'title' => $req->title,
            'desc' => $req->desc
        ]);

        $p = Post::find($post);
        $u = User::find($user);
        Notif::create([
            'user_id' => $p->user_id,
            'desc' => $u->name. ' memberi peringatan di postingan '.$p->title,
            'sauce' => url('home/detail/'.$post)
        ]);

        return response()->json(['u' => $u, 'p' => $w]);
    }

    /**
     * Update warning.
     *
     * @param int $warn
     * @param int $post
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function warnUpdate($warn, $post, Request $req){
        Warn::find($warn)->update([
            'title' => $req->title,
            'desc' => $req->desc
        ]);

        $p = Post::find($post);
        $u = User::find($warn);
        Notif::create([
            'user_id' => $p->user_id,
            'desc' => $u->name. ' meng-update peringatan di postingan '.$p->title,
            'sauce' => url('home/detail/'.$post)
        ]);

        return response()->json(['u' => $u, 'p' => Warn::find($warn)]);
    }

    /**
     * Delete warning.
     *
     * @param int $warn
     * @param int $post
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function warnDel($warn, $post, Request $req){
        $p = Post::find($post);

        Notif::create([
            'user_id' => $p->user_id,
            'desc' => User::find(Warn::find($warn)->user_id)->name. ' menghapus peringatan di postingan '.$p->title,
            'sauce' => url('home/detail/'.$post)
        ]);
        Warn::destroy($warn);

        return response()->json(['msg' => 'done']);
    }

    /**
     * Show user who reply the post
     * for report user.
     *
     * @param int $id
     * @param int $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function detailChild($id, $user){
        return response()->json(Reply::detailChild($id, $user));
    }
    public function reportSend($post, $user, Request $req){
        Report::create([
            'type' => $req->type,
            'suspect_id' => $req->type == 2 ? $req->suspect_id : $post,
            'desc' => $req->desc,
            'user_id' => $user
        ]);

        return response()->json(['msg' => 'done']);
    }

    /**
     * Search post.
     *
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function search(Request $req){
        return response()->json([
            0 => Post::search(1, $req->q),
            1 => Post::search(2, $req->q),
            2 => Post::search(3, $req->q),
        ]);
    }

    /**
     * Send user reply to selected post.
     *
     * @param int $id
     * @param int $user
     * @param int $owner
     * @param \Illuminate\Http\Request $req
     * 
     * @return object
     */
    public function sendReply($id, $user, $owner, Request $req){
        Reply::create([
            'post_id' => $id,
            'user_id' => $user,
            'desc' => $req->desc
        ]);

        if($user !== $owner) Notif::create([
            'user_id' => $owner,
            'desc' => User::find($user)->name.' menjawab postinganmu '.Post::find($id)->title,
            'sauce' => url('home/detail/'.$id)
        ]);

        return response()->json(['msg' => 'done']);
    }

    /**
     * Send child reply to selected reply.
     *
     * @param int $id
     * @param int $user
     * @param int $owner
     * @param int $reply
     * @param \Illuminate\Http\Request $req
     * 
     * @return void
     */
    public function sendChild($id, $user, $owner, $reply, Request $req){
        Reply::create([
            'post_id' => $id,
            'user_id' => $user,
            'desc' => $req->desc,
            'has_child_id' => $reply
        ]);
        
        $p = Post::find($id);
        if($user !== $owner) Notif::create([
            'user_id' => $owner,
            'desc' => User::find($user)->name.' menjawab komentarmu di postingan '.$p->title,
            'sauce' => url('home/detail/'.$p->id)
        ]);

        return response()->json(['msg' => 'done']);
    }

    /**
     * Update reply.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateRep($id, Request $req){
        Reply::find($id)->update($req->all());

        return response()->json(['msg' => 'done']);
    }
    public function destroyRep($id){
        Reply::where('has_child_id', $id)->delete();
        Reply::destroy($id);

        return response()->json(['msg' => 'done']);
    }

    /**
     * Store post data to database.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request $req
     *
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $req){
        $check = Validator::make($req->all(), [
            'type' => 'required|min:1|max:3',
            'title' => 'required|string',
            'location' => 'nullable|string',
            'desc' => 'required|string'
        ]);

        if($check->fails())
            return response()->json($check->errors(), 422);
        else{
            $resp = Post::create([
                'type' => $req->type,
                'title' => $req->title,
                'location' => $req->location,
                'desc' => $req->desc,
                'user_id' => $id
            ]);

            return response()->json($resp);
        }
    }

    /**
     * Send selected data.
     *
     * @param int $user
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit($user, $id){
        return response()->json(Post::where('user_id', $user)
            ->where('id', $id)
            ->first()
        );
    }

    /**
     * Update post.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $req){
        $check = Validator::make($req->all(), [
            'type' => 'required|min:1|max:3',
            'title' => 'required|string',
            'location' => 'nullable|string',
            'desc' => 'required|string'
        ]);

        if($check->fails())
            return response()->json($check->errors(), 422);
        else{
            Post::find($id)->update($req->all());

            return response()->json(['msg' => 'done']);
        }
    }

    /**
     * Destroy post with replies.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        Reply::where('post_id', $id)->delete();
        Post::destroy($id);

        return response()->json(['msg' => 'done']);
    }

    /**
     * Count notification for authenticated user.
     *
     * @param int $user
     *
     * @return \Illuminate\Http\Response
     */
    public function notifCount($user){
        return response()->json(Notif::where('user_id', $user)->count());
    }

    /**
     * Get notification for authenticated user.
     *
     * @param int $user
     *
     * @return \Illuminate\Http\Response
     */
    public function notif($user, $skip){
        return response()->json(Notif::where('user_id', $user)
            ->limit(10)
            ->skip($skip)
            ->get()
        );
    }

    /**
     * Set readed to selected notification.
     *
     * @param int $id
     */
    public function notifRead($id){
        Notif::find($id)->update(['read' => 1]);
    }

    /**
     * Delete selected notification.
     *
     * @param int $id
     * @param int $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function notifDel($id, $user){
        Notif::destroy($id);

        return response()->json(Notif::where('user_id', $user)->count());
    }

    /**
     * Set readed to all notification.
     *
     * @param int $user
     */
    public function notifAll($user){
        Notif::where('user_id', $user)->update(['read' => 1]);
    }

    /**
     * Delete all notification.
     *
     * @param int $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function notifAllDel($user){
        Notif::where('user_id', $user)->delete();
    }

    /**
     * Detail message from admin.
     *
     * @param int $id
     * @param int $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function notifDetail($id, $user){
        return response()->json(Notif::where('id', $id)
            ->where('user_id', $user)
            ->where('sauce', null)
            ->first()
        );
    }
}
