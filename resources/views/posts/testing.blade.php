@extends('layouts.master')

@section('page-title', '新增公告')

@section('content')


    <html>

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    </head>

    <body>

    <h1>臺北旅遊網-景點資料 及 台北市旅館資料庫</h1>


    <div id="DropDownList">

        分類：<select id="SelectCategory"></select>

        次分類：<select id="SelectSubCategory"></select>

        <label id="LabelViewpoint"></label>：<select id="SelectViewpoint"></select>

    </div>


    <div id="map" style="width:800px; height:600px"></div>


    <script type="text/javascript" src="scripts/jquery-1.7.2.min.js"></script>

    <script type="text/javascript" src="scripts/jquery.xml2json.js"></script>

    <script type="text/javascript" src="scripts/JSLINQ.js"></script>

    <script type="text/javascript">

        <!--

        var jsonScenery = [];

        var jsonHotel = [];


        $(document).ready(function () {

            Page_Init();

        });


        function Page_Init() {

            var jsonData =

                [

                    {

                        "categoryId": "2",

                        "categoryName": "住宿"

                    },

                    {

                        "categoryId": "1",

                        "categoryName": "景點"

                    }

                ];


            $('#SelectCategory').empty().append($('<option></option>').val('').text('------'));


            $.each(jsonData, function (i, item) {

                $('#SelectCategory').append($('<option></option>').val(item.categoryId).text(item.categoryName));

            });


            $('#SelectSubCategory').empty().append($('<option></option>').val('').text('------'));

            $('#SelectViewpoint').empty().append($('<option></option>').val('').text('------'));

            $('#LabelViewpoint').text('景點');


            $('#SelectCategory').change(function () {

                ChangeCategory();

            });


            $('#SelectSubCategory').change(function () {

                ChangeSubCategory();

            });


            GetSceneryJsonData();

            GetHotelJsonData();

        }


        function GetSceneryJsonData() {

            $.get('data/scenery.xml', function (xml) {

                jsonScenery = $.xml2json(xml);

            });

            return jsonScenery;

        }


        function GetHotelJsonData() {

            $.get('data/hotel.xml', function (xml) {

                jsonHotel = $.xml2json(xml);

            });

            return jsonHotel;

        }


        function ChangeCategory() {

            //變動第一個下拉選單


            $('#SelectSubCategory').empty().append($('<option></option>').val('').text('------'));

            $('#SelectViewpoint').empty().append($('<option></option>').val('').text('------'));


            var categoryId = $.trim($('#SelectCategory option:selected').val());


            var jsonData = [];


            if (categoryId == '1') {

                jsonData =

                    [

                        {

                            "subCategoryId": "S01",

                            "subCategoryName": "博物館"

                        },

                        {

                            "subCategoryId": "S02",

                            "subCategoryName": "歷史建築"

                        },

                        {

                            "subCategoryId": "S03",

                            "subCategoryName": "廟宇"

                        },

                        {

                            "subCategoryId": "S04",

                            "subCategoryName": "單車遊蹤"

                        },

                        {

                            "subCategoryId": "S05",

                            "subCategoryName": "城市公園"

                        },

                        {

                            "subCategoryId": "S06",

                            "subCategoryName": "親山健行"

                        },

                        {

                            "subCategoryId": "S07",

                            "subCategoryName": "藍色公路"

                        },

                        {

                            "subCategoryId": "S08",

                            "subCategoryName": "公共藝術"

                        },

                        {

                            "subCategoryId": "S09",

                            "subCategoryName": "展演會館"

                        },

                        {

                            "subCategoryId": "S10",

                            "subCategoryName": "教堂"

                        },

                        {

                            "subCategoryId": "S11",

                            "subCategoryName": "圖書館"

                        },

                        {

                            "subCategoryId": "S12",

                            "subCategoryName": "親子共遊"

                        },

                        {

                            "subCategoryId": "S13",

                            "subCategoryName": "養生溫泉"

                        },

                        {

                            "subCategoryId": "S14",

                            "subCategoryName": "其它"

                        }

                    ];

            }

            else if (categoryId == '2') {

                jsonData =

                    [

                        {

                            "subCategoryId": "H01",

                            "subCategoryName": "國際觀光旅館"

                        },

                        {

                            "subCategoryId": "H02",

                            "subCategoryName": "一般觀光旅館"

                        },

                        {

                            "subCategoryId": "H03",

                            "subCategoryName": "一般旅館"

                        }

                    ];

            }


            if (categoryId.length != 0) {

                $.each(jsonData, function (i, item) {

                    $('#SelectSubCategory').append($('<option></option>').val(item.subCategoryId).text(item.subCategoryName));

                });


                if (categoryId == '1') {

                    $('#LabelViewpoint').text("景點");

                }

                else {

                    $('#LabelViewpoint').text("旅館");

                }

            }

        }


        function ChangeSubCategory() {

            //變動第二個下拉選單


            $('#SelectViewpoint').empty().append($('<option></option>').val('').text('------'));


            var categoryId = $.trim($('#SelectCategory option:selected').val());

            var categoryName = $.trim($('#SelectCategory option:selected').text());

            var subCategoryName = $.trim($('#SelectSubCategory option:selected').text());


            if (categoryId == '1') {

                //景點

                var result = new JSLINQ(this.jsonScenery.Section)

                    .Where(function (item) {
                        return item.CAT1 == categoryName && item.CAT2 == subCategoryName;
                    })

                    .Select(function (item) {
                        return item;
                    }).ToArray();


                $.each(result, function (i, item) {

                    $('#SelectViewpoint').append($('<option></option>').val(item["SERIAL_NO"]).text(item["stitle"]));

                });

            }

            else if (categoryId == '2') {

                //住宿

                var result = new JSLINQ(this.jsonHotel.Section)

                    .Where(function (item) {
                        return item.CAT1 == categoryName && item.CAT2 == subCategoryName;
                    })

                    .Select(function (item) {
                        return item;
                    }).ToArray();


                $.each(result, function (i, item) {

                    $('#SelectViewpoint').append($('<option></option>').val(item["SERIAL_NO"]).text(item["stitle"]));

                });

            }

        };

        -->

    </script>


    </body>

    </html>

@endsection
