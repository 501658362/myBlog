<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Polygon Arrays</title>
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>

</head>
<body>
<div style="height: 100px">
    <button id="save" style=" ">保存</button>
    <button id="clean" style=" ">清除</button>

    <div>
        <form action="{!! url("polygon") !!}">
            <label for="lng"> 经度<input type="text" name="lng" value="{!! $lng !!}"> </label>
            <label for="lat"> 维度<input type="text" name="lat" value="{!! $lat !!}"> </label>
            <button type="submit">查询</button>
            <button type="button" id="reset">reset</button>
        </form>
    </div>
    <div>
        <form action="{!! url("polygon") !!}">
            <label for="lnglat"> 经维度<input type="text" name="lnglat" value="{!! old("lnglat") !!}"> </label>
            <button type="submit">查询</button>
            <button type="button" id="reset">reset</button>
        </form>
    </div>
</div>

<div id="map"></div>
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script src="{!! asset("assets/js/neuron-common.js") !!}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    var names = "少女慕琼并不是传统意义上的乖乖女她一直老老实实的待在学校里并不是因为校规校纪而是因为她自己将离校外出这件事判定为无意义但在亚当扎德那针对性满满的策略之下少女慕琼对离校外出这件事的必要性重新考量之后便连拐都不用拐甚至都不需要亚当在前面领路自己直挺挺的就走出了校门校规校纪判定无意义某种意义上来讲少女慕琼在心态上和杨老师很一致两人都少有显露在外的高人一等但那种我行我素、不因任何外力而打扰的然实则却是另一种绝对意义的骄傲但少女慕琼和杨老师之间却存在一个最大的区别——少女慕琼是空灵杨老师是通透杨老师是在滚滚红尘中摸爬滚打许久之后通达本心、洞彻天心对各种鬼蜮伎俩可谓是洞若观火若有人对他下套一般二般的套路那是决然不管用的但少女慕琼不是她不懂人心所以她轻轻松松就被亚当拐了出去并且完全不明白那冲着她笑的很得意的家伙到底有什么意图但她也有自己的一套而这一套让这一次拐带行为变得非常的不顺利在过去的十五分钟内你扭头冲我牵拉面部肌肉达二十二次累计时长七分四十四秒不懂就要问还没走出去多久少女慕琼就对着亚当直挺挺的开口了说出这种举动的目的所在咳咳——亚当扎德差点没被呛着连忙矢口否认没没什么目的……不除了病症行为每一种行动都有目的少女慕琼从包里拿出来一本书哗啦啦的准确翻到了某一页冲着亚当扎德指了指书上的一句话若真的没有目的那么说明你刚刚表现出来的是——精神病前期征兆亚当不由立时气结喂你这什么书啊怎么我瞬间就成精神病了啊心理医学专著少女慕琼把书捧在眼前目光诚挚的如同小学生看着游戏攻略宝典这本书在人类心理医学方面的见解很有参考价值然后她又抬头看向对方面容整肃认真我提议你应该去疯人院接受专业诊断有必要的话我推荐你进行电击治疗我勒个去杨绮差点没在精神世界中笑出声来年轻小伙子一个劲儿的冲女孩笑怎么办请去接受电击治疗~笑了几下就给整到疯人院去了亚当扎德那满脸表情简直精彩的无法描述他张大了嘴又是不可置信又是瞠目结舌最后现少女慕琼平淡如水的眼眸里似乎认定了她的判断立刻果断的猛然摇头矢口否认不是不是我确定我不是精神病你有心理医生行医资格证么呃……没有……那你没有资格判定自己没有精神病少女慕琼直接忽略了亚当的自我判断她掏出一个伦敦地图目光认真的在地图上找着什么那目光让亚当心口一寒喂你在找什么——你不会是在找最近的疯人院吧正确我才不要去什么疯人院苍天啊大地啊我就是好不容易拐带成功心里高兴而已啊话说我今天还精心准备了那么多的节目我容易吗我这什么破书人类的心理怎么可能这么简单的就被一本书描述透彻而且这个写书的家伙也不是什么降世圣灵他写的就是真的吗不行不行不能听信他的少女慕琼面露沉思然后竟然点点头观点无误原作者不能自证自己绝对正确所以这本书不能完全信任是吧是吧亚当扎德长长吁了一口气抹了抹额头上的冷汗随口说道科学工作者不能偏听偏信需要全面采样、全面研究之后得出的结论才能接近真理不盲目、不冒进、有耐心、有信心——这才是真正的科学精神亚当松气之中随口说了一句话但没想到对面的少女的双眼竟然一下子就亮了起来好似动漫中咔嚓一下脑后有雷霆劈过一般少女慕琼竟然有种拨云见日、醍醐灌顶一般的触动破天荒第一次在仔细思考之后很认真的对亚当大点其头语调中竟然出现了第一种活生生的语气你的观点很有启性你——很厉害各色的眼光亚当扎德见得多了但少女慕琼眼中那种直挺挺的认可这一瞬间竟然让他有种耀眼的炫目感仿佛长期生存在幽暗地狱中的生物第一次直视太阳亚当扎德迎着那双明亮的眼睛怔怔半晌他的脸上一瞬间褪去了所有习惯的伪装色只剩下一种隐晦难言的喜悦——还有一点百般隐藏后依然泄露出的丝丝……羞涩白皙的脸上微微闪过一点微红亚当扎德轻轻扭头躲开那双眼睛那个……呃……嘿嘿知道我厉害了吧……少女慕琼认真道经过了这一段时间的观察我一直以为你只是在糊弄我其实是在打着学术研究的幌子达到一些我暂时不清楚的目的可是现在看来你是个真正拥有科学精神的科学家我——所有小娇羞瞬间褪去亚当扎德的脸就像暴走漫画一样噎了半晌最后从牙缝里挤出一句话总之……你信我就没错……好少女慕琼的眼睛透亮的像稀世水晶从现在起我相信你………………凝噎半晌贵公子亚当扎德总算还是像个吐槽役一样把心里话爆喷了出来为什么你总像个过山车一样一下子弄得人很无语一下子又弄得人很感动转折要不要这么快你到底让我怎么反应谁能告诉我我到底该怎么反应啊这是亚当扎德第一次拐带少女慕琼逃学出门在心灵的世界中杨绮看着这一幕心情复杂起来那个扎德那个老扎德在这一刻看起来是真实的从这一次开始少女慕琼似乎一下子认同了亚当扎德各种行为的正确性她不再质疑他某些莫名其妙举动的动机和目的也不再认为他需要去疯人院电疗她只是跟着他走大街、过小巷徜徉在美丽的伦敦街头看过万花筒一般的花花世界而亚当对少女慕琼的态度竟然也悄然生了变化这种变化说不清道不明如果说之前还仅仅是基于某种兴趣的接近的话从这一刻起亚当扎德竟然好似正儿八经的将少女慕琼当做了能够分享、共享的红颜知己分享着他的心情分享着他的看法分享着他最真实的思维分享着他那似乎丢弃已久的——童趣互相的相处不再刻意而是变得自然而然少女慕琼奇怪的口癖不再让他困扰他似乎也不再觉得少女慕琼那直挺挺的语言逻辑有什么奇怪一个最上层的名流贵公子和一个来历成谜的奇怪少女一下子变得和谐融洽泛舟泰晤士河上两人共乘一叶扁舟波光摇晃绿水荡漾两人俱都神色专注的盯着河面迷之沉默持续半晌之后男子豁然一声大喝猛然抬手拉起鱼竿哗啦一声响水下暗流涌动鱼线一下子绷紧了哟还挺有劲儿看来是个大家伙啊好看我怎么把你弄上来亚当豪情奋正要挽起袖子大干一场旁边蓦地伸出来一双嫩手噗的一下两面夹击拍在他的脸上把他的腮帮子捧的嘟的一下鼓起两个肉包少女就像是要控制住一个标本一样死死摁住了他满脸的肌肉说明这种肌肉牵拉所代表的含义这个、这个是兴奋亚当伸着脸被挤压的嘴唇凸起只能如同鱼嘴一样一咪一咪的开口快快赶紧放开我鱼儿要跑了这是我第一次自己动手钓鱼我一定要把这大家伙弄上来这一刻他的兴奋看起来是真实的英格兰大歌剧厅中两人坐在最豪华的包厢中亚当穿着得体的豪华西装少女也换上了符合场合的晚礼服瘦削的少女穿起礼服竟然艳光四射如同黑夜中的一颗星辰男子半是偷眼看着少女半是专心看着舞台舞台上著名曲目图兰朵逐渐进入高潮在《今夜无人入眠》的旋律中戏中王子就要向图兰朵公主表白而戏外的白马王子也不由得白日做梦、想入非非想到妙处忍不住嘿嘿直乐噗旁边再次伸出一双嫩手死死摁住了他的脸皮把他满脸的傻笑控制住原滋原味的扳了过去少女仔细观察着手中的标本口中问说明这种肌肉牵拉所代表的含义这、这这、这个是陶醉这是我的陶醉脸男子眼珠乱转慌张之中扯谎连篇听这音乐多么优美我是陶醉在艺术之中无法自拔而已少女双眼一眯继续追问上午去美术展时你为什么没有陶醉在艺术中上午去美术展的时候你还穿着学生装哪有现在好看亚当摸了摸良心口诚实的解释因为我爱音乐这一刻他的陶醉看起来是真实的";
    var data = {!! ($data) !!};
</script>


<script>

    // This example creates a simple polygon representing the Bermuda Triangle.
    // When the user clicks on the polygon an info window opens, showing
    // information about the polygon's coordinates.

    var defaultPolygonOptions = {
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        editable: true,
        draggable: true,
        geodesic: true
    };

    var map;
    var infoWindow;

    var paths = [];
    var drawingManager;
    function initMap() {

        var defaultCenter =  new google.maps.LatLng(39.90874867307017, 116.39759302139282);

        @if($marker)
            defaultCenter =  new google.maps.LatLng({!! $lat !!},{!! $lng !!});
        @endif

        map = new google.maps.Map(document.getElementById('map'), {
            center: defaultCenter,
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        @if($marker)
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng( {!! $lat !!},{!! $lng !!}),
            title:"Hello World!"
        });
        marker.setMap(map);
        @endif
// To add the marker to the map, call setMap();


        // Define the LatLng coordinates for the polygon.
//        var triangleCoords = [
//            new google.maps.LatLng(39.92704916565799, 116.3917350769043),
//            new google.maps.LatLng(39.91302800326956, 116.39156341552734),
//            new google.maps.LatLng(39.91335717144218, 116.40254974365234),
//            new google.maps.LatLng(39.92408718745354, 116.40177726745605)
//        ];
//
//        // Construct the polygon.
//        var bermudaTriangle = new google.maps.Polygon($.extend(defaultPolygonOptions, {
//            paths: triangleCoords,
//            strokeColor: '#FF0000',
//            strokeOpacity: 0.8,
//            strokeWeight: 3,
//            fillColor: '#FF0000',
//            fillOpacity: 0.35
//        }));
//        paths.push(bermudaTriangle);
//        bermudaTriangle.setMap(map);
//
//        // Add a listener for the click event.
//        bermudaTriangle.addListener('click', clickPolygon);
//        bermudaTriangle.addListener("mouseover", mouseOverPolygon);
//        bermudaTriangle.addListener("mouseout", mouseOutPolygon);

        initPolygon();

        infoWindow = new google.maps.InfoWindow;

        //绘画工具 设置
        drawingManager = new google.maps.drawing.DrawingManager({
//            drawingMode: google.maps.drawing.OverlayType.MARKER,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    google.maps.drawing.OverlayType.POLYGON
                ]
            },

            //设置图形显示样式
            polygonOptions: $.extend(defaultPolygonOptions, {})
        });
        drawingManager.setMap(map);

        //注册 多边形 绘制完成事件
        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
            paths.push(polygon);
            var array = polygon.getPath().getArray();
            polygon.addListener("click", clickPolygon);
//            polygon.addListener("mouseover", mouseOverPolygon);
//            polygon.addListener("mouseout", mouseOutPolygon);
        });
    }
    //    function mouseOverPolygon(event) {
    //
    //        var _t = this;
    //        _t.setOptions({
    //            strokeColor: '#FFFF00',
    //            fillColor: '#FFFF00',
    //        });
    //        _t.setMap(map);
    //    }
    //
    //    function mouseOutPolygon(event) {
    //         var _t = this;
    //        _t.setOptions(defaultPolygonOptions);
    //        _t.setMap(map);
    //    }

    var currentPolygon = null;
    /** @this {google.maps.Polygon} */
    function clickPolygon(event) {
        // Since this polygon has only one path, we can call getPath() to return the
        // MVCArray of LatLngs.
        var _t = this;
        currentPolygon = _t;
        if (currentPolygon.name == undefined) {
            currentPolygon.name = "";
        }

        var vertices = this.getPath();

        var contentString = '<b>Polygon     lng, lat</b><br>' +
            'Clicked location: <br>' + event.latLng.lng() + ',' + event.latLng.lat() +
            '<br>';

        // Iterate over the vertices.
        for (var i = 0; i < vertices.getLength(); i++) {
            var xy = vertices.getAt(i);
            contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lng() + ',' +
                xy.lat();
        }
        contentString += '<br>';
        contentString += "<button onclick=\"cleanPolygon()\">清除此区域</button>";
        contentString += '<br>';
        contentString += "<input type='text' id='polygonName' value='" + currentPolygon.name + "'><button onclick=\"saveName()\">保存名称</button><span style='color: red' id=\"nameInfo\"></span>";
        // Replace the info window's content and position.
        infoWindow.setContent(contentString);
        infoWindow.setPosition(event.latLng);

        infoWindow.open(map);
    }
    function saveName() {
        $("#nameInfo").html("保存成功！");
        currentPolygon.name = $("#polygonName").val();
    }

    function cleanPolygon() {
        infoWindow.close();
        currentPolygon.setMap(null);
        paths.splice($.inArray(currentPolygon, paths), 1);
    }

    function showArrays(polygon) {
        // Since this polygon has only one path, we can call getPath() to return the
        // MVCArray of LatLngs.
        var vertices = polygon.getPath();
        // Iterate over the vertices.
        for (var i = 0; i < vertices.getLength(); i++) {
            var xy = vertices.getAt(i);
            contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
                xy.lng();
        }

    }

    function initPolygon() {
        if (data != undefined && data) {
            $.each(data, function (k, area) {
                var polygonPaths = [];
                $.each(area.location, function (k, point) {
                    // Construct the polygon.
                    polygonPaths.push(new google.maps.LatLng(point[1], point[0]))
                });

                var polygon = new google.maps.Polygon($.extend(defaultPolygonOptions, {
                    paths: polygonPaths
                }));
                polygon.addListener("click", clickPolygon);
                polygon.name = area.name;
                paths.push(polygon);
                polygon.setMap(map);
            });
        }
    }

    var cleanBtn = $("#clean");
    var saveBtn = $("#save");
    var resetBtn = $("button[id=reset]");

    saveBtn.click(function () {
        if (paths.length > 0) {
            var area = [];

            $.each(paths, function (k, v) {
                var vertices = v.getPath();
                // Iterate over the vertices.
                var points = [];
                for (var i = 0; i < vertices.getLength(); i++) {
                    var xy = vertices.getAt(i);
//                    console.log(xy.lat() + ',' + xy.lng());
                    points.push({
                        "lat": xy.lat(),
                        "lng": xy.lng(),
                    });
                }
                var name = v.name;
                if (name == undefined || name == '') {
                    var m = names.length;
                    var n = 0;
                    var c = m - n + 1;
                    name = names[Math.floor(Math.random() * c + n)];
                    for (var i = 0; i < Math.random() * 10 + 1; i++) {
                        name += names[Math.floor(Math.random() * c + n)];
                    }
                }
                area.push({
                    name: name,
                    paths: points
                })
            });
            $.console(JSON.stringify(area));
            $.request.post('{!! url("polygon") !!}', {paths: area}, function (data) {
                if (data != undefined && data.code != undefined && data.code == 200 && data.message != undefined) {
                    alert(data.message);
                } else {
                    alert(data);
                }
            });
        }
    });
    cleanBtn.click(function () {
        if (paths.length > 0) {
            $.each(paths, function (k, v) {
//                console.log();
                v.setMap(null);
            });
        }
    });

    resetBtn.click(function () {
        $.each( $("input", $("form")), function (k, v) {
            $(v).val("");
        });
    });
</script>
<script async defer
        src="http://maps.google.cn/maps/api/js?key=AIzaSyCaSrUU0x-inLhpRNXVQam6OSnreIoq9MM&v=3.exp&sensor=false&libraries=drawing&callback=initMap">
</script>
</body>
</html>