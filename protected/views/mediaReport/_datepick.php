		<div class="panel panel-default">
			<div class="panel-heading">時間</div>
			<div class="panel-body">
				<div class="filter-box">
					<div class="filter-datepicker">
						<div class="span5 col-md-5" id="sandbox-container">
							<div class="input-daterange input-group" id="datepicker">
							<input type="text" class="input-sm form-control" id="startDay" value="<?php echo $_GET['startDay'];?>">
							<span class="input-group-addon">至</span>
							<input type="text" class="input-sm form-control" id="endDay" value="<?php echo $_GET['endDay'];?>">
							</div>
						</div>
					</div>					
				</div>
				<div class="filter-box">
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<span id="supplier-report-dropup-now">昨天</span>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li><a href="#" data-type="yesterday" class="select-report">昨天</a></li>
							<li><a href="#" data-type="7day" class="select-report">最近7天</a></li>
							<li><a href="#" data-type="30day" class="select-report">最近30天</a></li>
							<li><a href="#" data-type="pastMonth" class="select-report">上個月</a></li>
							<li><a href="#" data-type="thisMonth" class="select-report">本月</a></li>
							<li><a href="#" data-type="thisMonth" class="select-report">自訂</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>