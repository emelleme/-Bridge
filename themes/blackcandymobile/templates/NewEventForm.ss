<form $FormAttributes>
	  <div id="{$FormName}_error"></div>
	  <div id="Event Name" class="field text "><div class="middleColumn"><input type="text" class="text" id="eventName" name="Event Name" placeholder="Event Name" value="" /></div></div>
	<p>Start Date and Time</p><input type="datetime-local" name="startDate" id="startDate" /><br />
	<p>End Date</p><input type="datetime-local" name="endDate" id="endDate" /><br /><br />
	<p>Description</p>
	<textarea class="input-xlarge" type="textarea" name="eventdescription" id="eventdescription"></textarea><br /><br />
	<p>Location</p>
	<input type="text" name="eventlocation" id="eventlocation" /><br /><br />
	$dataFieldByName(SecurityID)
	<% if Actions %>
		<% control Actions %>
		$Field
		<% end_control %>
   <% end_if %>
</form>
