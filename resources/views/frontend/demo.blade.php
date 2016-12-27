JSON
<div id="full"></div>

Root Level
<div id="root"></div>

TV Children
<div id="children"></div>
<script
        src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>
<script>
    $( document ).ready(function() {
        getInfo();
//        alert(123);
    });

    var $categoriesArray;

    function getInfo() {

        $.ajaxSetup({
            headers : {
                'Content-Language' : 'trad'
            }
        });

        $.getJSON( "api/index", function( data ) {
//            console.log(data);
            $.each( data, function( key, val ) {
                switch(key) {
                    case 'categories':
                        $categoriesArray = val;
                        break;
                }
            });

            showContent();
        });

    }

    function showContent() {
        makeCategoriesList();
    }

    function makeCategoriesList() {
        var full = [];
        var root = [];
        var children = [];

        $.each($categoriesArray, function(key, val) {
            full.push("<li><a href='"+val.url+"/"+val.slug+"'>"+val.name+"</a></li>")
        });

        $.each($categoriesArray, function(key, val) {
            if(val.parent == null) {
                root.push("<li><a href='"+val.url+"/"+val.slug+"'>"+val.name+"</a></li>")
            }
        });

        $.each($categoriesArray, function(key, val) {
            if(val.parent != null && val.parent.id == 4) {
                console.log(val.parent);
                children.push("<li><a href='"+val.url+"/"+val.slug+"'>"+val.name+"</a></li>")
            }
        });

        $('#full').html(full.join(""));
        $('#root').html(root.join(""));
        $('#children').html(children.join(""));
    }

</script>