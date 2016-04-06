<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">取得代碼</h4>
</div>
<div class="modal-body">	
<pre id="code-pre">

&lt;script type=&quot;text/javascript&quot;&gt;
	tosAdspaceInfo = {
		&#39;aid&#39;:<?php echo $id; ?>,
		&#39;serverbaseurl&#39;:&#39;tad.doublemax.net/&#39;,
		&#39;staticbaseurl&#39;:&#39;static.doublemax.net/js/&#39;
	}
&lt;/script&gt;
&lt;script type=&quot;text/javascript&quot; src=&quot;//static.doublemax.net/js/tr.js&quot;&gt;
&lt;/script&gt;


</pre>
</div>
<div class="modal-footer">
	<a target="_blank" href="getAdSpaceCode?id=<?php echo $id; ?>&download=1" class="btn btn-primary copy-btn">下載代碼</a>
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>