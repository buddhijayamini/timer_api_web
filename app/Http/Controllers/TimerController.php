<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Timer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $timer = Timer::all();
        return response()->json($timer);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
       return request()->get('index') ;
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'number' => 'required|numeric|min:2',
            'timer' => 'required|numeric',//date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $data = [];
            $randomNumber = $this->generateUniqueCode();
            $qty = $request->number;
            $exTime = $request->timer;
            ini_set('memory_limit', '1024M');

            if ( $qty <= 50000) {
                $exTime = 30;
            }else if( $qty >= 50001 && $qty <= 100000) {
                $exTime = 60;
            }else if( $qty >= 100001 && $qty <= 150000) {
                $exTime = 120;
            }

            for($i=0; $i<$qty; $i++)
            {
                array_push($data,$randomNumber);
                $randomNumber++;
            }

            $collection = collect($data);
            $chunks = $collection->chunk(1);
            $chunks->toArray();

            foreach($chunks as $chunk)
             {
                $randomNumber++;
                ini_set('max_execution_time', $exTime);

              Timer::create([
                            'unique_code' => $randomNumber,
                           ]);
             }

            DB::commit();

            $timer1 = Timer::all();
            return response()->json($timer1);
        } catch (Exception $e) {
            return $e;
        }

    }

    public function generateUniqueCode()
    {
        do{
            $randomNumber = random_int(1111111, 9999999);
        }while (Timer::where('unique_code', $randomNumber)->first());

        return  $randomNumber;
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(int $id)
    {
        return response()->json(Timer::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(int $id)
    {
        return response()->json(Timer::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, int $id)
    {
        $timer = Timer::findOrFail($id);
        $timer->update($request->all());

        return response()->json($timer, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(int $id)
    {
        Timer::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
