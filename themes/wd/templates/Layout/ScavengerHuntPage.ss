<div class="typography">
	
	<% if CurrentMember %>
		<% if $CurrentMemberTask %>
			<div class="task">
			<% if $CurrentMemberTask.Response.Status == 'Pending' %>

				<p>Your recent submission is being reviewed, you'll be notified when it's accepted!</p>

			<% else %>

				<% if $CurrentMemberTask.Viewable %>
					<h2>$CurrentMemberTask.Title</h2>
					<div class="task-description">
						$CurrentMemberTask.Description
					</div>
					<% if $CurrentMemberTask.Answerable %>
						$TaskForm
					<% else %>
						<p>You can respond to this task after $CurrentMemberTask.AnswerableAfter.Format(g:ia jS M)</p>
					<% end_if %>

				<% else %>
					<p>You can see this task after $CurrentMemberTask.AvailableAfter.Format(g:ia jS M)</p>
				<% end_if %>
			</div>
			<% end_if %>
		<% end_if %>
		
	<% else %>
		
		<h2>Log in</h2>
		$LoginForm
		
		<h2 class="space-above">Register</h2>
		$RegisterForm

	<% end_if %>
</div>