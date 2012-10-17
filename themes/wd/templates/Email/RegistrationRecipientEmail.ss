<p>Hi $Name,</p>

<p>Thanks for your registration for the SilverStripe Information Session.</p> 

<h2>Event Details</h2>

<% with DateTime %>
	<ul>
		<li>Date: $StartDate.Format(d F Y)</li>
		<li>Time: <% if StartTime %>$TimeRange<% else %>TBA<% end_if %></li>
		<li>Location: $Location</li>
		<li>Venue: <% if Venue %>$Venue<% else %>TBA<% end_if %></li>
	</ul>
	
	<% with Event %>
		$Content
	<% end_with %>

<% end_with %>

