<?php

namespace App\Http\Controllers;

use App\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * TODO項目コントローラー
 */
class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        logger()->info('TodoController->index()');
        logger()->debug('$request->user():' . $request->user());

        // ユーザーIDに一致するレコードを取得
        $data = Todo::where('user_id', $request->user()->id)
            ->orderByRaw('limit_at is null asc, limit_at asc, created_at desc')
            ->get();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        logger()->info('TodoController->create()');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        logger()->info('TodoController->store()');

        $row = Todo::create([
            'user_id' => $request->user()->id,
            'subject' => $request->subject,
            'detail' => $request->detail,
            'limit_at' => $request->limit_at != null ? Carbon::parse($request->limit_at)->toDateTimeString() : null,
            'complete_at' => null,
        ]);
        logger()->debug($row);

        return $row;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        logger()->info('TodoController->show()');
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        logger()->info('TodoController->edit()');
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::info('message');
        logger()->info('TodoController->update()');
        logger()->debug($request);

        // 更新対象を取得
        $todo = Todo::find($id);

        // 更新値を設定
        $todo->subject = $request->subject;
        $todo->detail = $request->detail;
        $todo->complete_at = $request->complete_at != null ? Carbon::parse($request->complete_at)->toDateTimeString() : null;
        $todo->limit_at = $request->limit_at != null ? Carbon::parse($request->limit_at)->toDateTimeString() : null;

        $todo->save();

        return response('', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Todo::destroy($id);
        return response('', 200);
    }
}
