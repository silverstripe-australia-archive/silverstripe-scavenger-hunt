<div class="typography">
	<ul id="calendar-events-list">
	<% loop Events %>
		<li class="vevent $Location.ATT">
		<a href="$Link">
			<span class="city"></span>
			<h2>$Location</h2>
			<ul>
				<li><span>$StartDate.Format(d M Y)</span></li>
				<% if StartTime %><li><span>$TimeRange</span></li><% end_if %>
			</ul>
		</a>
		</li>
	<% end_loop %>
	</ul>
</div>