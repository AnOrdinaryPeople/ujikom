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
    public function users(){
        return response()->json(User::adminUser());
    }
    public function notifSend($id, Request $req){
        Notif::create([
            'user_id' => $id,
            'desc' => $req->desc
        ]);
    }
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
    public function posts(){
        return response()->json(Post::adminPost());
    }
    public function postsRepDel($id, $user, Request $req){
        $this->del($user, 'komentar di postingan '.Post::find(Reply::find($id)->post_id)->title);

        Reply::where('has_child_id', $id)->delete();
        Reply::destroy($id);

        Notif::create([
            'user_id' => $user,
            'desc' => $req->desc
        ]);
    }
    public function postsDel($id, $user, Request $req){
        $this->del($user, 'postingan '.Post::find($id)->title);

        Reply::where('post_id', $id)->delete();
        Post::destroy($id);

        Notif::create([
            'user_id' => $user,
            'desc' => $req->desc
        ]);
    }
    public function reports(){
        return response()->json(Report::adminReport());
    }
    public function reportsDet($id){
        return response()->json(Notif::where('report_id', $id)->first());
    }
    public function reportsRep($id, $user, Request $req){
        Report::find($id)->update(['read' => 1]);
        Notif::create([
            'user_id' => $user,
            'desc' => $req->desc,
            'report_id' => $id
        ]);
    }
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
    public function msg(){
        return response()->json(Notif::adminNotif());
    }
    public function msgUpdate($id, Request $req){
        Notif::find($id)->update(['desc' => $req->desc]);
    }
    public function msgDel($id, $user){
        $this->del(1, 'pesan untuk '.User::find($user)->name);
        Notif::destroy($id);
    }
    public function history(){
        return response()->json(Del::orderBy('id', 'desc')->paginate(10));
    }
    private function del($user, $desc){
        $u = User::find($user);

        Del::create([
            'name' => $u->name,
            'email' => $u->email,
            'desc' => 'Menghapus '.$desc
        ]);
    }
}
