<div id="left" class="col-md-9">

    <div id="block" style="border: 0px !important">
        <div id="category-head">Home > <span>{{ $categoryDetail['name'] }}</span></div>
        <div id="stories">

            @foreach($categoryStories as $story)
                {{--{{ dd($categoryDetail) }}--}}
                <?php
                    $image_path = $story['photo']['s3'] ? config("app.s3_path").$story['photo']['image_path'] : asset($story['photo']['image_path']);
                ?>
                <div class='news'>
                    <div class='col-md-5 left'>
                        <a href='{{ route('frontend.article', ['slug' => $story['slug']]) }}'>
                            <img src='{{ $image_path }}' class='img-responsive' style='width:100%'>
                        </a>
                    </div>
                    <div class='col-md-7 right'>
                        <ul class='ul-clean'>
                            <li class='cate'><a href="{{ route("frontend.category", ['slug' => $story['category']['slug']]) }}">{{ $story['category']['name'] }}</a></li>
                            <li class='title'><a href='{{ route('frontend.article', ['slug' => $story['slug']]) }}'>{{ $story['title'] }}</a></li>
                            <li class='desc' style='clear:both'>{{ $story['description'] }}</li>
                        </ul>
                    </div>
                </div>
                <div style='clear:both'></div>
            @endforeach

            <script>

                $.each(stories[topic], function(key, val) {

                $('.stories-active').removeClass('stories-active');
                // $(obj).addClass('stories-active');

                var category = getCategoryByID(val.category_id);
                var categoryName = category.name;
                if(category.name != category.default_name) categoryName = category.default_name+" "+category.name;

                if(val.photo.s3) {
                var image_root_path = s3_root;
                } else {
                var image_root_path = site_root;
                }

                // console.log(image_root_path);

                topicList.push("<div class='news'>\
                    <div class='col-md-5 left'><a href='"+site_root+default_language+"/article/"+val.slug+"'><img src='"+image_root_path+val.photo.image_path+"' class='img-responsive' style='width:100%'></a></div>\
                    <div class='col-md-7 right'>\
                        <ul class='ul-clean'>\
                            <li class='cate'>"+categoryName+"</li>\
                            <li class='title'><a href='"+site_root+default_language+"/article/"+val.slug+"'>"+val.title+"</a></li>\
                            <li class='desc' style='clear:both'>"+val.short_desc+"</li>\
                        </ul>\
                    </div>\
                </div>\
                <div style='clear:both'></div>");
                });
            </script>

        </div>
    </div>

</div>