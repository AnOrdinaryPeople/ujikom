<?php

namespace App\Http\Controllers;

use App\HistoryDelete as Del;
use App\Notification as Notif;
use App\Post;
use App\Reply;
use App\Report;
use App\Status;
use App\User;
use App\Vote;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Send data for chart and total
     * of history deletes,
     * user, post, and report.
     *
     * @return void
     */
    public function home(){
        return response()->json([
            'post' => Post::adminHome(),
            'type' => Post::adminType(),
            'report' => Post::adminHome('reports'),
            'user' => Post::adminHome('users'),
            'typee' => [Post::adminTypee(1), Post::adminTypee(2), Post::adminTypee(3)],
            'del' => Del::orderBy('id', 'desc')->limit(20)->get(),
            'total' => [User::count(), Post::count(), Report::count()]
        ]);
    }

    /**
     * Showing table users.
     *
     * @return \Illuminate\Http\Response
     */
    public function users(){
        return response()->json(User::adminUser());
    }

    /**
     * Send message to selected user.
     *
     * @param int $id
     * @param Request $req
     */
    public function notifSend($id, Request $req){
        Notif::create([
            'user_id' => $id,
            'desc' => $req->desc
        ]);
    }

    /**
     * Delete selected user with report,
     * notification, status, reply, post,
     * and vote.
     *
     * @param int $id
     * @param string $desc
     */
    public function userDel($id, $desc){
        $u = User::find($id);

        Del::create([
            'name' => $u->name,
            'email' => $u->email,
            'desc' => base64_decode($desc)
        ]);

        Report::where('user_id', $id)->delete();
        Report::where('type', 2)->where('suspect_id', $id)->delete();
        Notif::where('user_id', $id)->delete();
        Status::where('user_id', $id)->delete();
        Reply::where('user_id', $id)->delete();
        Post::where('user_id', $id)->delete();
        Vote::where('user_id', $id)->delete();
        User::destroy($id);
    }

    /**
     * Showing table posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function posts(){
        return response()->json(Post::adminPost());
    }

    /**
     * Delete reply and send message
     * to user for deleting the reply.
     *
     * @param int $id
     * @param int $user
     * @param Request $req
     */
    public function postsRepDel($id, $user, Request $req){
        $this->del($user, 'komentar di postingan '.Post::find(Reply::find($id)->post_id)->title);

        Reply::where('has_child_id', $id)->delete();
        Reply::destroy($id);

        Notif::create([
            'user_id' => $user,
            'desc' => $req->desc
        ]);
    }

    /**
     * Delete post with reply and send
     * message to user for deleting the reply.
     *
     * @param mixed $id
     * @param mixed $user
     * @param Request $req
     */
    public function postsDel($id, $user, Request $req){
        $this->del($user, 'postingan '.Post::find($id)->title);

        Reply::where('post_id', $id)->delete();
        Post::destroy($id);

        Notif::create([
            'user_id' => $user,
            'desc' => $req->desc
        ]);
    }

    /**
     * Showing table reports.
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(){
        return response()->json(Report::adminReport());
    }

    /**
     * Show detail of selected report.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function reportsDet($id){
        return response()->json(Notif::where('report_id', $id)->first());
    }

    /**
     * Reply report for user.
     *
     * @param int $id
     * @param int $user
     * @param Request $req
     */
    public function reportsRep($id, $user, Request $req){
        Report::find($id)->update(['read' => 1]);
        Notif::create([
            'user_id' => $user,
            'desc' => $req->desc,
            'report_id' => $id
        ]);
    }

    /**
     * Delete selected report.
     *
     * @param int $id
     * @param int $user
     * @param Request $req
     */
    public function reportsDel($id, $user, Request $req){
        $u = User::find($user);

        Del::create([
            'name' => $u->name,
            'email' => $u->email,
            'desc' => $req->desc
        ]);
        Notif::where('report_id', $id)->delete();
        Report::destroy($id);
    }

    /**
     * Showing messages.
     *
     * @return \Illuminate\Http\Response
     */
    public function msg(){
        return response()->json(Notif::adminNotif());
    }

    /**
     * Update selected message.
     *
     * @param int $id
     * @param Request $req
     */
    public function msgUpdate($id, Request $req){
        Notif::find($id)->update(['desc' => $req->desc]);
    }

    /**
     * Delete selected message.
     *
     * @param int $id
     * @param int $user
     */
    public function msgDel($id, $user){
        $this->del(1, 'pesan untuk '.User::find($user)->name);
        Notif::destroy($id);
    }

    /**
     * Showing table history deletes.
     *
     * @return \Illuminate\Http\Response
     */
    public function history(){
        return response()->json(Del::orderBy('id', 'desc')->paginate(10));
    }

    /**
     * Create history delete.
     *
     * @param int $user
     * @param int $desc
     */
    private function del($user, $desc){
        $u = User::find($user);

        Del::create([
            'name' => $u->name,
            'email' => $u->email,
            'desc' => 'Menghapus '.$desc
        ]);
    }
}
