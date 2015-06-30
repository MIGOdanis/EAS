/**
 * TinyMCE設定檔
 *
 * @author	Danis
 * @date 		2013.6
 * @spend		5 min
 * -------------------
 * 編輯器加上分享我的路線
 * 
 * @author 	KeaNy 
 * @date 	2013.7.25
 * @spend  	5 min
 * -------------------
 * 編輯器加上底線
 * 
 * @author Danis
 * @date 2013.11.18
 * @spend 5 min
 */
//停用的toolbar : fullscreen styleselect 
tinymce.init({
 	//selector: "content",
 	relative_urls : false,
    selector: "textarea",
	language : 'zh_TW',
    //menubar: false,
	toolbar: " undo redo | forecolor fontsizeselect | bold italic underline strikethrough | sharemaps | alignleft aligncenter alignright | bullist numlist | link unlink | image jbimages | preview ",
	plugins: [
		" fullscreen textcolor advlist autolink lists link image charmap print preview anchor",
		"searchreplace visualblocks code fullscreen ",
		"insertdatetime media table contextmenu paste jbimages"
	]
 });
