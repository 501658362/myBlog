<?php
namespace App\Http\Controllers;

use App\Http\Model\Post;
use App\Services\HttpRequest;
use App\Services\HttpResponse;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class TestPolygonController extends Controller {
    
    public function __construct() {
        //                $this->middleware('test') ;
    }
    
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //        DB::setFetchMode(\PDO::FETCH_ASSOC);
        //        $data = DB::select('select * from common_region_area a left join common_region_area_paths b on a.area_id = b.area_id');
        $lng = $request->get("lng");
        $lat = $request->get("lat");
        $lnglat = $request->get("lnglat");
        if(isset($lnglat) &&  !empty($request->get("lnglat"))){
            $lng=explode(",", $lnglat)[0];
            $lat=explode(",", $lnglat)[1];
        }
        $esData = self::getFromEs($lng, $lat);
        $results = [];
        //        $results1 = collect($data)->groupBy('area_id')->toArray();
        //        dd($esData[ 'hits' ][ 'hits' ], $results1);
        if ($esData[ 'hits' ] != null && $esData[ 'hits' ][ 'hits' ] != null) {
            foreach ($esData[ 'hits' ][ 'hits' ] as $key => $v) {
                $size = count($v[ '_source' ][ 'location' ][ 'coordinates' ][ 0 ]);
                if ($size > 1) {
                    unset($v[ '_source' ][ 'location' ][ 'coordinates' ][ 0 ][ $size - 1 ]);
                }
                $results [] = [
                    "name"     => $v[ '_source' ][ 'name' ],
                    "location" => $v[ '_source' ][ 'location' ][ 'coordinates' ][ 0 ],
                ];
            }
        }
        $request->flash();
        return view("test.polygon", [
            "data" => json_encode($results),
            "lng" => $lng,
            "lat" => $lat,
            "marker" => empty($lng) || empty($lat) ? false:true
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $request = new HttpRequest();
        $data = $request->get("http://localhost:9200/attractions/landmark/_search");
        dd(self::formatResponseData($data));
        //
        return view("contact");
    }
    
    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->get("paths");
        //                return response()->json($data);
        DB::table('common_region_area')->truncate();
        DB::table('common_region_area_paths')->truncate();
        self::deleteAllData();
        if (count($data) > 0) {
            foreach ($data as $k => $v) {
                $es [ 'name' ] = $v[ 'name' ];
                $es [ 'created_at' ] = time();
                $es [ 'updated_at' ] = time();
                $es [ 'location' ] = [
                    "type" => "polygon",
                ];
                $point = [];
                if (count($v[ 'paths' ]) > 0) {
                    $id = DB::table('common_region_area')->insertGetId([
                        'name'       => $v[ 'name' ],
                        'created_at' => time(),
                        'updated_at' => time()
                    ]);
                    $es [ 'area_id' ] = $id;
                    $paths = [];
                    $firstPath = [];
                    foreach ($v[ 'paths' ] as $value) {
                        if (count($firstPath) == 0) {
                            $firstPath = [$value[ 'lng' ], $value[ 'lat' ]];
                        }
                        $point [] = [$value[ 'lng' ], $value[ 'lat' ]];
                        $paths[] = [
                            'area_id'    => $id,
                            'lng'        => $value[ 'lng' ],
                            'lat'        => $value[ 'lat' ],
                            'created_at' => time(),
                            'updated_at' => time()
                        ];
                    }
                    $point[] = $firstPath;
                    DB::table('common_region_area_paths')->insert($paths);
                }
                $es [ 'location' ][ 'coordinates' ] = [
                    $point
                ];
                self::saveToEs($es, $k + 1);
            }
        }
        $return [ 'code' ] = 200;
        $return [ 'data' ] = $data;
        $return [ 'message' ] = "保存成功";
        return response()->json($return);
        //
    }
    
    /**
     * Display the specified resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }
    
    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
    
    /**
     * @brief 格式化输出结果集
     * @param HttpResponse $response
     * @return array
     */
    protected function formatResponseData(HttpResponse $response) {
        $data = $response->getResult();
        return [
            'data'    => $data,
            'code'    => $response->getCode(),
            'message' => $response->getMessage()
        ];
    }
    
    private function getFromEs($lng, $lat) {
        $request = new HttpRequest();
        $result = [];
        if (empty($lng) || empty($lat)) {
            $result = $request->get("http://localhost:9200/attractions/landmark/_search");
        } else {
            $data
                = "{
  \"query\": {
    \"geo_shape\": {
      \"location\": { 
        \"shape\": { 
          \"type\":   \"point\", 
          
          \"coordinates\": [ 
           
         $lng,$lat
          ]
        }
      }
    }
  }
}";
            $result = $request->post("http://localhost:9200/attractions/landmark/_search", json_decode($data, true));
        }
        if ($result->getStatusCode() == 200) {
            return $result->getResult();
        }
        dd($data);
    }
    
    private function saveToEs($data, $id) {
        $request = new HttpRequest();
        $data = $request->put("http://localhost:9200/attractions/landmark/" . $id, $data);
        if ($data->getStatusCode() == 200) {
        }
    }
    
    private function deleteAllData() {
        $request = new HttpRequest();
        $data = json_decode("{
  \"query\": { 
    \"range\": {
		\"created_at\":{
			\"gte\":0
		}
    }
  }
}", true);
        $data = $request->post("http://localhost:9200/attractions/landmark/_delete_by_query", $data);
        if ($data->getStatusCode() == 200) {
        }
    }
}
