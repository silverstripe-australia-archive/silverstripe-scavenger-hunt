<div class="row">
    <div class="span3">
        <% include Sidebar %>
    </div>

    <div class="span9">
		$Content
		<% if CurrentMember %>
			<% if $CurrentMemberTask %>
				
				<% if $CurrentMemberTask.Response.Status == 'Pending' %>

				Your recent submission is being reviewed, you'll be notified when it's accepted!

				<% else %>

					<% if $CurrentMemberTask.Viewable %>
						<div class="task-description">
							$CurrentMemberTask.Description
						</div>
						<% if $CurrentMemberTask.Answerable %>
						$TaskForm
						<% else %>
						You can respond to this task after $CurrentMemberTask.AnswerableAfter.Format(g:ia jS M)
						<% end_if %>

					<% else %>
						You can see this task after $CurrentMemberTask.AvailableAfter.Format(g:ia jS M)
					<% end_if %>

				<% end_if %>
			<% end_if %>
			
		<% else %>

			<div class="row">
				<div class="span4">
					<strong>Log in</strong>
					$LoginForm
				</div>

				<div class="span5">
					<strong>OR register below</strong>

					$RegisterForm
				</div>
			</div>

		
		<% end_if %>
		
    </div>
</div>
