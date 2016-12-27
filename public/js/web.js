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

eval("$( document ).ready(function() {\r\n    getInfo();\r\n});\r\n\r\nvar $categoriesArray;\r\nvar $bannersArray;\r\n\r\nfunction getInfo() {\r\n\r\n    $.ajaxSetup({\r\n        headers : {\r\n            'Content-Language' : 'trad'\r\n        }\r\n    });\r\n\r\n    $.getJSON( \"api/index\", function( data ) {\r\n        $.each( data, function( key, val ) {\r\n            switch(key) {\r\n                case 'categories':\r\n                    $categoriesArray = val;\r\n                    break;\r\n                case 'banners':\r\n                    $bannersArray = val;\r\n                    break;\r\n            }\r\n        });\r\n\r\n        showContent();\r\n    });\r\n\r\n}\r\n\r\nfunction showContent() {\r\n    makeCategoriesList();\r\n    makeBanners();\r\n}\r\n\r\nfunction makeCategoriesList() {\r\n    var categoriesItems = [];\r\n    $.each($categoriesArray, function(key, val) {\r\n        if(val.parent == null) {\r\n            categoriesItems.push(\"<li><a href='\"+val.url+\"/\"+val.slug+\"'>\"+val.name+\"</a></li>\")\r\n        }\r\n    });\r\n    $('#categoriesList').html(categoriesItems.join(\"\"));\r\n}\r\n\r\nfunction makeBanners() {\r\n    var indicators = [];\r\n    var banners = [];\r\n    console.log($bannersArray);\r\n\r\n    $.each($bannersArray, function(key, val) {\r\n        var indicatorClass = \"\";\r\n        if(key == 0) indicatorClass = \"class='active'\";\r\n        indicators.push(\"<li data-target='#carousel-main-banner' data-slide-to='\"+key+\"' \"+indicatorClass+\"></li>\");\r\n\r\n        var bannerClass = \"\";\r\n        if(key == 0) bannerClass = \"active\";\r\n        banners.push(\"<div class='item \"+bannerClass+\"'><img src='\"+val.image_path+\"' alt='...'><div class='carousel-caption'>\"+val.alt+\"</div></div>\");\r\n    });\r\n\r\n    $('.carousel-indicators').html(indicators.join(\"\"));\r\n    $('.carousel-indicators').children('li').css('margin', '0 3px');\r\n\r\n    $('.carousel-inner').html(banners.join(\"\"));\r\n}\r\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy9yZXNvdXJjZXMvYXNzZXRzL2pzL3dlYi5qcz9mZDg3Il0sInNvdXJjZXNDb250ZW50IjpbIiQoIGRvY3VtZW50ICkucmVhZHkoZnVuY3Rpb24oKSB7XHJcbiAgICBnZXRJbmZvKCk7XHJcbn0pO1xyXG5cclxudmFyICRjYXRlZ29yaWVzQXJyYXk7XHJcbnZhciAkYmFubmVyc0FycmF5O1xyXG5cclxuZnVuY3Rpb24gZ2V0SW5mbygpIHtcclxuXHJcbiAgICAkLmFqYXhTZXR1cCh7XHJcbiAgICAgICAgaGVhZGVycyA6IHtcclxuICAgICAgICAgICAgJ0NvbnRlbnQtTGFuZ3VhZ2UnIDogJ3RyYWQnXHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcblxyXG4gICAgJC5nZXRKU09OKCBcImFwaS9pbmRleFwiLCBmdW5jdGlvbiggZGF0YSApIHtcclxuICAgICAgICAkLmVhY2goIGRhdGEsIGZ1bmN0aW9uKCBrZXksIHZhbCApIHtcclxuICAgICAgICAgICAgc3dpdGNoKGtleSkge1xyXG4gICAgICAgICAgICAgICAgY2FzZSAnY2F0ZWdvcmllcyc6XHJcbiAgICAgICAgICAgICAgICAgICAgJGNhdGVnb3JpZXNBcnJheSA9IHZhbDtcclxuICAgICAgICAgICAgICAgICAgICBicmVhaztcclxuICAgICAgICAgICAgICAgIGNhc2UgJ2Jhbm5lcnMnOlxyXG4gICAgICAgICAgICAgICAgICAgICRiYW5uZXJzQXJyYXkgPSB2YWw7XHJcbiAgICAgICAgICAgICAgICAgICAgYnJlYWs7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgc2hvd0NvbnRlbnQoKTtcclxuICAgIH0pO1xyXG5cclxufVxyXG5cclxuZnVuY3Rpb24gc2hvd0NvbnRlbnQoKSB7XHJcbiAgICBtYWtlQ2F0ZWdvcmllc0xpc3QoKTtcclxuICAgIG1ha2VCYW5uZXJzKCk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIG1ha2VDYXRlZ29yaWVzTGlzdCgpIHtcclxuICAgIHZhciBjYXRlZ29yaWVzSXRlbXMgPSBbXTtcclxuICAgICQuZWFjaCgkY2F0ZWdvcmllc0FycmF5LCBmdW5jdGlvbihrZXksIHZhbCkge1xyXG4gICAgICAgIGlmKHZhbC5wYXJlbnQgPT0gbnVsbCkge1xyXG4gICAgICAgICAgICBjYXRlZ29yaWVzSXRlbXMucHVzaChcIjxsaT48YSBocmVmPSdcIit2YWwudXJsK1wiL1wiK3ZhbC5zbHVnK1wiJz5cIit2YWwubmFtZStcIjwvYT48L2xpPlwiKVxyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG4gICAgJCgnI2NhdGVnb3JpZXNMaXN0JykuaHRtbChjYXRlZ29yaWVzSXRlbXMuam9pbihcIlwiKSk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIG1ha2VCYW5uZXJzKCkge1xyXG4gICAgdmFyIGluZGljYXRvcnMgPSBbXTtcclxuICAgIHZhciBiYW5uZXJzID0gW107XHJcbiAgICBjb25zb2xlLmxvZygkYmFubmVyc0FycmF5KTtcclxuXHJcbiAgICAkLmVhY2goJGJhbm5lcnNBcnJheSwgZnVuY3Rpb24oa2V5LCB2YWwpIHtcclxuICAgICAgICB2YXIgaW5kaWNhdG9yQ2xhc3MgPSBcIlwiO1xyXG4gICAgICAgIGlmKGtleSA9PSAwKSBpbmRpY2F0b3JDbGFzcyA9IFwiY2xhc3M9J2FjdGl2ZSdcIjtcclxuICAgICAgICBpbmRpY2F0b3JzLnB1c2goXCI8bGkgZGF0YS10YXJnZXQ9JyNjYXJvdXNlbC1tYWluLWJhbm5lcicgZGF0YS1zbGlkZS10bz0nXCIra2V5K1wiJyBcIitpbmRpY2F0b3JDbGFzcytcIj48L2xpPlwiKTtcclxuXHJcbiAgICAgICAgdmFyIGJhbm5lckNsYXNzID0gXCJcIjtcclxuICAgICAgICBpZihrZXkgPT0gMCkgYmFubmVyQ2xhc3MgPSBcImFjdGl2ZVwiO1xyXG4gICAgICAgIGJhbm5lcnMucHVzaChcIjxkaXYgY2xhc3M9J2l0ZW0gXCIrYmFubmVyQ2xhc3MrXCInPjxpbWcgc3JjPSdcIit2YWwuaW1hZ2VfcGF0aCtcIicgYWx0PScuLi4nPjxkaXYgY2xhc3M9J2Nhcm91c2VsLWNhcHRpb24nPlwiK3ZhbC5hbHQrXCI8L2Rpdj48L2Rpdj5cIik7XHJcbiAgICB9KTtcclxuXHJcbiAgICAkKCcuY2Fyb3VzZWwtaW5kaWNhdG9ycycpLmh0bWwoaW5kaWNhdG9ycy5qb2luKFwiXCIpKTtcclxuICAgICQoJy5jYXJvdXNlbC1pbmRpY2F0b3JzJykuY2hpbGRyZW4oJ2xpJykuY3NzKCdtYXJnaW4nLCAnMCAzcHgnKTtcclxuXHJcbiAgICAkKCcuY2Fyb3VzZWwtaW5uZXInKS5odG1sKGJhbm5lcnMuam9pbihcIlwiKSk7XHJcbn1cclxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHJlc291cmNlcy9hc3NldHMvanMvd2ViLmpzIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTsiLCJzb3VyY2VSb290IjoiIn0=");

/***/ }
/******/ ]);