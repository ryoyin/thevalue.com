var newsObject;

$( document ).ready(function() {

	//Generate Index Data
	$.getJSON( "json/index.json", function( data ) {
		$.each( data, function( key, val ) {
		  var indexJson = val;

		  $.each( indexJson, function( key1, val1 ) {
		    var indexName = key1;
		    var indexValue = val1;

		    // console.log(indexName);
		    var newIndexObj = $(".index-temp").clone().appendTo(".index-tbody");
		    newIndexObj.removeClass("index-temp").addClass("new-index-temp");

		    $(".new-index-temp th").html(indexName);
		    var indexCounter = 0;
		    $.each( indexValue, function( key2, val2 ) {
		      // console.log(key2 + val2);
		      if(indexCounter == 1) {
		        if(val2 > 0) {
		          var glyphicon = "glyphicon-arrow-up";
		          var text_color = "green";
		        } else {
		          var glyphicon = "glyphicon-arrow-down";
		          var text_color = "red";
		        }
		        $(".new-index-temp td:eq("+indexCounter+")").html("<span class=\"glyphicon "+glyphicon+"\" aria-hidden=\"true\"></span> " + val2).css("color", text_color).css("font-weight", "bold");
		      } else {
		        $(".new-index-temp td:eq("+indexCounter+")").html(val2);                
		      }
		      indexCounter ++;
		    });

		    $(".new-index-temp .glyphicon").addClass("abc");

		    $(".new-index-temp").removeClass("new-index-temp");
		  });
		});
	});

});



/*Convert Timestamp to YYYY-MM-DD HH:MM*/
/*
function convertTimestamp(time) {

	// Create a new JavaScript Date object based on the timestamp
	var returnDate = new Date(time);
	var year = returnDate.getFullYear();
	var month = returnDate.getMonth();
	var date = ("0" + returnDate.getDate()).slice(-2);
	var hours = returnDate.getHours();
	var minutes = "0" + returnDate.getMinutes();
	//var seconds = "0" + returnDate.getSeconds();

	// Will display time in YYYY-MM-DD HH:MM format
	return year + '-' + month + '-'+ date + ' ' + hours + ':' + minutes.substr(-2);

}*/

$(function() {
    function reposition() {
        var modal = $(this),
            dialog = modal.find('.modal-dialog');
        modal.css('display', 'block');

        // Dividing by two centers the modal exactly, but dividing by three
        // or four works better for larger screens.
        dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 4));
    }
    // Reposition when a modal is shown
    $('.modal').on('show.bs.modal', reposition);
    // Reposition when the window is resized
    $(window).on('resize', function() {
        $('.modal:visible').each(reposition);
    });
});
