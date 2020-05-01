<?php

namespace App\Http\Controllers;

use App\Api;
use App\ApiLog;
use App\Post;
use App\Reply;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    /**
     * ------------------------------
     * Dashboard
     * ------------------------------
     * 
     * Send log request and token API
     * to dashboard.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function index($id){
        $api = Api::where('user_id', $id)->first();

        if(!empty($api->id)){
            return response()->json([
                'token' => [
                    'access_token' => $api->access_token,
                    'secret_token' => base64_decode($api->secret_token),
                ],
                'result' => 1,
                'log_success' => ApiLog::dashboard($api->id, 0),
                'log_error' => ApiLog::dashboard($api->id, 1),
                'date' => ApiLog::dashboard($api->id)
            ]);
        }else return response()->json(['result' => 0]);
    }

    /**
     * Generate access and secret token.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function generate($id){
        $token = $this->token($id);

        if($api = Api::where('user_id', $id)->first()){
            if(strtotime(now()) - strtotime($api->updated_at) > 3600){
                Api::find($api->id)->update([
                    'secret_token' => base64_encode($token[1])
                ]);
                $api = Api::where('user_id', $id)->first();
            }else return response()->json(['status' => 'error'], 406);
        }
        else $api = Api::create([
            'user_id' => $id,
            'access_token' => md5($token[0]),
            'secret_token' => base64_encode($token[1])
        ]);

        return response()->json([
            'token' => [
                'access_token' => $api->access_token,
                'secret_token' => base64_decode($api->secret_token)
            ]
        ]);
    }

    /**
     * Make the token.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    private function token($id){
        $user = User::find($id);
        $email = md5(Hash::make($user->email));
        
        return [
            base64_encode(bin2hex($user->id)).$email.'.'.base64_encode(bin2hex($user->created_at)),
            Hash::make(base64_encode(bin2hex(base64_encode(date('Y-m-d', strtotime($user->created_at)))).substr_replace($email, '-', 3, 1).bin2hex(base64_encode($user->id))))
        ];
    }

    /**
     * Paginate success log.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function getSuccess($id){
        return ApiLog::where('api_id', Api::where('user_id', $id)->first()->id)
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->paginate(5);
    }

    /**
     * Paginate failed log.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFailed($id){
        return ApiLog::where('api_id', Api::where('user_id', $id)->first()->id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(5);
    }

    /**
     * ------------------------------
     * API
     * ------------------------------
     * 
     * Send user data.
     *
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function user(Request $req){
        if($data = $this->header($req))
            return response()->json($data[0], $data[1]);
        else{
            $arr = $this->validator([$req->limit, $req->skip, $req->sort, $req->sort_by], 'user');

            $this->createLog($arr, $req);
            
            return $this->isAssoc($arr)
                ? response()->json(['error' => $arr], 422)
                : response()->json(User::apiGet($arr[0], $arr[1], $arr[2], $arr[3]));
        }
    }

    /**
     * Send random user data.
     *
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function userRand(Request $req){
        if($data = $this->header($req))
            return response()->json($data[0], $data[1]);
        else{
            $arr = $this->validator([$req->limit, $req->skip], 'user.rand');

            $this->createLog($arr, $req);
            
            return $this->isAssoc($arr)
                ? response()->json(['error' => $arr], 422)
                : response()->json(User::apiGetRand($arr[0], $arr[1]));
        }
    }

    /**
     * Send pagination user data.
     *
     * @param int $count
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function userPag($count = 10, Request $req){
        if($data = $this->header($req))
            return response()->json($data[0], $data[1]);
        else{
            $arr = $this->validator([$req->limit, $req->skip, $req->sort, $req->sort_by], 'user');

            $this->createLog($arr, $req);
            
            return $this->isAssoc($arr)
                ? response()->json(['error' => $arr], 422)
                : response()->json(User::apiGetPag($count, $arr[2], $arr[3]));
        }
    }

    /**
     * Send post data.
     *
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function post(Request $req){
        if($data = $this->header($req))
            return response()->json($data[0], $data[1]);
        else{
            $arr = $this->validator([$req->limit, $req->skip, $req->sort, $req->sort_by, $req->user_id], 'post');

            if(!$this->isAssoc($arr)){
                $data['post'] = Post::apiGet($arr[0], $arr[1], $arr[2], $arr[3], $arr[4]);
    
                if($arr[4]) $data['user_info'] = User::find($arr[4]);
            }
            $this->createLog($arr, $req);
            
            return $this->isAssoc($arr)
                ? response()->json(['error' => $arr], 422)
                : response()->json($data);
        }
    }

    /**
     * Send random post data.
     *
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function postRand(Request $req){
        if($data = $this->header($req))
            return response()->json($data[0], $data[1]);
        else{
            $arr = $this->validator([$req->limit, $req->skip], 'post.rand');

            if(!$this->isAssoc($arr))
                $data['post'] = Post::apiGetRand($arr[0], $arr[1]);
            $this->createLog($arr, $req);
            
            return $this->isAssoc($arr)
                ? response()->json(['error' => $arr], 422)
                : response()->json($data);
        }
    }

    /**
     * Send pagination post data.
     *
     * @param int $count
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function postPag($count = 10, Request $req){
        if($data = $this->header($req))
            return response()->json($data[0], $data[1]);
        else{
            $arr = $this->validator([$req->limit, $req->skip, $req->sort, $req->sort_by, $req->user_id], 'post');

            $this->createLog($arr, $req);
            
            return $this->isAssoc($arr)
                ? response()->json(['error' => $arr], 422)
                : response()->json(Post::apiGetPag($count, $arr[2], $arr[3]));
        }
    }

    /**
     * Send comment data.
     *
     * @param int $id
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function comment($id, Request $req){
        if($data = $this->header($req))
            return response()->json($data[0], $data[1]);
        else{
            $arr = $this->validator([$req->limit, $req->skip, $req->sort, $req->sort_by, $req->user_id], 'reply');

            if(!$this->isAssoc($arr)){
                $data['comment'] = Reply::apiGet($id, $arr[0], $arr[1], $arr[2], $arr[3], $arr[4]);
    
                if($arr[4]) $data['user_info'] = User::find($arr[4]);
            }
            $this->createLog($arr, $req);
            
            return $this->isAssoc($arr)
                ? response()->json(['error' => $arr], 422)
                : response()->json($data);
        }
    }

    /**
     * Send random comment data.
     *
     * @param int $id
     * @param Request $req
     * 
     * @return \Illuminate\Http\Response
     */
    public function commentRand($id, Request $req){
        if($data = $this->header($req))
            return response()->json($data[0], $data[1]);
        else{
            $arr = $this->validator([$req->limit, $req->skip], 'reply.rand');

            if(!$this->isAssoc($arr))
                $data['comment'] = Reply::apiGetRand($id, $arr[0], $arr[1]);
            $this->createLog($arr, $req);
            
            return $this->isAssoc($arr)
                ? response()->json(['error' => $arr], 422)
                : response()->json($data);
        }
    }

    /**
     * Send pagination comment data.
     *
     * @param mixed $id
     * @param integer $count
     * @param Request $req
     * 
     * @return void
     */
    public function commentPag($id, $count = 10, Request $req){
        if($data = $this->header($req))
            return response()->json($data[0], $data[1]);
        else{
            $arr = $this->validator([$req->limit, $req->skip, $req->sort, $req->sort_by, $req->user_id], 'reply');

            $this->createLog($arr, $req);
            
            return $this->isAssoc($arr)
                ? response()->json(['error' => $arr], 422)
                : response()->json(Reply::apiGetPag($count, $id, $arr[2], $arr[3]));
        }
    }

    /**
     * Validation API.
     *
     * @param array $req
     * @param string $type
     * 
     * @return void
     */
    private function validator($req, $type){
        $arr = [10, 0, 'desc', 'id', null];
        $err = [];

        if($type === 'user' || $type === 'user.rand' || $type === 'post' || $type === 'post.rand' || $type === 'reply' || $type === 'reply.rand'){
            if($req[0]){
                if(!is_numeric($req[0]))
                    $err['limit'] = 'Limit must be integer.';
                else $arr[0] = $req[0];
            }
        }
        if($type === 'user' || $type === 'user.rand' || $type === 'post' || $type === 'post.rand' || $type === 'reply' || $type === 'reply.rand'){
            if($req[1]){
                if(!is_numeric($req[1]))
                    $err['skip'] = 'Skip must be integer.';
                else $arr[1] = $req[1];
            }
        }
        if($type === 'user' || $type === 'post' || $type === 'reply'){
            if($req[2]){
                if(strtolower($req[2]) === 'desc' || strtolower($req[2]) === 'asc')
                    $arr[2] = $req[2];
                else $err['sort'] = "Sort only 'asc' and 'desc'.";
            }
        }
        if($type === 'user' || $type === 'post' || $type === 'reply'){
            if($req[3]){
                if(strtolower($req[3]) === 'id'
                    || strtolower($req[3]) === 'created_at'
                    || strtolower($req[3]) === 'name' && $type === 'user'
                    || strtolower($req[3]) === 'email' && $type === 'user'
                    || (strtolower($req[3]) === 'title' && $type === 'post')
                    || (strtolower($req[3]) === 'votes' && ($type === 'post' || $type === 'reply'))
                    || (strtolower($req[3]) === 'user_id' && ($type === 'post' || $type === 'reply')))
                    $arr[3] = $req[3];
                else{
                    $err['sort_by'] = "Sort By only 'id', ".
                        ($type === 'post'
                            ? "'title', 'user_id', 'votes',"
                            : ($type === 'reply' ? "'user_id', 'votes'," : "'name', 'email',")
                        )." and 'created_at'.";
                }
            }
        }
        if($type === 'post' || $type === 'reply'){
            if($req[4]){
                if(!is_numeric($req[4]))
                    $err['limit'] = 'User_id must be integer.';
                else $arr[4] = $req[4];
            }
        }

        return count($err) ? $err : $arr;
    }

    /**
     * Create log after request API.
     *
     * @param mixed $arr
     * @param Request $req
     */
    private function createLog($arr, $req){
        ApiLog::create([
            'api_id' => Api::where('access_token', $req->header('Access'))->first()->id,
            'description' => str_replace(url('/'), '', $req->fullUrl()),
            'status' => $this->isAssoc($arr) ? 1 : 0
        ]);
    }

    /**
     * Validation if associative array.
     *
     * @param Array $arr
     * 
     * @return bool
     */
    private function isAssoc(Array $arr){
        return array() === $arr
            ? false
            : array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Validation if request has header.
     *
     * @param Request $req
     * 
     * @return void
     */
    private function header($req){
        if($req->bearerToken()){
            if(!empty(Api::where('secret_token', base64_encode($req->bearerToken()))->first()->id)) $data = false;
            else $data = [['status' => 'Secret token not found'], 422];
        }else $data = [['status' => 'Unauthorized'], 401];

        if($data) return $data;
        
        if($req->headers->has('Access')){
            if(!empty(Api::where('access_token', $req->header('Access'))->first()->id)) $data = false;
            else $data = [['status' => 'Access token not found'], 422];
        }else $data = [['status' => 'Forbidden'], 403];

        return $data ? $data : false;
    }
}
