/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.l = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// identity function for calling harmory imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };

/******/ 	// define getter function for harmory exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		Object.defineProperty(exports, name, {
/******/ 			configurable: false,
/******/ 			enumerable: true,
/******/ 			get: getter
/******/ 		});
/******/ 	};

/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};

/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

eval("$( document ).ready(function() {\r\n    getInfo();\r\n});\r\n\r\nvar $categoriesArray;\r\nvar $bannersArray;\r\nvar $featuredArticlesArray;\r\n\r\nfunction getInfo() {\r\n\r\n    $.ajaxSetup({\r\n        headers : {\r\n            'Content-Language' : 'trad'\r\n        }\r\n    });\r\n\r\n    $.getJSON( \"api/index\", function( data ) {\r\n        $.each( data, function( key, val ) {\r\n            switch(key) {\r\n                case 'categories':\r\n                    $categoriesArray = val;\r\n                    break;\r\n                case 'banners':\r\n                    $bannersArray = val;\r\n                    break;\r\n                case 'featuredArticles':\r\n                    $featuredArticlesArray = val;\r\n                    break;\r\n            }\r\n        });\r\n\r\n        showContent();\r\n    });\r\n\r\n}\r\n\r\nfunction showContent() {\r\n    makeCategoriesList();\r\n    makeBanners();\r\n    makeFeaturedArticles();\r\n}\r\n\r\nfunction makeCategoriesList() {\r\n    var categoriesItems = [];\r\n    $.each($categoriesArray, function(key, val) {\r\n        if(val.parent == null) {\r\n            categoriesItems.push(\"<li><a href='\"+val.url+\"/\"+val.slug+\"'>\"+val.name+\"</a></li>\")\r\n        }\r\n    });\r\n    $('#categoriesList').html(categoriesItems.join(\"\"));\r\n}\r\n\r\nfunction makeBanners() {\r\n    var indicators = [];\r\n    var banners = [];\r\n\r\n    $.each($bannersArray, function(key, val) {\r\n        var indicatorClass = \"\";\r\n        if(key == 0) indicatorClass = \"class='active'\";\r\n        indicators.push(\"<li data-target='#carousel-main-banner' data-slide-to='\"+key+\"' \"+indicatorClass+\"></li>\");\r\n\r\n        var bannerClass = \"\";\r\n        if(key == 0) bannerClass = \"active\";\r\n        banners.push(\"<div class='item \"+bannerClass+\"'><img src='\"+val.image_path+\"' alt='...'><div class='carousel-caption'>\"+val.alt+\"</div></div>\");\r\n    });\r\n\r\n    $('.carousel-indicators').html(indicators.join(\"\"));\r\n    $('.carousel-indicators').children('li').css('margin', '0 3px');\r\n\r\n    $('.carousel-inner').html(banners.join(\"\"));\r\n}\r\n\r\nfunction makeFeaturedArticles() {\r\n\r\n    var featuredArticles = [];\r\n    // console.log($featuredArticlesArray);\r\n    $.each($featuredArticlesArray, function(key, val) {\r\n        var category = getCategoryByID(val.category_id);\r\n\r\n        var categoryName = category.name;\r\n\r\n        if(category.name != category.default_name) categoryName = category.default_name+\" \"+category.name;\r\n\r\n        featuredArticles.push(\"<ul class='col-md-3 ul-clean'><li><img src='\"+val.photo.image_path+\"' class='img-responsive'></li><li>\"+categoryName+\"</li><li>\"+val.title+\"</li></ul>\");\r\n    });\r\n\r\n    $('#featured-article').html(featuredArticles.join(\"\"));\r\n\r\n}\r\n\r\nfunction getCategoryByID(id) {\r\n    // console.log(id);\r\n    for(var i = 0; i < $categoriesArray.length; i++ ) {\r\n\r\n        if($categoriesArray[i].id == id) {\r\n            var category = $categoriesArray[i];\r\n            return category;\r\n        }\r\n    }\r\n}\r\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy9yZXNvdXJjZXMvYXNzZXRzL2pzL3dlYi5qcz9mZDg3Il0sInNvdXJjZXNDb250ZW50IjpbIiQoIGRvY3VtZW50ICkucmVhZHkoZnVuY3Rpb24oKSB7XHJcbiAgICBnZXRJbmZvKCk7XHJcbn0pO1xyXG5cclxudmFyICRjYXRlZ29yaWVzQXJyYXk7XHJcbnZhciAkYmFubmVyc0FycmF5O1xyXG52YXIgJGZlYXR1cmVkQXJ0aWNsZXNBcnJheTtcclxuXHJcbmZ1bmN0aW9uIGdldEluZm8oKSB7XHJcblxyXG4gICAgJC5hamF4U2V0dXAoe1xyXG4gICAgICAgIGhlYWRlcnMgOiB7XHJcbiAgICAgICAgICAgICdDb250ZW50LUxhbmd1YWdlJyA6ICd0cmFkJ1xyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG5cclxuICAgICQuZ2V0SlNPTiggXCJhcGkvaW5kZXhcIiwgZnVuY3Rpb24oIGRhdGEgKSB7XHJcbiAgICAgICAgJC5lYWNoKCBkYXRhLCBmdW5jdGlvbigga2V5LCB2YWwgKSB7XHJcbiAgICAgICAgICAgIHN3aXRjaChrZXkpIHtcclxuICAgICAgICAgICAgICAgIGNhc2UgJ2NhdGVnb3JpZXMnOlxyXG4gICAgICAgICAgICAgICAgICAgICRjYXRlZ29yaWVzQXJyYXkgPSB2YWw7XHJcbiAgICAgICAgICAgICAgICAgICAgYnJlYWs7XHJcbiAgICAgICAgICAgICAgICBjYXNlICdiYW5uZXJzJzpcclxuICAgICAgICAgICAgICAgICAgICAkYmFubmVyc0FycmF5ID0gdmFsO1xyXG4gICAgICAgICAgICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAnZmVhdHVyZWRBcnRpY2xlcyc6XHJcbiAgICAgICAgICAgICAgICAgICAgJGZlYXR1cmVkQXJ0aWNsZXNBcnJheSA9IHZhbDtcclxuICAgICAgICAgICAgICAgICAgICBicmVhaztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBzaG93Q29udGVudCgpO1xyXG4gICAgfSk7XHJcblxyXG59XHJcblxyXG5mdW5jdGlvbiBzaG93Q29udGVudCgpIHtcclxuICAgIG1ha2VDYXRlZ29yaWVzTGlzdCgpO1xyXG4gICAgbWFrZUJhbm5lcnMoKTtcclxuICAgIG1ha2VGZWF0dXJlZEFydGljbGVzKCk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIG1ha2VDYXRlZ29yaWVzTGlzdCgpIHtcclxuICAgIHZhciBjYXRlZ29yaWVzSXRlbXMgPSBbXTtcclxuICAgICQuZWFjaCgkY2F0ZWdvcmllc0FycmF5LCBmdW5jdGlvbihrZXksIHZhbCkge1xyXG4gICAgICAgIGlmKHZhbC5wYXJlbnQgPT0gbnVsbCkge1xyXG4gICAgICAgICAgICBjYXRlZ29yaWVzSXRlbXMucHVzaChcIjxsaT48YSBocmVmPSdcIit2YWwudXJsK1wiL1wiK3ZhbC5zbHVnK1wiJz5cIit2YWwubmFtZStcIjwvYT48L2xpPlwiKVxyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG4gICAgJCgnI2NhdGVnb3JpZXNMaXN0JykuaHRtbChjYXRlZ29yaWVzSXRlbXMuam9pbihcIlwiKSk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIG1ha2VCYW5uZXJzKCkge1xyXG4gICAgdmFyIGluZGljYXRvcnMgPSBbXTtcclxuICAgIHZhciBiYW5uZXJzID0gW107XHJcblxyXG4gICAgJC5lYWNoKCRiYW5uZXJzQXJyYXksIGZ1bmN0aW9uKGtleSwgdmFsKSB7XHJcbiAgICAgICAgdmFyIGluZGljYXRvckNsYXNzID0gXCJcIjtcclxuICAgICAgICBpZihrZXkgPT0gMCkgaW5kaWNhdG9yQ2xhc3MgPSBcImNsYXNzPSdhY3RpdmUnXCI7XHJcbiAgICAgICAgaW5kaWNhdG9ycy5wdXNoKFwiPGxpIGRhdGEtdGFyZ2V0PScjY2Fyb3VzZWwtbWFpbi1iYW5uZXInIGRhdGEtc2xpZGUtdG89J1wiK2tleStcIicgXCIraW5kaWNhdG9yQ2xhc3MrXCI+PC9saT5cIik7XHJcblxyXG4gICAgICAgIHZhciBiYW5uZXJDbGFzcyA9IFwiXCI7XHJcbiAgICAgICAgaWYoa2V5ID09IDApIGJhbm5lckNsYXNzID0gXCJhY3RpdmVcIjtcclxuICAgICAgICBiYW5uZXJzLnB1c2goXCI8ZGl2IGNsYXNzPSdpdGVtIFwiK2Jhbm5lckNsYXNzK1wiJz48aW1nIHNyYz0nXCIrdmFsLmltYWdlX3BhdGgrXCInIGFsdD0nLi4uJz48ZGl2IGNsYXNzPSdjYXJvdXNlbC1jYXB0aW9uJz5cIit2YWwuYWx0K1wiPC9kaXY+PC9kaXY+XCIpO1xyXG4gICAgfSk7XHJcblxyXG4gICAgJCgnLmNhcm91c2VsLWluZGljYXRvcnMnKS5odG1sKGluZGljYXRvcnMuam9pbihcIlwiKSk7XHJcbiAgICAkKCcuY2Fyb3VzZWwtaW5kaWNhdG9ycycpLmNoaWxkcmVuKCdsaScpLmNzcygnbWFyZ2luJywgJzAgM3B4Jyk7XHJcblxyXG4gICAgJCgnLmNhcm91c2VsLWlubmVyJykuaHRtbChiYW5uZXJzLmpvaW4oXCJcIikpO1xyXG59XHJcblxyXG5mdW5jdGlvbiBtYWtlRmVhdHVyZWRBcnRpY2xlcygpIHtcclxuXHJcbiAgICB2YXIgZmVhdHVyZWRBcnRpY2xlcyA9IFtdO1xyXG4gICAgLy8gY29uc29sZS5sb2coJGZlYXR1cmVkQXJ0aWNsZXNBcnJheSk7XHJcbiAgICAkLmVhY2goJGZlYXR1cmVkQXJ0aWNsZXNBcnJheSwgZnVuY3Rpb24oa2V5LCB2YWwpIHtcclxuICAgICAgICB2YXIgY2F0ZWdvcnkgPSBnZXRDYXRlZ29yeUJ5SUQodmFsLmNhdGVnb3J5X2lkKTtcclxuXHJcbiAgICAgICAgdmFyIGNhdGVnb3J5TmFtZSA9IGNhdGVnb3J5Lm5hbWU7XHJcblxyXG4gICAgICAgIGlmKGNhdGVnb3J5Lm5hbWUgIT0gY2F0ZWdvcnkuZGVmYXVsdF9uYW1lKSBjYXRlZ29yeU5hbWUgPSBjYXRlZ29yeS5kZWZhdWx0X25hbWUrXCIgXCIrY2F0ZWdvcnkubmFtZTtcclxuXHJcbiAgICAgICAgZmVhdHVyZWRBcnRpY2xlcy5wdXNoKFwiPHVsIGNsYXNzPSdjb2wtbWQtMyB1bC1jbGVhbic+PGxpPjxpbWcgc3JjPSdcIit2YWwucGhvdG8uaW1hZ2VfcGF0aCtcIicgY2xhc3M9J2ltZy1yZXNwb25zaXZlJz48L2xpPjxsaT5cIitjYXRlZ29yeU5hbWUrXCI8L2xpPjxsaT5cIit2YWwudGl0bGUrXCI8L2xpPjwvdWw+XCIpO1xyXG4gICAgfSk7XHJcblxyXG4gICAgJCgnI2ZlYXR1cmVkLWFydGljbGUnKS5odG1sKGZlYXR1cmVkQXJ0aWNsZXMuam9pbihcIlwiKSk7XHJcblxyXG59XHJcblxyXG5mdW5jdGlvbiBnZXRDYXRlZ29yeUJ5SUQoaWQpIHtcclxuICAgIC8vIGNvbnNvbGUubG9nKGlkKTtcclxuICAgIGZvcih2YXIgaSA9IDA7IGkgPCAkY2F0ZWdvcmllc0FycmF5Lmxlbmd0aDsgaSsrICkge1xyXG5cclxuICAgICAgICBpZigkY2F0ZWdvcmllc0FycmF5W2ldLmlkID09IGlkKSB7XHJcbiAgICAgICAgICAgIHZhciBjYXRlZ29yeSA9ICRjYXRlZ29yaWVzQXJyYXlbaV07XHJcbiAgICAgICAgICAgIHJldHVybiBjYXRlZ29yeTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcbn1cclxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHJlc291cmNlcy9hc3NldHMvanMvd2ViLmpzIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9");

/***/ }
/******/ ]);