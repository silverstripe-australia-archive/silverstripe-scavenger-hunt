<div class="row">
    <div class="span3">
        <% include Sidebar %>
    </div>

    <div class="span9">
		$Content
		<% if CurrentMember %>
		
		<p>
		Hi $CurrentMember.Username
		</p>
		
			$TaskForm
		
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
