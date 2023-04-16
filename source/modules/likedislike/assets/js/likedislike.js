/**
 * 
 */
function likedislike(post_id,post_type){
	var base_url = $("#mybaseurl").val();
	$.post( base_url, {post_id:post_id,post_type:post_type} ,
	function(data) {
			if(data.status==true){
				$("#displaytext_"+post_id+"_"+post_type).html(data.displaytext);
				$("#likedislikecount_"+post_id+"_"+post_type).html(data.count);
			}
	},'json');
}